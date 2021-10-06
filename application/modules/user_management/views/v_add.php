<div class="col s12 m12 l12">
    <div class="row" style="padding: 0 0.75rem;">
        <div class="col s12 red lighten-4" style="padding: 0 0.75rem;">
            <h5 class="grey-text text-darken-3"><span style="font-size: 1.4rem;font-weight: 600;" id="title_form"></span></h5>
        </div>
    </div>

    <form class="col s12 form_sub" id="form_submit" name="form_submit" enctype="multipart/form-data">
        <input type="hidden" id="submit_type" name="submit_type" value="" />
        <div class="row card card-area">
            <div class="input-field col s12 m6 l3">
                <input onkeypress="return keypress_Numeric(event)" type="text" id="nik" name="nik" />
                <label for="nik" class="active">NIK</label>
            </div>
            <div class="input-field col s12 m6 l5">
                <input type="text" id="first_name" name="first_name" />
                <label for="first_name" class="active">First Name</label>
            </div>
            <div class="input-field col s12 m6 l4">
                <input type="text" id="last_name" name="last_name" />
                <label for="last_name" class="active">Last Name</label>
            </div>
            <div class="input-field col s12 m6 l3">
                <input type="text" id="short_name" name="short_name" />
                <label for="short_name" class="active">Short Name</label>
            </div>
            <div class="input-field col s12 m6 l4">
                <input type="text" id="username" name="username" />
                <label for="username" class="active">Username</label>
            </div>
            <div class="input-field col s12 m6 l5">
                <input type="email" id="email" name="email" />
                <label for="email" class="active">Email</label>
            </div>
            <div class="input-field col s12 m6 l4">
                <select class="validate" id="id_position" name="id_position">
                </select>
                <label for="id_position" class="active">Position</label>
            </div>
            <div class="input-field col s12 m6 l4">
                <select class="validate" id="id_parent" name="id_parent">
                </select>
                <label for="id_parent" class="active">Supervisi By</label>
            </div>
            <div class="input-field col s12 m6 l4">
                <select class="validate" id="id_role" name="id_role">
                </select>
                <label for="id_role" class="active">Role</label>
            </div>
            <div class="input-field col s12 m6 l6">
                <input type="text" id="department" name="department" readonly/>
                <label for="department" class="active">Department</label>
            </div>
            <div class="input-field col s12 m6 l6">
                <input type="text" id="division" name="division" readonly/>
                <label for="division" class="active">Division</label>
            </div>
            <div class="input-field col s12">
                <textarea id="address" name="address" class="materialize-textarea"></textarea>
                <label for="address" class="active">Address</label>
            </div>
            <div class="input-field col s12 m6 l6">
                <select class="validate" id="id_province" name="id_province">
                </select>
                <label for="id_province" class="active">Provinsi</label>
            </div>
            <div class="input-field col s12 m6 l6">
                <select class="validate" id="id_city" name="id_city">
                </select>
                <label for="id_city" class="active">Kota/Kabupaten</label>
            </div>
            <div class="input-field col s12 m6 l4">
                <select class="validate" id="id_district" name="id_district">
                </select>
                <label for="id_district" class="active">Kecamatan</label>
            </div>
            <div class="input-field col s12 m6 l4">
                <select class="validate" id="id_subdistrict" name="id_subdistrict">
                </select>
                <label for="id_subdistrict" class="active">Kelurahan</label>
            </div>
            <div class="input-field col s12 m6 l4">
                <select class="validate" id="id_postalcode" name="id_postalcode">
                </select>
                <label for="id_postalcode" class="active">Kode Pos</label>
            </div>
            <div class="input-field col s12 m6 l6">
                <input onkeypress="return keypress_Numeric(event)" type="text" id="phone" name="phone" />
                <label for="phone" class="active">Phone/Mobile</label>
            </div>
            <div class="input-field col s12 m6 l6">
                <select class="validate" id="sex" name="sex">
                </select>
                <label for="sex" class="active">Sex</label>
            </div>
            <div class="input-field col s12 m6 l6">
                <input type="text" id="birth_place" name="birth_place" />
                <label for="birth_place" class="active">Birth Place</label>
            </div>
            <div class="input-field col s12 m6 l6">
                <input type="text" id="birth_date" name="birth_date" />
                <label for="birth_date" class="active">Birth Date</label>
            </div>
        </div>

        <div class="row card card-area" id="div_detail_akses_role">

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