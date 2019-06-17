<?php
set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER["DOCUMENT_ROOT"]);

require_once 'common/QuickBase/classes/QuickBaseQuery.class.php';

$ino = $_REQUEST['ino']; // invoice #
$rdp = $_REQUEST['rdp']; // related delivery partner

if (($ino > ' ')) { 
    $table = 'bhzbau9hg';
    $query = "11.EX.".$ino."[a]34.EX.".$rdp;
    //   3 record id#
    //  11 invoice #
    //  20 approval status
    //  34 related deployment partner
    // 131 related technician
    // 167 upload source
    $clist  = '3.11.20.34.131.167';      // 'all' = all (fields to return)
    $format = 'structured';
    $obj = new QuickBaseQuery($table, $query, '', '', $clist, $format, '', '');
    echo $obj->json;
} else {
    die("Invalid invoice # for get_qb_invoice.php");
}
