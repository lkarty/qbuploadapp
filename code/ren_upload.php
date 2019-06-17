<?php
$old_path = $_REQUEST['old_path'];
$new_path = $_REQUEST['new_path'];
$result = rename($old_path, $new_path);
echo(json_encode($result));
