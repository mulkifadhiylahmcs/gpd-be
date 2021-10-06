<div class="col s12 m12 l12">
    <div class="row" style="padding: 0 0.75rem;">
        <div class="col s12 red lighten-4" style="padding: 0 0.75rem;">
            <h5 class="grey-text text-darken-3"><span style="font-size: 1.4rem;font-weight: 600;" id="title_form"></span></h5>
        </div>
    </div>

    <form class="col s12 form_sub" id="form_submit" name="form_submit" enctype="multipart/form-data">
        <input type="hidden" id="id" name="id" />
        <input type="hidden" id="submit_type" name="submit_type" value="" />
        <div class="row card card-area">
            <div class="input-field col s12 m6 l4">
                <input type="text" id="code" name="code" />
                <label for="code" class="active">Code</label>
            </div>
            <div class="input-field col s12 m6 l8">
                <input type="text" id="name" name="name" />
                <label for="name" class="active">Name</label>
            </div>
            <div class="input-field col s12">
                <textarea id="description" name="description" class="materialize-textarea"></textarea>
                <label for="description" class="active">Description</label>
            </div>
            <div class="input-field col s12 m6 l6">
                <select class="validate" id="is_active" name="is_active">
                </select>
                <label for="is_active" class="active">Is Active?</label>
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