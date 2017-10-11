<?php

namespace ModularityFormBuilder;

class Form extends \Modularity\Module
{
    public $slug = 'form';
    public $nameSingular = 'Form';
    public $namePlural = 'Forms';
    public $description = 'Build submittable forms';
    public $icon = 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4KPHN2ZyB3aWR0aD0iMzJweCIgaGVpZ2h0PSIyNHB4IiB2aWV3Qm94PSIwIDAgMzIgMjQiIHZlcnNpb249IjEuMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayI+CiAgICA8IS0tIEdlbmVyYXRvcjogU2tldGNoIDQzICgzODk5OSkgLSBodHRwOi8vd3d3LmJvaGVtaWFuY29kaW5nLmNvbS9za2V0Y2ggLS0+CiAgICA8dGl0bGU+bm91bl81OTgzNzFfY2M8L3RpdGxlPgogICAgPGRlc2M+Q3JlYXRlZCB3aXRoIFNrZXRjaC48L2Rlc2M+CiAgICA8ZGVmcz48L2RlZnM+CiAgICA8ZyBpZD0iUGFnZS0xIiBzdHJva2U9Im5vbmUiIHN0cm9rZS13aWR0aD0iMSIgZmlsbD0ibm9uZSIgZmlsbC1ydWxlPSJldmVub2RkIj4KICAgICAgICA8ZyBpZD0ibm91bl81OTgzNzFfY2MiIGZpbGwtcnVsZT0ibm9uemVybyIgZmlsbD0iIzAwMDAwMCI+CiAgICAgICAgICAgIDxnIGlkPSJHcm91cCI+CiAgICAgICAgICAgICAgICA8cGF0aCBkPSJNMjkuNSwyMCBMMTQsMjAgQzEzLjQ0OCwyMCAxMywxOS41NTMgMTMsMTkgQzEzLDE4LjQ0NyAxMy40NDgsMTggMTQsMTggTDI5LjUsMTggQzI5Ljc3NSwxOCAzMCwxNy43NzUgMzAsMTcuNSBMMzAsNi41IEMzMCw2LjIyNCAyOS43NzUsNiAyOS41LDYgTDE0LDYgQzEzLjQ0OCw2IDEzLDUuNTUyIDEzLDUgQzEzLDQuNDQ4IDEzLjQ0OCw0IDE0LDQgTDI5LjUsNCBDMzAuODc5LDQgMzIsNS4xMjIgMzIsNi41IEwzMiwxNy41IEMzMiwxOC44NzkgMzAuODc5LDIwIDI5LjUsMjAgWiIgaWQ9IlNoYXBlIj48L3BhdGg+CiAgICAgICAgICAgICAgICA8cGF0aCBkPSJNNiwyMCBMMi41LDIwIEMxLjEyMiwyMCAwLDE4Ljg3OSAwLDE3LjUgTDAsNi41IEMwLDUuMTIyIDEuMTIyLDQgMi41LDQgTDYsNCBDNi41NTIsNCA3LDQuNDQ4IDcsNSBDNyw1LjU1MiA2LjU1Miw2IDYsNiBMMi41LDYgQzIuMjI0LDYgMiw2LjIyNCAyLDYuNSBMMiwxNy41IEMyLDE3Ljc3NSAyLjIyNCwxOCAyLjUsMTggTDYsMTggQzYuNTUyLDE4IDcsMTguNDQ3IDcsMTkgQzcsMTkuNTUzIDYuNTUyLDIwIDYsMjAgWiIgaWQ9IlNoYXBlIj48L3BhdGg+CiAgICAgICAgICAgICAgICA8cGF0aCBkPSJNMTAsMjQgQzkuNDQ4LDI0IDksMjMuNTUzIDksMjMgTDksMSBDOSwwLjQ0OCA5LjQ0OCwwIDEwLDAgQzEwLjU1MiwwIDExLDAuNDQ4IDExLDEgTDExLDIzIEMxMSwyMy41NTMgMTAuNTUyLDI0IDEwLDI0IFoiIGlkPSJTaGFwZSI+PC9wYXRoPgogICAgICAgICAgICAgICAgPHBhdGggZD0iTTEzLDIgTDcsMiBDNi40NDgsMiA2LDEuNTUyIDYsMSBDNiwwLjQ0OCA2LjQ0OCwwIDcsMCBMMTMsMCBDMTMuNTUyLDAgMTQsMC40NDggMTQsMSBDMTQsMS41NTIgMTMuNTUyLDIgMTMsMiBaIiBpZD0iU2hhcGUiPjwvcGF0aD4KICAgICAgICAgICAgICAgIDxwYXRoIGQ9Ik0xMywyNCBMNywyNCBDNi40NDgsMjQgNiwyMy41NTMgNiwyMyBDNiwyMi40NDcgNi40NDgsMjIgNywyMiBMMTMsMjIgQzEzLjU1MiwyMiAxNCwyMi40NDcgMTQsMjMgQzE0LDIzLjU1MyAxMy41NTIsMjQgMTMsMjQgWiIgaWQ9IlNoYXBlIj48L3BhdGg+CiAgICAgICAgICAgIDwvZz4KICAgICAgICA8L2c+CiAgICA8L2c+Cjwvc3ZnPg==';
    public $supports = array();
    public $plugin = array();
    public $cacheTtl = 3600 * 24 * 7; // Defaults to 7 days
    public $hideTitle  = false;
    public $isDeprecated = false;

