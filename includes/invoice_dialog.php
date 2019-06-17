<div id="invoice-dialog" class="invoice_dialog container" title="Please enter the following for this invoice" style="display:none;">
    <form id="invoice-form" class="invoice_form">
        <fieldset>
            <div class="col-md-12">
                <div class='col-md-3 d-none'>
                    <label>
                        &nbsp;Related Work Order:<br/>
                        <input name="related_wo"
                               id="related-wo"
                               class="text-center"
                               type="text"
                               READONLY REQUIRED />
                    </label>
                </div>
                <div class='col-md-3 d-none'>
                    <label>&nbsp;Invoice Type:<br/>
                        <input name="invoice_type"
                               id="invoice-type"
                               class="text-center"
                               type="text"
                               value="Deployment Partner"
                               READONLY REQUIRED />
                    </label>
                </div>
                <div class='col-md-3 d-none'>
                    <label>
                        &nbsp;Related DP:<br/>
                        <input name="related_dp"
                               id="related-dp"
                               class="text-center"
                               type="text"
                               READONLY />
                    </label>
                </div>
            </div>
            <div class="col-md-12" style="margin:0 0 0 .33em;">
                <div class='col-md-4'>
                    <label>
                        &nbsp;Invoice #:<br/>
                        <input name="invoice_no"
                               id="invoice-no"
                               class="col-md-11 text-center"
                               type="text"
                               REQUIRED />
                    </label>
                </div>
                <div class='col-md-4'>
                    <label>
                        &nbsp;Invoice Date:<br/>
                        <input name="invoice_date"
                               id="invoice-date"
                               class="col-md-11 text-center"
                               type="text"
                               value=<?php echo date("Y-m-d"); ?>
                               REQUIRED />
                    </label>
                </div>
                <div class='col-md-4'>
                    <label>
                        &nbsp;Your Name:<br/>
                        <input name="uploader_name"
                               id="uploader-name"
                               class="col-md-11 text-center"
                               type="text"
                               REQUIRED />
                    </label>
                    <input name="user_name" id="user-name" type="text"
                           value="<?php echo($_SESSION['fullname']); ?>"
                           class="d-none"
                           READONLY />
                </div>
            </div>
        </fieldset>

        <h4>Cost Breakouts</h4>
        <fieldset>
            <div class="row col-md-12 cost_breakouts" style="margin:0 0 0 .33em;">
                <div class="col-md-3">
                    <label>
                        Equipment Cost:<br/>
                        <input name="equipment_cost"
                               id="equipment-cost"
                               class='col-md-11 money_us align_right'
                               type="text"
                               value="0" />
                    </label>
                </div>
                <div class="col-md-3">
                    <label>
                        Labor/Material:<br/>
                        <input name="labor_cost"
                               id="labor-cost"
                               class='col-md-11 money_us align_right'
                               type="text"
                               placeholder="(required)"
                               REQUIRED />
                    </label>
                </div>
                <div class="col-md-3">
                    <label>
                        Out of Scope:<br/>
                        <input name="out_of_scope"
                               id="out-of-scope"
                               class='col-md-11 money_us align_right'
                               type="text"
                               value="0" />
                    </label>
                </div>
                <div class="col-md-3">
                    <label>
                        Tax (GST/HST):<br/>
                        <input name="tax_gst_hst"
                               id="tax-gst-hst"
                               class='tax_gst_hst col-md-11 money_us align_right'
                               type="text"
                               value="0" />
                    </label>
                </div>
                <br/>
                <div>
                    <input type="submit" value="&nbsp;Submit &nbsp;"
                           id="submit-invoice"
                           class="btn btn-primary col-md-2 text-center"
                           style="margin:2em 47%;"  />
                </div>
            </div>
        </fieldset>
    </form>
</div>

<div id="dialog-frame"></div>
