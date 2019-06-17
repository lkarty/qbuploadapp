<?php

set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER["DOCUMENT_ROOT"]);

//require_once 'QuickBase/classes/QuickBase.class.php';
require_once 'common/QuickBase/classes/UpdateDownloadCount.class.php';

$row  = $_REQUEST['row'];
$ccnt = $_REQUEST['ccnt'];
$pcnt = $_REQUEST['pcnt'];

$upd  = new UpdateDownloadCount($row,$ccnt,$pcnt);

$json = array();
$json[] = $upd->result;

unset($upd);

echo '{"result":'.json_encode($json).'}';

