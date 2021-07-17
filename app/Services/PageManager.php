<?php namespace App\Services;

use App\Services\Service;

use DB;

use App\Models\Subject\SubjectCategory;
use App\Models\Subject\TimeDivision;
use App\Models\Page\Page;

class PageManager extends Service
{
    /*
    |--------------------------------------------------------------------------
    | Page Manager
    |--------------------------------------------------------------------------
    |
    | Handles the creation and editing of pages.
    |
    */

    /**
     * Creates a page.
     *
     * @param  array                         $data
     * @param  \App\Models\User\User         $user
     * @return bool|\App\Models\Page\Page
     */
    public function createPage($data, $user)
    {
        DB::beginTransaction();

        try {
            // Process data for storage
            $data = $this->processPageData($data);

            // Encode data before saving
            if(isset($data['data'])) $data['data'] = json_encode($data['data']);
            else $data['data'] = null;

            // Create page
            $page = Page::create($data);

            return $this->commitReturn($page);
        } catch(\Exception $e) {
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }

    /**
     * Updates a page.
     *
     * @param  \App\Models\Page\Page     $page
     * @param  array                     $data
     * @param  \App\Models\User\User     $user
     * @return \App\Models\Page\Page|bool
     */
    public function updatePage($page, $data, $user)
    {
        DB::beginTransaction();

        try {
            // More specific validation
            if(Page::where('title', $data['title'])->where('id', '!=', $page->id)->exists()) throw new \Exception("The page title has already been taken.");

            // Process data for storage
            $data = $this->processPageData($data, $page);

            // Encode data before saving
            if(isset($data['data'])) $data['data'] = json_encode($data['data']);
            else $data['data'] = null;

            // Update page
            $page->update($data);

            return $this->commitReturn($page);
        } catch(\Exception $e) {
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }

    /**
     * Delete a page.
     *
     * @param  \App\Models\Page\Page  $page
     * @return bool
     */
    public function deletePage($page)
    {
        DB::beginTransaction();

        try {
            // There will be more checking and processing here in time
            // But for now all that needs to be done is delete the page

            $page->delete();

            return $this->commitReturn(true);
        } catch(\Exception $e) {
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }

    /**
     * Processes page data for storage.
     *
     * @param  array                     $data
     * @param  App\Models\Page\Page      $page
     * @return array
     */
    private function processPageData($data, $page = null)
    {
        // Fetch category-- either from the page if it already exists, or from the category ID
        $category = $page ? $page->category : SubjectCategory::where('id', $data['category_id'])->first();

        // Record the introduction as necessary
        $data['data']['description'] = isset($data['description']) ? $data['description'] : null;

        // Cycle through the category's form fields
        // Data is recorded here in a flat array/only according to key, as keys should not
        // be duplicated in a template, and the template accounts for form building as well
        // as page formatting
        foreach($category->formFields as $key=>$field)
            $data['data'][$key] = isset($data[$key]) ? $data[$key] : null;

        // Process any subject-specific data
        switch($category->subject['key']) {
            case 'people';
                // Record name
                $data['data']['people_name'] = isset($data['people_name']) ? $data['people_name'] : null;

                // Record birth and death data
                foreach(['birth', 'death'] as $segment) {
                    if(isset($data[$segment.'_place_id']) || isset($data[$segment.'_chronology_id'])) $data['data'][$segment] = [
                        'place' => isset($data[$segment.'_place_id']) ? $data[$segment.'_place_id'] : null,
                        'chronology' => isset($data[$segment.'_chronology_id']) ? $data[$segment.'_chronology_id'] : null
                    ];
                    foreach((new TimeDivision)->dateFields() as $key=>$field)
                        if(isset($data[$segment.'_'.$key])) $data['data'][$segment]['date'][$key] = isset($data[$segment.'_'.$key]) ? $data[$segment.'_'.$key] : null;
                }
                break;
            case 'places';
                // Record parent location
                $data['parent_id'] = isset($data['parent_id']) ? $data['parent_id'] : null;
                break;
            case 'time';
                // Record chronology
                $data['parent_id'] = isset($data['parent_id']) ? $data['parent_id'] : null;
                // Record dates
                foreach(['start', 'end'] as $segment) {
                    foreach((new TimeDivision)->dateFields() as $key=>$field)
                        if(isset($data['date_'.$segment.'_'.$key])) $data['data']['date'][$segment][$key] = isset($data['date_'.$segment.'_'.$key]) ? $data['date_'.$segment.'_'.$key] : null;
                }
                break;
        }

        return $data;
    }

}
