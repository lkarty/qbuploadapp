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

$stmt = $dbh->prepare("INSERT INTO dbo.tblDeliverableDownloads "
	. "(WorkOrder,Downloader,FileName,FileType,Link,Row,IsCustomerDownload,IsPreviousDownload,DownloadDateTime) "
	. "VALUES (?,?,?,?,?,?,?,?,?)") 
	or die("unable to prepare statement");

$stmt->bindParam(1,$_REQUEST['workorder'],PDO::PARAM_INT) or die("unable to bind workorder");
$stmt->bindParam(2,$_REQUEST['downloader'],PDO::PARAM_STR) or die("unable to bind downloader");
$stmt->bindParam(3,$_REQUEST['filename'],PDO::PARAM_STR) or die("unable to bind filename"); 
$stmt->bindParam(4,$_REQUEST['filetype'],PDO::PARAM_STR) or die("unable to bind filetype");
$stmt->bindParam(5,$_REQUEST['link'],PDO::PARAM_STR) or die("unable to bind link");
$stmt->bindParam(6,$_REQUEST['row'],PDO::PARAM_INT) or die("unable to bind row");
$stmt->bindParam(7,$_REQUEST['customer'],PDO::PARAM_BOOL) or die("unable to bind customer");
$stmt->bindParam(8,$_REQUEST['previous'],PDO::PARAM_BOOL) or die("unable to bind previous");
$stmt->bindParam(9,$_REQUEST['datetime'],PDO::PARAM_STR) or die("unable to bind datetime"); 

$stmt->execute() or die("unable to execute statement 1");

$stmt = '';

$stmt = $dbh->prepare("SELECT SUM(COALESCE(IsPreviousDownload,0)) AS PreviousDownloadCount, SUM(COALESCE(IsCustomerDownload,0)) AS CustomerDownloadCount FROM tblDeliverableDownloads WHERE WorkOrder = ? AND FileName = ?") or die("unable to prepare statement");
$stmt->bindParam(1,$_REQUEST['workorder'],PDO::PARAM_INT) or die("unable to bind workorder");
$stmt->bindParam(2,$_REQUEST['filename'],PDO::PARAM_STR) or die("unable to bind filename");

$stmt->execute() or die("unable to execute statement 2");

$data = $stmt->fetch(PDO::FETCH_ASSOC);

unset($dbh); 
unset($stmt);

echo json_encode($data);
