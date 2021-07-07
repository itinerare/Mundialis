<?php namespace App\Services;

use App\Services\Service;

use DB;

use App\Models\Subject\SubjectTemplate;

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
            $template = SubjectTemplate::where('subject', $subject)->first();

            // Collect and record sections
            if(isset($data['section_key'])) foreach($data['section_key'] as $key=>$section) {
                $data['data']['sections'][strtolower($section)] = $data['section_name'][$key];
            }

            // Collect and record infobox and form fields
            $data = $this->processFormFields($data);

            // Encode data before saving either way, for convenience
            if(isset($data['data'])) $data['data'] = json_encode($data['data']);
            else $data['data'] = null;

            // Either create or update template data
            if(!$template)
                $template = SubjectTemplate::create([
                    'subject' => $subject,
                    'data' => $data['data']
                ]);
            else
                $template->update($data);

            return $this->commitReturn($template);
        } catch(\Exception $e) {
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }

    /**
     * Processes form field information.
     *
     * @param  array              $data
     * @return array
     */
    private function processFormFields($data)
    {
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
