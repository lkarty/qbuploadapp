<?php

set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER["DOCUMENT_ROOT"]);

require_once 'common/QuickBase/classes/QuickBaseQuery.class.php';

$rec_id = isset($_REQUEST["rec_id"]) && $_REQUEST["rec_id"] > "" ? $_REQUEST["rec_id"] : "0";
$tech_name = isset($_REQUEST["tech_name"]) && $_REQUEST["tech_name"] > "" ? $_REQUEST["tech_name"] : " ";
$tech_phone = isset($_REQUEST["tech_phone"]) && $_REQUEST["tech_phone"] > "" ? $_REQUEST["tech_phone"] : " ";

$table = "bg7gm3rff";               // technicians table
$clist = "3.7.8.9.12.13.58.59";     // 'all' = all (fields to return) 
//$clist = "all";                   // 'all' = all (fields to return) ... weakness: returns all url/button fields as card card-body bg-light
$format = "structured";

//   3 Record ID#
//   7 Deployment Company
//   8 Employment Status
//   9 Technician Name
//  12 Phone Number
//  13 Email Address
//  58 Initial Text Sent
//  59 Text Opt Out

if ($rec_id != "0") {
    $query = "3.EX." . $rec_id;
} else if ($tech_name != " ") {
    $query = "9.EX." . $tech_name;
} else if ($tech_phone != " ") {
    $query = "12.EX." . $tech_phone;
} else {
    $query = "3.XEX.0"; // all
}

$obj = new QuickBaseQuery($table, $query, "", "", $clist, $format, "", "");

echo $obj->json;
