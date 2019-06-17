<?php

set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER["DOCUMENT_ROOT"]);

require_once 'common/QuickBase/classes/QuickBaseQuery.class.php';

$rid = is_numeric($_REQUEST["rid"]) && $_REQUEST["rid"] > 0 ? $_REQUEST["rid"] : 0; // work order

$table  = "bhv9szuph";
$clist  = "3.123.587.1074.1174";      // 'all' = all (fields to return) 
$format = "structured";
/**
 * fields
 * 
 *    3 - Record ID#
 *  123 - Work Order #
 *  587 - # of Deliverables
 * 1074 - Exempt From Lien Waiver 
 * 1174 - # of Lien Waiver Documents 
 */
if (!$rid == 0) {
    $query = "3.EX." . $rid;
    $obj   = new QuickBaseQuery($table, $query, "", "", $clist, $format, "", "");
    echo $obj->json;
} else {
    die("Invalid work order for get_deliverables_count.php");
}
