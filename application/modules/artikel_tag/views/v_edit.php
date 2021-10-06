<div class="col s12 m12 l12">
    <div class="row">
        <div class="col s12 red lighten-4">
            <h5 class="grey-text text-darken-3"><span style="font-size: 1.4rem;font-weight: 600;" id="title_form"></span></h5>
        </div>
    </div>

    <form class="col s12 form_sub" id="form_submit" name="form_submit" enctype="multipart/form-data">
        <input type="hidden" id="id" name="id" />
        <input type="hidden" id="submit_type" name="submit_type" value="" />
        <div class="row card card-area">
            <div class="input-field col s12 m6 l4">
                <input type="text" id="tag_text" name="tag_text" />
                <label for="tag_text" class="active">Text Tag </label>
            </div>
            <div class="input-field col s12 m6 l8">
                <input type="text" id="tag_alias" name="tag_alias" readonly />
                <label for="tag_alias" class="active">Alias</label>
            </div>
           
            <div class="input-field col s8 m8 l4">
                <select class="validate" id="is_publish" name="is_publish">
                </select>
                <label for="is_publish">Is Publish?</label>
            </div>

            <div class="input-field col s8 m8 l4">
                <select class="validate" id="is_trash" name="is_trash">
                </select>
                <label for="is_trash">Is Trash?</label>
            </div>

            
        </div>

        <div class="row">
            <div class="col s4 m2 left-align">
                <button style="width: 100%;" class="btn grey" type="button" id="btn_back" name="btn_back">Back</button>
            </div>
            <div class="col m8 l8 hide-on-small-and-down">
            </div>
            <div class="col s4 m2 right-align">
                <button style="width: 100%;" class="btn indigo" type="submit" id="btn_submit" name="btn_submit">Submit</button>
            </div>
        </div>
    </form>
</div>