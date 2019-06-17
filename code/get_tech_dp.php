<?php
set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER["DOCUMENT_ROOT"]);

require_once 'common/QuickBase/classes/QuickBaseQuery.class.php';

$rti = $_REQUEST['rti']; // tech id
$tpn = $_REQUEST['tpn']; // tech phone

/**
 * must exclude Field Nation (related DP = 2549)
 */
if (($rti > 0)) {
    $table = 'bg7gm3rff';
    // $query = "8.EX.Active"."[a]6.XEX.2549"."[a]3.EX.".$rti."[o]8.EX.Active"."[a]6.XEX.2549"."[a]12.EX.".htmlentities($tpn);
    $query = "8.EX.Active[a]3.EX.".$rti."[o]8.EX.Active[a]12.EX.".htmlentities($tpn);
    //  3 record id#
    //  6 related deployment company
    //  7 deployment company
    //  8 employment status -- (Active/Inactive)
    //  9 technician name
    // 12 technician phone
    // 28 tech ID
    $clist = '3.6.7.9.12.28';      // 'all' = all (fields to return)
    $format = 'structured';
    $obj = new QuickBaseQuery($table, $query, '', '', $clist, $format, '', '');
    echo $obj->json;
} else {
    die("Invalid tech/phone for get_tech_dp.php");
}
