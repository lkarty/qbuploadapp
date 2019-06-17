<?php
/**
 * 
 */
?>
<div id="modal-source-1" class="modal fade">
    <div class="modal-dialog modal-sm"> 
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Select work order ...</h4>
                <button type="button" 
                        class="close" 
                        data-dismiss="modal">
                    &times;
                </button>
            </div>
            <div class="modal-body">
                <form id="form-select-upload" class="modal_login">  
                    <label class="col-md-12">
                        &nbsp;Work Order #:
                        <input id="login-work-order"
                               name="login_work_order"
                               class="form-control"
                               type="text"
                               value="<?php echo($wko > '' ? $wko : ''); ?>"
                               placeholder="Work Order #" 
                               REQUIRED >
                    </label>
                    <label class="col-md-12" style="margin-top:1rem">
                        &nbsp;Lead Tech ID or Phone #:
                        <input id="login-tech-key"
                               name="login_tech_key"
                               class="form-control phone_us" 
                               type="text"
                               value="<?php echo($key > '' ? $key : ''); ?>"
                               placeholder="Tech ID or Phone #" 
                               REQUIRED >
                    </label>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" 
                        class="btn btn_nav_blue">
                    <i class="fas fa-check"></i>
                    Login
                </button>
                <button type="button" 
                        class="btn btn-md btn_nav_blue" 
                        data-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<div id="modal-source-2" class="modal fade">
    <div class="modal-dialog"> 
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">To create a zip file ...</h4>
                <button type="button" 
                        class="close" 
                        data-dismiss="modal">
                    &times;
                </button>
            </div>
            <div class="modal-body">
                <?php require_once "apps/deliverable_uploads/includes/zip_files.php"; ?>
            </div>

            <div class="d-flex justify-content-between">
                <div class="modal-footer">
                    <a href="#" 
                       class="btn btn-md btn_nav_blue" 
                       onclick="window.open('/common/help/pdfs/Using the Upload Zip Window.pdf', '_blank', 'fullscreen=yes');
                               return false;">
                        Help
                    </a>
                </div>

                <div class="modal-footer">
                    <button type="button" 
                            class="btn btn-md btn_nav_blue" 
                            data-dismiss="modal">
                        Close
                    </button>
                </div>

            </div>   
        </div>
    </div>
</div>

<div id="modal-source-3" class="modal fade">
    <div class="modal-dialog modal-lg"> 
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Upload Notes (please read carefully) ...</h4>
                <button type="button" 
                        class="close" 
                        data-dismiss="modal">
                    &times;
                </button>
            </div>
            <div class="modal-body">
                <?php require_once "apps/deliverable_uploads/includes/instructions.php"; ?>
            </div>
            <div class="modal-footer">
                <button type="button" 
                        class="btn btn_nav_blue" 
                        data-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

