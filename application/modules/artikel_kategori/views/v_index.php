    <ul class="collapsible card-panel roboto main_content" style="margin: 1rem 0.75rem 1rem 0.75rem; padding: 5px;">
        <li>
            <div class="collapsible-header filter-header"><i class="material-icons">search</i>Advance Search</div>
            <div class="collapsible-body row filter-body">
                <form id="formFilter" name="formFilter" method="post">

                    <div class="input-field col s2 s2 l2">
                        <input type="text" class="validate" id="fil_parent" name="fil_parent">
                        <label for="fil_parent">Parent Kategori Text</label>
                    </div>

                    <div class="input-field col s10 m6 l6">
                        <input type="text" class="validate" id="fil_kategori_text" name="fil_kategori_text">
                        <label for="fil_kategori_text">Kategori Text</label>
                    </div>
                    
                   
                    <div class="input-field col s2 m2 l2">
                        <select class="validate" multiple id="fil_is_publish" name="fil_is_publish[]">
                        </select>
                        <label for="fil_is_publish">Is Publish?</label>
                    </div>

                    <div class="input-field col s2 m2 l2">
                        <select class="validate" multiple id="fil_is_trash" name="fil_is_trash[]">
                        </select>
                        <label for="fil_is_trash">Is Trash?</label>
                    </div>
                    <div class="input-field col s12 right-align">
                        <button type="button" id="btn_filter_search" class="btn btn-floating btn-small waves-effect waves-light"><i class="material-icons">search</i>
                        </button>
                    </div>
                </form>
            </div>
        </li>
    </ul>



    <div class="row card-panel roboto main_content" style="min-height:500px;margin: 0rem 0.75rem 1rem 0.75rem; padding: 5px 5px;">
        <div class="col s12 m12 l12" style="box-sizing: border-box;overflow: auto;width: 100%;">
            <table id="mainGrid" class="display cell-border hover compact" style="width:100%">
                <thead class="grey-text text-darken-3 orange lighten-4">
                    <tr class="center-align">
                         <th class="center-align">No</th>
                         <th class="center-align">Parent</th>
                        <th class="center-align">Text</th>
                        <th class="center-align">Alias</th>
                        <th class="center-align">Is Publish?</th>
                        <th class="center-align">Is Trash?</th>
                        <th class="center-align action-col" style="width: 23%;">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="row card-panel roboto form_content" style="display: none; margin: 1rem 0.75rem 1rem 0.75rem; padding: 5px;">

    </div>