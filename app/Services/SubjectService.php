<?php namespace App\Services;

use App\Services\Service;

use DB;

use App\Models\Subject\SubjectTemplate;
use App\Models\Subject\SubjectCategory;

class SubjectService extends Service
{
    /*
    |--------------------------------------------------------------------------
    | Subject Service
    |--------------------------------------------------------------------------
    |
    | Handles the editing of subject templates and creation of categories.
    |
    */

    /**
     * Updates a subject template.
     *
     * @param  string                        $subject
     * @param  array                         $data
     * @param  \App\Models\User\User         $user
     * @return bool|\App\Models\SubjectTemplate
     */
    public function editTemplate($subject, $data, $user)
    {
        DB::beginTransaction();

        try {
            // Record subject
            $data['subject'] = $subject;
            $template = SubjectTemplate::where('subject', $subject)->first();

            // Collect and record infobox and form fields
            $data = $this->processTemplateData($data);

            // Check if changes should cascade, and if so, perform comparison
            // and make updates as necessary
            if($template && (isset($data['cascade_template']) && $data['cascade_template']) && $data['data'] != $template->data) {
                // First find any impacted categories
                $categories = $template->categories()->whereNotNull('data')->get();

                // Collect existing template data
                $data['old'] = $template->data;

                // Cascade changes to impacted categories
                $this->cascadeTemplateChanges($categories, $data);
            }

            // Encode data before saving either way, for convenience
            if(isset($data['data'])) $data['data'] = json_encode($data['data']);
            else $data['data'] = null;

            // Either create or update template data
            if(!$template)
                $template = SubjectTemplate::create($data);
            else
                $template->update($data);

            return $this->commitReturn($template);
        } catch(\Exception $e) {
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }

    /**
     * Creates a category.
     *
     * @param  array                         $data
     * @param  \App\Models\User\User         $user
     * @param  string                        $subject
     * @return bool|\App\Models\SubjectCategory
     */
    public function createCategory($data, $user, $subject)
    {
        DB::beginTransaction();

        try {
            // Record subject
            $data['subject'] = $subject;

            // Collect and record infobox and form fields
            $data = $this->processTemplateData($data);

            // Overwrite with data from subject template if necessary
            if(isset($data['populate_template']) && $data['populate_template']) {
                if(isset($data['parent_id'])) $parent = SubjectCategory::find($data['parent_id']);
                $data['data'] = isset($parent) && $parent ? $parent->data : SubjectTemplate::where('subject', $subject)->first()->data;
            }

            // Encode data before saving either way, for convenience
            if(isset($data['data'])) $data['data'] = json_encode($data['data']);
            else $data['data'] = null;

            // Create category
            $category = SubjectCategory::create($data);

            return $this->commitReturn($category);
        } catch(\Exception $e) {
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }

    /**
     * Updates a category.
     *
     * @param  \App\Models\Gallery\Category   $category
     * @param  array                          $data
     * @param  \App\Models\User\User          $user
     * @return \App\Models\Gallery\Project|bool
     */
    public function updateCategory($category, $data, $user)
    {
        DB::beginTransaction();

        try {
            // More specific validation
            if(SubjectCategory::where('name', $data['name'])->where('id', '!=', $category->id)->exists()) throw new \Exception("The name has already been taken.");

            // Collect and record template information
            $data = $this->processTemplateData($data);

            // Overwrite with data from subject template if necessary
            if(isset($data['populate_template']) && $data['populate_template'])
                $data['data'] = $category->parent ? $category->parent->data : $category->subjectTemplate->data;

            // Check if changes should cascade, and if so, perform comparison
            // and make updates as necessary
            if((isset($data['cascade_template']) && $data['cascade_template']) && $data['data'] != $category->data) {
                // Collect existing template data
                $data['old'] = $category->data;

                // Find any impacted categories
                $categories = $category->children()->whereNotNull('data')->get();

                // Check if changes should be cascaded recursively (to the childrens' children)
                if(isset($data['cascade_recursively']) && $data['cascade_recursively']) {
                    $this->cascadeTemplateChangesRecursively($categories, $data);
                }
                else {
                    // Cascade changes to impacted categories
                    $this->cascadeTemplateChanges($categories, $data);
                }
            }

            // Encode data before saving either way, for convenience
            if(isset($data['data'])) $data['data'] = json_encode($data['data']);
            else $data['data'] = null;

            // Update category
            $category->update($data);

            return $this->commitReturn($category);
        } catch(\Exception $e) {
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }

    /**
     * Processes template information.
     *
     * @param  array              $data
     * @return array
     */
    private function processTemplateData($data)
    {
        // Collect and record sections if present
        if(isset($data['section_key'])) foreach($data['section_key'] as $key=>$section) {
            $data['data']['sections'][strtolower($section)] = $data['section_name'][$key];
        }

        // Format and record infobox fields if present
        if(isset($data['infobox_key'])) foreach($data['infobox_key'] as $key=>$fieldKey) {
            if(isset($data['infobox_choices'][$key]))
                $data['infobox_choices'][$key] = explode(',', $data['field_choices'][$key]);

            $data['data']['infobox'][$fieldKey] = [
                'label' => $data['infobox_label'][$key],
                'type' => $data['infobox_type'][$key],
                'rules' => isset($data['infobox_rules'][$key]) ? $data['infobox_rules'][$key] : null,
                'choices' => isset($data['infobox_choices'][$key]) ? $data['infobox_choices'][$key] : null,
                'value' => isset($data['infobox_value'][$key]) ? $data['infobox_value'][$key] : null,
                'help' => isset($data['infobox_help'][$key]) ? $data['infobox_help'][$key] : null
            ];
        }

        // Format and record widgets if present
        if(isset($data['widget_key'])) foreach($data['widget_key'] as $key=>$widget) {
            $data['data']['widgets'][$data['widget_section'][$key]][] = $widget;
        }

        // Format and record form fields if present
        if(isset($data['field_key'])) foreach($data['field_key'] as $key=>$fieldKey) {
            if(isset($data['field_choices'][$key]))
                $data['field_choices'][$key] = explode(',', $data['field_choices'][$key]);

            $data['data']['fields'][$data['field_section'][$key]][$fieldKey] = [
                'label' => $data['field_label'][$key],
                'type' => $data['field_type'][$key],
                'rules' => isset($data['field_rules'][$key]) ? $data['field_rules'][$key] : null,
                'choices' => isset($data['field_choices'][$key]) ? $data['field_choices'][$key] : null,
                'value' => isset($data['field_value'][$key]) ? $data['field_value'][$key] : null,
                'help' => isset($data['field_help'][$key]) ? $data['field_help'][$key] : null,
                'is_subsection' => $data['field_is_subsection'][$key]
            ];
        }

        return $data;
    }

    /**
     * Cascades template changes.
     *
     * @param Illuminate\Database\Eloquent\Collection    $categories
     * @param  array                                     $data
     * @return array
     */
    private function cascadeTemplateChangesRecursively($categories, $data)
    {
        $this->cascadeTemplateChanges($categories, $data);

        foreach($categories as $category) {
            if($category->children()->count()) {
                // Find any impacted categories
                $categories = $category->children()->whereNotNull('data')->get();

                // Cascade changes to impacted categories
                $this->cascadeTemplateChangesRecursively($categories, $data);
            }
        }

        return $data;
    }

    /**
     * Cascades template changes.
     *
     * @param Illuminate\Database\Eloquent\Collection    $categories
     * @param  array                                     $data
     * @return array
     */
    private function cascadeTemplateChanges($categories, $data)
    {
        // Recursively compare arrays
        $data['changes']['added'] = $this->array_diff_recursive($data['data'], $data['old']);
        $data['changes']['removed'] = $this->array_diff_recursive($data['old'], $data['data']);

        // Perform operations on impacted categories
        foreach($categories as $key=>$category) {
            $categoryData[$key] = $category->data;
            // Perform any removals
            if(isset($data['changes']['removed'])) {
                foreach($data['changes']['removed'] as $segment=>$items) {
                    if($segment == 'fields' || $segment == 'widgets') {
                        // If segment is nested, step down first
                        foreach($items as $section=>$sectionData) {
                            foreach($sectionData as $itemKey=>$item) {
                                // Check to see if key exists in the array and
                                // unset if so
                                if(array_key_exists(($segment == 'widgets' ? $itemKey : $item), $category->data[$segment][$section]))
                                    unset($categoryData[$key][$segment][$section][($segment == 'widgets' ? $itemKey : $item)]);
                            }
                        }
                    }
                    else {
                        // If segment is not nested, simply proceed
                        if(isset($data['changes']['removed'][$segment]) && $data['changes']['removed'][$segment])
                            foreach($items as $item) {
                            // Check to see if key exists in the array and
                            // unset if so
                            if(array_key_exists($item, $category->data[$segment]))
                                unset($categoryData[$key][$segment][$item]);
                            }
                    }
                }
            }

            // Perform any additions
            if(isset($data['changes']['added'])) {
                foreach($data['changes']['added'] as $segment=>$items) {
                    if($segment == 'fields' || $segment == 'widgets') {
                        // If segment is nested, step down first
                        foreach($items as $section=>$sectionData) {
                            foreach($sectionData as $itemKey=>$item) {
                                // Check to see if the item should be inserted
                                if(!isset($category->data[$segment][$section]) || !array_key_exists($item, $category->data[$segment][$section])) {
                                    // If so, append it to the end of the array
                                    if(!isset($categoryData[$key][$segment][$section]) || !array_key_exists($item, $categoryData[$key][$segment][$section]))
                                        $categoryData[$key][$segment][$section][$itemKey] = $data['changes']['added'][$segment][$section][$itemKey];
                                }

                            }
                        }
                    }
                    else {
                        // If segment is not nested, simply proceed
                        if(isset($data['changes']['added'][$segment]) && $data['changes']['added'][$segment])
                            foreach($items as $itemKey=>$item) {
                            // Check to see if the item should be inserted
                                if(!array_key_exists($item, $category->data[$segment])) {
                                    // If so, append it to the end of the array
                                    if(!isset($categoryData[$key][$segment]) || !array_key_exists($item, $categoryData[$key][$segment]))
                                        $categoryData[$key][$segment][$itemKey] = $data['changes']['added'][$segment][$itemKey];
                                }
                            }
                    }
                }
            }

            // Update the category
            $categoryData[$key] = json_encode($categoryData[$key]);
            $category->update(['data' => $categoryData[$key]]);
        }

        return $data;
    }

}
