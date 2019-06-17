/*
 * jQuery File Upload User Interface Plugin 8.7.1
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 * 
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

/**
 * fn_remove_uploaded_file()
 */
function fn_remove_uploaded_file(url) {
    /**
     * this must be this domain -- change only when live!
     */
    var domain = /http:\/\/upload.federatedservice.com/i;
    var fullpath = url.replace(domain, "/var/www/upload");

    $.ajax({
        type: 'POST',
        data: {
            action: 'remove',
            fullpath: fullpath
        },
        url: '/apps/deliverable_uploads/code/rem_file.php',
        success: function (msg) {
            return(msg);
        },
        failure: function () {
            alert("YOWZER -- unable to remove the file and I can't think why! Maybe it doesn't really exist?");
            return false;
        },
        async: false
    });
}

/**
 * fn_remove_qb_invoice()
 */
function fn_remove_qb_invoice(row_id) {

    $.ajax({
        type: 'POST',
        data: {
            rid: row_id
        },
        url: '/apps/deliverable_uploads/code/delete_qb_invoice.php',
        success: function (msg) {
            return(msg);
        },
        failure: function () {
            alert("YOWZER -- unable to remove the invoice and I can't think why! Maybe it doesn't really exist?");
            return false;
        },
        async: false
    });
}


/**
 * modifications to return upload confirmation # and upload invoice to QB
 */
