<?php

set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER["DOCUMENT_ROOT"]);


if (get_magic_quotes_gpc() == true) {
    foreach ($_REQUEST as $k => $v) {
        $_REQUEST[$k] = stripslashes($v);
    }
}

$cnm = $_REQUEST["cnm"] > '' ? $_REQUEST["cnm"] : '';
$prj = is_numeric($_REQUEST["prj"]) && $_REQUEST["prj"] > 0 ? $_REQUEST["prj"] : 0;
$wko = is_numeric($_REQUEST["wko"]) && $_REQUEST["wko"] > 0 ? $_REQUEST["wko"] : 0;

if ($cnm > '') {
    if ($prj > 0) {
        $path = "../documents/Documents/Customers/$cnm/$prj"; 
        if (!file_exists($path)) {
            mkdir($path, 0766, true);
        }
        if ($wko > 0) {
            $path .= "/$wko"; 
            if (!file_exists($path)) {
                mkdir($path, 0766, true);
            }
        }
    } else {
        $path = "../documents/Documents/Customers/$cnm";
        if (!file_exists($path)) {
            mkdir($path, 0766, true);
        }
    }
} else {
    die("FATAL ERROR -- missing customer name");
}

/**
 * compile file names and return as json
 */
$files = array();

$handle = opendir('../' . $path . '/');

//while (($file = readdir($handle)) !== false) {
while ($file = readdir($handle)) {
    // must exclude folders (id'd by project number using ctype_digit)
    if ("." !== $file && ".." !== $file && (!ctype_digit(strval($file)))) {
        array_push($files, $file);
    }
}

closedir($handle);

if (count($files) > 0) {
    sort($files);
}

$json = json_encode($files);

unset($handle, $ext, $file, $path);

echo $json;
