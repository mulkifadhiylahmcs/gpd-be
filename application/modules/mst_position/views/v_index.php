    <ul class="collapsible card-panel roboto main_content" style="margin: 1rem 0.75rem 1rem 0.75rem; padding: 5px;">
        <li>
            <div class="collapsible-header filter-header"><i class="material-icons">search</i>Advance Search</div>
            <div class="collapsible-body row filter-body">
                <form id="formFilter" name="formFilter" method="post">
                    <div class="input-field col s12 m4 l4">
                        <input type="text" class="validate" id="fil_code" name="fil_code">
                        <label for="fil_code" class="active">Code</label>
                    </div>
                    <div class="input-field col s12 m5 l5">
                        <input type="text" class="validate" id="fil_name" name="fil_name">
                        <label for="fil_name" class="active">Name</label>
                    </div>
                    <div class="input-field col s12 m3 l3">
                        <select class="validate" multiple id="fil_is_active" name="fil_is_active[]">
                        </select>
                        <label for="fil_is_active" class="active">Is Active?</label>
                    </div>
                    <div class="input-field col s12 m4 l4">
                        <select class="validate" multiple id="fil_department" name="fil_department[]">
                        </select>
                        <label for="fil_department" class="active">Department</label>
                    </div>
                    <div class="input-field col s12 m4 l4">
                        <select class="validate" multiple id="fil_division" name="fil_division[]">
                        </select>
                        <label for="fil_division" class="active">Division</label>
                    </div>
                    <div class="input-field col s12 m4 l4">
                        <select class="validate" multiple id="fil_position_parent" name="fil_position_parent[]">
                        </select>
                        <label for="fil_position_parent" class="active">Supervisi</label>
                    </div>
                    <div class="input-field col s12">
                        <button style="width: 100%;" type="button" id="btn_filter_search" class="btn waves-effect waves-light">Search
                        </button>
                    </div>
                </form>
            </div>
        </li>
    </ul>

    <div class="row card-panel roboto main_content" style="min-height: 500px; margin: 0rem 0.75rem 1rem 0.75rem; padding: 5px;">
        <div class="col s12 m12 l12" style="box-sizing: border-box;overflow: auto;width: 100%;">
            <table id="mainGrid" class="display cell-border hover compact" style="width:100%">
                <thead class="grey-text text-darken-3 orange lighten-4">
                    <tr class="center-align">
                        <th class="center-align">No</th>
                        <th class="center-align">Code</th>
                        <th class="center-align">Name</th>
                        <th class="center-align">Supervisi</th>
                        <th class="center-align">Department</th>
                        <th class="center-align">Division</th>
                        <th class="center-align">Is Active?</th>
                        <th class="center-align action-col" style="min-width: 100px; width: 120px; max-width: 150px;">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="row card-panel roboto form_content" style="display: none; margin: 1rem 0.75rem 1rem 0.75rem; padding: 5px;">

    </div>