<?php
/**
 * Tech Deliverables Upload Portal 
 */
set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER["DOCUMENT_ROOT"]);

/**
 * clean all input
 */
if (get_magic_quotes_gpc() == true) {
    foreach ($_REQUEST as $k => $v) {
        $_REQUEST[$k] = stripslashes($v);
    }

    foreach ($_COOKIE as $k => $v) {
        $_COOKIE[$k] = stripslashes($v);
    }
}

/**
 * set up fss_cookie variable
 */
$fss_cookie = json_decode($_COOKIE["fss_cookie"], true);

/**
 * get passed values
 */
$wko = is_numeric($_REQUEST["wko"]) && $_REQUEST["wko"] > 0 ? $_REQUEST["wko"] : 0;
$key = is_numeric($_REQUEST["key"]) && $_REQUEST["key"] > 0 ? $_REQUEST["key"] : 0;
$zip = "Photos_$wko.zip";
?>
<!doctype html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta charset="utf-8">

        <meta name="author" content="Rich Whiting">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Tech Deliverables Upload Portal</title>

        <!-- Force latest IE rendering engine or ChromeFrame if installed -->
        <!--[if IE]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <![endif]-->

        <link type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/10up-sanitize.css/5.0.0/sanitize.min.css" />

        <?php require_once "common/assets/assets_major.php"; ?>

        <!--
            APP-SPECIFIC LINKS
        -->

        <link type="text/css" rel="stylesheet" href="/style/css/technician.css" />

        <!-- blueimp upload css -->
        <link href="/libs/blueimp/css/style.css" rel="stylesheet" />
        <link href="/libs/blueimp/css/jquery.fileupload-ui.css" rel="stylesheet" />
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

        <?php require_once "apps/deliverable_uploads/includes/deliverables_navmenu.php"; ?>

        <div id="primary-panel" class="mutable container h-100 w-100 pr-0">
            <h4 id="panel-title">
                Tech Deliverables Upload
            </h4>
            <div id="activity-window" class="">
                <?php require_once "apps/deliverable_uploads/includes/deliverables_upload_form.php"; ?>
                <?php require_once "apps/deliverable_uploads/includes/template_upload_script.php"; ?>
                <?php require_once "apps/deliverable_uploads/includes/template_download_script.php"; ?>
                <?php require_once "apps/deliverable_uploads/includes/blueimp_scripts.php"; ?>
            </div>

            <div id="response-window" class="json_window col-md-12">
                <?php require_once "apps/deliverable_uploads/includes/invoice_dialog.php"; ?>
                <?php require_once "apps/deliverable_uploads/includes/deliverables_modals.php"; ?>
            </div>
        </div>

        <!-- JavaScript -->

        <?php require_once "common/assets/assets_minor.php"; ?>

        <!-- zip js -->
        <?php require_once "common/assets/assets_zip.php"; ?>

        <script type="text/javascript">

            var dt = new Date().yyyymmdd();
            var dtm = new Date().toString('yyyy-MM-dd hh:mm:ss');

            var gritter;
            var phone_flag = false;
            var is_USA = null;
            var is_grittered = false;
            var is_dp = true; // by default, assume tech is a DP

            function fn_reset_form() {
                $('#customer').val('');
                $('#project-no').val('');
                $('#project-name').val('');
                $('#tech-id').html('');
                $('#tech-id').val('');
                $('#site-id').val('');
                $('#site-name').val('');
                $('#install-date').html('');
                $('#row-id').val('');
                $('#related-wko').val('');
                $('#related-tech').val('');
                $('#tech-name').val('');
                $('#uploader-name').val('');
                $('#zip-name-input').val('Photos_' + ($('#work-order').val() > 0 ? $('#work-order').val() : '0') + '.zip');
                $('.mutable').addClass('d-none');
                $('.resettable').trigger('reset');
            }

            $(document).ready(function () {

                $('.mutable').addClass('d-none');

                $('body').show();

                $('#invoice-date').datepicker({dateFormat: 'yy-mm-dd'});
                $('#invoice-date').datepicker('setDate', dtm);

                $('form.invoice_form').trigger('reset');
                $('.phone_us').mask('(000) 000-0000');
                $('.money_us').mask('000,000,000,000,000.00', {reverse: true});

                $('#fileupload')[0].reset();
                $('#zip-name-input').val('Photos_' + $('#work-order').val() + '.zip');

                /**
                 * enforce integers only on invoice
                 * note: this does not work, since the form is only
                 * a template to be used later when cloning the form.
                 * Must figure out a way around this.
                 */
                $('.positive-integer').numeric({decimal: false, negative: false}, function () {
                    $.gritter.add({
                        position: 'bottom-left',
                        title: 'Invalid Invoice Number',
                        text: 'Invoice numbers must include positive integers only.',
                        image: '/images/common/roma.jpg',
                        fade_in_speed: 'medium',
                        fade_out_speed: 'slow',
                        time: 3000,
                        sticky: false
                    });
                    this.value = '';
                    this.focus();
                });

                $('#fileupload')
                        .on('fileuploadchange', function (e, data) {
                            /* first callback to fire each time a file is ADDED ... */
                        })
                        .on('fileuploadadd', function (e, data) {
                            /* fires after EACH fileuploadchange ... */
                            /* file is queued and thumbnail shows */
                        })
                        .on('fileuploadadded', function (e, data) {
                            /* fires AFTER each file is added to queue ... */
                        })
                        .on('fileuploadsubmit', function (e, data) {
                            /**
                             * upload deliverable(s)
                             */
                            /**
                             * if no site visits, need fake install-date
                             */
                            if ($('#install-date').val() == null) {
                                var idate = new Date().toJSON().substring(0, 10);
                                var sdate = '<option value="'
                                        + idate + '">'
                                        + idate
                                        + '</option>';
                                $('#install-date').html(sdate);
                                $('#install-date').val($('#install-date option:first').val());
                                //alert('null -> ' + $('#install-date').val());
                            } else {
                                //alert('not null -> ' + $('#install-date').val());
                            }

                            /**
                             * confirm all required fields supplied
                             */
                            var ctx = data.context.find($(':input'));
                            if (ctx.filter('[REQUIRED][value=""]').first().focus().length) {
                                return false;
                            }

                            /**
                             * IMPORTANT: pass upload type to files
                             *
                             * keep this!
                             *
                             * we MUST push doc_type onto formData
                             * for it to correctly pass upload type to files[]
                             *
                             * doc_type is the select field ADDED into the
                             * form after the upload window is painted;  must be
                             * obtained via context.find since it is dynamic and
                             * not part of the static form
                             */
                            var frm = ctx.serializeArray();
                            data.formData = $('#fileupload').serializeArray();
                            data.formData.push({name: 'doc_type', value: frm[0].value});
                        })
                        .on('fileuploadstart', function (e) {
                            /* fires after fileuploadsubmit ... */
                        })
                        .on('fileuploadsend', function (e, data) {
                            /* initiates cycle for EACH file after fileuploadstart ... */
                        })
                        .on('fileuploadsent', function (e, data) {
                            /* fires after fileuploadsend ... */
                        })
                        .on('fileuploadprogress', function (e, data) {
                            //alert('fileuploadPROGRESS');
                        })
                        .on('fileuploaddone', function (e, data) {
                            /* upload for one file done */
                        })
                        .on('fileuploadalways', function (e, data) {
                            /* fires for current file after fileuploadprogress ... */
                        })
                        .on('fileuploadstop', function (e, data) {
                            /* goto next fileuploadsubmit */
                        })
                        .on('fileuploadprogressall', function (e, data) {
                            progress = parseInt(data.loaded / data.total * 100, 10);
                            if (progress === 100) {
                                //fn_add_gritter('all done', 'hover and click x to close window.', 'fa-trophy', 'faa-tada faa-slow', true);
                            }
                        });

                $('#login-tech-key').on('keyup', function () {
                    if ($('#login-tech-key').val().length > 6) {
                        $('#login-tech-key').addClass('phone_us');
                        $('#tech-key').addClass('phone_us');
                        $('.xphone_us').addClass('phone_us');
                    } else {
                        //$('#login-tech-key').removeClass('phone_us');
                        //$('#tech-key').removeClass('phone_us');
                    }
                });

                $('#login-tech-key').on('blur', function () {
                    if ($('#login-tech-key').val().length > 6) {
                        $('#login-tech-key').addClass('phone_us');
                        $('#tech-key').addClass('phone_us');
                        $('.xphone_us').addClass('phone_us');
                    } else {
                        //$('#login-tech-key').removeClass('phone_us');
                        //$('#tech-key').removeClass('phone_us');
                    }
                });

                /**
                 * when tech id/phone changed
                 */
                $('#login-tech-key').change(function () {
                    $('#tech-key').val($('#login-tech-key').val());
                    if ($('#login-tech-key').val() > ' ') {
                        if ($('#login-work-order').val() > ' ') {
                            fn_get_wko_tech_visits();
                        } else {
                            $('#login-work-order').focus();
                        }
                    } else {
                        $('#login-work-order').focus();
                    }
                });

                /**
                 * when work order changed
                 */
                $('#login-work-order').change(function () {
                    $('#zip-name-input').val('Photos_' + $('#login-work-order').val() + '.zip');
                    $('#work-order').val($('#login-work-order').val());

                    if ($('#login-work-order').val() > ' ') {
                        if ($('#login-tech-key').val() > ' ') {
                            fn_get_wko_tech_visits();
                        } else {
                            $('#login-tech-key').focus();
                        }
                    } else {
                        $('#login-tech-key').focus();
                    }
                });

                /**
                 * when tech name changed
                 */
                $('#tech-name').change(function () {
                    $('#uploader-name').val($('#tech-name').val());
                });

                $('#zip-name-input').change(function () {
                    // nothing here, just for possible future use;
                });

                /**
                 * get site visit data from QuickBase
                 */
                function fn_get_wko_tech_visits() {
                    gritter = fn_new_gritter('Please wait', 'searching ...', '', '', true);

                    $.getJSON('/apps/deliverable_uploads/code/get_wko_tech_visits.php', {
                        tid: $('#login-tech-key').val(),
                        wko: $('#login-work-order').val()
                    }, function (json) {
                        if (json[0] > '') {
                            var idate = new Date().toJSON().substring(0, 10); // format yyyy-mm-dd

                            if (json[0]['Visit Start Date'] <= idate || 1 == 1) { 	// exclude future visits
                                rid = json[0]['Record ID#'];
                                tid = json[0]['Scheduled Tech ID'];
                                tnm = json[0]['Scheduled Technician'];
                                tpn = json[0]['Scheduled Technician Phone'];
                                sdc = json[0]['Scheduled Deployment Company'];
                                cnm = json[0]['Customer Name'];
                                sno = json[0]['Site#'];
                                sid = json[0]['Site ID'];
                                snm = json[0]['Site Name'];
                                adr = json[0]['Full Address'];
                                pno = json[0]['Project #'];
                                pnm = json[0]['Project Name'];
                                wko = json[0]['Work Order'];
                                fci = json[0]['Date/Time of First Check In (EST)'];
                                rwo = json[0]['Related Work Order'];
                                rti = json[0]['Related Technician'];

                                is_USA = (adr.indexOf('United States') > -1) ? true : false;
                                is_grittered = false;

                                switch (sdc) {
                                    case 'Federated Service Solutions (FSS)':
                                    case 'Field Nation LLC':
                                    case 'OnForce':
                                        is_dp = false;
                                        break;
                                    default:
                                       is_dp = true;
                                }



                                $('#tech-name').val(tnm);
                                $('#customer').val(cnm);
                                $('#project-no').val(pno);
                                $('#project-name').val(pnm);
                                $('#tech-id').val(tid);
                                $('#site-id').val(sid);
                                $('#site-name').val(snm);
                                $('#first-visit').val(fci.substring(0, 10));
                                $('#install-date').val(fci.substring(0, 10));
                                $('#related-wko').val(rwo);
                                $('#related-tech').val(rti);
                                $('#uploader-name').val(tnm);
                                $('#related-wo').val(rwo);  // for possible invoice

                                /**
                                 * get delivery partner info
                                 */
                                $.getJSON('/apps/deliverable_uploads/code/get_tech_dp.php', {
                                    rti: rti,
                                    tpn: tpn
                                }, function (data) {
                                    if (data[0] > '') {
                                        tek = data[0]['Technician Name'];
                                        rdc = data[0]['Related Deployment Company'];
                                        dco = data[0]['Deployment Company'];

                                        $('#uploader-name').val(tek);
                                        $('#related-dp').val(rdc);
                                    } else {
                                        fn_reset_form();
                                        fn_replace_gritter(gritter, 'Oops', 'Related Tech ' + rti + ' does not appear to be a delivery partner.', 'dn', false, false);
                                    }
                                });
                                /**
                                 * close the modal
                                 */
                                $('#modal-source-1').modal('toggle');
                                /**
                                 * do on hiding the modal (not used)
                                 */
                                $('#modal-source-1').on('d-none.bs.modal', function () {
                                    //alert('we did it!');
                                });
                                $('.mutable').removeClass('d-none');

                                fn_remove_gritter(gritter);
                            } else {
                                $('.mutable').addClass('d-none');

                                fn_reset_form();
                                fn_replace_gritter(gritter, 'Oops', 'This appears to be a future visit', 'dn', false, false);
                            }
                        } else {
                            //$('.mutable').addClass('d-none');
                            fn_reset_form();
                            fn_replace_gritter(gritter, 'Oops', 'Invalid W/O or lead tech ID/phone number.', 'dn', false, false);
                        }
                    });
                }

                /**
                 * NOTE use of '$(document).on('focus', '.invoice_form', function(){...});'
                 * so cloned form can see it
                 */
                $(document).on('focus', '.invoice_form', function () {
                    if (is_grittered === false) {
                        fn_add_gritter('FSS is a U.S. resale tax exempt company.', 'If needed, please request a copy of our tax exempt certificate from accounting@federatedservice.com. Please include whether you need a specific state or multi-jurisdictional in your email. Thank you.', 'fa-institution', true, true);
                        is_grittered = true;
                    }
                    if (is_USA === true) {
                        $('.tax_gst_hst').attr('disabled', true);
                    } else {
                        $('.tax_gst_hst').attr('disabled', false);
                    }
                });

                $(document).on('submit', '.invoice_form', function () {
                    // alert('came this way'); YES! This does work!
                });

                /**
                 * fix amount.replace(/[^\d\.]/g, '');
                 */

                /**
                 * check if being sent parameters on initial call
                 */
                var wko = $.url.param('wko') > '' ? $.url.param('wko') : null;
                $('#login-work-order').val(wko);
                $('#work-order').val(wko);

                var key = $.url.param('key') > '' ? $.url.param('key') : null;
                $('#login-tech-key').val(key);
                $('#tech-key').val(key);

                if (wko !== null && wko > '') {
                    if (key !== null && key > '') {
                        /**
                         * check if long enough to be phone #
                         */
                        if ($('#login-tech-key').val().length > 6) {
                            $('#login-tech-key').addClass('phone_us');
                            $('#tech-key').addClass('phone_us');
                        } else {
                            $('#login-tech-key').removeClass('phone_us');
                            $('#tech-key').removeClass('phone_us');
                        }
                        /**
                         * open upload page for work order
                         */
                        fn_get_wko_tech_visits();
                        /**
                         * close the modal dialog
                         */
                        $('#modal-source-1').modal('toggle');
                    }
                } else {
                    //$('#login-work-order').focus();
                }

                /**
                 * cookie routines
                 */

                /**
                 * delete cookie
                 */
                function fn_del_cookie_data(_name) {
                    Cookies.remove(_name, {path: '/'});
                }

                /**
                 * store data in cookie
                 *
                 * _name is the cookie name (e.g., "fss_cookie")
                 * _cookie is the cookie content object
                 */
                function fn_set_cookie_data(_name, _cookie) {
                    var data = JSON.stringify(_cookie);
                    Cookies.set(_name, data, {expires: 7, path: '/'});
                }

                /**
                 * get data from cookie
                 */
                function fn_get_cookie_data(_name) {
                    var data = Cookies.get(_name);
                    return JSON.parse(data);
                }

                /**
                 * return updated data in cookie
                 */
                function fn_upd_cookie_data(_name, _object) {
                    var tmp = {};
                    tmp = fn_get_cookie_data(_name);
                    jQuery.extend(tmp, _object);
                    fn_set_cookie_data(_name, tmp)
                    return fn_get_cookie_data(_name);
                }

            });
        </script>
    </body>
</html>
