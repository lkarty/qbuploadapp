<?php
/**
 * this is the drop-down menu for the deliverables nav header
 */
?>
<nav class="navbar navbar-expand-sm navbar-dark bg-primary mb-4 mb-4 navbar-fixed-top">

    <a class="navbar-brand" href="http://core.federatedservice.com/index.php?wko=<?php echo $wko; ?>&key=<?php echo $key; ?>">
        <img src="/images/logos/fss_logo_white.gif" class="fss_logo"> 
        <span class="d-none d-lg-inline" style="font-size:smaller">
            Deliverables Upload Portal
        </span>
    </a>

    <button type="button" 
            class="navbar-toggler navbar-toggler-right camel_case" 
            data-toggle="collapse" 
            data-target=".navbar_tgt" 
            aria-controls="deliverables-dropdown-div" 
            aria-expanded="false"
            aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div id="deliverables-dropdown-div" class="collapse navbar-collapse justify-content-end navbar_tgt">
        <ul class="navbar-nav">
            <li class="nav-item li_navbar">
                <button id="modal-anchor-1" 
                        type="button" 
                        class="btn btn-sm btn_nav_blue" 
                        data-toggle="modal" 
                        data-target="#modal-source-1">
                    <i class="fas fa-file-invoice">&nbsp;</i>
                    Select Work Order
                </button>
            </li>
            <li class="nav-item li_navbar">
                <button id="modal-anchor-2" 
                        type="button" 
                        class="btn btn-sm btn_nav_blue" 
                        data-toggle="modal" 
                        data-target="#modal-source-2">
                    <i class="fas fa-file-archive">&nbsp;</i>
                    Create Zip File
                </button>
            </li>
            <li class="nav-item li_navbar">
                <button id="modal-anchor-3" 
                        type="button" 
                        class="btn btn-sm btn_nav_blue" 
                        data-toggle="modal" 
                        data-target="#modal-source-3">
                    <i class="fas fa-info">&nbsp;</i>
                    Upload Notes
                </button>
            </li>
        </ul>
    </div>

    <?php require_once "apps/deliverable_uploads/includes/deliverables_navmodals.php"; ?>

</nav>