    /**
     * Available "magic" methods for modules:
     * init()            What to do on initialization (if you must, use __construct with care, this will probably break stuff!!)
     * data()            Use to send data to view (return array)
     * style()           Enqueue style only when module is used on page
     * script            Enqueue script only when module is used on page
     * adminEnqueue()    Enqueue scripts for the module edit/add page in admin
     * template()        Return the view template (blade) the module should use when displayed
     */

    public function init()
    {
        $this->nameSingular = __('Form', 'modularity-form-builder');
        $this->namePlural = __('Forms', 'modularity-form-builder');
        $this->description = __('Build submittable forms', 'modularity-form-builder');

        add_action('add_meta_boxes', array($this, 'metaBoxResponses'), 10, 2);
        add_action('current_screen', array($this, 'export'));
        add_action('wp_ajax_get_selected_field', array($this, 'getSelectedField'));
    }

    /**
     * Export from submissions
     * @return void
     */
    public function export()
    {
        if (!is_admin()) {
            return;
        }

        $screen = get_current_screen();

        if ($screen->post_type !== 'mod-form' || !isset($_GET['post']) || !isset($_GET['export'])) {
            return;
        }

        $formId = $_GET['post'];
        $form = get_post($formId);
        $submissions = new \WP_Query(array(
            'posts_per_page' => -1,
            'post_type' => 'form-submissions',
            'post_status' => 'publish',
            'meta_query' => array(
                'relation' => 'OR',
                array(
                    'key' => 'modularity-form-id',
                    'value' => $formId,
                    'compare' => '='
                )
            )
        ));

        $submissions = $submissions->posts;
        $csvData = array();

        foreach ($submissions as $submission) {
            $data = \ModularityFormBuilder\Submission::getSubmissionData($submission->ID);
            $csvData[] = $data;
        }

        $this->downloadSendHeaders(sanitize_title($form->post_title) . '_' . date('Y-m-d') . '.csv');
        echo chr(239) . chr(187) . chr(191);
        echo $this->array2csv($csvData);
        die();
    }

    public function array2csv(array &$array)
    {
        if (count($array) == 0) {
            return null;
        }

        ob_start();
        $df = fopen("php://output", 'w');
        fputcsv($df, array_keys(reset($array)), ';');

        foreach ($array as $row) {
            fputcsv($df, $row, ';');
        }

        fclose($df);
        return ob_get_clean();
    }

    public function downloadSendHeaders($filename)
    {
        // disable caching
        $now = gmdate("D, d M Y H:i:s");
        header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
        header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
        header("Last-Modified: {$now} GMT");

        // force download
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");

        // disposition / encoding on response body
        header("Content-Disposition: attachment;filename={$filename}");
        header("Content-Transfer-Encoding: binary");
    }

    /**
     * Add responses metabox
     * @param  string $postType
     * @param  WP_Post $post
     * @return void
     */
    public function metaBoxResponses($postType, $post)
    {
        if ($postType !== 'mod-form') {
            return;
        }

        add_meta_box('form-responses', __('Responses', 'modularity-form-builder'), array($this, 'showResponses'), $postType, 'normal', 'high');
    }

