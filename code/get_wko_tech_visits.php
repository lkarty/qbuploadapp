<?php

set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER["DOCUMENT_ROOT"]);

require_once 'common/QuickBase/classes/QuickBaseQuery.class.php';

$wko = is_numeric($_REQUEST["wko"]) && $_REQUEST["wko"] > 0 ? $_REQUEST["wko"] : 0; // work order
$tid = isset($_REQUEST["tid"]) && $_REQUEST["tid"] > "" ? $_REQUEST["tid"] : "0";   // tech id (or phone)

$table  = "bhv9szup6";
$clist  = "3.11.13.14.15.20.24.25.26.27.28.33.44.46.47.48.70.72.85.87.129.143.145.193.531";      // 'all' = all (fields to return) 
$format = "structured";
/**
 * fields
 *   3 record id#
 *  11 related work order
 *  13 
 *  14 
 *  15 
 *  20 work order
 *  24 Scheduled Deployment Company ID #
 *  25 Scheduled Deployment Company
 *  26 related technician
 *  27 scheduled tech name
 *  28 scheduled tech phone
 *  33 visit start date
 *  44 visit start date/time (EST)
 *  46 project name
 *  47 project #
 *  48 site #
 *  70 Name of Customer
 *  72 visit status
 *  85 site ID
 *  87 scheduled tech id
 * 129 date/time of last check out (EST)
 * 143 last check in
 * 145 date/time first check in (EST)
 * 193 full address
 * 531 Customer Name
 */
if (!$wko == 0) {
    if (!$tid == "0") {
        $query = "20.EX." . $wko . "[a]87.EX." . $tid . "[o]20.EX." . $wko . "[a]28.EX." . htmlentities($tid);
    } else {
        $query = "20.EX." . $wko;
    }
    $obj = new QuickBaseQuery($table, $query, "", "", $clist, $format, "", "");
    echo $obj->json;
} else {
    die("Invalid work order for get_wko_tech_visits.php");
}
