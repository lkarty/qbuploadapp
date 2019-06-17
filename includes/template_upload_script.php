<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
    {% for (var i=0, file; file=o.files[i]; i++) { console.log(o); %}
    <tr class="template-upload row d-flex h-100 w-100 mx-0 px-0">
    <td class="col-12 d-table-cell col-md-2 col-lg-1 mt-auto mb-auto" style="border: none;">
    <span class="preview"></span>
    </td>
    <td  class="col-12 col-md-4 col-lg-5 mt-auto mb-auto" style="border: none;">
    <input type="text" id="doc-name" class="doc_name form-control upload_entry" value="{%=file.name%}" READONLY />
    {% if (file.error) { %}
    <div><span class="label label-important mt-auto mb-auto">Error</span>{%=file.error%}</div>
    {% } %}
    </td>
    <td class="col-12 d-table-cell col-md-2 col-lg-3 mt-auto mb-auto" style="border: none;">
    <p class="size" style="font-size:smaller">{%=o.formatFileSize(file.size)%}</p>
    {% if (!o.files.error) { %}
    <div class="progress progress-success progress-striped active upload_entry" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
    <div class="bar" style="width:0%;">
    </div>
    </div>
    {% } %}
    </td>
    <td class="dox_type col-12 col-md-4 col-lg-3 mt-auto mb-auto" style="border: none;">
    <label class="pt-2 h-100 w-100"><!-- upload type -->
    <select id="doc-type" class="form-control upload_entry" style="height:calc(2.25rem + 2px);" name="doc_type[]" REQUIRED>
    <option value=NULL>&nbsp;- Upload Type -&nbsp;</option>
    <option value="Advanced Services">Advanced Services</option>
    <option value="As-Builts">As-Builts</option>
    <option value="Business License">Business License</option>
    <option value="Completed Forms">Completed Forms</option>
    <option value="Daily Checklist">Daily Checklist</option>
    {% if (is_dp === true) { %}
    <option value="DP Invoice">DP Invoice</option>               
    {% } %} 
    <option value="Equipment Inventory">Equipment Inventory</option>
    <option value="Permit">Permit</option>
    <option value="Photographs">Photographs</option>
    <option value="Post Install Photos">&nbsp;&blacktriangleright; Post-Install</option>
    <option value="Pre Install Photos">&nbsp;&blacktriangleright; Pre-Install</option>
    <option value="Survey Photos">&nbsp;&blacktriangleright; Survey</option>
    <option value="Signed Work Order">Signed Work Order</option>
    <option value="Survey Form">Survey Form</option>
    <option value="Test Results">Test Results</option>
    <option value="Lien Waiver">Lien Waiver</option>
    </select>
    </label>
    </td>
    <td class="d-none">
    {% if (!o.files.error && !i && !o.options.autoUpload) { %}
    <button class="btn btn-primary start d-none">
    <i class="fas fa-upload"></i>
    <span>Start</span>
    </button>
    {% } %}
    {% if (!i) { %}
    <button class="btn btn-warning cancel">
    <i class="fas fa-ban"></i>
    <span>Cancel</span>
    </button>
    {% } %}
    </td>
    </tr>
    {% } %}
</script>
