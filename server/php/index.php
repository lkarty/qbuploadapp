<?php

/*
 * jQuery File Upload Plugin PHP Example 5.14
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

/* replacement for BlueImp UploadHandler class (extends UploadHandler)
 * to provide specific requirements for FSS documentation uploads
 */
set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER["DOCUMENT_ROOT"]);

error_reporting(E_ALL | E_STRICT);

require_once 'apps/deliverable_uploads/server/php/UploadHandler.php';
require_once 'common/QuickBase/classes/QuickBaseUpdate.class.php';

global $qb_rid;

$project      = $_REQUEST['project_no'];
$workorder    = $_REQUEST['work_order'];
$tech_id      = $_REQUEST['tech_id'];
$site_id      = $_REQUEST['site_id'];
$row_id       = $_REQUEST['row_id'];
$ins_date     = str_replace("/", "-", $_REQUEST['install_date']);
$upl_time     = '0000000000';
$related_wko  = $_REQUEST['related_wko'];
$related_tech = $_REQUEST['related_tech'];
$upl_src      = $_REQUEST['upl_src'];
$doc_type     = $_REQUEST['doc_type'];
$uploader     = (isset($_REQUEST['user_name']) && ($_REQUEST['user_name'] > "")) ? $_REQUEST['user_name'] : $_REQUEST['tech_name'];

class CustomUploadHandler extends UploadHandler {

    public $qb_rid;
    protected $related_wko, $related_tech;
    protected $project, $workorder;
    protected $tech_id, $site_id, $row_id;
    protected $doc_type, $ins_date;
    protected $upl_name, $upl_locn, $upl_time, $upl_src, $uploader;

    function __construct($uploader, $related_wko = '0', $related_tech = '0', $project = 'test', $workorder = 'test', $tech_id = 'unknown', $site_id = 'test', $row_id = '0', $doc_type = 'test', $ins_date = '10-12-1974', $upl_time = '0000000000', $upl_src = 'external', $options = null, $initialize = true, $error_messages = null) {

        $this->uploader     = $uploader;
        $this->related_wko  = $related_wko;
        $this->related_tech = $related_tech;
        $this->project      = $project;
        $this->workorder    = $workorder;
        $this->tech_id      = $tech_id;
        $this->site_id      = $site_id;
        $this->row_id       = $row_id;
        $this->doc_type     = $doc_type;
        $this->ins_date     = $ins_date;
        $this->upl_time     = $this->mstime();
        $this->upl_src      = $upl_src;
        //parent::__construct(array('image_versions' => array()), $initialize, $error_messages, $project, $workorder);
        parent::__construct($options, $initialize, $error_messages, $project, $workorder);
    }

    /* this does not appear to be used??? */

    protected function handle_form_data($file, $index) {
        // unused?
    }

    /**
     * this is where we do filename replacement processing
     * 
     *  give new descriptive (but unique) name to uploaded file 
     *  NOTE: doc_type now bound via fileuploadsubmit to #fileupload' in index.php
     *  current bug: ins_date missing! FIX THIS!
     */
    protected function trim_file_name($name, $type = null, $index = null, $content_range = null) { // still org name
        /**
         * first get file extension
         */
        $ext = strtolower(pathinfo($filepath, PATHINFO_EXTENSION));

        /**
         * Remove path information and dots around the filename, to prevent uploading
         * into different directories or replacing hidden system files.
         * Also remove control characters and spaces (\x00..\x20) around the filename:
         */
        $new_name = str_replace(" ", "-", $this->site_id . '_' . $this->doc_type . '_' . substr($this->ins_date, 2) . '_' . $this->upl_time);
        $new_name = trim(basename(stripslashes($new_name)), ".\x00..\x20");

        // Use a timestamp for empty filenames:
        if (!$new_name) {
            $new_name = str_replace('.', '-', microtime(true));
        }

        // Add missing file extension for known image types:
        if (strpos(substr($new_name, -5), '.') === false && preg_match('/^image\/(gif|jpe?g|png)/', $type, $matches)) {
            $new_name .= '.' . $matches[1];
        } else {
            $new_name .= '.' . $ext;
        }

        return $new_name;
    }

    protected function get_unique_filename($name, $type = null, $index = null, $content_range = null) {
        while (is_dir($this->get_upload_path($name))) {
            $name = $this->upcount_name($name);
        }
        // Keep an existing filename if this is part of a chunked upload:
        $uploaded_bytes = $this->fix_integer_overflow(intval($content_range[1]));
        while (is_file($this->get_upload_path($name))) {
            if ($uploaded_bytes === $this->get_file_size($this->get_upload_path($name))) {
                break;
            }
            $name = $this->upcount_name($name);
        }
        return $name;
    }

    protected function get_file_name($name, $type = null, $index = null, $content_range = null) { // still org name
        $upl_name = $this->get_unique_filename(
                $this->trim_file_name($name, $type, $index, $content_range), $type, $index, $content_range
        ); // here it's the altered name

        $this->upl_name = $upl_name;
        $this->upl_locn = 'http://upload.federatedservice.com/Documentation/' . $this->project . '/' . $this->workorder . '/' . $upl_name;
        return $this->upl_name;
    }

