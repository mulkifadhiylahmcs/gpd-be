    <ul class="collapsible card-panel roboto main_content" style="margin: 1rem 0.75rem 1rem 0.75rem; padding: 5px;">
        <li>
            <div class="collapsible-header filter-header"><i class="material-icons">search</i>Advance Search</div>
            <div class="collapsible-body row filter-body">
                <form id="formFilter" name="formFilter" method="post">
                    <div class="input-field col s12 m4 l4">
                        <input type="text" class="validate" id="fil_fullname" name="fil_fullname">
                        <label for="fil_fullname" class="active">Fullname</label>
                    </div>
                    <div class="input-field col s12 m4 l4">
                        <input type="text" class="validate" id="fil_username" name="fil_username">
                        <label for="fil_username" class="active">Username</label>
                    </div>
                    <div class="input-field col s12 m4 l4">
                        <input type="text" class="validate" id="fil_nik" name="fil_nik">
                        <label for="fil_nik" class="active">NIK</label>
                    </div>
                    <div class="input-field col s12 m3 l4">
                        <select class="validate" multiple id="fil_position" name="fil_position[]">
                        </select>
                        <label for="fil_position" class="active">Position</label>
                    </div>
                    <div class="input-field col s12 m3 l2">
                        <select class="validate" multiple id="fil_sex" name="fil_sex[]">
                        </select>
                        <label for="fil_sex" class="active">Sex</label>
                    </div>
                    <div class="input-field col s6 m3 l3">
                        <select class="validate" multiple id="fil_is_trash" name="fil_is_trash[]">
                        </select>
                        <label for="fil_is_trash" class="active">Is Trash?</label>
                    </div>
                    <div class="input-field col s6 m3 l3">
                        <select class="validate" multiple id="fil_is_active" name="fil_is_active[]">
                        </select>
                        <label for="fil_is_active" class="active">Is Active?</label>
                    </div>
                    <div class="input-field col s12">
                        <button style="width: 100%;" type="button" id="btn_filter_search" class="btn waves-effect waves-light">Search
                        </button>
                    </div>
                </form>
            </div>
        </li>
    </ul>

    <div class="row card-panel roboto main_content" style="min-height: 500px; margin: 0rem 0.75rem 1rem 0.75rem; padding: 5px 5px;">
        <div class="col s12 m12 l12" style="box-sizing: border-box;overflow: auto;width: 100%;">
            <table id="mainGrid" class="display cell-border hover compact" style="width:100%">
                <thead class="grey-text text-darken-3 orange lighten-4">
                    <tr class="center-align">
                        <th class="center-align">No</th>
                        <th class="center-align">NIK</th>
                        <th class="center-align">Fullname</th>
                        <th class="center-align">Sex</th>
                        <th class="center-align">Username</th>
                        <th class="center-align">Position</th>
                        <th class="center-align">Email</th>
                        <th class="center-align">Phone</th>
                        <th class="center-align">Is Login?</th>
                        <th class="center-align">Is Trash?</th>
                        <th class="center-align">Is Active?</th>
                        <th class="center-align action-col" style="min-width: 100px; width: 120px; max-width: 150px;">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="row card-panel roboto form_content" style="display: none; margin: 1rem 0.75rem 1rem 0.75rem; padding: 5px;">

    </div>