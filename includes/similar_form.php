<!-- similar_form.php -->
<form id="fileupload" 
      action="/apps/deliverable_uploads/server/php/index.php"
      method="POST" 
      enctype="multipart/form-data" >

    <input name="qb_rid" id="qb-rid" value="" type="hidden" />
    <input name="upl_src" id="upl-src" value="external" type="hidden" />

    <!-- 
        fileupload-buttonbar MUST be in div below for jquery.fileupload-ui.js 
    -->
    <div class="row fileupload-buttonbar">
        <div class="tech_inputs col-sm-12">
            <div class="col-sm-8" style="margin-top:.5em;">
                <label class="col-sm-8 pull-left align_right" style="margin-top:.25em;">
                    Work Order #: 
                </label>
                <input id="work-order"
                       name="work_order"
                       class="col-sm-2 push-right form-control"
                       style="text-align:center; max-width:7em; max-height:30px;"
                       value="<?php echo($workorder); ?>"
                       type="text"
                       REQUIRED />
            </div>
            <div class="col-sm-4 mutable d-none" style="margin-top:.5em;">
                <input name="tech_name" 
                       id="tech-name"  
                       class="form-control"
                       style="width:100%;text-align:center; max-width:14em; max-height:30px;"
                       REQUIRED />                                                
            </div>
            <div>
                <label class="col-sm-4 float-right align_left mutable d-none" style="padding:.35em 1.95em;">
                    If this is <em>not</em> you, please <br/>enter your name above.
                </label>
            </div>
            <div class="col-sm-8" style="margin-top:.5em;">
                <label class="col-sm-8 pull-left align_right" style="margin-top:.25em;">
                    Assigned Lead Tech ID or Phone Number:
                </label>
                <input id="tech-key" 
                       name="tech_key" 
                       class="col-sm-4 form-control" 
                       type="text" 
                       style="text-align:center;max-width:10em; max-height:30px;"
                       value="<?php echo($tech_id); ?>"
                       REQUIRED />
            </div>
            <!--
            <div id="go-button" class="col-sm-7 float-right" style="margin-top:.5em;padding-left:1.20em;">
                <button type="button" class="btn btn-default btn-md" style="max-height:30px;">
                    <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Go
                </button>
            </div>
            -->
        </div>
        <div class="row mutable d-none" style="margin-top:1em;">
            <div class="card card-body bg-light feedback row col-sm-11">
                <div class="row">
                    <div class='col-sm-5 pull-left mutable d-none'>
                        <label style="width:100%;">&nbsp;Customer:<br/>  
                            <input name="customer"
                                   id="customer"
                                   class="form-control"
                                   style="width:100%;"
                                   type="text"
                                   READONLY />
                        </label>
                    </div>
                    <div class='col-sm-2 mutable d-none'>
                        <label>&nbsp;Project #:<br/>   
                            <input name="project_no"
                                   id="project-no" 
                                   class="form-control"
                                   style="text-align:center;"
                                   type="text"
                                   READONLY />
                        </label>
                    </div>
                    <div class='col-sm-5 mutable d-none'>
                        <label style="width:100%;">&nbsp;Project Name:<br/>    
                            <input name="project_name" 
                                   id="project-name" 
                                   class="form-control"
                                   style="width:100%;"
                                   type="text"
                                   READONLY />
                        </label>
                    </div>
                </div>
                <div class="row">
                    <div class='col-sm-2 mutable d-none'>
                        <label>&nbsp;Site ID:<br/>	
                            <input name="site_id" 
                                   id="site-id" 
                                   class="form-control"
                                   style="text-align:center;"
                                   type="text"
                                   READONLY />
                        </label>
                    </div>
                    <div class='col-sm-8 mutable d-none'>
                        <label style="width:100%">&nbsp;Site Name:<br/>
                            <input name="site_name" 
                                   id="site-name" 
                                   class="form-control"
                                   style="width:100%;"
                                   type="text"
                                   READONLY />
                        </label>
                    </div>
                    <div class='col-sm-2 mutable d-none'>
                        <label>&nbsp;First Site Visit:<br/>
                            <input name="first_visit"
                                   id="first-visit"
                                   class="form-control"
                                   style="text-align:center;"
                                   type="text"
                                   READONLY />
                        </label>
                    </div>
                    <div class='col-sm-3 d-none'>
                        <label>&nbsp;Install Date:<br/>
                            <select name="install_date"
                                    id="install-date"
                                    class="form-control" >
                            </select>
                        </label>
                    </div>
                    <div class='col-sm-3 d-none'>
                        <input name="related_wko"
                               id="related-wko"
                               type="text"
                               READONLY />
                    </div>
                    <div class='col-sm-3 d-none'>
                        <input name="related_tech"
                               id="related-tech"
                               type="text"
                               READONLY />
                    </div>
                </div>
            </div>
        </div>

        <div class="corral pull-left mutable d-none"> 
            <!-- The fileinput-button span is used to style the file input field as button -->
            <div class="btn btn-success fileinput-button">
                <i class="fas fa-plus"></i>
                <span>Add files...</span>
                <input type="file" name="files[]" multiple />  <!--multiple-->
            </div>
            <button id="submit-btn" type="submit" class="btn btn-primary start">
                <i class="fas fa-upload"></i>
                <span>Upload</span>
            </button>
            <button type="reset" class="btn btn-warning cancel">
                <i class="fas fa-ban"></i>
                <span>Cancel</span>
            </button>
            <button type="button" class="btn btn-danger delete">
                <i class="fas fa-trash"></i>
                <span>Delete</span>
            </button>
            <input type="checkbox" class="toggle" style='margin-left:3px;'>
        </div>

        <!-- The global progress information -->
        <div class="col-sm-2 fileupload-progress fade">
            <!-- The global progress bar -->
            <div class="progress progress-success progress-striped active" 
                 role="progressbar" 
                 aria-valuemin="0" 
                 aria-valuemax="100">
                <div class="bar" style="width:0%;"></div>
            </div>
            <!-- The extended global progress information -->
            <div class="progress-extended">&nbsp;</div>
        </div>
    </div>
    <div class="fileupload-loading"></div>

    <!-- The table listing the files available for upload/download 
    <table role="presentation" class="table table-striped"><tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery"></tbody></table>-->
    <!-- The table listing the files available for upload/download -->
    <table role="presentation" class="table table-striped">
        <tbody class="files"></tbody>
    </table>
    
    <?php require_once "apps/deliverable_uploads/includes/drop_zone.php"; ?>

</form>
