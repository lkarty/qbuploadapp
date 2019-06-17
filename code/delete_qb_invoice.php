<?php

set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER["DOCUMENT_ROOT"]);

require_once "common/QuickBase/classes/QuickBaseUpdate.class.php";

$rid = $_REQUEST['rid'];

$dat = '[{'
        . '"RowID":"' . $rid
        . '"}]';

$qbo = new QuickBaseUpdate('bhzbau9hg', 'del', $dat);

$rst = json_decode($qbo->json);
$qb_err = json_encode($rst[0]->{"errcode"});

if ($qb_err == 0) {
    $qb_rid = json_encode($rst[0]->{"rid"});
    echo $qb_rid;
} else {
    echo false;
}
