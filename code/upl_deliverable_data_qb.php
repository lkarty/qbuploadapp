<?php

set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER["DOCUMENT_ROOT"]);

require_once "common/QuickBase/classes/QuickBaseUpdate.class.php";

foreach ($_REQUEST as $k => $v) {
    $_REQUEST[$k] = urldecode($v);
}

/**
 * params come from function "_renderDownload" in file "jquery.fileupload-ui.js" 
 */
$loc = $_REQUEST["loc"];
$typ = $_REQUEST["typ"];
$rwo = $_REQUEST["rwo"];
$idt = $_REQUEST["idt"];
$rtk = $_REQUEST["rtk"];
$unm = $_REQUEST["unm"];

if ($typ == "NULL") {
    echo(json_encode($typ));
} else {
    /**
     *  update QuickBase Deliverables Upload table 
     */
    $table_id = "bf8syh8uj";
    $rec      = '[{'
            . '"File Location":"' . $loc
            . '","File Type":"' . $typ
            . '","Related Work Order":"' . $rwo
            . '","Install Date":"' . $idt
            . '","Approval Status":"Needs Review'
            . '","Related Technician":"' . $rtk
            . '","Uploader Name":"' . $unm
            . '"}]';

    $qbo = new QuickBaseUpdate($table_id, "add", $rec);
    $rst = json_decode($qbo->json);

    $qb_rid = json_encode($rst[0]->{"rid"});   // return update_id or rid

    /**
     * $this->result = json_decode('{"action":"API_AddRecord","errcode":"0","errtext":"No error","rid":"97","update_id":"1368712078584"}');
     * unset($qbo); // try this to see if we need to clean up old objects -- may need to leave out iot access qb_rid later
     * echo $qbo->json;
     */
    echo $qb_rid;
}
