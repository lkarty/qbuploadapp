<?php

set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER["DOCUMENT_ROOT"]);

require_once 'common/QuickBase/classes/QuickBaseQuery.class.php';

$wko = is_numeric($_REQUEST["wko"]) && $_REQUEST["wko"] > 0 ? $_REQUEST["wko"] : 0; // work order

$table = "bhv9szuph";
$clist = "3.123.305.306.307.308.309.320.373";      // 'all' = all (fields to return) 
$format = "structured";
//   3 Record ID#
// 123 Work Order # 
// 305 Site Name
// 306 Address
// 307 Address 2
// 308 City
// 309 State
// 320 Site #
// 373 Site ID

if (!$wko == 0) {
    $query = "123.EX." . $wko;
    $obj = new QuickBaseQuery($table, $query, "", "", $clist, $format, "", "");
    echo $obj->json;
} else {
    die("Invalid work order for get_site_location.php");
}
