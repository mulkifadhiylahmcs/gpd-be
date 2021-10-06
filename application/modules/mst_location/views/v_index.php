    <ul class="collapsible card-panel roboto main_content" style="margin: 1rem 0.75rem 1rem 0.75rem; padding: 5px;">
        <li>
            <div class="collapsible-header filter-header"><i class="material-icons">search</i>Advance Search</div>
            <div class="collapsible-body row filter-body">
                <form id="formFilter" name="formFilter" method="post">
                    <div class="input-field col s4 m2 l2">
                        <input type="text" class="validate" id="fil_province" name="fil_province">
                        <label for="fil_province" class="active">Provinsi</label>
                    </div>
                    <div class="input-field col s8 m4 l2">
                        <input type="text" class="validate" id="fil_city" name="fil_city">
                        <label for="fil_city" class="active">Kota/Kabupaten</label>
                    </div>
                    <div class="input-field col s8 m4 l3">
                        <input type="text" class="validate" id="fil_district" name="fil_district">
                        <label for="fil_district" class="active">Kecamatan</label>
                    </div>
                    <div class="input-field col s4 m2 l3">
                        <input type="text" class="validate" id="fil_subdistrict" name="fil_subdistrict">
                        <label for="fil_subdistrict" class="active">Kelurahan</label>
                    </div>
                    <div class="input-field col s4 m2 l2">
                        <input type="text" class="validate" id="fil_postalcode" name="fil_postalcode">
                        <label for="fil_postalcode" class="active">Kode Pos</label>
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
                        <th class="center-align">Provinsi</th>
                        <th class="center-align">Kota/Kabupaten</th>
                        <th class="center-align">Kecamatan</th>
                        <th class="center-align">Kelurahan</th>
                        <th class="center-align">Kode Pos</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
