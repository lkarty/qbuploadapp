<form id="fileupload" 
      action="/apps/deliverable_uploads/server/php/index.php"
      method="POST"
      enctype="multipart/form-data" >

    <input name="qb_rid" id="qb-rid" value="" type="hidden" />
    <input name="upl_src" id="upl-src" value="external" type="hidden" />

    <!-- 
        fileupload-buttonbar MUST be in div below for jquery.fileupload-ui.js 
    -->
    <div class="container fileupload-buttonbar px-1" style="text-align:left;">
        <div class="row w-100 px-0">
            <label class="col-4 col-sm-4 col-lg-3 mt-1">&nbsp;Work Order:
                <input id="work-order"
                       name="work_order"
                       class="form-control"
                       style="text-align:center;"
                       type="text"
                       value="<?php echo($wko); ?>"
                       READONLY >
            </label>
            <label class="col-8 col-sm-8 col-lg-9 mt-1 px-0">&nbsp;Customer:
                <input id="customer"
                       name="customer"
                       class="form-control"
                       type="text"
                       READONLY>
            </label>
        </div>

        <div class="row w-100">
            <label class="col-4 col-sm-4 col-lg-3 mt-1">&nbsp;Project:
                <input id="project-no" 
                       name="project_no"
                       class="form-control"
                       style="text-align:center;"
                       type="text"
                       READONLY>
            </label>
            <label class="col-8 col-sm-8 col-lg-9 mt-1 px-0">&nbsp;Project Name:
                <input  id="project-name"
                        name="project_name"
                        class="form-control"
                        type="text"
                        READONLY>
            </label>
        </div>

        <div class="row w-100">
            <label class="col-6 col-sm-7 col-md-4 col-lg-3 mt-1 order-1">&nbsp;Site ID:<br/>
                <input id="site-id"
                       name="site_id"                     
                       class="form-control"
                       style="text-align:center;"
                       type="text"
                       READONLY>
            </label>
            <label class="col-6 col-sm-5 col-md-3 col-lg-2 mt-1 pr-0 order-2 order-md-3">&nbsp;First Site Visit:<br/>
                <input id="first-visit"
                       name="first_visit"
                       class="form-control"
                       style="text-align:center;"
                       type="text"
                       READONLY>
            </label>
            <label class="col-12 col-sm-12 col-md-5 col-lg-7 mt-1 pl-md-0 pr-0 order-3 order-md-2">&nbsp;Site Name:<br/>
                <input id="site-name" 
                       name="site_name"
                       class="form-control"
                       type="text"
                       READONLY>
            </label>
        </div>

        <div class="row w-100">
            <label class="col-12 col-sm-6 col-md-7 col-lg-6 mt-1 pr-0">&nbsp;Enter your name (if not correct):
                <input id="tech-name" 
                       name="tech_name"
                       class="form-control"
                       style="text-align:center;"
                       type="text"
                       REQUIRED >
            </label>
            <label class="col-12 col-sm-6 col-md-5 col-lg-6 mt-1 pr-0">&nbsp;Lead Tech ID or Phone #:
                <input id="tech-key"
                       name="tech_key"
                       class="form-control xphone_us" 
                       style="text-align:center;"
                       type="text"
                       value="<?php echo($key); ?>"
                       READONLY >
            </label>
        </div>

        <div>
            <input id="related-wko" 
                   name="related_wko" 
                   class='d-none'
                   type="text"
                   READONLY>

            <input id="related-tech" 
                   name="related_tech" 
                   class='d-none'
                   type="text"
                   READONLY>
        </div>

        <div class="d-flex w-100 px-0">
            <div class="mt-4 d-flex justify-content-between">
                <!-- The fileinput-button span is used to style the file input field as button -->
                <div class="btn btn-md btn-success fileinput-button mr-1">
                    <i class="fas fa-plus"></i>
                    <span>Select File</span>
                    <input type="file" name="files[]" multiple />  <!--multiple-->
                </div>

                <button id="submit-btn" type="submit" class="btn btn-md btn-primary start">
                    <i class="fas fa-upload"></i>
                    <span>Upload</span>
                </button>

                <button type="reset" class="btn btn-md btn-warning cancel ml-1">
                    <i class="fas fa-ban"></i>
                    <span>Cancel</span>
                </button>
            </div>

            <!-- The global progress information -->
            <div class="d-none">
                <!-- The global progress bar -->
                <div class="progress progress-success progress-striped active"
                     role="progressbar"
                     aria-valuemin="0"
                     aria-valuemax="100">
                    <div class="bar" style="width:0%;"></div>
                </div>
                <!-- The extended global progress information
                <div class="progress-extended">&nbsp;</div> -->
            </div>
        </div>
    </div>

    <div class="fileupload-loading"></div>

    <!-- The table listing the files available for upload/download -->
    <div class="row col-12 pr-0 container">
        <table role="presentation" class="table table-striped d-flex h-100 mt-4">
            <tbody class="files h-100 w-100 flex-column"></tbody>
        </table>
    </div>
</form>

