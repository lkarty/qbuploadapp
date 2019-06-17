<?php
if ($_POST["action"] == "remove") {
    $fullpath = $_POST['fullpath'];
    unlink($fullpath);
}
