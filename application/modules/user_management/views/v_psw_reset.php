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
            <div class="input-field col s12 m3 l3">
                <input type="text" id="nik" name="nik" readonly />
                <label for="nik" class="active">NIK</label>
            </div>
            <div class="input-field col s12 m5 l5">
                <input type="text" id="first_name" name="first_name" readonly />
                <label for="first_name" class="active">First Name</label>
            </div>
            <div class="input-field col s12 m4 l4">
                <input type="text" id="last_name" name="last_name" readonly />
                <label for="last_name" class="active">Last Name</label>
            </div>
            <div class="input-field col s12 m3 l3">
                <input type="text" id="short_name" name="short_name" readonly />
                <label for="short_name" class="active">Short Name</label>
            </div>
            <div class="input-field col s12 m4 l4">
                <input type="text" id="username" name="username" readonly />
                <label for="username" class="active">Username</label>
            </div>
            <div class="input-field col s12 m5 l5">
                <input type="email" id="email" name="email" readonly />
                <label for="email" class="active">Email</label>
            </div>
            <div class="input-field col s12 m4 l4">
                <input type="text" id="id_position" name="id_position" readonly />
                <label for="id_position" class="active">Position</label>
            </div>
            <div class="input-field col s12 m4 l4">
                <input type="text" id="id_parent" name="id_parent" readonly />
                <label for="id_parent" class="active">Supervisi By</label>
            </div>
            <div class="input-field col s12 m4 l4">
                <input type="text" id="id_role" name="id_role" readonly />
                <label for="id_role" class="active">Role</label>
            </div>
            <div class="input-field col s12 m6 l6">
                <input type="text" id="department" name="department" readonly />
                <label for="department" class="active">Department</label>
            </div>
            <div class="input-field col s12 m6 l6">
                <input type="text" id="division" name="division" readonly />
                <label for="division" class="active">Division</label>
            </div>
            <div class="input-field col s12 m12 l6">
                <input type="password" id="password" name="password" />
                <label for="password" class="active">New Password</label>
            </div>
            <div class="input-field col s12 m12 l6">
                <input type="password" id="password2" name="password2" />
                <label for="password2" class="active">Conf. New Password</label>
            </div>
        </div>

        <div class="row">
            <div class="col s4 m2 left-align">
                <button style="width: 100%;" class="btn grey" type="button" id="btn_back" name="btn_back">Back</button>
            </div>
            <div class="col m8 l8 hide-on-small-and-down">
            </div>
            <div class="col s4 m2 right-align">
                <button style="width: 100%;" class="btn indigo" type="submit" id="btn_submit_ResetPsw" name="btn_submit_ResetPsw">Submit</button>
            </div>
        </div>
    </form>
</div>