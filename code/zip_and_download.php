<?php

set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER["DOCUMENT_ROOT"]);


if (get_magic_quotes_gpc() == true) {
    foreach ($_REQUEST as $k => $v) {
        $_REQUEST[$k] = stripslashes($v);
    }
}

/**
 *  Code for creating array of filenames
 * 
 *  $cnm = customer name
 *  $prj = project #
 *  $tgt = target name (project # or work order #)
 *  mode = path type: 1) project#, 2) project#/workorder#
 *  $path = path to source folder
 */
$tgt  = "";
$cnm  = $_REQUEST["cnm"] > "" ? $_REQUEST["cnm"] : "";
$prj  = is_numeric($_REQUEST["prj"]) && $_REQUEST["prj"] > 0 ? $_REQUEST["prj"] : 0;
$wko  = is_numeric($_REQUEST["wko"]) && $_REQUEST["wko"] > 0 ? $_REQUEST["wko"] : 0;
$mode = is_numeric($_REQUEST["mode"]) && $_REQUEST["mode"] > 0 ? $_REQUEST["mode"] : 1;

switch ($mode) {
    case 1:
        //$tgt  = "Docs_" . $prj;
        $path = "../../documents/Documents/Customers/$cnm/$prj";
        break;

    case 2:
        //$tgt  = "Docs_" . $wko;
        $path = "../../documents/Documents/Customers/$cnm/$prj/$wko";
        break;

    case 3:
        //$tgt  = "Dels_" . $wko;
        $path = "../../upload/Documentation/$prj/$wko";
        break;

    default:
        die("error error");
}

if ($handle = opendir($path)) {

    $files = array();

    while (($file = readdir($handle)) !== false) {
        if ($file != "." && $file != ".." && (!ctype_digit(strval($file)))) {
            array_push($files, "$path/$file");
        }
    }

    sort($files);

    closedir($handle);

    zipFilesAndDownload($files);
}

/**
 * Function for returning a zip of a directory
 * 
 * @param type $_tgt = target name of zip file
 * @param type $_files = file names
 * 
 */
function zipFilesAndDownload($_files) {

    $zip = new ZipArchive();

    // create a temp file & open it
    $tmp_file = tempnam('.', '');
    $zip->open($tmp_file, ZipArchive::CREATE);

    // loop through each file and add to zip
    foreach ($_files as $file) {
        $download_file = file_get_contents($file);
        $zip->addFromString(basename($file), $download_file);
    }

    $zip->close();

    // send the file to the browser as a download
    header('Content-disposition: attachment; filename=Resumes.zip');
    header('Content-type: application/zip');

    // return contents of zip file and then remove it
    readfile($tmp_file);
    unlink($tmp_file);
}
