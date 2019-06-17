<?php

try {
    $hostname = "Bronco2";
    $dbname = "QuickBaseSQL";
    $username = "sa";
    $password = "sWp!6PE3";

    $dbh = new PDO("dblib:host=$hostname;dbname=$dbname", $username, $password);

} catch (PDOException $e) {
    die ($e->getMessage());
}

$stmt = $dbh->prepare("SELECT WorkOrder,Downloader,FileName,FileType AS 'Type',Link,Row,CASE WHEN IsCustomerDownload = 1 THEN 'Y' ELSE 'N' END AS 'Customer?',IsPreviousDownload AS 'Prev DL',replace(CONVERT(VARCHAR,DownloadDateTime,109),':00:000',' ') AS 'Date/Time' FROM tblDeliverableDownloads WHERE WorkOrder = ? AND Row = ?")
	    or die("unable to prepare statement");
$stmt->bindParam(1,$_REQUEST['workorder'],PDO::PARAM_INT) or die("unable to bind workorder");
$stmt->bindParam(2,$_REQUEST['row'],PDO::PARAM_INT) or die("unable to bind row");
//$stmt->bindParam(2,$_REQUEST['filename'],PDO::PARAM_STR) or die("unable to bind filename");

$stmt->execute() or die("unable to execute statement");

$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
//die(print_r($data));
unset($dbh); 
unset($stmt);

//die(json_encode($data)); 

echo json_encode($data); 
