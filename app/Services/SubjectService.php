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

}
