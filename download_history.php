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

        <!--
        <link rel="stylesheet" type="text/css" href="/style/css/fss_table.css" media="all" />
        <link rel="stylesheet" type="text/css" href="/style/css/view.css" media="all" />
        <link rel="stylesheet" type="text/css" href="/style/css/upload_minor.css" />
        -->

    </head>

    <body style="display:none;">

        <noscript>
        <p class="noscript">You must enable Javascript in your browser in order to view this site properly!
            <small>
                [ <a href="https://www.google.com/adsense/support/bin/answer.py?answer=12654"
                     title="enable javascript in your browser" target="_blank" rel="nofollow">
                    View Help!
                </a> ]
            </small>
        </p>
        </noscript>

        <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4 navbar-fixed-top">
            <img src="/images/logos/fss_logo_white.gif" class="fss_logo">
            <a class="navbar-brand" href="http://core.federatedservice.com/index.php?wko=<?php echo $wko; ?>&key=<?php echo $key; ?>">
                FSS Document Upload
            </a>
            <div id="navi-head" class="container" style="width:100%;">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div id="navi-head-options" class="navbar-body collapse navbar-collapse navbar-right navbar-ex1-collapse">
                    <ul class="nav navbar-nav">
                        <li class="nav-item li_navbar">
                            <a id="select-file" href="#" data-toggle="modal" data-target="#modal-source-1" class="btn btn-md btn-default">
                                <i class="fas fa-lg fa-cloud-upload"></i>
                                Document Upload
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- /.navbar-collapse -->
            </div>
            <!-- /.container -->
        </nav>

        <div class="no-js" style="display:none;">
            <p>This application requires that JavaScript be enabled in your browser in order to function!</p>
        </div>

        <div id="modal-source-1" class="modal fade">
            <div class="modal-dialog">
                <div class="loginmodal-container" style="background-color: #eee;text-align:center">
                    <h4>Select the file(s) to upload ...</h4>
                </div>
            </div>
        </div>

        <div id="primary-panel" class="card card-body bg-light container" style="margin:2em auto;padding-right:3.75em;text-align:center;background-color:#eee;color:black">
            <h3 id="panel-title" class="mb-0">
                Download History
            </h3>
            <div id="activity-window" class="results_window col-md-12" style="margin:1em;text-align:left;">         
                <form>
                    <h1 style="background-color:#00336E;color:white;">Document Downloads</h1>
                    <div class="form_description" align="center" style="margin-top:1em;">
                        <IMG title="FederatedLogo" alt="Federated Logo" src="./images/logos/logo.jpg" border=0 /><p>
                    </div>	
                    <div style="margin:auto auto;text-align:center">
                        <h3>Download History</h3>
                        <h4 id="file-name"><a href="#"></a></h4>
                        <h4 id="descript"></h4>
                        <div id="download-history"></div>
                    </div>	
                </form>
            </div>
        </div>

        <div id="response-window" class="json_window col-md-12" style="margin:1em;text-align:left;"></div>
        
        <?php require_once "common/page/assets/assets_minor.php"; ?>

        <script type="text/javascript">
            
            $(document).ready(function () {

                $("body").show();
                
                $.getJSON("/apps/deliverable_uploads/code/get_deliverable_download_history.php", {
                    'workorder': $.url.param("workorder") > '' ? $.url.param("workorder") : 0,
                    'row': $.url.param("row") > '' ? $.url.param("row") : 0
                }, function (json) {
                    if (json) {
                        // update totals in QuickBase Document table 
                        $.getJSON("/apps/deliverable_uploads/code/get_document_totals.php", {
                            'row': $.url.param("row") > '' ? $.url.param("row") : 0
                        }, function (data) {
                            if (data.Totals !== undefined && data.Totals.length > 0) {
                                var row = data.Totals[0].Row > 0 ? data.Totals[0].Row : $.url.param("row") > '' ? $.url.param("row") : 0;
                                var ccnt = data.Totals[0].CustomerDownloadCount > 0 ? data.Totals[0].CustomerDownloadCount : 0;
                                var pcnt = data.Totals[0].PreviousDownloadCount > 0 ? data.Totals[0].PreviousDownloadCount : 0;
                                var prm = {'row': row, 'ccnt': ccnt, 'pcnt': pcnt};
                                $.getJSON("/apps/deliverable_uploads/code/upd_download_count.php", prm, function () {
                                    //
                                });
                            } else {
                                var row = $.url.param("row") > '' ? $.url.param("row") : 0;
                                var ccnt = 0;
                                var pcnt = 0;
                                var prm = {'row': row, 'ccnt': ccnt, 'pcnt': pcnt};
                                $.getJSON("/apps/deliverable_uploads/code/upd_download_count.php", prm, function () {
                                    //
                                });
                            }
                        });

                        $('#file-name a').append(json[0].FileName).fadeIn();
                        $('#file-name a').attr("href", json[0].Link);
                        $('#descript').append('Work Order# ' + json[0].WorkOrder + ' / Row ' + json[0].Row + ' / ' + json[0].Type).fadeIn();

                        // display history in web form
                        $('#download-history').append(ModifiedTableView(json, "fsscore", true, 'download', json[0].FileName)).fadeIn();
                        //$('#download-history').append(CreateTableView(json, "fsscore", true, 'download')).fadeIn();
                        //$('#download-history').append(CreateDetailView(json, "fsscore", true)).fadeIn();
                        //$('#download-history').html(ConvertJsonToTable(json, 'download-history', null, 'download'));
                    } else {
                        alert("COLOSSAL FAIL");
                    }
                });
            });

            // This function creates a modified table
            // Parameter Information
            // objArray = Anytype of object array, like JSON results
            // theme (optional) = A css class to add to the table (e.g. <table class="<theme>">
            // enableHeader (optional) = Controls if you want to hide/show, default is show
            function ModifiedTableView(objArray, theme, enableHeader, linkLabel, fileName) {

                var link = linkLabel ? '<a href="{0}">' + linkLabel + '</a>' : '<a href="{0}">{0}</a>';
                var tdLink = "<td align='center'>{0}</td>";
                var urlRegExp = new RegExp(/(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig);
                var jsRegExp = new RegExp(/(^javascript:[\s\S]*;$)/ig);

                // set optional theme parameter
                if (theme === undefined) {
                    theme = 'fsscore'; //default theme
                }

                if (enableHeader === undefined) {
                    enableHeader = true; //default enable headers
                }

                // If the returned data is an object do nothing, else try to parse
                var array = typeof objArray !== 'object' ? JSON.parse(objArray) : objArray;

                var str = '<table class="' + theme + '">';

                // table head
                if (enableHeader) {
                    str += '<thead><tr>';
                    for (var index in array[0]) {
                        if (index !== 'Link'
                                && index !== 'WorkOrder'
                                && index !== 'FileName'
                                && index !== 'Type'
                                && index !== 'Prev DL'
                                && index !== 'Row') {
                            str += '<th scope="col">' + index + '</th>';
                        }
                    }
                    str += '</tr></thead>';
                }

                // table body
                str += '<tbody>';
                for (var i = 0; i < array.length; i++) {
                    str += (i % 2 === 0) ? '<tr class="alt">' : '<tr>';
                    for (var index in array[i]) {
                        if (index !== 'Link'
                                && index !== 'WorkOrder'
                                && index !== 'FileName'
                                && index !== 'Type'
                                && index !== 'Prev DL'
                                && index !== 'Row') {
                            var value = array[i][index];
                            var isUrl = urlRegExp.test(value) || jsRegExp.test(value);
                            if (index == 'Date/Time' || index == 'Downloader') {
                                str += '<td style="text-align:left">' + array[i][index] + '</td>';
                            } else {
                                if (isUrl) {
                                    str += tdLink.format(link.format(value));
                                } else {
                                    str += '<td style="text-align:center">' + array[i][index] + '</td>';
                                }
                            }
                        }
                    }
                    str += '</tr>';
                }
                str += '</tbody>';
                str += '</table>';
                return str;
            }


            // This function creates a standard table
            // Parameter Information
            // objArray = Anytype of object array, like JSON results
            // theme (optional) = A css class to add to the table (e.g. <table class="<theme>">
            // enableHeader (optional) = Controls if you want to hide/show, default is show
            function CreateTableView(objArray, theme, enableHeader, linkLabel) {

                var link = linkLabel ? '<a href="{0}">' + linkLabel + '</a>' : '<a href="{0}">{0}</a>';
                var tdLink = "<td align='center'>{0}</td>";
                var urlRegExp = new RegExp(/(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig);
                var jsRegExp = new RegExp(/(^javascript:[\s\S]*;$)/ig);

                // set optional theme parameter
                if (theme === undefined) {
                    theme = 'fss_table'; //default theme
                }

                if (enableHeader === undefined) {
                    enableHeader = true; //default enable headers
                }

                // If the returned data is an object do nothing, else try to parse
                var array = typeof objArray !== 'object' ? JSON.parse(objArray) : objArray;

                var str = '<table class="' + theme + '">';

                // table head
                if (enableHeader) {
                    str += '<thead><tr>';
                    for (var index in array[0]) {
                        str += '<th scope="col">' + index + '</th>';
                    }
                    str += '</tr></thead>';
                }

                // table body
                str += '<tbody>';
                for (var i = 0; i < array.length; i++) {
                    str += (i % 2 === 0) ? '<tr class="alt">' : '<tr>';
                    for (var index in array[i]) {
                        var value = array[i][index];
                        var isUrl = urlRegExp.test(value) || jsRegExp.test(value);
                        if (isUrl) {
                            str += tdLink.format(link.format(value));
                        } else {
                            str += '<td>' + array[i][index] + '</td>';
                        }
                    }
                    str += '</tr>';
                }
                str += '</tbody>';
                str += '</table>';
                return str;
            }

            // This function creates a detail view table with column 1 as the header and column 2 as the detail
            // Parameter Information
            // objArray = Any type of object array, like JSON results
            // theme (optional) = A css class to add to the table (e.g. <table class="<theme>">
            // enableHeader (optional) = Controls if you want to hide/show, default is show
            function CreateDetailView(objArray, theme, enableHeader) {
                // set optional theme parameter
                if (theme === undefined) {
                    theme = 'fss_table';  //default theme
                }

                if (enableHeader === undefined) {
                    enableHeader = true;    //default enable headers
                }

                // If the returned data is an object do nothing, else try to parse
                var array = typeof objArray !== 'object' ? JSON.parse(objArray) : objArray;

                var str = '<table class="' + theme + '">';
                str += '<tbody>';

                for (var i = 0; i < array.length; i++) {
                    var row_id = 0;
                    for (var index in array[i]) {
                        str += (row_id % 2 === 0) ? '<tr class="alt">' : '<tr>';

                        if (enableHeader) {
                            str += '<th scope="row">' + index + '</th>';
                        }

                        str += '<td>' + array[i][index] + '</td>';
                        str += '</tr>';
                        row_id++;
                    }
                }
                str += '</tbody>';
                str += '</table>';
                return str;
            }
        </script>

    </body>
</html> 