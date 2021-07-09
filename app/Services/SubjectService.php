<?php namespace App\Services;

use App\Services\Service;

use DB;

use App\Models\Subject\SubjectTemplate;
use App\Models\Subject\SubjectCategory;

use App\Models\Subject\TimeDivision;
use App\Models\Subject\TimeChronology;

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
     * @return bool|\App\Models\Subject\SubjectCategory
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
     * @param  \App\Models\Subject\SubjectCategory  $category
     * @param  array                                $data
     * @param  \App\Models\User\User                $user
     * @return \App\Models\Subject\SubjectCategory|bool
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
     * Delete a category.
     *
     * @param  \App\Models\Subject\SubjectCategory  $category
     * @return bool
     */
    public function deleteCategory($category)
    {
        DB::beginTransaction();

        try {
            // Check first if the project is currently in use
            if(SubjectCategory::where('parent_id', $category->id)->exists()) throw new \Exception('A sub-category of this category exists. Please move or delete it first.');
            //if(Piece::where('project_id', $project->id)->exists()) throw new \Exception("A piece with this category exists. Please move or delete it first.");

            $category->delete();

            return $this->commitReturn(true);
        } catch(\Exception $e) {
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }

    /**
     * Sorts category order.
     *
     * @param  array   $data
     * @param  string  $subject
     * @return bool
     */
    public function sortCategory($data, $subject)
    {
        DB::beginTransaction();

        try {
            // explode the sort array and reverse it since the order is inverted
            $sort = array_reverse(explode(',', $data));

            foreach($sort as $key => $s) {
                SubjectCategory::where('subject', $subject)->where('id', $s)->update(['sort' => $key]);
            }

            return $this->commitReturn(true);
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
        $data['changes']['added'] = $this->diff_recursive($data['data'], $data['old']);
        $data['changes']['removed'] = $this->diff_recursive($data['old'], $data['data']);

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
                                // Check to see if key exists in the array and unset if so
                                if(array_key_exists($itemKey, $category->data[$segment][$section]))
                                    unset($categoryData[$key][$segment][$section][$itemKey]);
                            }
                        }
                    }
                    else {
                        // If segment is not nested, simply proceed
                        if(isset($data['changes']['removed'][$segment]) && $data['changes']['removed'][$segment])
                            foreach($items as $item) {
                                // Check to see if key exists in the array and unset if so
                                if(array_key_exists($item, $category->data[$segment]))
                                    unset($categoryData[$key][$segment][$item]);
                            }
                    }
                }
            }

            // Recursively perform any additions
            $categoryData[$key] = array_replace_recursive($categoryData[$key], $data['changes']['added']);

            // Update the category
            $categoryData[$key] = json_encode($categoryData[$key]);
            $category->update(['data' => $categoryData[$key]]);
        }

        return $data;
    }

    /******************************************************************************
        SPECIALIZED - TIME
    *******************************************************************************/

    /**
     * Updates time divisions.
     *
     * @param  array                         $data
     * @param  \App\Models\User\User         $user
     * @return bool|\App\Models\TimeDivision
     */
    public function editTimeDivisions($data, $user)
    {
        DB::beginTransaction();

        try {
            // Remove divisions not present in the form data
            TimeDivision::whereNotIn('name', $data['name'])->delete();

            // Process each entered division
            foreach($data['name'] as $key=>$name) {
                // More specific validation
                foreach($data['name'] as $subKey=>$subName) if($subName == $name && $subKey != $key) throw new \Exception("The name has already been taken.");

                $division = TimeDivision::where('name', $name)->first();

                // Assemble data
                $data[$key] = [
                    'name' => $data['name'][$key],
                    'abbreviation' => isset($data['abbreviation'][$key]) ? $data['abbreviation'][$key] : null,
                    'unit' => isset($data['unit'][$key]) ? $data['unit'][$key] : null
                ];

                // Create or update division data
                if(!$division)
                    $divisions[] = TimeDivision::create($data[$key]);
                else {
                    $division->update($data[$key]);
                    $divisions[] = $division;
                }
            }

            // Process sort information
            if(isset($data['sort'])) {
                // explode the sort array and reverse it since the order is inverted
                $sort = array_reverse(explode(',', $data['sort']));

                foreach($sort as $key => $s) {
                    TimeDivision::where('id', $s)->update(['sort' => $key]);
                }
            }

            return $this->commitReturn($divisions);
        } catch(\Exception $e) {
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }

    /**
     * Creates a chronology.
     *
     * @param  array                         $data
     * @param  \App\Models\User\User         $user
     * @return bool|\App\Models\TimeChronology
     */
    public function createChronology($data, $user)
    {
        DB::beginTransaction();

        try {
            // Create chronology
            $chronology = TimeChronology::create($data);

            return $this->commitReturn($chronology);
        } catch(\Exception $e) {
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }

    /**
     * Updates a chronology.
     *
     * @param  \App\Models\Subject\TimeChronology    $chronology
     * @param  array                                 $data
     * @param  \App\Models\User\User                 $user
     * @return \App\Models\Subject\TimeChronology|bool
     */
    public function updateChronology($chronology, $data, $user)
    {
        DB::beginTransaction();

        try {
            // More specific validation
            if(TimeChronology::where('name', $data['name'])->where('id', '!=', $chronology->id)->exists()) throw new \Exception("The name has already been taken.");

            // Update chronology
            $chronology->update($data);

            return $this->commitReturn($chronology);
        } catch(\Exception $e) {
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }

    /**
     * Delete a chronology.
     *
     * @param  \App\Models\Subject\TimeChronology  $chronology
     * @return bool
     */
    public function deleteChronology($chronology)
    {
        DB::beginTransaction();

        try {
            // Check first if the project is currently in use
            if(TimeChronology::where('parent_id', $chronology->id)->exists()) throw new \Exception('A sub-chronology of this chronology exists. Please move or delete it first.');
            //if(Piece::where('project_id', $project->id)->exists()) throw new \Exception("A piece with this chronology exists. Please move or delete it first.");

            $chronology->delete();

            return $this->commitReturn(true);
        } catch(\Exception $e) {
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }

    /**
     * Sorts chronology order.
     *
     * @param  array   $data
     * @param  string  $subject
     * @return bool
     */
    public function sortChronology($data, $subject)
    {
        DB::beginTransaction();

        try {
            // explode the sort array and reverse it since the order is inverted
            $sort = array_reverse(explode(',', $data));

            foreach($sort as $key => $s) {
                TimeChronology::where('subject', $subject)->where('id', $s)->update(['sort' => $key]);
            }

            return $this->commitReturn(true);
        } catch(\Exception $e) {
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }

}
