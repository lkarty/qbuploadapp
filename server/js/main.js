/*
 * jQuery File Upload Plugin JS Example 8.3.0
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

/*jslint nomen: true, regexp: true */
/*global $, window, blueimp */

$(function () {
    'use strict';

    /* RPW - indicate here the form action file location, max file size and acceptable file types */
    // Initialize the jQuery File Upload widget: (rpw 8/6/13: add max file size and accepted file types here)
    $('#fileupload').fileupload({
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        url: '/apps/deliverable_uploads/server/php/index.php',
        maxFileSize: 50000000,
        //acceptFileTypes: /(\.|\/)(zip|pdf|pcap|dwg|xls|xlsx|doc|docx|gif|jpe?g|png)$/i
        acceptFileTypes: /(\.|\/)(zip|pdf|pcap|dwg|xls|xlsx|doc|docx|gif|jpe?g|png|.3gp|mp4|mov|avi)$/i
    });

    // Enable iframe cross-domain access via redirect option:
    $('#fileupload').fileupload(
            'option',
            'redirect',
            window.location.href.replace(
                    /\/[^\/]*$/,
                    '/cors/result.html?%s'
                    )
            );

    // Load existing files:
    $('#fileupload').addClass('fileupload-processing');

    $.ajax({
        /**
         * Uncomment the following to send cross-domain cookies:
         * 
         * xhrFields: {withCredentials: true},
         */

        /**
         * url is the full link to the file location
         */
        url: $('#fileupload').fileupload('option', 'url'),
        dataType: 'json',
        context: $('#fileupload')[0]
    }).always(function () {
        $(this).removeClass('fileupload-processing');
    }).done(function (result) { 
        $(this).fileupload('option', 'done').call(this, null, {result: result});
    });

});