    /**
     * function handle_file_upload
     * 
     * NOTE:
     *      this is where the params must be loaded for passing via files[] array
     *      to the rest of the code
     * 
     * @return \stdClass
     */
    protected function handle_file_upload($uploaded_file, $name, $size, $type, $error, $doc_type, $upl_src, $index = null, $content_range = null) {

        $file = new stdClass();

        $file->name         = $this->get_file_name($name, $type, $index, $content_range);
        $file->size         = $this->fix_integer_overflow(intval($size));
        $file->type         = $type;
        $file->upl_src      = $upl_src;
        $file->upl_locn     = $this->upl_locn;
        $file->doc_type     = $doc_type;
        $file->org_name     = $name;
        $file->related_wko  = $this->related_wko;
        $file->related_tech = $this->related_tech;
        $file->ins_date     = $this->ins_date;
        $file->uploader     = $this->uploader;

        if ($this->validate($uploaded_file, $file, $error, $index)) {
            /**
             * do any preprocessing?
             * 
             * $this->handle_form_data($file, $index);
             */
            $upload_dir = $this->get_upload_path();

            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, $this->options['mkdir_mode'], true);
            }

            $file_path = $this->get_upload_path($file->name);

            $append_file = $content_range && is_file($file_path) && $file->size > $this->get_file_size($file_path);
            if ($uploaded_file && is_uploaded_file($uploaded_file)) {
                // multipart/formdata uploads (POST method uploads)
                if ($append_file) {
                    file_put_contents(
                            $file_path, fopen($uploaded_file, 'r'), FILE_APPEND
                    );
                } else {
                    move_uploaded_file($uploaded_file, $file_path);
                }
            } else {
                // Non-multipart uploads (PUT method support)
                file_put_contents(
                        $file_path, fopen('php://input', 'r'), $append_file ? FILE_APPEND : 0
                );
            }
            $file_size = $this->get_file_size($file_path, $append_file);
            if ($file_size === $file->size) {
                $file->url = $this->get_download_url($file->name);
                list($img_width, $img_height) = @getimagesize($file_path);
                if (is_int($img_width) &&
                        preg_match($this->options['inline_file_types'], $file->name)) {
                    $this->handle_image_file($file_path, $file);
                }
            } else {
                $file->size = $file_size;
                if (!$content_range && $this->options['discard_aborted_uploads']) {
                    unlink($file_path);
                    $file->error = 'abort';
                }
            }
            $this->set_additional_file_properties($file);
        }

        return $file;
    }

    /**
     * post function
     * 
     * @param type $print_response
     * @return type
     * 
     * NOTE: This is where the action is! DO NOT IGNORE -- rpw
     * 
     * files[] array is created here
     * 
     * if you send more than one file, it takes first path
     * if you send only one file, it takes second path
     */
    public function post($print_response = true) {
        if (isset($_REQUEST['_method']) && $_REQUEST['_method'] === 'DELETE') {
            return $this->delete($print_response);
        }
        $upload        = isset($_FILES[$this->options['param_name']]) ? $_FILES[$this->options['param_name']] : null;
        // Parse the Content-Disposition header, if available:
        $file_name     = $this->get_server_var('HTTP_CONTENT_DISPOSITION') ? rawurldecode(preg_replace('/(^[^"]+")|("$)/', '', $this->get_server_var('HTTP_CONTENT_DISPOSITION'))) : null;
        // Parse the Content-Range header, which has the following form:
        // Content-Range: bytes 0-524287/2000000
        $content_range = $this->get_server_var('HTTP_CONTENT_RANGE') ? preg_split('/[^0-9]+/', $this->get_server_var('HTTP_CONTENT_RANGE')) : null;
        $size          = $content_range ? $content_range[3] : null;
        $files         = array();
        if ($upload && is_array($upload['tmp_name'])) {
            /**
             * NOTE: param_name is an array identifier like "files[]",
             *      $_FILES is a multi-dimensional array:
             */
            foreach ($upload['tmp_name'] as $index => $value) {
                $files[] = $this->handle_file_upload(
                        $upload['tmp_name'][$index], $file_name ? $file_name : $upload['name'][$index], $size ? $size : $upload['size'][$index], $upload['type'][$index], $upload['error'][$index], $this->doc_type, $this->upl_src, $index, $content_range
                );
            }
        } else {
            /**
             * NOTE: param_name is a single object identifier like "file",
             *      $_FILES is a one-dimensional array:
             */
            $files[] = $this->handle_file_upload(
                    isset($upload['tmp_name']) ? $upload['tmp_name'] : null, $file_name ? $file_name : (isset($upload['name']) ? $upload['name'] : null), $size ? $size : (isset($upload['size']) ? $upload['size'] : $this->get_server_var('CONTENT_LENGTH')), isset($upload['type']) ? $upload['type'] : $this->get_server_var('CONTENT_TYPE'), isset($upload['error']) ? $upload['error'] : null, $this->doc_type, $this->upl_src, null, $content_range
            );
        }

        return $this->generate_response(array($this->options['param_name'] => $files), $print_response);
    }

    function mstime() {
        $date = new DateTime();
        $t1   = $date->format('His');
        list($microSec, $timeStamp) = explode(" ", microtime());
        $t2   = str_replace("0.", "", $microSec);
        return substr(str_replace(":", "", $t1 . $t2), 0, 10);
    }

}

/**
 * instantiate the upload handler
 */
$upload_handler = new CustomUploadHandler(
        $uploader, $related_wko, $related_tech, $project, $workorder, $tech_id, $site_id, $row_id, $doc_type, $ins_date, $upl_time, $upl_src, null, true, null
);
