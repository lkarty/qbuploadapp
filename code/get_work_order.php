<?php

set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER["DOCUMENT_ROOT"]);

require_once 'common/QuickBase/classes/QuickBaseQuery.class.php';

$wko = is_numeric($_REQUEST["wko"]) && $_REQUEST["wko"] > 0 ? $_REQUEST["wko"] : 0; // work order

/**
 * work orders table
 */
$table = "bhv9szuph";
$clist = "3.123.305.316.317.319.320.373.375";      // 'all' = all (fields to return) 
$format = "structured";
//   3 record id#
// 123 work order # 
// 305 site name
// 316 project name
// 317 project #
// 319 customer name
// 320 site #
// 373 site id
// 375 project manager

if (!$wko == 0) {
    $query = "123.EX." . $wko;
    $obj = new QuickBaseQuery($table, $query, "", "", $clist, $format, "", "");
    echo $obj->json;
} else {
    die("Invalid work order for get_work_order.php");
}
