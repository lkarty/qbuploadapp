<?php

set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER["DOCUMENT_ROOT"]);

require_once "common/QuickBase/classes/QuickBaseUpdate.class.php";

$rwo = $_REQUEST['rwo'];
$typ = $_REQUEST['typ'];
$rdp = $_REQUEST['rdp'];
$dtr = $_REQUEST['dtr'];
$ino = $_REQUEST['ino'];
$idt = $_REQUEST['idt'];
$eqt = $_REQUEST['eqt'];
$lab = $_REQUEST['lab'];
$oos = $_REQUEST['oos'];
$tax = $_REQUEST['tax'];
$unm = $_REQUEST['unm'];
$tid = $_REQUEST['tid'];
$src = $_REQUEST['src'];

$rec = '[{'
        . '"Related Work Order":"' . $rwo
        . '","Invoice Type":"' . $typ
        . '","Related Deployment Partner":"' . $rdp
        . '","Date Received":"' . $dtr
        . '","Invoice #":"' . $ino
        . '","Date of Invoice":"' . $idt
        . '","Equipment Cost":"' . $eqt
        . '","Labor and Material Cost":"' . $lab
        . '","Out of Scope":"' . $oos
        . '","Tax (GST/HST)":"' . $tax
        . '","Uploader Name":"' . $unm
        . '","Related Technician":"' . $tid
        . '","Upload Source":"' . $src
        . '"}]';

$qbo = new QuickBaseUpdate('bhzbau9hg', 'add', $rec);
$rst = json_decode($qbo->json);
$qb_rid = json_encode($rst[0]->{"rid"});   // return update_id or rid
//$this->result = json_decode('{"action":"API_AddRecord","errcode":"0","errtext":"No error","rid":"97","update_id":"1368712078584"}');
//unset($qbo); // try this to see if we need to clean up old objects -- may need to leave out iot access qb_rid later

echo $qb_rid;
//echo $qbo->json;
