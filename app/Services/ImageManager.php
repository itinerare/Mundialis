<?php

namespace App\Services;

use App\Facades\Notifications;
use App\Models\Page\Page;
use App\Models\Page\PageImage;
use App\Models\Page\PageImageCreator;
use App\Models\Page\PageImageVersion;
use App\Models\Page\PagePageImage;
use App\Models\User\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class ImageManager extends Service {
    /*
    |--------------------------------------------------------------------------
    | Image Manager
    |--------------------------------------------------------------------------
    |
    | Handles the creation and editing of images.
    |
    */

    /**
     * Creates an image.
     *
     * @param array $data
     * @param Page  $page
     * @param User  $user
     *
     * @return bool|PageImage
     */
    public function createPageImage($data, $page, $user) {
        DB::beginTransaction();

        try {
            // Ensure user can edit the parent page
            if (!$user->canEdit($page)) {
                throw new \Exception('You don\'t have permission to edit this page.');
            }

            // Process toggles
            if (!isset($data['is_valid'])) {
                $data['is_valid'] = 0;
            }
            if (!isset($data['is_visible'])) {
                $data['is_visible'] = 0;
            }
            if (!isset($data['mark_invalid'])) {
                $data['mark_invalid'] = 0;
            }
            if (!isset($data['mark_active'])) {
                $data['mark_active'] = 0;
            }

            // Set version type before moving into image creation
            $data['version_type'] = 'Image Created';

            // Process data and create image
            $image = $this->handlePageImage($data, $page, $user);
            if (!$image) {
                throw new \Exception('An error occurred while trying to create image.');
            }

            // Create link for the creating page
            PagePageImage::create([
                'page_id'       => $page->id,
                'page_image_id' => $image->id,
                'is_valid'      => $data['is_valid'],
            ]);

            // Update the page's image ID if relevant
            if (isset($data['mark_active']) && $data['mark_active']) {
                $page->image_id = $image->id;
                $page->save();
            }

            // Send a notification to users that have watched this page
            if ($page->watchers->count()) {
                foreach ($page->watchers as $recipient) {
                    if ($recipient->id != $user->id) {
                        Notifications::create('WATCHED_PAGE_IMAGE_UPDATED', $recipient, [
                            'page_url'   => $page->url,
                            'page_title' => $page->title,
                            'user_url'   => $user->url,
                            'user_name'  => $user->name,
                        ]);
                    }
                }
            }

            return $this->commitReturn($image);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Updates an image.
     *
     * @param Page      $page
     * @param PageImage $image
     * @param array     $data
     * @param User      $user
     *
     * @return bool|Page
     */
    public function updatePageImage($page, $image, $data, $user) {
        DB::beginTransaction();

        try {
            // Ensure user can edit the parent page
            if (!$user->canEdit($page)) {
                throw new \Exception('You don\'t have permission to edit this page.');
            }

            if (!$page->images()->where('page_image_id', $image->id)->exists()) {
                throw new \Exception('This image does not belong to this page.');
            }

            if ($image->isProtected && !$user->isAdmin) {
                throw new \Exception('One or more pages this image is linked to are protected; you do not have permission to edit it.');
            }

            // Process toggles
            if (!isset($data['is_valid'])) {
                $data['is_valid'] = 0;
            }
            if (!isset($data['is_visible'])) {
                $data['is_visible'] = 0;
            }
            if (!isset($data['mark_invalid'])) {
                $data['mark_invalid'] = 0;
            }
            if (!isset($data['mark_active'])) {
                $data['mark_active'] = 0;
            }

            // Process data and handle image
            $image = $this->handlePageImage($data, $page, $user, $image);
            if (!$image) {
                throw new \Exception('An error occurred while trying to process image.');
            }

            // If image is being marked invalid, update
            if ($image->pages()->where('pages.id', $page->id)->first()->pivot->is_valid && !$data['is_valid']) {
                $page->images()->updateExistingPivot($image->id, [
                    'is_valid' => 0,
                ]);

                // Remove the image as the page's active one if it is
                if ($page->image_id == $image->id) {
                    $page->image_id = null;
                    $page->save();
                }
            }
            // If an image is being marked invalid, it should not be able to be marked valid
            // or made the page's active image, so this is dependent on the above not being the case
            else {
                // If image is being re-marked valid, update
                if (!$image->pages()->where('pages.id', $page->id)->first()->pivot->is_valid && $data['is_valid']) {
                    $page->images()->updateExistingPivot($image->id, [
                        'is_valid' => 1,
                    ]);
                }

                // Update the page's image ID if relevant
                if (isset($data['mark_active']) && $data['mark_active']) {
                    $page->image_id = $image->id;
                    $page->save();
                }
            }

            // Update image
            $image->update(Arr::only($data, ['description', 'is_visible']));

            // Send a notification to users that have watched this page
            if ($page->watchers->count()) {
                foreach ($page->watchers as $recipient) {
                    if ($recipient->id != $user->id) {
                        Notifications::create('WATCHED_PAGE_IMAGE_UPDATED', $recipient, [
                            'page_url'   => $page->url,
                            'page_title' => $page->title,
                            'user_url'   => $user->url,
                            'user_name'  => $user->name,
                        ]);
                    }
                }
            }

            return $this->commitReturn($image);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Restore a deleted image.
     *
     * @param User   $user
     * @param string $reason
     * @param mixed  $image
     *
     * @return bool
     */
    public function restorePageImage($image, $user, $reason) {
        DB::beginTransaction();

        try {
            if (!$image) {
                throw new \Exception('Invalid image selected.');
            }

            if (!$image->deleted_at) {
                throw new \Exception('This image has not been deleted.');
            }

            // First, restore the image itself
            $image->restore();

            // Then, create a version logging the restoration
            $version = $this->logImageVersion($image->id, $user->id, null, 'Image Restored', $reason, $image->version->data, false);
            if (!$version) {
                throw new \Exception('An error occurred while saving image version.');
            }

            return $this->commitReturn($image);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Delete an image.
     *
     * @param PageImage $image
     * @param bool      $forceDelete
     * @param User      $user
     * @param string    $reason
     *
     * @return bool
     */
    public function deletePageImage($image, $user, $reason, $forceDelete = false) {
        DB::beginTransaction();

        try {
            if ($image->isProtected && !$user->isAdmin) {
                throw new \Exception('One or more pages this image is linked to are protected; you cannot delete it.');
            }

            // Unset this image ID from any pages where it is the active image
            if (Page::where('image_id', $image->id)->exists()) {
                Page::where('image_id', $image->id)->update(['image_id' => null]);
            }

            if ($forceDelete) {
                // Delete version files
                foreach ($image->versions as $version) {
                    if (isset($version->hash)) {
                        unlink($image->imagePath.'/'.$version->thumbnailFileName);
                        unlink($image->imagePath.'/'.$version->imageFileName);
                    }
                }

                // Delete the image and any relevant objects
                // Delete creator records
                $image->creators()->delete();
                // Delete version records
                $image->versions()->delete();
                // Detach linked pages
                $image->pages()->detach();
                // Finally, force-delete the image itself
                $image->forceDelete();
            } else {
                // Create a log of the deletion
                $version = $this->logImageVersion($image->id, $user->id, null, 'Image Deleted', $reason, $image->version->data, false);
                if (!$version) {
                    throw new \Exception('Error occurred while logging image version.');
                }

                // Delete the image itself
                $image->delete();
            }

            return $this->commitReturn(true);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Records a new image version.
     *
     * @param int    $imageId
     * @param int    $userId
     * @param array  $imageData
     * @param string $type
     * @param string $reason
     * @param array  $data
     * @param bool   $isMinor
     *
     * @return bool|PageImageVersion
     */
    public function logImageVersion($imageId, $userId, $imageData, $type, $reason, $data, $isMinor = false) {
        try {
            $version = PageImageVersion::create([
                'page_image_id' => $imageId,
                'user_id'       => $userId,
                'hash'          => $imageData['hash'] ?? null,
                'extension'     => $imageData['extension'] ?? null,
                'x0'            => $imageData['x0'] ?? null,
                'x1'            => $imageData['x1'] ?? null,
                'y0'            => $imageData['y0'] ?? null,
                'y1'            => $imageData['y1'] ?? null,
                'type'          => $type,
                'reason'        => $reason,
                'is_minor'      => $isMinor,
                'data'          => $data,
            ]);

            return $version;
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return false;
    }

    /**
     * Generates and saves test images for page image test purposes.
     *
     * @param PageImage        $image
     * @param PageImageVersion $version
     * @param bool             $create
     *
     * @return bool
     */
    public function testImages($image, $version, $create = true) {
        if ($create) {
            $file['image'] = UploadedFile::fake()->image('test_image.png');
            $file['thumbnail'] = UploadedFile::fake()->image('test_thumb.png');

            $this->handleImage($file['image'], $image->imagePath, $version->imageFileName);
            $this->handleImage($file['thumbnail'], $image->imagePath, $version->thumbnailFileName);
        } elseif (!$create && File::exists($image->imagePath.'/'.$version->thumbnailFileName)) {
            unlink($image->imagePath.'/'.$version->thumbnailFileName);
            unlink($image->imagePath.'/'.$version->imageFileName);
        }

        return true;
    }

    /**
     * Sorts a page's images.
     *
     * @param array $data
     * @param Page  $page
     * @param User  $user
     *
     * @return bool
     */
    public function sortImages($data, $page, $user) {
        DB::beginTransaction();

        try {
            if (!$page) {
                throw new \Exception('Invalid page selected.');
            }

            $ids = array_reverse(explode(',', $data['sort']));
            $images = PageImage::whereIn('id', $ids)->where('is_visible', 1)->orderBy(DB::raw('FIELD(id, '.implode(',', $ids).')'))->whereHas('pages', function ($query) use ($page) {
                $query->where('pages.id', $page->id);
            })->get();

            if (count($images) != count($ids)) {
                throw new \Exception('Invalid image(s) included in sorting order.');
            }

            $count = 0;
            foreach ($images as $image) {
                $page->images()->updateExistingPivot($image->id, [
                    'sort' => $count,
                ]);
                $count++;
            }

            return $this->commitReturn(true);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Handles page image data.
     *
     * @param array     $data
     * @param Page      $page
     * @param User      $user
     * @param PageImage $image
     *
     * @return bool|PageImage
     */
    private function handlePageImage($data, $page, $user, $image = null) {
        try {
            // Process data stored on the image
            $imageData['description'] = $data['description'] ?? null;
            $imageData['is_visible'] = $data['is_visible'] ?? 0;

            // If there's no preexisting image, create one
            if (!$image) {
                $image = PageImage::create(Arr::only($imageData, ['description', 'is_visible']));
            }

            // If new or re-uploading an image
            if (isset($data['image'])) {
                // Collect image-specific data
                $imageData = Arr::only($data, [
                    'use_cropper', 'x0', 'x1', 'y0', 'y1',
                ]);
                $imageData['use_cropper'] = isset($data['use_cropper']);
                $imageData['hash'] = randomString(15);
                $imageData['extension'] = $data['image']->getClientOriginalExtension();

                // If no version type is set yet, by default, this is a reupload
                if (!isset($data['version_type'])) {
                    $data['version_type'] = 'Image Reuploaded';
                }

                // Create version
                $version = $this->logImageVersion($image->id, $user->id, $imageData, $data['version_type'], $data['reason'] ?? null, null, false);
                if (!$version) {
                    throw new \Exception('An error occurred while creating the image version.');
                }

                // Save image
                if (!$this->handleImage($data['image'], $image->imagePath, $version->imageFileName)) {
                    throw new \Exception('An error occurred while handling image file.');
                }

                // Save thumbnail
                if (isset($data['use_cropper']) && $data['use_cropper']) {
                    $this->cropThumbnail(Arr::only($data, ['x0', 'x1', 'y0', 'y1']), $image, $version);
                } elseif (!$this->handleImage($data['thumbnail'], $image->imagePath, $version->thumbnailFileName)) {
                    throw new \Exception('An error occurred while handling thumbnail file.');
                }

                // Trim transparent parts of image.
                $processImage = Image::make($image->imagePath.'/'.$version->imageFileName)->trim('transparent');

                if (config('mundialis.settings.image_thumbnail_automation') == 1) {
                    // Make the image be square
                    $imageWidth = $processImage->width();
                    $imageHeight = $processImage->height();

                    if ($imageWidth > $imageHeight) {
                        // Landscape
                        $canvas = Image::canvas($processImage->width(), $processImage->width());
                        $processImage = $canvas->insert($processImage, 'center');
                    } else {
                        // Portrait
                        $canvas = Image::canvas($processImage->height(), $processImage->height());
                        $processImage = $canvas->insert($processImage, 'center');
                    }
                }

                // Save the processed image
                $processImage->save($image->imagePath.'/'.$version->imageFileName, 100, $imageData['extension']);
            } else {
                // Otherwise, just create a new version
                $version = $this->logImageVersion($image->id, $user->id, null, 'Image Info Updated', $data['reason'] ?? null, null, $data['is_minor'] ?? 0);
                if (!$version) {
                    throw new \Exception('An error occurred while creating the image version.');
                }
            }

            // Check that users with the specified id(s) exist on site
            foreach ($data['creator_id'] as $id) {
                if (isset($id) && $id) {
                    $user = User::find($id);
                    if (!$user) {
                        throw new \Exception('One or more creators are invalid.');
                    }
                }
            }

            // Delete existing creator records
            if ($image && $image->creators->count()) {
                $image->creators()->delete();
            }

            // Just attach creators
            foreach ($data['creator_id'] as $key => $id) {
                if ($id || isset($data['creator_url'][$key])) {
                    PageImageCreator::create([
                        'page_image_id' => $image->id,
                        'url'           => $id ? null : $data['creator_url'][$key],
                        'user_id'       => $id ? $id : null,
                    ]);
                }
            }

            // Check that pages with the specified id(s) exist on site
            if (isset($data['page_id'])) {
                foreach ($data['page_id'] as $id) {
                    if (isset($id) && $id) {
                        $pageCheck = Page::find($id);
                        if (!$pageCheck) {
                            throw new \Exception('One or more pages are invalid.');
                        }
                    }
                }
            }

            // If old images should be marked invalid, do so
            if (isset($data['mark_invalid']) && $data['mark_invalid']) {
                foreach ($page->images()->pluck('page_images.id')->toArray() as $imageKey) {
                    $page->images()->updateExistingPivot($imageKey, [
                        'is_valid' => 0,
                    ]);
                }
            }

            // Add ID of existing page since it's not included in the form field
            $data['page_id'][] = $page->id;

            // Process page link information
            if (isset($data['page_id'])) {
                if ($image && $image->pages()->where('pages.id', '!=', $page->id)->count()) {
                    // This is just a matter of checking to see if there are changes in
                    // the list of page IDs.
                    $oldPages = $image->pages()->pluck('pages.id')->toArray();

                    $pageDiff['removed'] = array_diff($oldPages, $data['page_id']);
                    $pageDiff['added'] = array_diff($data['page_id'], $oldPages);

                    // Delete removed page links
                    foreach ($pageDiff['removed'] as $pageId) {
                        // Check to see if the user can detach the page
                        if (Page::find($pageId)->protection && Page::find($pageId)->protection->is_protected && !$user->canEdit(Page::find($pageId))) {
                            throw new \Exception('One or more of the pages being detached is protected; you do not have permission to detach it from this image.');
                        }

                        // Check to see if the image is the page's active image, and if so,
                        // unset it
                        if ($image->pages()->where('pages.id', $pageId)->where('image_id', $image->id)) {
                            $image->pages()->where('pages.id', $pageId)->where('image_id', $image->id)->update(['image_id' => null]);
                        }

                        // Delete the link
                        $image->pages()->newPivotStatementForId($pageId)->wherePageImageId($image->id)->delete();
                    }

                    // Create added links
                    foreach ($pageDiff['added'] as $pageId) {
                        // Check to see if the user can attach the page
                        if (Page::find($pageId)->protection && Page::find($pageId)->protection->is_protected && !$user->canEdit(Page::find($pageId))) {
                            throw new \Exception('One or more of the pages being added is protected; you do not have permission to add it to this image.');
                        }

                        // Create the link
                        PagePageImage::create([
                            'page_id'       => $pageId,
                            'page_image_id' => $image->id,
                            'is_valid'      => 1,
                        ]);
                    }
                } else {
                    // Just attach links
                    foreach ($data['page_id'] as $pageId) {
                        // Check to see if the user can attach the page
                        if (Page::find($pageId)->protection && Page::find($pageId)->protection->is_protected && !$user->canEdit(Page::find($pageId))) {
                            throw new \Exception('One or more of the pages being added is protected; you do not have permission to add it to this image.');
                        }

                        // Create the link
                        if ($pageId != $page->id) {
                            PagePageImage::create([
                                'page_id'       => $pageId,
                                'page_image_id' => $image->id,
                                'is_valid'      => 1,
                            ]);
                        }
                    }
                }
            } elseif (!isset($data['page_id']) && $image->pages->count() > 1) {
                $image->pages()->where('page_id', '!=', $page->id)->detach();
            }

            // Process image information for storage on the version
            $imageData['version'] = $this->processVersionData($data);

            // Update version with archival data
            $version->update([
                'data' => $imageData['version'],
            ]);

            return $image;
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return false;
    }

    /**
     * Crops a thumbnail for the given image.
     *
     * @param array            $points
     * @param PageImage        $pageImage
     * @param PageImageVersion $version
     */
    private function cropThumbnail($points, $pageImage, $version) {
        $image = Image::make($pageImage->imagePath.'/'.$version->imageFileName);

        if (config('mundialis.settings.watermark_image_thumbnails') == 1) {
            // Trim transparent parts of image
            $image->trim('transparent');

            if (config('mundialis.settings.image_thumbnail_automation') == 1) {
                // Make the image be square
                $imageWidth = $image->width();
                $imageHeight = $image->height();

                if ($imageWidth > $imageHeight) {
                    // Landscape
                    $canvas = Image::canvas($image->width(), $image->width());
                    $image = $canvas->insert($image, 'center');
                } else {
                    // Portrait
                    $canvas = Image::canvas($image->height(), $image->height());
                    $image = $canvas->insert($image, 'center');
                }
            }

            $cropWidth = config('mundialis.settings.image_thumbnails.width');
            $cropHeight = config('mundialis.settings.image_thumbnails.height');

            $imageWidthOld = $image->width();
            $imageHeightOld = $image->height();

            $trimOffsetX = $imageWidthOld - $image->width();
            $trimOffsetY = $imageHeightOld - $image->height();

            // Now shrink the image

            $imageWidth = $image->width();
            $imageHeight = $image->height();

            if ($imageWidth > $imageHeight) {
                // Landscape
                $image->resize(null, $cropWidth, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            } else {
                // Portrait
                $image->resize($cropHeight, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }
        } else {
            $cropWidth = $points['x1'] - $points['x0'];
            $cropHeight = $points['y1'] - $points['y0'];

            if (config('mundialis.settings.image_thumbnail_automation') == 0) {
                // Crop according to the selected area
                $image->crop($cropWidth, $cropHeight, $points['x0'], $points['y0']);
            }

            // Resize to fit the thumbnail size
            $image->resize(config('mundialis.settings.image_thumbnails.width'), config('mundialis.settings.image_thumbnails.height'));
        }

        // Save the thumbnail
        $image->save($pageImage->thumbnailPath.'/'.$version->thumbnailFileName, 100, $version->extension);
    }

    /**
     * Processes version data for storage.
     *
     * @param array $data
     *
     * @return array
     */
    private function processVersionData($data) {
        // Record image information for inclusion in version data
        $versionData = [
            'is_visible'  => $data['is_visible'],
            'description' => $data['description'],
        ];

        // Record creator information
        if (isset($data['creator_id'])) {
            foreach ($data['creator_id'] as $key=>$creator) {
                $versionData['creators'][] = $creator ?? ($data['creator_url'] ?? null);
            }
        }

        // Record page information
        if (isset($data['page_id'])) {
            foreach ($data['page_id'] as $key=>$page) {
                $versionData['pages'][] = $page;
            }
        }

        return $versionData;
    }
}
