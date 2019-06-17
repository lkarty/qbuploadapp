<?php

set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER["DOCUMENT_ROOT"]);

require_once 'common/QuickBase/classes/QuickBaseQuery.class.php';

$pno = is_numeric($_REQUEST["pno"]) && $_REQUEST["pno"] > 0 ? $_REQUEST["pno"] : 0; // work order

/**
 * projects table
 */
$table = "bhv9szup8";
$clist = "3.7.8.14.28.35.90";      // 'all' = all (fields to return) 
$format = "structured";
//   3 record id#
//   7 project name
//   8 project #
//  14 customer name
//  28 project manager
//  35 customer #
//  90 call of code required

if (!$pno == 0) {
    $query = "8.EX." . $pno;
    $obj = new QuickBaseQuery($table, $query, "", "", $clist, $format, "", "");
    echo $obj->json;
} else {
    die("Invalid work order for get_project.php");
}
