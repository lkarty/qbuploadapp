<?php
set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER["DOCUMENT_ROOT"]);

try {
    $hostname = "Bronco2";
    $dbname = "QuickBaseSQL";
    $username = "sa";
    $password = "sWp!6PE3";

    $dbh = new PDO("dblib:host=$hostname;dbname=$dbname", $username, $password);

} catch (PDOException $e) {
    die ($e->getMessage());
}

$stmt = $dbh->prepare("SELECT [Row], SUM(COALESCE(IsCustomerDownload,0)) AS CustomerDownloadCount, SUM(COALESCE(IsPreviousDownload,0)) AS PreviousDownloadCount FROM tblDeliverableDownloads GROUP BY Row") or die("unable to prepare statement");

$stmt->execute() or die("unable to execute statement");

$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

unset($dbh); 
unset($stmt);

echo '{"Totals":'.json_encode($data).'}';

