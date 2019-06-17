<?php
set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER["DOCUMENT_ROOT"]);

if (get_magic_quotes_gpc() == true) {
    foreach ($_REQUEST as $k => $v) {
        $_REQUEST[$k] = stripslashes($v);
    }

    foreach ($_COOKIE as $k => $v) {
        $_COOKIE[$k] = stripslashes($v);
    }
}
$fss_cookie = json_decode($_COOKIE["fss_cookie"], true);

?>
<!doctype html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta charset="utf-8">

        <meta name="author" content="Rich Whiting">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Document Upload Portal</title>

        <!-- Force latest IE rendering engine or ChromeFrame if installed -->
        <!--[if IE]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <![endif]-->

        <?php require_once "common/assets/assets_major.php"; ?>

        <!-- 
            APP-SPECIFIC LINKS 
        -->


    </head>

    <body id="main_body">

        <div id="container" style="margin-top:5em;"></div>
        
        <script type="text/javascript">

            $(document).ready(function () {

                $("body").show();

                $dt = new Date().toString('yyyy-MM-dd hh:mm:ss');

                var href = this.href;
                var cust = $.url.param("customer") === 'true' ? 1 : 0;

                $.getJSON("/apps/deliverable_uploads/code/ins_deliverable_download.php", {
                    'workorder': $.url.param("workorder") > '' ? $.url.param("workorder") : -1,
                    'downloader': $.url.param("downloader") > '' ? $.url.param("downloader") : 'missing downloader',
                    'filename': $.url.param("file") > '' ? $.url.param("file") : 'missing filename',
                    'filetype': $.url.param("type") > '' ? $.url.param("type") : 'missing filetype',
                    'link': $.url.param("link") > '' ? $.url.param("link") : 'missing link',
                    'row': $.url.param("row") > '' ? $.url.param("row") : 0,
                    'customer': $.url.param("customer") === 'true' ? 1 : 0,
                    'previous': 1,
                    'datetime': $dt
                }, function (data) { //dump(data); // gets here fine!
                    if (data['PreviousDownloadCount']) {
                        var ccnt = data['CustomerDownloadCount'];
                        var pcnt = data['PreviousDownloadCount'];
                        //var url = "http://core.federatedservice.com/apps/deliverable_uploads/code/upd_download_count.php";
                        var url = "/code/upd_download_count.php";
                        var prm = {'row': $.url.param("row"), 'ccnt': ccnt, 'pcnt': pcnt}; 
                        $.getJSON(url, prm, function () { //dump(data); // but not here!
                            window.location = $.url.param("link"); //window.close();			    
                        });
                    } else {
                        alert("COLOSSAL FAIL");
                    }
                });
            });
        </script>

    </body>
</html> 