    /**
     * Show responses
     * @return void
     */
    public function showResponses()
    {
        global $post;

        $query = new \WP_Query(array(
            'post_type' => 'form-submissions',
            'posts_per_page' => -1,
            'meta_query' => array(
                'relation' => 'OR',
                array(
                    'key' => 'modularity-form-id',
                    'value' => $post->ID,
                    'compare' => '='
                )
            )
        ));

        $submissions = $query->posts;

        if (empty($submissions)) {
            echo '<p>' . __('There is no submissions for this form.', 'modularity-form-builder') . '</p>';
            return;
        }

        echo '<p>';
        echo sprintf(__('There is %d submissions to this form.', 'modularity-form-builder'), count($submissions));
        echo '</p><p>';
        echo '<a href="' . admin_url('edit.php?post_type=form-submissions&form=' . $post->ID) . '" class="button">' . __('View submissions', 'modularity-form-builder') . '</a>';
        echo ' <a href="' . admin_url('post.php?post=' . $post->ID . '&action=edit&export=csv') . '" class="button" target="_blank">' . __('Export csv', 'modularity-form-builder') . '</a>';
        echo '</p>';
    }

    /**
     * View data
     * @return array
     */
    public function data() : array
    {
        $data = get_fields($this->ID);
        $data['classes'] = implode(' ', apply_filters('Modularity/Module/Classes', array('box', 'box-panel'), $this->post_type, $this->args));
        $data['module_id'] = $this->ID;
        $data['hasFileUpload'] = false;

        foreach ($data['form_fields'] as &$field) {
            $field['conditional_hidden'] = '';
            if (!empty($field['conditional_logic']) && !empty($field['conditonal_field'])) {
                $field['conditional_hidden'] = "style='display:none;' conditional-target='" . $field['conditonal_field'] . "'";
            }

            if ($field['acf_fc_layout'] === 'radio') {
                foreach ($field['values'] as &$value) {
                    $label = $this->conditionalString($field['label']);
                    $option_value = $this->conditionalString($value['value']);
                    $conditional_value = array(
                                        'label' => $label,
                                        'value' => $option_value
                                    );
                    $value['conditional_value'] = json_encode($conditional_value);
                }
            }

            if (isset($field['required_fields']) && empty($field['required_fields'])) {
                $field['required_fields'] = array();
            }

            if ($field['acf_fc_layout'] === 'file_upload') {
                $data['hasFileUpload'] = true;
            }
        }

        return $data;
    }

    /**
     * Replace spaces and specials chars from string
     * @param  string $string String to be replaced
     * @return string         Modified string
     */
    public function conditionalString($string)
    {
        $string = str_replace(' ', '_', strtolower($string));
        $string = preg_replace('/[^a-z0-9\_]/', '', $string);

        return $string;
    }

    /**
     * Enqueue required styles and scripts for admin ui
     * @return void
     */
    public function adminEnqueue()
    {
        wp_register_script('form-builder', FORM_BUILDER_MODULE_URL . '/dist/js/form-builder-admin.min.js', true);
        wp_localize_script('form-builder', 'formbuilder', array(
            'selections_missing' => __("Please create radio selections before adding conditional logic.", 'modularity-form-builder'),
        ));
        wp_enqueue_script('form-builder');
    }

    /**
     * Enqueue required scripts for front ui
     * @return void
     */
    public function script()
    {
        wp_register_script('form-builder', FORM_BUILDER_MODULE_URL . '/dist/js/form-builder-front.min.js', array('jquery'), false, true);
        wp_enqueue_script('form-builder');
    }

    /**
     * Get conditional select field from database
     * @return string
     */
    public function getSelectedField()
    {
        $selected  = 'error';
        if (!isset($_POST['moduleId']) || !isset($_POST['fieldName'])) {
            echo $selected;
            die();
        }

        $moduleId =  $_POST['moduleId'];
        $fieldName = $_POST['fieldName'];
        preg_match('#\[(\d+)\]#', $fieldName, $match);
        if (!empty($match[1])) {
            $key = 'form_fields_' . $match[1] . '_conditonal_field';
            $selected = get_post_meta($moduleId, $key, true);
        }

        echo $selected;
        die();
    }
}
