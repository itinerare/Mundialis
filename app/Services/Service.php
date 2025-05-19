<?php

namespace App\Services;

use App\Models\Page\Page;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

abstract class Service {
    /*
    |--------------------------------------------------------------------------
    | Base Service
    |--------------------------------------------------------------------------
    |
    | Base service, setting up error handling.
    |
    */

    /**
     * Errors.
     *
     * @var Illuminate\Support\MessageBag
     */
    protected $errors = null;
    protected $cache = [];
    protected $user = null;

    /**
     * Default constructor.
     */
    public function __construct() {
        $this->callMethod('beforeConstruct');
        $this->resetErrors();
        $this->callMethod('afterConstruct');
    }

    /**
     * Return if an error exists.
     *
     * @return bool
     */
    public function hasErrors() {
        return $this->errors->count() > 0;
    }

    /**
     * Return if an error exists.
     *
     * @param mixed $key
     *
     * @return bool
     */
    public function hasError($key) {
        return $this->errors->has($key);
    }

    /**
     * Return errors.
     *
     * @return Illuminate\Support\MessageBag
     */
    public function errors() {
        return $this->errors;
    }

    /**
     * Return errors.
     *
     * @return array
     */
    public function getAllErrors() {
        return $this->errors->unique();
    }

    /**
     * Return error by key.
     *
     * @param mixed $key
     *
     * @return Illuminate\Support\MessageBag
     */
    public function getError($key) {
        return $this->errors->get($key);
    }

    /**
     * Empty the errors MessageBag.
     */
    public function resetErrors() {
        $this->errors = new MessageBag;
    }

    public function remember($key = null, $fn = null) {
        if (isset($this->cache[$key])) {
            return $this->cache[$key];
        }

        return $this->cache[$key] = $fn();
    }

    public function forget($key = null) {
        unset($this->cache[$key]);
    }

    public function setUser($user) {
        $this->user = $user;

        return $this;
    }

    public function user() {
        return $this->user ? $this->user : Auth::user();
    }

    // 1. Old image exists, want to move it to a new location.
    // 2. Given new image, want to upload it to new location.
    //    (old image may or may not exist)
    // 3. Nothing happens (no changes required)
    public function handleImage($image, $dir, $name, $oldName = null, $copy = false) {
        if (!$oldName && !$image) {
            return true;
        }

        if (!$image) {
            // Check if we're moving an old image, and move it if it does.
            if ($oldName) {
                return $this->moveImage($dir, $name, $oldName, $copy);
            }
        } else {
            // Don't want to leave a lot of random images lying around,
            // so move the old image first if it exists.
            if ($oldName) {
                $this->moveImage($dir, $name, $oldName, $copy);
            }

            // Then overwrite the old image.
            return $this->saveImage($image, $dir, $name, $copy);
        }

        return false;
    }

    public function deleteImage($dir, $name) {
        unlink($dir.'/'.$name);
    }

    /**
     * Recursively compares two arrays.
     * Taken from https://gist.github.com/jondlm/7709e54f84a3f1e1b67b.
     *
     * @param array $array1
     * @param array $array2
     *
     * @return array
     */
    public function diff_recursive($array1, $array2) {
        $difference = [];
        foreach ($array1 as $key => $value) {
            if (is_array($value) && isset($array2[$key])) {
                // it's an array and both have the key
                $new_diff = $this->diff_recursive($value, $array2[$key]);
                if (!empty($new_diff)) {
                    $difference[$key] = $new_diff;
                }
            } elseif (is_string($value) && !in_array($value, $array2)) {
                // the value is a string and it's not in array B
                $difference[$key] = $value;
            } elseif (!is_numeric($key) && !array_key_exists($key, $array2)) {
                // the key is not numberic and is missing from array B
                $difference[$key] = $value;
            }
        }

        return $difference;
    }

