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

$row  = $_GET['row'];
$wko  = $_GET["workorder"];
$type = $_GET['filetype'];
$name = $_GET['filename'];
$link = $_GET['filelink'];
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

    <body style="display:none; height:101%; overflow: scroll;">

        <div class="content">
            <table class="frame">
                <tr>
                    <td>
                        <table class="logo">
                            <tr>
                                <td class="logo">
                                    <img src="/images/logos/federated_service_solutions.gif" alt="federated service solutions" />
                                </td>
                                <td class="byline top"></td>
                            </tr>
                        </table>

                        <div class="frame_bar"><span>FSS Change Upload Type</span></div>

                        <div class="outer card card-body bg-light">                        
                            <div class="row">
                                <div class="col-lg-12 container">
                                    <form id="update-form" 
                                          action="#" 
                                          method="POST" 
                                          enctype="multipart/form-data" >
                                        <input name="qb_rid" id="qb-rid" value="" type="hidden" />
                                        <input name="upl_src" id="upl-src" value="filetype" type="hidden" />
                                        <div class="row">
                                            <div class="tech_inputs col-lg-12">
                                                <div class="col-lg-2"> 
                                                    <label class="col-lg-12">&nbsp;W/O #:<br/>
                                                        <input id="work-order"
                                                               name="work_order"
                                                               class="form-control"
                                                               style="text-align:center;"
                                                               value="<?php echo($wko); ?>"
                                                               type="text"
                                                               READONLY
                                                               REQUIRED />   
                                                    </label>
                                                </div>
                                                <div class="col-lg-7">
                                                    <label class="col-lg-12">&nbsp;File Name:<br/>
                                                        <input id="file-name" 
                                                               name="file_name" 
                                                               class="form-control"
                                                               value="<?php echo($name); ?>"
                                                               type="text"
                                                               READONLY />
                                                    </label>
                                                </div>
                                                <div class="col-lg-3"> 
                                                    <label class="col-lg-12">&nbsp;Upload Type:<br/> <!-- upload type -->
                                                        <select id="doc-type" class="form-control" name="doc_type[]" REQUIRED >
                                                            <option value="Advanced Services">Advanced Services</option>
                                                            <option value="As-Builts">As-Builts</option>
                                                            <option value="Business License">Business License</option>
                                                            <option value="Completed Forms">Completed Forms</option>
                                                            <option value="Daily Checklist">Daily Checklist</option>
                                                            <!-- <option value="Invoice">Invoice</option> -->
                                                            <option value="Permit">Permit</option>
                                                            <option value="Photographs">Photographs</option>
                                                            <option value="Post Install Photos">&nbsp;&blacktriangleright; Post-Install</option>
                                                            <option value="Pre Install Photos">&nbsp;&blacktriangleright; Pre-Install</option>
                                                            <option value="Survey Photos">&nbsp;&blacktriangleright; Survey</option>
                                                            <option value="Signed Work Order">Signed Work Order</option>
                                                            <option value="Survey Form">Survey Form</option>
                                                            <option value="Heat Map Survey">Heat Map Survey</option>
                                                            <option value="Test Results">Test Results</option>
                                                            <option value="Lien Waiver">Lien Waiver</option>
                                                        </select>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="d-none">
                                                <input id="old-name" 
                                                       name="old_name" 
                                                       value="<?php echo($name); ?>"
                                                       type="text"
                                                       style="width:800px;"
                                                       READONLY />
                                            </div>
                                            <div class="d-none">
                                                <input id="old-link" 
                                                       name="old_link" 
                                                       value="<?php echo($link); ?>"
                                                       type="text"
                                                       style="width:100%;"
                                                       READONLY />
                                            </div>
                                            <div>
                                                <button id="submit-form" type="submit" class="btn btn-primary" style="text-align:center;margin: 2em 47% 1em;">                                          
                                                    Submit
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div> <!-- row -->

                        <table>
                            <tr>
                                <td>
                                    <img src="/images/logos/technology_deployment.gif" alt="technology deployment installation solutions">
                                </td>
                            </tr>
                        </table>

                        <table style="width:100%; background-color:#ffffff;">
                            <tr>
                                <td class="footer">
                                    <hr />
                                    © <?php echo date("Y"); ?> <a href="http://www.federatedservice.com">Federated Service Solutions</a>. All Rights Reserved.<br>
                                    Corporate Office: 30955 Northwestern Highway | Farmington Hills, Michigan 48334  USA
                                    <br/><br/>
                                    <table style="width:432px;">
                                        <tr>
                                            <td>
                                                <a href="contact.php">
                                                    <img src="/images/logos/FSS_newsletter_signup.gif" alt="FSS e-newsletter sign up" style="width:64px; height:28px;">
                                                </a>
                                            </td>
                                            <td style="width:20px; text-align:center;">
                                                <span class="headerTop">|</span>
                                            </td>
                                            <td style="width:78px; text-align:right;" class="headerTop">Follow us on: </td>
                                            <td style="text-align:left;">
                                                <span class="headerTop">
                                                    <a href="http://www.linkedin.com/companies/federated-service-solutions" target="_blank">
                                                        <img src="/images/logos/linkedin.gif" alt="FSS linked in" style="width:67px; height:25px;">
                                                    </a>
                                                </span>
                                            </td>
                                            <td style="width:20px; text-align:center;">
                                                <span class="headerTop">|</span>
                                            </td>
                                            <td style="width:180px;">
                                                <img src="/images/logos/WBENC_logo.gif" style="width:69px; height:29px; padding-left:5px !important;">
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>

        <script type="text/javascript" src="/js/modernizr-2.6.2.min.js"></script>
        <script type="text/javascript" src="/js/json2.min.js"></script>
        <script type="text/javascript" src="/js/jquery.session.js"></script>
        <script type="text/javascript" src="/js/jquery.url.min.js"></script>
        <script type="text/javascript" src="/libs/purl/purl.js"></script>
        <script type="text/javascript" src="/libs/cookies/jquery.cookie.js"></script>

        <!-- utils.js needed for utils like 'dump', 'contains', various prototypes, etc. -->
        <script type="text/javascript" src="/js/utils.js"></script>

        <!-- load gritter first, then common gritter functions -->
        <script src="/libs/gritter/js/jquery.gritter.js" type="text/javascript"></script>
        <script src="/libs/gritter/js/gritter_functions.js" type="text/javascript"></script>

        <script type="text/javascript">

            var rename_alert;

            /**
             * DOM loaded
             */
            $(document).ready(function () {

                var old_type, new_type;
                var old_name, new_name;
                var old_link, new_link;
                $("update-form#submit-form").attr("disabled", true);

                /**
                 * initialize everything
                 */
                function fn_init_form() {

                    old_type = "<?php echo $type; ?>";
                    old_type = old_type.replace(/ /g, "-");
                    old_name = "<?php echo $name; ?>";
                    old_link = "<?php echo $link; ?>";

                    if (old_name.indexOf("_Invoice_") === -1) {
                        $("#doc-type").val("<?php echo $type; ?>");
                        $("#submit-form").attr('disabled', false);
                    } else {
                        fn_gritter_alert("File Type Error", "You cannot change the file type to/from invoice. You must delete the file and reupload it.", "", "", true)
                        $("#submit-form").attr('disabled', true);
                    }
                }

                /**
                 * when doc type changed
                 */
                $("#doc-type").change(function () {
                    if ($("#doc-type").val() > ""
                            && $("#doc-type").val() !== "<?php echo $type; ?>"
                            && $("#doc-type").val() !== "Invoice") {
                        $("#submit-form").attr('disabled', false);
                    } else {
                        $("#submit-form").attr('disabled', true);
                    }
                });


                /**
                 * when form submitted
                 */
                $("#update-form").submit(function (event) {
                    event.preventDefault();
                    new_type = $("#doc-type").val().replace(/ /g, "-");
                    if (new_type !== old_type) {
                        fn_rename_file();
                    } else {
                        fn_gritter_alert("Type selection error!", "You must select a different file type if you wish to change it.", "", "", true)
                    }
                });


                /**
                 * call php to rename file
                 */
                function fn_rename_file() {
                    rename_alert = $.gritter.add({
                        class_name: "gritter-warning",
                        position: "bottom-left",
                        title: "Changing Upload Type",
                        text: "from " + old_type + " to " + new_type,
                        image: "/images/common/notice.png",
                        fade_in_speed: "medium",
                        fade_out_speed: "slow",
                        sticky: true
                    });

                    new_type = $("#doc-type").val().replace(/ /g, "-");
                    new_name = old_name.replace(old_type, new_type);
                    new_link = old_link.replace(old_type, new_type);

                    var old_path = old_link.replace("http://core.federatedservice.com/apps/deliverable_uploads/Documentation/", "/var/www/upload/Documentation/");
                    var new_path = new_link.replace("http://core.federatedservice.com/apps/deliverable_uploads/Documentation/", "/var/www/upload/Documentation/");
                    var url = "/code/ren_upload.php";
                    var parms = {
                        "old_path": old_path,
                        "new_path": new_path
                    };

                    $.getJSON(url, parms, function (result) {
                        if (result == true) {
                            fn_update_qb(<?php echo $row; ?>);
                        } else {
                            fn_replace_gritter(rename_alert, "Unsuccessful!", "Uploaded file could not be renamed.", "dn", false, false);
                        }
                    });

                    old_name = new_name;
                    old_link = new_link;
                    old_type = new_type;
                }

                /* 
                 * update QuickBase
                 * 
                 * always be cognizant of the fact that:
                 * when reading from QuickBase you retrieve 'Record ID#' for the row id, but
                 * when writing back to QuickBase in my classes, you send 'RowID' for the row id
                 */
                function fn_update_qb(row_id) {

                    var url = "/common/QuickBase/json/jsonQuickBaseUpdate.php";

                    var json_content = '[{';
                    json_content += '"RowID":"' + row_id + '",';
                    json_content += '"File Type":"' + $("select#doc-type").val() + '",';
                    json_content += '"File Location":"' + new_link + '"';
                    json_content += '}]';
                    var parms = {
                        "table": "bf8syh8uj",
                        "action": "upd",
                        "content": json_content,
                        "mode": "data"
                    };
                    $.getJSON(url, parms, function (result) {
                        /*
                         * should return [{"action":"API_EditRecord","errcode":"0","errtext":"No error","rid":"96122","num_fields_changed":"2","update_id":"1445034978739"}]
                         */

                        $("#submit-form").attr('disabled', true);
                        $("#file-name").val(new_name);
                        $("#file-link").val(new_link);
                        $.gritter.add({
                            position: "bottom-left",
                            title: "Update ID: " + result[0].update_id,
                            text: "Upload type changed to " + new_type,
                            image: "/images/common/notice.png",
                            fade_in_speed: "medium",
                            fade_out_speed: "slow",
                            //time: 3000,
                            before_open: function () {
                                $.gritter.remove(rename_alert, {});
                            },
                            sticky: true
                        });
                    });
                }

                fn_init_form();

                $("#doc-type").focus();
                $("body").show();
            });
        </script>
    </body>
</html>
