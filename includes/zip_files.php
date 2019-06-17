<style>
    input[type="file"]#zip-file-input {
        display: none;
    }

    #zip-steps-container a,
    #zip-steps-container a:visited,
    #zip-steps-container a:hover,
    #zip-steps-container a:active {
        color: white !important;
    }
    
    ol.ol_steps {
        list-style-type: none;
        /*list-style-type: decimal !ie; IE 7- hack*/

        margin: 0;
        margin-left: 3rem;
        padding: 0;

        counter-reset: li-counter;
    }
    ol.ol_steps > li{
        position: relative;
        margin-bottom: 1rem;
        padding-left: 0.5rem;
        min-height: 3rem;
        border-left: 2px solid #CCCCCC;
    }
    ol.ol_steps > li:before {
        position: absolute;
        top: 0;
        left: -1em;
        width: 0.8em;

        font-size: 3em;
        line-height: 1;
        font-weight: bold;
        text-align: right;
        color: #464646;

        content: counter(li-counter);
        counter-increment: li-counter;
    }
</style>

<div style="margin:1em;">
    <ol id="zip-steps-container" class="ol_steps">
        <li style="display: none !important;">
            <label>
                <select id="creation-method-input">
                    <option selected="selected" value="Blob">RAM</option>
                    <option value="File">HDD</option>
                </select>
            </label>
        </li>
        <li class="nav-item pt-1">
            <div id="select-files">
                <label for="zip-file-input" class="btn btn-md btn_modal_blue">
                    <i class="fas xfa-lg fa-upload"></i>
                    Select Files 
                    <input id="zip-file-input" name="files" type="file" multiple />
                </label>
            </div>
             <div id="zip-content" class="mb-2">
                Zip Contents:
                <ul id="zip-content-list" class="pl-3 pr-2 mb-3" style="border:2px solid #CCC;min-height:1rem"></ul>
            </div>
        </li>
        <li class="nav-item mb-3">
            <span id="zip-name" class="row col-12">
                Enter Name (or use default):&nbsp;
            </span>
            <input id="zip-name-input" 
                   class="form-control"
                   type="text" 
                   value="<?php echo $zip; ?>" >
        </li>
        <li class="nav-item pt-2 mb-3">  
            <div id="save-files">
                <label for="zip-download-anchor" class="btn btn-md btn_modal_blue">
                    <i class="fas xfa-lg fa-download"></i>
                    <a id="zip-download-anchor" href="#">
                        Save the Zip
                    </a> 
                </label>
            </div>
        </li>  
        <li class="nav-item pt-3">
            Upload the Zip File
        </li>
    </ol>
</div>