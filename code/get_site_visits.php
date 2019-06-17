<?php

set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER["DOCUMENT_ROOT"]);

require_once 'common/QuickBase/classes/QuickBaseQuery.class.php';

$table = "bhv9szup6";
$clist = "3.11.20.26.27.28.44.46.47.48.70.72.85.87.129.130.143.224.225.226.531";      // 'all' = all (fields to return) 
$format = "structured";

$next_cycle_dt =  date('m-d-Y g:i a', strtotime("+120 minutes", strtotime(date('Y-m-d g:i a'))));

//  3 record id#
// 11 related work order
// 20 work order
// 26 related technician
// 27 scheduled tech name
// 28 scheduled tech phone
// 44 Visit Start Date/Time (EST)
// 46 project name
// 47 project #
// 48 site #
// 70 Name of Customer
// 72 visit status
// 85 site ID
// 87 scheduled tech id
// 129 date/time of last check out (EST)
// 130 Next Schedule Date/Time (EST)
// 143 last check in
// 224 Visit Confirmed
// 225 Visit Confirmed By
// 226 Visit Confirmed On
// 531 Customer Name

$query = "224.EX.no[a]130.BF." . $next_cycle_dt;
//die($query);

$obj = new QuickBaseQuery($table, $query, "", "", $clist, $format, "", "");
echo $obj->json;