    /**
     * Parses inputted data for wiki-style links, and returns
     * formatted data.
     *
     * @param array $data
     *
     * @return array
     */
    public function parse_wiki_links($data) {
        $data['parsed'] = $data;

        foreach ($data['parsed'] as $key=>$item) {
            $i = 1;
            // Test content against both a wiki-style link pattern without label and one with
            foreach (['/\[\[([a-zA-Z0-9_-_!-\/:;\^-`{}~\sÀ-ɏʰ-˾̀-ͯ᷀-᷿Ḁ-ỿ]+)\]\]/', '/\[\[([a-zA-Z0-9_-_!-\/:;\^-`{}~\sÀ-ɏʰ-˾̀-ͯ᷀-᷿Ḁ-ỿ]+)\|([a-zA-Z0-9_-_!-\/:;\^-`{}~\sÀ-ɏʰ-˾̀-ͯ᷀-᷿Ḁ-ỿ]+)\]\]/'] as $pattern) {
                $i2 = 0;

                $matches = null;
                $links = [];
                if (is_string($item)) {
                    $count = preg_match_all($pattern, $item, $matches);
                }
                if (isset($count) && $count && isset($matches[1])) {
                    foreach ($matches[1] as $match) {
                        // Convert HTML entities back to the relevant special characters
                        $decoded = html_entity_decode($match);

                        // Attempt to locate an associated page
                        $page = Page::get()->where('displayTitle', $decoded)->first();

                        // Make a version of the match suitable for regex replacement
                        $regexMatch = str_replace('(', '\(', $match);
                        $regexMatch = str_replace(')', '\)', $regexMatch);

                        // If there is a page, simply substitute out the text for a proper link
                        if ($page) {
                            if ($i == 1) {
                                $item = preg_replace('/\[\['.$regexMatch.'\]\]/', $page->displayName, $item);
                            } elseif ($i == 2) {
                                $item = preg_replace('/\[\['.$regexMatch.'\|'.$matches[$i][$i2].'\]\]/', '<a href="'.$page->url.'" class="text-primary"'.($page->summary ? ' data-toggle="tooltip" title="'.$page->summary.'"' : '').'>'.$matches[$i][$i2].'</a>', $item);
                            }
                            // And make a note that the page is being linked to
                            $data['links'][] = [
                                'link_id' => $page->id,
                            ];
                        } else {
                            if ($i == 1) {
                                $item = preg_replace('/\[\['.$regexMatch.'\]\]/', '<a href="'.url('special/create-wanted/'.str_replace(' ', '_', $match)).'" class="text-danger">'.$match.'</a>', $item);
                            } elseif ($i == 2) {
                                $item = preg_replace('/\[\['.$regexMatch.'\|'.$matches[$i][$i2].'\]\]/', '<a href="'.url('special/create-wanted/'.str_replace(' ', '_', $match)).'" class="text-danger">'.$matches[$i][$i2].'</a>', $item);
                            }

                            // If there's no page yet, log a placeholder link
                            // This won't do much, but it will store two pieces of info:
                            // 1. That the linked-to page is wanted
                            // 2. That this specific page tried to link to it
                            // which will help generate maintenance reports and, when the
                            // page is created, help update this page.
                            $data['links'][] = [
                                'title' => $decoded,
                            ];
                        }
                        $i2++;
                    }
                }
                $i++;
            }
            $data['parsed'][$key] = $item;
        }

        return $data;
    }

    /**
     * Add an error to Laravel session $errors.
     *
     * @author Pavel Lint via https://stackoverflow.com/questions/58690463/best-way-to-store-error-messages-in-laravel-session-or-variable with modifications
     *
     * @param string $key
     * @param bool   $flash
     * @param string $error_msg
     */
    public function addError($error_msg, $flash = true, $key = 'default') {
        $errors = Session::get('errors', new ViewErrorBag);

        if (!$errors instanceof ViewErrorBag) {
            $errors = new ViewErrorBag;
        }

        $bag = $errors->getBags()['default'] ?? new MessageBag;
        $bag->add($key, $error_msg);

        Session::flash(
            'errors',
            $errors->put('default', $bag)
        );

        if ($flash) {
            flash($error_msg)->error();
        }
    }

    /**
     * Calls a service method and injects the required dependencies.
     *
     * @param string $methodName
     *
     * @return mixed
     */
    protected function callMethod($methodName) {
        if (method_exists($this, $methodName)) {
            return App::call([$this, $methodName]);
        }
    }

    /**
     * Add an error to the MessageBag.
     *
     * @param string $key
     * @param string $value
     */
    protected function setError($key, $value) {
        $this->errors->add($key, $value);
    }

    /**
     * Add multiple errors to the message bag.
     *
     * @param Illuminate\Support\MessageBag $errors
     */
    protected function setErrors($errors) {
        $this->errors->merge($errors);
    }

    /**
     * Commits the current DB transaction and returns a value.
     *
     * @param mixed $return
     *
     * @return mixed $return
     */
    protected function commitReturn($return = true) {
        DB::commit();

        return $return;
    }

    /**
     * Rolls back the current DB transaction and returns a value.
     *
     * @param mixed $return
     *
     * @return mixed $return
     */
    protected function rollbackReturn($return = false) {
        DB::rollback();

        return $return;
    }

    /**
     * Returns the current field if it is numeric, otherwise searches for a field if it is an array or object.
     *
     * @param mixed  $data
     * @param string $field
     *
     * @return mixed
     */
    protected function getNumeric($data, $field = 'id') {
        if (is_numeric($data)) {
            return $data;
        } elseif (is_object($data)) {
            return $data->$field;
        } elseif (is_array($data)) {
            return $data[$field];
        } else {
            return 0;
        }
    }

    // Moves an old image within the same directory.
    private function moveImage($dir, $name, $oldName, $copy = false) {
        if ($copy) {
            File::copy($dir.'/'.$oldName, $dir.'/'.$name);
        } else {
            File::move($dir.'/'.$oldName, $dir.'/'.$name);
        }

        return true;
    }

    // Moves an uploaded image into a directory, checking if it exists.
    private function saveImage($image, $dir, $name, $copy = false) {
        if (!file_exists($dir)) {
            // Create the directory.
            if (!mkdir($dir, 0755, true)) {
                $this->setError('error', 'Failed to create image directory.');

                return false;
            }
            chmod($dir, 0755);
        }
        if ($copy) {
            File::copy($image, $dir.'/'.$name);
        } else {
            File::move($image, $dir.'/'.$name);
        }
        chmod($dir.'/'.$name, 0755);

        return true;
    }
}