function fn_upload_qb_invoice(inv_vals, doc_file) {
    //alert("entering");
    /**
     * upload invoice data to QuickBase
     */
    var len = inv_vals.length;
    var valsObj = {};
    for (i = 0; i < len; i++) {
        valsObj[inv_vals[i].name] = inv_vals[i].value; //alert(inv_vals[i].name + ' -> ' + inv_vals[i].value);
    }
    //dump(doc_file);
    //dump(valsObj);

    var invoice_gritter = fn_new_gritter("Please wait", "uploading to invoices ...", '', '', true);

    $.getJSON("/apps/deliverable_uploads/code/get_qb_invoice.php", {
        ino: valsObj["invoice_no"],
        rdp: valsObj["related_dp"]
    }, function (json) {
        /*
         * queue up any existing invoices and ...
         */
        var arr = new Array(); // or arr = [];
        $.each(json, function (key, val) {
            status = val["Approval Status"];
            if (!arr.contains(status)) {
                arr.push(status);
            }
        });
        /*
         * ... disallow if any approved or entered (i.e., NOT rejected)
         */
        if (arr.contains('Approved') || arr.contains('Entered')) {
            /**
             * invoice exists and was not rejected! remove uploaded file
             */
            fn_remove_uploaded_file(doc_file['url']);
            $("#modal-invoice-exists").modal({
                closeClass: 'icon-remove'
            });
            fn_replace_gritter(invoice_gritter, "Oops", "DP Invoice " + valsObj["invoice_no"] + " already exists. Your document upload was terminated unsuccessfully and the file has been removed. If necessary, please upload your file again.", "dn", false, true);

        } else if (valsObj["equipment_cost"] + valsObj["labor_cost"] + valsObj["out_of_scope"] == 0) {
            /**
             * invoice amount due == 0! remove uploaded file
             */
            fn_remove_uploaded_file(doc_file['url']);
            $("#modal-invoice-exists").modal({
                closeClass: 'icon-remove'
            });
            fn_replace_gritter(invoice_gritter, "Oops", "DP Invoice " + valsObj["invoice_no"] + " amount due == 0. Your document upload was terminated unsuccessfully and the file has been removed. If necessary, please upload your file again.", "dn", false, true);

        } else {
            /**
             * upload invoice info to QB
             */
            var parms = {
                rwo: valsObj["related_wo"],
                typ: valsObj["invoice_type"],
                rdp: valsObj["related_dp"],
                dtr: new Date().toJSON().substring(0, 10), // does this work?
                ino: valsObj["invoice_no"],
                idt: valsObj["invoice_date"],
                eqt: valsObj["equipment_cost"],
                lab: valsObj["labor_cost"],
                oos: valsObj["out_of_scope"],
                tax: valsObj["tax_gst_hst"],
                unm: valsObj["uploader_name"],
                tid: doc_file['related_tech'],
                src: doc_file["upl_src"]
            };

            $.ajax({
                url: "/apps/deliverable_uploads/code/put_qb_invoice.php",
                type: "post",
                data: parms,
                dataType: "json",
                success: function (result) {
                    /**
                     * now proceed to upload doc info to QB
                     */
                    var parms = {
                        "loc": doc_file['url'],
                        "typ": doc_file['doc_type'],
                        "rwo": doc_file['related_wko'],
                        "idt": doc_file['ins_date'],
                        "rtk": doc_file['related_tech'],
                        "unm": doc_file['uploader']
                    };

                    $.ajax({
                        url: "/apps/deliverable_uploads/code/upl_deliverable_data_qb.php",
                        type: "post",
                        data: parms,
                        dataType: "json",
                        success: function (row_id) {
                            if (row_id == "NULL") {
                                /**
                                 * unsuccessful document upload! remove invoice and uploaded file
                                 */
                                fn_remove_invoice_qb();
                                fn_remove_uploaded_file(doc_file['url']);
                                $("#modal-upload-failed").modal({
                                    closeClass: 'icon-remove'
                                });
                                fn_replace_gritter(invoice_gritter, 'Unable to complete upload.', 'Your upload was unsuccessful and the invoice was removed. Please review your actions (did you remember to select an "upload type"?) and try again or call <b>248.688.0024</b> and press "2" for assistance.', "dn", false, true);
                            } else {
                                fn_replace_gritter(invoice_gritter, 'Confirmation #: ' + row_id, doc_file.name + ' has been successfully uploaded.<br/>(' + doc_file.org_name + ').<br/>Mouse over this alert and click x to close it.', "up", false, true);
                            }
                        },
                        failure: function () {
                            /**
                             * unsuccessful document upload! remove invoice and uploaded file
                             */
                            fn_remove_invoice_qb();
                            fn_remove_uploaded_file(doc_file['url']);
                            $("#modal-invoice-failed").modal({
                                closeClass: 'icon-remove'
                            });
                            fn_replace_gritter(invoice_gritter, 'Unable to complete upload.', 'Your upload was unsuccessful. Please review your actions and try again or call <b>248.688.0024</b> and press "2" for assistance.', "dn", false, true);
                        }
                    });
                },
                failure: function () {
                    /**
                     * unsuccessful invoice upload! remove uploaded file
                     */
                    fn_remove_uploaded_file(doc_file['url']);
                    fn_replace_gritter(invoice_gritter, "Oops", "Invalid invoice data. Document upload terminated unsuccessfully.", "dn", false, true);
                },
                async: false
            });
        }
    });
}


