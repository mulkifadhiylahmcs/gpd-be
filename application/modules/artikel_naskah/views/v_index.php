    <ul class="collapsible card-panel roboto main_content" style="margin: 1rem 0.75rem 1rem 0.75rem; padding: 5px;">
        <li>
            <div class="collapsible-header filter-header"><i class="material-icons">search</i>Advance Search</div>
            <div class="collapsible-body row filter-body">
                <form id="formFilter" name="formFilter" method="post">
                    <div class="input-field col s4 m4 l4">
                        <select class="validate regionfil" multiple id="fil_region_id" name="fil_region_id[]">
                        </select>
                        <label for="fil_region_id">Region</label>
                    </div>
                    <div class="input-field col s4 m4 l4">
                        <select class="validate channelfil" multiple id="fil_channel_id" name="fil_channel_id[]">
                        </select>
                        <label for="fil_channel_id">Channel</label>
                    </div>
                    <div class="input-field col s4 m4 l4">
                        <select class="validate accountfil" multiple id="fil_account_id" name="fil_account_id[]">
                        </select>
                        <label for="fil_account_id">Account</label>
                    </div>
                    <div class="input-field col s12 m12 l3">
                        <select class="validate" multiple id="fil_approval_status_code" name="fil_approval_status_code[]">
                        </select>
                        <label for="fil_approval_status_code">Status</label>
                    </div>
                    <div class="input-field col s4 m5 l3">
                        <input type="text" class="datepicker" id="fil_start_date" name="fil_start_date">
                        <label for="fil_start_date">Date From</label>
                    </div>
                    <div class="input-field col s4 m5 l3">
                        <input type="text" class="datepicker" id="fil_end_date" name="fil_end_date">
                        <label for="fil_end_date">Date To</label>
                    </div>
                    <div class="input-field col s4 m2 l3 right-align">
                        <button type="button" id="btn_filter_search" class="btn btn-floating btn-small"><i class="material-icons">search</i>
                        </button>
                        <button type="button" id="btn_filter_download" class="btn btn-floating btn-small green lighten-2">
                            <i class="material-icons">file_download</i></button>
                    </div>
                </form>
            </div>
        </li>
    </ul>



    <div class="row card-panel roboto main_content" style="min-height:500px;margin: 0rem 0.75rem 1rem 0.75rem; padding: 5px 5px;">
        <div class="col s12 m12 l12" style="box-sizing: border-box;overflow: auto;width: 100%;">
            <span>CONTENT</span>
        </div>
    </div>

    <div class="row card-panel roboto form_content" style="display: none; margin: 1rem 0.75rem 1rem 0.75rem; padding: 5px;">

    </div>