(function (factory) {
    'use strict';
    if (typeof define === 'function' && define.amd) {
        // Register as an anonymous AMD module:
        define([
            'jquery',
            'tmpl',
            '/libs/blueimp/js/jquery.fileupload-image',
            '/libs/blueimp/js/jquery.fileupload-audio',
            '/libs/blueimp/js/jquery.fileupload-video',
            '/libs/blueimp/js/jquery.fileupload-validate'
        ], factory);
    } else {
        // Browser globals:
        factory(
                window.jQuery,
                window.tmpl
                );
    }
}(function ($, tmpl, loadImage) {
    'use strict';
    $.blueimp.fileupload.prototype._specialOptions.push(
            'filesContainer',
            'uploadTemplateId',
            'downloadTemplateId'
            );

    /**
     * The UI version extends the file upload widget
     * and adds complete user interface interaction:
     */
    $.widget('blueimp.fileupload', $.blueimp.fileupload, {
        options: {
            /**
             * By default, files added to the widget are uploaded as soon
             * as the user clicks on the start buttons. To enable automatic
             * uploads, set the following option to true:
             */
            autoUpload: false,

            /**
             *  The ID of the upload template:
             */
            uploadTemplateId: 'template-upload',

            /** 
             * The ID of the download template:
             * 
             * downloadTemplateId: 'template-download',
             * 
             * commented this out 9/21/18 rw
             */

            /** 
             * The container for the list of files. If undefined, it is set to
             * an element with class "files" inside of the widget element:
             */
            filesContainer: undefined,

            /** 
             * By default, files are appended to the files container.
             * Set the following option to true, to prepend files instead:
             */
            prependFiles: false,

            /** 
             * The expected data type of the upload response, sets the dataType
             *  option of the $.ajax upload requests:
             */
            dataType: 'json',

            /**
             * Function returning the current number of files,
             * used by the maxNumberOfFiles validation:
             */
            getNumberOfFiles: function () {
                return this.filesContainer.children().length;
            },

            /** 
             * Callback to retrieve the list of files from the server response: 
             */
            getFilesFromResponse: function (data) {
                if (data.result && $.isArray(data.result.files)) {
                    return data.result.files;
                }
                return [];
            },

            /**
             * The add callback is invoked as soon as files are added to the fileupload
             * widget (via file input selection, drag & drop or add API call).
             * See the basic file upload widget for more information:
             */
            add: function (e, data) {
                var $this = $(this),
                        that = $this.data('blueimp-fileupload') || $this.data('fileupload'),
                        options = that.options,
                        files = data.files;
                data.process(function () {
                    return $this.fileupload('process', data);
                }).always(function () {
                    data.context = that._renderUpload(files).data('data', data);
                    that._renderPreviews(data);
                    options.filesContainer[
                            options.prependFiles ? 'prepend' : 'append'
                    ](data.context);
                    that._forceReflow(data.context);
                    that._transition(data.context).done(
                            function () {
                                if ((that._trigger('added', e, data) !== false)
                                        && (options.autoUpload || data.autoUpload)
                                        && data.autoUpload !== false && !data.files.error) {
                                    data.submit();
                                }
                            }
                    );
                });
            },

            /**
             * Callback for the start of each file upload request:
             */
            send: function (e, data) {
                var that = $(this).data('blueimp-fileupload') || $(this).data('fileupload');
                if (data.context && data.dataType &&
                        data.dataType.substr(0, 6) === 'iframe') {
                    /**
                     *  Iframe Transport does not support progress events.
                     *  
                     *   In lack of an indeterminate progress bar, we set
                     *    the progress to 100%, showing the full animated bar:
                     */
                    data.context
                            .find('.progress').addClass(!$.support.transition && 'progress-animated')
                            .attr('aria-valuenow', 100)
                            .find('.bar').css(
                            'width',
                            '100%'
                            );
                }
                return that._trigger('sent', e, data);
            },

            /**
             * Callback for successful uploads: here first!
             */
            done: function (e, data) {
                var that = $(this).data('blueimp-fileupload') || $(this).data('fileupload'),
                        getFilesFromResponse = data.getFilesFromResponse || that.options.getFilesFromResponse,
                        files = getFilesFromResponse(data),
                        template,
                        deferred;
                if (data.context) {
                    data.context.each(function (index) {
                        var file = files[index] || {error: 'Empty file upload result'};
                        deferred = that._addFinishedDeferreds();
                        that._transition($(this)).done(
                                function () {
                                    var node = $(this);
                                    template = that._renderDownload([file])
                                            .replaceAll(node);
                                    that._forceReflow(template);
                                    that._transition(template).done(
                                            function () {
                                                data.context = $(this);
                                                that._trigger('completed', e, data);
                                                that._trigger('finished', e, data);
                                                deferred.resolve();
                                            }
                                    );
                                }
                        );
                    });
                } else {
                    template = that._renderDownload(files)[
                            that.options.prependFiles ? 'prependTo' : 'appendTo'
                    ](that.options.filesContainer);
                    that._forceReflow(template);
                    deferred = that._addFinishedDeferreds();
                    that._transition(template).done(
                            function () {
                                data.context = $(this);
                                that._trigger('completed', e, data);
                                that._trigger('finished', e, data);
                                deferred.resolve();
                            }
                    );
                }
            },

            /**
             *   Callback for failed (abort or error) uploads:
             */
            fail: function (e, data) {
                var that = $(this).data('blueimp-fileupload') || $(this).data('fileupload'),
                        template,
                        deferred;
                if (data.context) {
                    data.context.each(function (index) {
                        if (data.errorThrown !== 'abort') {
                            var file = data.files[index];
                            file.error = file.error || data.errorThrown || true;
                            deferred = that._addFinishedDeferreds();
                            that._transition($(this)).done(
                                    function () {
                                        var node = $(this);
                                        template = that._renderDownload([file])
                                                .replaceAll(node);
                                        that._forceReflow(template);
                                        that._transition(template).done(
                                                function () {
                                                    data.context = $(this);
                                                    that._trigger('failed', e, data);
                                                    that._trigger('finished', e, data);
                                                    deferred.resolve();
                                                }
                                        );
                                    }
                            );
                        } else {
                            deferred = that._addFinishedDeferreds();
                            that._transition($(this)).done(
                                    function () {
                                        $(this).remove();
                                        that._trigger('failed', e, data);
                                        that._trigger('finished', e, data);
                                        deferred.resolve();
                                    }
                            );
                        }
                    });
                } else if (data.errorThrown !== 'abort') {
                    data.context = that._renderUpload(data.files)[
                            that.options.prependFiles ? 'prependTo' : 'appendTo'
                    ](that.options.filesContainer)
                            .data('data', data);
                    that._forceReflow(data.context);
                    deferred = that._addFinishedDeferreds();
                    that._transition(data.context).done(
                            function () {
                                data.context = $(this);
                                that._trigger('failed', e, data);
                                that._trigger('finished', e, data);
                                deferred.resolve();
                            }
                    );
                } else {
                    that._trigger('failed', e, data);
                    that._trigger('finished', e, data);
                    that._addFinishedDeferreds().resolve();
                }
            },

            /**
             * Callback for upload progress events:
             */
            progress: function (e, data) {
                if (data.context) {
                    var progress = Math.floor(data.loaded / data.total * 100);
                    data.context.find('.progress')
                            .attr('aria-valuenow', progress)
                            .find('.bar').css(
                            'width',
                            progress + '%'
                            );
                }
            },

            /** 
             * Callback for global upload progress events:
             */
            progressall: function (e, data) {
                /*
                 var $this = $(this),
                 progress = Math.floor(data.loaded / data.total * 100),
                 globalProgressNode = $this.find('.fileupload-progress'),
                 extendedProgressNode = globalProgressNode
                 .find('.progress-extended');
                 if (extendedProgressNode.length) {
                 extendedProgressNode.html(
                 ($this.data('blueimp-fileupload') || $this.data('fileupload'))
                 ._renderExtendedProgress(data)
                 );
                 }
                 globalProgressNode
                 .find('.progress')
                 .attr('aria-valuenow', progress)
                 .find('.bar').css(
                 'width',
                 progress + '%'
                 );
                 */
                progress = Math.floor(data.loaded / data.total * 100);
                if (progress === 100) {
                    $('.please_wait').html('<div style="margin:.67em;"></div>');
                }
            },

            /** 
             * Callback for uploads start, equivalent to the global ajaxStart event:
             */
            start: function (e) {
                var that = $(this).data('blueimp-fileupload') || $(this).data('fileupload');
                that._resetFinishedDeferreds();
                that._transition($(this).find('.fileupload-progress')).done(
                        function () {
                            that._trigger('started', e);
                        }
                );
            },

            /** 
             * Callback for uploads stop, equivalent to the global ajaxStop event:
             */
            stop: function (e) {
                var that = $(this).data('blueimp-fileupload') || $(this).data('fileupload'),
                        deferred = that._addFinishedDeferreds();
                $.when.apply($, that._getFinishedDeferreds())
                        .done(function () {
                            that._trigger('stopped', e);
                        });
                that._transition($(this).find('.fileupload-progress')).done(
                        function () {
                            $(this).find('.progress')
                                    .attr('aria-valuenow', '0')
                                    .find('.bar').css('width', '0%');
                            $(this).find('.progress-extended').html('&nbsp;');
                            deferred.resolve();
                        }
                );
            },

            processstart: function () {
                $(this).addClass('fileupload-processing');
            },

            processstop: function () {
                $(this).removeClass('fileupload-processing');
            },

            /** 
             * Callback for file deletion:
             */
            destroy: function (e, data) {
                var that = $(this).data('blueimp-fileupload') ||
                        $(this).data('fileupload'),
                        removeNode = function () {
                            that._transition(data.context).done(
                                    function () {
                                        $(this).remove();
                                        that._trigger('destroyed', e, data);
                                    }
                            );
                        };
                if (data.url) {
                    $.ajax(data).done(removeNode);
                } else {
                    removeNode();
                }
            }
        },

        _resetFinishedDeferreds: function () {
            this._finishedUploads = [];
        },

        _addFinishedDeferreds: function (deferred) {
            if (!deferred) {
                deferred = $.Deferred();
            }
            this._finishedUploads.push(deferred);
            return deferred;
        },

        _getFinishedDeferreds: function () {
            return this._finishedUploads;
        },

        /** 
         * Link handler, that allows to download files
         * by drag & drop of the links to the desktop:
         */
        _enableDragToDesktop: function () {
            var link = $(this),
                    url = link.prop('href'),
                    name = link.prop('download'),
                    type = 'application/octet-stream';
            link.bind('dragstart', function (e) {
                try {
                    e.originalEvent.dataTransfer.setData(
                            'DownloadURL',
                            [type, name, url].join(':')
                            );
                } catch (ignore) {
                }
            });
        },

        _formatFileSize: function (bytes) {
            if (typeof bytes !== 'number') {
                return '';
            }
            if (bytes >= 1000000000) {
                return (bytes / 1000000000).toFixed(2) + ' GB';
            }
            if (bytes >= 1000000) {
                return (bytes / 1000000).toFixed(2) + ' MB';
            }
            return (bytes / 1000).toFixed(2) + ' KB';
        },

        _formatBitrate: function (bits) {
            if (typeof bits !== 'number') {
                return '';
            }
            if (bits >= 1000000000) {
                return (bits / 1000000000).toFixed(2) + ' Gbit/s';
            }
            if (bits >= 1000000) {
                return (bits / 1000000).toFixed(2) + ' Mbit/s';
            }
            if (bits >= 1000) {
                return (bits / 1000).toFixed(2) + ' kbit/s';
            }
            return bits.toFixed(2) + ' bit/s';
        },

        _formatTime: function (seconds) {
            var date = new Date(seconds * 1000),
                    days = Math.floor(seconds / 86400);
            days = days ? days + 'd ' : '';
            return days +
                    ('0' + date.getUTCHours()).slice(-2) + ':' +
                    ('0' + date.getUTCMinutes()).slice(-2) + ':' +
                    ('0' + date.getUTCSeconds()).slice(-2);
        },

        _formatPercentage: function (floatValue) {
            return (floatValue * 100).toFixed(2) + ' %';
        },

        _renderExtendedProgress: function (data) {
            return this._formatBitrate(data.bitrate) + ' | ' +
                    this._formatTime(
                            (data.total - data.loaded) * 8 / data.bitrate
                            ) + ' | ' +
                    this._formatPercentage(
                            data.loaded / data.total
                            ) + ' | ' +
                    this._formatFileSize(data.loaded) + ' / ' +
                    this._formatFileSize(data.total);
        },

        /**
         * this is the biggie
         * 
         * modified rpw 7/2015
         * modified rpw 3/4/2016
         */
        _renderDownload: function (files) {
            /**
             * upload doc (and--if required--invoice), then gritter the upload record row ID for confirmation #
             */
            if ((typeof files[0] !== 'undefined')) {

                var fnm = files[0].name;
                var onm = files[0].org_name;
                var rid = files[0].related_wko;
                //console.log(files[0]);
                /**
                 * check if DP invoice
                 */
                if (files[0].doc_type.indexOf("DP Invoice") > -1) {
                    /**
                     * if so, first check whether previous deliverables 
                     * uploaded, and if not, then deny this invoice upload
                     */
                    $.getJSON("/apps/deliverable_uploads/code/get_deliverables_count.php", {rid: rid}, function (json) {
                        /*
                         * once the lien waiver is announced, replace the if statement with the commented out one
                         * and also reinstall the else if code below
                         * rpw 4/2/18
                         * 
                         * should also now account for fact that only after signed work order is uploaded
                         * should it be possible for DP to upload invoice -- TO DO rpw 10/8/18
                         */
                        //if ((json[0]["# of Deliverables"] > 0 || files[0].doc_type.indexOf("Service") > -1)
                        //&& (json[0]["# of Lien Waiver Documents"] > 0 || json[0]["Exempt From Lien Waiver"] == "yes")) {
                        if ((json[0]["# of Deliverables"] > 0 || files[0].doc_type.indexOf("Service") > -1)) {
                            /**
                             * clone invoice data form and handle invoice upload to QB ... only THEN upload doc info to QB
                             */
                            var dlg = $("#invoice-dialog").clone().appendTo("#dialog-frame");
                            var inv = dlg.clone(true, true);
                            inv.dialog({
                                title: "  Enter data for invoice file " + onm + " ...",
                                dialogClass: "alert no-close",
                                autoOpen: true,
                                height: 320,
                                width: 680,
                                modal: false,
                                closeOnEscape: false,
                                open: function (event, ui) {
                                    $(this).find("form.invoice_form").on("submit", function (event) {
                                        event.preventDefault();
                                        var inv_vals = $(this).serializeArray(); // what we want here is the form! May or may not be $(this)
                                        /**
                                         * call function to upload invoice and doc info to QB
                                         * note that we must pass BOTH the invoice form data AND the doc info data
                                         */
                                        fn_upload_qb_invoice(inv_vals, files[0]);
                                        $(this).parents("div.ui-dialog-content:first").dialog("close");
                                    });
                                },
                                close: function (event, ui) {
                                    $(this).remove();
                                }
                            });
                            /**
                             * and install this code, too! rpw 4/2/18
                             } else if (!(json[0]["# of Lien Waiver Documents"] > 0 || json[0]["Exempt From Lien Waiver"] == "yes")) {
                             /**
                             * lien waiver not qualified
                             *//*
                              fn_remove_uploaded_file(files[0]['xxxxurlxxxx']);
                              $("#modal-deny-invoice").modal({
                              closeClass: 'icon-remove'
                              });
                              fn_add_gritter('Unable to upload invoice.', 'NOTE: Your invoice has NOT been submitted. You must either have uploaded a lien waiver or be exempt from doing so before uploading an invoice.', 'fa-thumbs-down', 'faa-ring', true);
                              */
                        } else if (!(json[0]["# of Deliverables"] > 0 || files[0].doc_type.indexOf("Service") > -1)) {
                            /**
                             * missing deliverables and not a service
                             */
                            fn_remove_uploaded_file(files[0]['url']);
                            $("#modal-deny-invoice").modal({
                                closeClass: 'icon-remove'
                            });
                            fn_add_gritter('Unable to upload invoice.', 'NOTE: Your invoice has NOT been submitted. You cannot submit an invoice until AFTER ALL DELIVERABLES HAVE BEEN UPLOADED.', 'fa-thumbs-down', 'faa-ring', true);
                        }
                    });
                } else {
                    /**
                     * NOT a DP invoice, so normal upload
                     */
                    var parms = {
                        "loc": files[0]['url'],
                        "typ": files[0]['doc_type'],
                        "rwo": files[0]['related_wko'],
                        "idt": files[0]['ins_date'],
                        "rtk": files[0]['related_tech'],
                        "unm": files[0]['uploader']
                    };

                    $.ajax({
                        url: "/apps/deliverable_uploads/code/upl_deliverable_data_qb.php",
                        type: "post",
                        data: parms,
                        dataType: "json",
                        success: function (row_id) {
                            if (row_id == "NULL" || row_id == "") {
                                /**
                                 * upload was unsuccessful
                                 */
                                alert("row_id is null");
                                fn_remove_uploaded_file(files[0]['url']);
                                $("#modal-upload-failed").modal({
                                    closeClass: 'icon-remove'
                                });
                                fn_add_gritter('Unable to complete upload.', 'Your upload was unsuccessful. Please review your actions (did you remember to select an "upload type"?) and try again or call <b>248.688.0024</b> and press "2" for assistance.', 'fa-bomb', 'faa-pulse animated', true);
                            } else {
                                fn_add_gritter('Confirmation #: ' + row_id, fnm + ' has been successfully uploaded.<br/>(' + onm + ').<br/>Mouse over this alert and click x to close it.', 'fa-bell', 'faa-ring', true);
                            }
                        },
                        failure: function () {
                            /**
                             * unsuccessful doc info upload! remove uploaded file
                             */
                            fn_remove_uploaded_file(files[0]['url']);
                            $("#modal-upload-failed").modal({
                                closeClass: 'icon-remove'
                            });
                            fn_add_gritter('Unable to complete upload.', 'Your upload was unsuccessful. Please review your actions and try again or call <b>248.688.0024</b> and press "2" for assistance.', 'fa-animated', 'faa-ring', true);
                        },
                        //async: false
                    });

                }
            } else {
                /**
                 * this path is taken on initial load, 
                 * so no action since file[0] is empty
                 */
            }

            return this._renderTemplate(
                    this.options.downloadTemplate,
                    files
                    ).find('a[download]').each(this._enableDragToDesktop).end();
        },
        _renderTemplate: function (func, files) {
            if (!func) {
                return $();
            }
            var result = func({
                files: files,
                formatFileSize: this._formatFileSize,
                options: this.options
            });

            if (result instanceof $) {
                return result;
            }
            return $(this.options.templatesContainer).html(result).children();
        },
        _renderUpload: function (files) {
            return this._renderTemplate(
                    this.options.uploadTemplate,
                    files
                    );
        },
        _renderPreviews: function (data) {
            data.context.find('.preview').each(function (index, elm) {
                $(elm).append(data.files[index].preview);
            });
        },

        _startHandler: function (e) {
            e.preventDefault();
            var button = $(e.currentTarget),
                    template = button.closest('.template-upload'),
                    data = template.data('data');

            if (data && data.submit && !data.jqXHR && data.submit()) {
                button.prop('disabled', true);
            }
        },
        _cancelHandler: function (e) {
            e.preventDefault();
            var template = $(e.currentTarget).closest('.template-upload'),
                    data = template.data('data') || {};
            if (!data.jqXHR) {
                data.errorThrown = 'abort';
                this._trigger('fail', e, data);
            } else {
                data.jqXHR.abort();
            }
        },
        _deleteHandler: function (e) {
            e.preventDefault();
            /*
             var button = $(e.currentTarget);
             this._trigger('destroy', e, $.extend({
             context: button.closest('.template-download'),
             type: 'DELETE'
             }, button.data()));
             */
        },
        _forceReflow: function (node) {
            return $.support.transition && node.length &&
                    node[0].offsetWidth;
        },
        _transition: function (node) {
            var dfd = $.Deferred();
            if ($.support.transition && node.hasClass('fade') && node.is(':visible')) {
                node.bind(
                        $.support.transition.end,
                        function (e) {
                            // Make sure we don't respond to other transitions events
                            // in the container element, e.g. from button elements:
                            if (e.target === node[0]) {
                                node.unbind($.support.transition.end);
                                dfd.resolveWith(node);
                            }
                        }
                ).toggleClass('in');
            } else {
                node.toggleClass('in');
                dfd.resolveWith(node);
            }
            return dfd;
        },
        _initButtonBarEventHandlers: function () {
            var fileUploadButtonBar = this.element.find('.fileupload-buttonbar'),
                    filesList = this.options.filesContainer;
            this._on(fileUploadButtonBar.find('.start'), {
                click: function (e) {
                    e.preventDefault();
                    filesList.find('.start').click();
                }
            });
            this._on(fileUploadButtonBar.find('.cancel'), {
                click: function (e) {
                    e.preventDefault();
                    filesList.find('.cancel').click();
                }
            });
            /*
             this._on(fileUploadButtonBar.find('.delete'), {
             click: function (e) {
             e.preventDefault();
             filesList.find('.toggle:checked')
             .closest('.template-download')
             .find('.delete').click();
             fileUploadButtonBar.find('.toggle')
             .prop('checked', false);
             }
             });
             */
            this._on(fileUploadButtonBar.find('.toggle'), {
                change: function (e) {
                    filesList.find('.toggle').prop(
                            'checked',
                            $(e.currentTarget).is(':checked')
                            );
                }
            });
        },
        _destroyButtonBarEventHandlers: function () {
            this._off(
                    this.element.find('.fileupload-buttonbar')
                    .find('.start, .cancel, .delete'),
                    'click'
                    );
            this._off(
                    this.element.find('.fileupload-buttonbar .toggle'),
                    'change.'
                    );
        },
        _initEventHandlers: function () {
            this._super();
            this._on(this.options.filesContainer, {
                'click .start': this._startHandler,
                'click .cancel': this._cancelHandler,
                'click .delete': this._deleteHandler
            });
            this._initButtonBarEventHandlers();
        },
        _destroyEventHandlers: function () {
            this._destroyButtonBarEventHandlers();
            this._off(this.options.filesContainer, 'click');
            this._super();
        },
        _enableFileInputButton: function () {
            this.element.find('.fileinput-button input')
                    .prop('disabled', false)
                    .parent().removeClass('disabled');
        },
        _disableFileInputButton: function () {
            this.element.find('.fileinput-button input')
                    .prop('disabled', true)
                    .parent().addClass('disabled');
        },
        _initTemplates: function () {
            var options = this.options;
            options.templatesContainer = this.document[0].createElement(
                    options.filesContainer.prop('nodeName')
                    );
            if (tmpl) {
                if (options.uploadTemplateId) {
                    options.uploadTemplate = tmpl(options.uploadTemplateId);
                }
                if (options.downloadTemplateId) {
                    options.downloadTemplate = tmpl(options.downloadTemplateId);
                }
            }
        },
        _initFilesContainer: function () {
            var options = this.options;
            if (options.filesContainer === undefined) {
                options.filesContainer = this.element.find('.files');
            } else if (!(options.filesContainer instanceof $)) {
                options.filesContainer = $(options.filesContainer);
            }
        },
        _initSpecialOptions: function () {
            this._super();
            this._initFilesContainer();
            this._initTemplates();
        },
        _create: function () {
            this._super();
            this._resetFinishedDeferreds();
            if (!$.support.fileInput) {
                this._disableFileInputButton();
            }
        },
        enable: function () {
            var wasDisabled = false;
            if (this.options.disabled) {
                wasDisabled = true;
            }
            this._super();
            if (wasDisabled) {
                this.element.find('input, button').prop('disabled', false);
                this._enableFileInputButton();
            }
        },
        disable: function () {
            if (!this.options.disabled) {
                this.element.find('input, button').prop('disabled', true);
                this._disableFileInputButton();
            }
            this._super();
        }
    });
}));
