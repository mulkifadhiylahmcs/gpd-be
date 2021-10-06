<script type="text/javascript">
    var form;
    var def_mainGrid_dt_obj = {
        fixedHeader: {
            header: true,
            headerOffset: $('#navbar_fixed').height()
        },
        pageLength: 20,
        searching: true,
        responsive: {
            details: {
                type: 'column',
                target: 'tr'
            }
        },
        columnDefs: [{
                responsivePriority: 1,
                targets: [1, -1, -2, -3, -4]
            },
            {
                responsivePriority: 2,
                targets: [2, 3]
            },
            {
                targets: 'action-col',
                orderable: false,
                searchable: false,
                className: "center-align",
            },
            {
                className: "center-align",
                targets: [0, -1]
            }
        ]
    };



    function get_main_table() {
        //datatables
        if ($.fn.DataTable.isDataTable('#mainGrid')) {
            $('#mainGrid').DataTable().destroy();
        }

        $('#mainGrid').DataTable(
            $.extend(
                def_mainGrid_dt_obj, {
                    "processing": true,
                    "serverSide": true,
                    "pageLength": 20,
                    "pagingType": "full_numbers",
                    "order": [],
                    "ajax": {
                        "url": "<?php echo base_url('user_management/get_main_table'); ?>",
                        "type": "POST",
                        "data": function(data) {
                            data.fil_fullname = $('#fil_fullname').val();
                            data.fil_username = $('#fil_username').val();
                            data.fil_nik = $('#fil_nik').val();
                            data.fil_position = $('#fil_position').val();
                            data.fil_sex = $('#fil_sex').val();
                            data.fil_is_trash = $('#fil_is_trash').val();
                            data.fil_is_active = $('#fil_is_active').val();
                        }
                    },
                    dom: 'Bftip',
                    buttons: [{
                        text: '<i class="material-icons">add</i>',
                        attr: {
                            'data-mode': "form",
                            'id': 'btn_add',
                            'class': 'btn cyan darken-2',
                            'style': 'margin-top:15px;margin-bottom: 15px;'
                        }
                    }, ],
                }
            )
        ).on('draw', function() {
            set_akses_clientside();
        });
    }

    function getForm(mode = 'add', id = null) {
        var param = {
            'mode': mode,
            'id': id
        };

        $.ajax({
            type: "POST",
            url: "<?php echo base_url('user_management/getForm'); ?>",
            data: param,
            cache: false,
            success: function(data) {
                var obj = $.parseJSON(data);
                if (parseInt(obj['res']) === 1) {
                    $('.form_content').empty().html(obj['content']).fadeIn();
                    $('.main_content').fadeOut();
                    $('#submit_type').val(mode);


                    if (mode == 'add') {
                        $('#title_form').html('Add New User');
                        getDataSelect_parent_user($('#id_parent'));
                        getDataSelect_role($('#id_role'));
                        getDataSelect_position($('#id_position'));
                        getDataSelect_sex($('#sex'));
                        getDataSelect_province($('#id_province'));
                        resetSelect($('#id_city'), "Choose \"Province\" First");
                        resetSelect($('#id_district'), "Choose \"City\" First");
                        resetSelect($('#id_subdistrict'), "Choose \"District\" First");
                        resetSelect($('#id_postalcode'), "Choose \"Subdistrict\" First");
                        $('#birth_date').datepicker(
                            $.extend(
                                def_date_obj, {
                                    yearRange: 100,
                                    maxDate: new Date(YeardateNow(), MonthdateNow() - 1, DaydateNow())
                                }
                            )
                        );
                    } else {
                        if (mode == 'edit') {
                            $('#title_form').html('Edit User');
                            getDataEdit(id, mode);
                        } else {
                            if (mode == 'view') {
                                $('#title_form').html('Detail User');
                                getDataView(id, mode);
                            } else {
                                $('#title_form').html('Reset Password User');
                                getDataResetPsw(id, mode);
                            }
                        }
                    }
                } else {
                    if (parseInt(obj['res']) === 99) {
                        $.alert({
                            title: 'FAILED!',
                            content: obj['message'],
                            type: 'red',
                            draggable: false,
                        });
                    }
                }
            }
        });
    }

    function getDataView(id, mode) {
        var param = {
            'id': id
        };

        $.ajax({
            type: "POST",
            url: "<?php echo base_url('user_management/getDataView'); ?>",
            data: param,
            cache: false,
            success: function(data) {
                var obj = $.parseJSON(data);

                if (parseInt(obj['res']) === 1) {

                    $('#id').val(obj['data']['user'].id);
                    $('#nik').val(obj['data']['user'].nik);
                    $('#first_name').val(obj['data']['user'].first_name);
                    $('#last_name').val(obj['data']['user'].last_name);
                    $('#short_name').val(obj['data']['user'].short_name);
                    $('#username').val(obj['data']['user'].username);
                    $('#email').val(obj['data']['user'].email);
                    $('#id_position').val(obj['data']['user'].position);
                    $('#id_parent').val(obj['data']['user'].parent);
                    $('#id_role').val(obj['data']['user'].role);
                    $('#department').val(obj['data']['user'].department);
                    $('#division').val(obj['data']['user'].division);
                    $('#address').val(obj['data']['user'].address);
                    M.textareaAutoResize($('#address'));
                    $('#id_province').val(obj['data']['user'].province);
                    $('#id_city').val(obj['data']['user'].city);
                    $('#id_district').val(obj['data']['user'].district);
                    $('#id_subdistrict').val(obj['data']['user'].subdistrict);
                    $('#id_postalcode').val(obj['data']['user'].postalcode);
                    $('#phone').val(obj['data']['user'].phone);
                    $('#sex').val(obj['data']['user'].sex);
                    $('#birth_place').val(obj['data']['user'].birth_place);
                    $('#birth_date').val(obj['data']['user'].birth_date);
                    $('#is_active').val(obj['data']['user'].is_active);
                    $('#is_trash').val(obj['data']['user'].is_trash);
                    $('#is_login').val(obj['data']['user'].is_login);
                    $('#http_cookie').val(obj['data']['user'].http_cookie);
                    $('#last_login_ip').val(obj['data']['user'].last_login_ip);

                    getData_user_akses(id, mode);
                } else {
                    if (parseInt(obj['res']) === 99) {
                        $.alert({
                            title: 'FAILED!',
                            content: obj['message'],
                            type: 'red',
                            draggable: false,
                        });
                    }
                }
            }
        });
    }

    function getDataEdit(id, mode) {
        var param = {
            'id': id
        };

        $.ajax({
            type: "POST",
            url: "<?php echo base_url('user_management/getDataEdit'); ?>",
            data: param,
            cache: false,
            success: function(data) {
                var obj = $.parseJSON(data);

                if (parseInt(obj['res']) === 1) {
                    $('#id').val(obj['data']['user'].id);
                    $('#nik').val(obj['data']['user'].nik);
                    $('#first_name').val(obj['data']['user'].first_name);
                    $('#last_name').val(obj['data']['user'].last_name);
                    $('#short_name').val(obj['data']['user'].short_name);
                    $('#username').val(obj['data']['user'].username);
                    $('#email').val(obj['data']['user'].email);
                    $('#address').val(obj['data']['user'].address);
                    M.textareaAutoResize($('#address'));
                    $('#phone').val(obj['data']['user'].phone);
                    $('#birth_place').val(obj['data']['user'].birth_place);
                    $('#birth_date').val(obj['data']['user'].birth_date);
                    $('#is_login').val(obj['data']['user'].is_login);
                    $('#http_cookie').val(obj['data']['user'].http_cookie);
                    $('#last_login_ip').val(obj['data']['user'].last_login_ip);

                    getDataSelect_position($('#id_position'), obj['data']['user'].id_position);
                    getDataSelect_parent_user($('#id_parent'), obj['data']['user'].id_parent);
                    getDataSelect_role($('#id_role'), obj['data']['user'].id_role);
                    getDataSelect_province($('#id_province'), obj['data']['user'].id_province);
                    getDataSelect_city($('#id_city'), obj['data']['user'].id_city, obj['data']['user'].id_province);
                    getDataSelect_district($('#id_district'), obj['data']['user'].id_district, obj['data']['user'].id_city);
                    getDataSelect_subdistrict($('#id_subdistrict'), obj['data']['user'].id_subdistrict, obj['data']['user'].id_district);
                    getDataSelect_postalcode($('#id_postalcode'), obj['data']['user'].id_postalcode, obj['data']['user'].id_subdistrict);
                    getDataSelect_sex($('#sex'), obj['data']['user'].sex);
                    getDataSelect_is_active($('#is_active'), obj['data']['user'].is_active);
                    getDataSelect_is_trash($('#is_trash'), obj['data']['user'].is_trash);

                    getData_user_akses(id, mode);
                } else {
                    if (parseInt(obj['res']) === 99) {
                        $.alert({
                            title: 'FAILED!',
                            content: obj['message'],
                            type: 'red',
                            draggable: false,
                        });
                    }
                }
            }
        });
    }

    function getDataResetPsw(id, mode) {
        var param = {
            'id': id
        };

        $.ajax({
            type: "POST",
            url: "<?php echo base_url('user_management/getDataResetPsw'); ?>",
            data: param,
            cache: false,
            success: function(data) {
                var obj = $.parseJSON(data);

                if (parseInt(obj['res']) === 1) {
                    $('#id').val(obj['data']['user'].id);
                    $('#nik').val(obj['data']['user'].nik);
                    $('#first_name').val(obj['data']['user'].first_name);
                    $('#last_name').val(obj['data']['user'].last_name);
                    $('#short_name').val(obj['data']['user'].short_name);
                    $('#username').val(obj['data']['user'].username);
                    $('#email').val(obj['data']['user'].email);
                    $('#id_position').val(obj['data']['user'].position);
                    $('#id_parent').val(obj['data']['user'].parent);
                    $('#id_role').val(obj['data']['user'].role);
                    $('#department').val(obj['data']['user'].department);
                    $('#division').val(obj['data']['user'].division);
                } else {
                    if (parseInt(obj['res']) === 99) {
                        $.alert({
                            title: 'FAILED!',
                            content: obj['message'],
                            type: 'red',
                            draggable: false,
                        });
                    }
                }
            }
        });
    }

    function getData_user_akses(id, mode) {
        var param = {
            'id': id
        };
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('user_management/getData_user_akses'); ?>",
            data: param,
            cache: false,
            success: function(data) {
                var obj = $.parseJSON(data);

                if (parseInt(obj['res']) === 1) {
                    $('#div_detail_akses_role').empty();

                    var str = '';
                    str += '<div class="col s12 blue-grey lighten-4 margin-bottom-10" style="padding-bottom: 5px;">';
                    str += '<h6 class="grey-text text-darken-3"><span style="font-size: 1rem;font-weight: 600;">Setup Akses Module</span></h6>';
                    str += '</div>';


                    $.each(obj['data']['def_akses_header'], function(i, val) {
                        var icon_h = '';
                        var iconColor_h = '';
                        var color_h = '';


                        if (mode != 'view') {
                            if (mode == 'add') {
                                color_h = 'red lighten-4';
                            } else {
                                if (mode == 'edit') {
                                    if (val.is_active == 1) {
                                        color_h = 'green lighten-4';
                                    } else {
                                        color_h = 'red lighten-4';
                                    }
                                }
                            }
                        } else {
                            if (val.is_active == 1) {
                                icon_h = 'check_circle';
                                color_h = 'green lighten-4';
                                iconColor_h = 'green-text';
                            } else {
                                icon_h = 'remove_circle';
                                color_h = 'red lighten-4';
                                iconColor_h = 'red-text';
                            }
                        }


                        str += '<div class="col s12 m6">';
                        str += '<div class="row col s12 no-padding-left-right">';
                        str += '<ul class="collection with-header z-depth-2">';
                        str += '<li class="collection-header s12 ' + color_h + '">';

                        if (mode != 'view') {
                            if (mode == 'add') {
                                str += '<div class="switch">';
                                str += '<label class="grey-text text-darken-3">';
                                str += '<i class="material-icons red-text">check_circle</i>';
                                str += '<input id="cb_akses_h-' + val.id + '" name="cb_akses_h-' + val.id + '" data-id="' + val.id + '" type="checkbox">';
                                str += '<span class="lever" style="bottom: 10px; margin: 0 5px;"></span>';
                                str += '<i class="material-icons green-text">remove_circle</i>';
                                str += '<strong class="right" style="margin-top: 5px;"> ' + val.name + '</strong>';
                                str += '</label>';
                                str += '</div>';
                            } else {
                                if (mode == 'edit') {
                                    var is_checked = '';
                                    if (val.is_active == 1) {
                                        is_checked = 'checked';
                                    }

                                    str += '<div class="switch">';
                                    str += '<label class="grey-text text-darken-3">';
                                    str += '<i class="material-icons red-text">check_circle</i>';
                                    str += '<input id="cb_akses_h-' + val.id + '" name="cb_akses_h-' + val.id + '" data-id="' + val.id + '" type="checkbox" ' + is_checked + '>';
                                    str += '<span class="lever" style="bottom: 10px; margin: 0 5px;"></span>';
                                    str += '<i class="material-icons green-text">remove_circle</i>';
                                    str += '<strong class="right" style="margin-top: 5px;"> ' + val.name + '</strong>';
                                    str += '</label>';
                                    str += '</div>';
                                }
                            }
                        } else {
                            str += '<h6 class="grey-text text-darken-3 no-margin" style="font-size: 0.9rem;font-weight: 600;">';
                            str += '<i class="material-icons ' + iconColor_h + '">' + icon_h + '</i> <span class="right" style="margin-top: 7px;">' + val.name + '</span>';
                            str += '</h6>';
                        }


                        $.each(obj['data']['def_akses_detail'], function(i_d, val_d) {
                            if (val_d.id_header == val.id) {
                                var icon_d = '';
                                var iconColor_d = '';
                                if (val_d.is_active == 1) {
                                    icon_d = 'check_circle';
                                    iconColor_d = 'green-text';
                                } else {
                                    icon_d = 'remove_circle';
                                    iconColor_d = 'red-text';
                                }

                                if (mode != 'view') {
                                    if (mode == 'add') {
                                        str += '<li class="collection-item no-padding" title="' + val_d.description + '">';
                                        str += '<div class="switch" style="margin-left: 7px;margin-top: 3px;">';
                                        str += '<label class="grey-text text-darken-3">';
                                        str += '<i class="material-icons red-text">check_circle</i>';
                                        str += '<input id="cb_akses_d-' + val.id + '-' + val_d.id + '" name="cb_akses_d-' + val.id + '-' + val_d.id + '" data-id_h="' + val.id + '" data-id_d="' + val_d.id + '" type="checkbox">';
                                        str += '<span class="lever" style="bottom: 10px; margin: 0 5px;"></span>';
                                        str += '<i class="material-icons green-text">remove_circle</i>';
                                        str += '<span style="bottom: 7px;margin-left: 8px;display: inline-block;position: relative;"> ' + val_d.name + '</span>';
                                        str += '</label>';
                                        str += '</div>';
                                        str += '</li>';
                                    } else {
                                        if (mode == 'edit') {
                                            var is_checked = '';
                                            if (val.is_active == 1) {
                                                is_checked = 'checked';
                                            }

                                            str += '<li class="collection-item no-padding" title="' + val_d.description + '">';
                                            str += '<div class="switch" style="margin-left: 7px;margin-top: 3px;">';
                                            str += '<label class="grey-text text-darken-3">';
                                            str += '<i class="material-icons red-text">check_circle</i>';
                                            str += '<input id="cb_akses_d-' + val.id + '-' + val_d.id + '" name="cb_akses_d-' + val.id + '-' + val_d.id + '" data-id_h="' + val.id + '" data-id_d="' + val_d.id + '" type="checkbox" ' + is_checked + '>';
                                            str += '<span class="lever" style="bottom: 10px; margin: 0 5px;"></span>';
                                            str += '<i class="material-icons green-text">remove_circle</i>';
                                            str += '<span style="bottom: 7px;margin-left: 8px;display: inline-block;position: relative;"> ' + val_d.name + '</span>';
                                            str += '</label>';
                                            str += '</div>';
                                            str += '</li>';
                                        }
                                    }
                                } else {
                                    str += '<li class="collection-item" title="' + val_d.description + '">' + val_d.name + '<span class="right"><i class="material-icons ' + iconColor_d + '">' + icon_d + '</i></span></li>';
                                }
                            }
                        });
                        str += '</ul>';
                        str += '</div>';
                        str += '</div>';

                        if (parseInt(i) > 0) {
                            if ((parseInt(i) + 1) % 2 == 0) {
                                str += '<div class="row hide-on-small-only"></div>';
                            }
                        }
                    });

                    $('#div_detail_akses_role').html(str);
                } else {
                    if (parseInt(obj['res']) === 99) {
                        $.alert({
                            title: 'FAILED!',
                            content: obj['message'],
                            type: 'red',
                            draggable: false,
                        });
                    }
                }
            }
        });
    }

    function getData_role_akses(id, mode) {
        var param = {
            'id': id
        };
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('user_management/getData_role_akses'); ?>",
            data: param,
            cache: false,
            success: function(data) {
                var obj = $.parseJSON(data);

                if (parseInt(obj['res']) === 1) {
                    $('#div_detail_akses_role').empty();

                    var str = '';
                    str += '<div class="col s12 blue-grey lighten-4 margin-bottom-10" style="padding-bottom: 5px;">';
                    str += '<h6 class="grey-text text-darken-3"><span style="font-size: 1rem;font-weight: 600;">Setup Akses Module</span></h6>';
                    str += '</div>';


                    $.each(obj['data']['def_akses_header'], function(i, val) {
                        var icon_h = '';
                        var iconColor_h = '';
                        var color_h = '';


                        if (mode != 'view') {
                            if (mode == 'add') {
                                if (val.is_active == 1) {
                                    color_h = 'green lighten-4';
                                } else {
                                    color_h = 'red lighten-4';
                                }
                            } else {
                                if (mode == 'edit') {
                                    if (val.is_active == 1) {
                                        color_h = 'green lighten-4';
                                    } else {
                                        color_h = 'red lighten-4';
                                    }
                                }
                            }
                        } else {
                            if (val.is_active == 1) {
                                icon_h = 'check_circle';
                                color_h = 'green lighten-4';
                                iconColor_h = 'green-text';
                            } else {
                                icon_h = 'remove_circle';
                                color_h = 'red lighten-4';
                                iconColor_h = 'red-text';
                            }
                        }


                        str += '<div class="col s12 m6">';
                        str += '<div class="row col s12 no-padding-left-right">';
                        str += '<ul class="collection with-header z-depth-2">';
                        str += '<li class="collection-header s12 ' + color_h + '">';

                        if (mode != 'view') {
                            if (mode == 'add') {
                                var is_checked = '';
                                if (val.is_active == 1) {
                                    is_checked = 'checked';
                                }

                                str += '<div class="switch">';
                                str += '<label class="grey-text text-darken-3">';
                                str += '<i class="material-icons red-text">check_circle</i>';
                                str += '<input id="cb_akses_h-' + val.id + '" name="cb_akses_h-' + val.id + '" data-id="' + val.id + '" type="checkbox" ' + is_checked + '>';
                                str += '<span class="lever" style="bottom: 10px; margin: 0 5px;"></span>';
                                str += '<i class="material-icons green-text">remove_circle</i>';
                                str += '<strong class="right" style="margin-top: 5px;"> ' + val.name + '</strong>';
                                str += '</label>';
                                str += '</div>';
                            } else {
                                if (mode == 'edit') {
                                    var is_checked = '';
                                    if (val.is_active == 1) {
                                        is_checked = 'checked';
                                    }

                                    str += '<div class="switch">';
                                    str += '<label class="grey-text text-darken-3">';
                                    str += '<i class="material-icons red-text">check_circle</i>';
                                    str += '<input id="cb_akses_h-' + val.id + '" name="cb_akses_h-' + val.id + '" data-id="' + val.id + '" type="checkbox" ' + is_checked + '>';
                                    str += '<span class="lever" style="bottom: 10px; margin: 0 5px;"></span>';
                                    str += '<i class="material-icons green-text">remove_circle</i>';
                                    str += '<strong class="right" style="margin-top: 5px;"> ' + val.name + '</strong>';
                                    str += '</label>';
                                    str += '</div>';
                                }
                            }
                        } else {
                            str += '<h6 class="grey-text text-darken-3 no-margin" style="font-size: 0.9rem;font-weight: 600;">';
                            str += '<i class="material-icons ' + iconColor_h + '">' + icon_h + '</i> <span class="right" style="margin-top: 7px;">' + val.name + '</span>';
                            str += '</h6>';
                        }


                        $.each(obj['data']['def_akses_detail'], function(i_d, val_d) {
                            if (val_d.id_header == val.id) {
                                var icon_d = '';
                                var iconColor_d = '';
                                if (val_d.is_active == 1) {
                                    icon_d = 'check_circle';
                                    iconColor_d = 'green-text';
                                } else {
                                    icon_d = 'remove_circle';
                                    iconColor_d = 'red-text';
                                }

                                if (mode != 'view') {
                                    if (mode == 'add') {
                                        var is_checked = '';
                                        if (val.is_active == 1) {
                                            is_checked = 'checked';
                                        }

                                        str += '<li class="collection-item no-padding" title="' + val_d.description + '">';
                                        str += '<div class="switch" style="margin-left: 7px;margin-top: 3px;">';
                                        str += '<label class="grey-text text-darken-3">';
                                        str += '<i class="material-icons red-text">check_circle</i>';
                                        str += '<input id="cb_akses_d-' + val.id + '-' + val_d.id + '" name="cb_akses_d-' + val.id + '-' + val_d.id + '" data-id_h="' + val.id + '" data-id_d="' + val_d.id + '" type="checkbox" ' + is_checked + '>';
                                        str += '<span class="lever" style="bottom: 10px; margin: 0 5px;"></span>';
                                        str += '<i class="material-icons green-text">remove_circle</i>';
                                        str += '<span style="bottom: 7px;margin-left: 8px;display: inline-block;position: relative;"> ' + val_d.name + '</span>';
                                        str += '</label>';
                                        str += '</div>';
                                        str += '</li>';
                                    } else {
                                        if (mode == 'edit') {
                                            var is_checked = '';
                                            if (val.is_active == 1) {
                                                is_checked = 'checked';
                                            }

                                            str += '<li class="collection-item no-padding" title="' + val_d.description + '">';
                                            str += '<div class="switch" style="margin-left: 7px;margin-top: 3px;">';
                                            str += '<label class="grey-text text-darken-3">';
                                            str += '<i class="material-icons red-text">check_circle</i>';
                                            str += '<input id="cb_akses_d-' + val.id + '-' + val_d.id + '" name="cb_akses_d-' + val.id + '-' + val_d.id + '" data-id_h="' + val.id + '" data-id_d="' + val_d.id + '" type="checkbox" ' + is_checked + '>';
                                            str += '<span class="lever" style="bottom: 10px; margin: 0 5px;"></span>';
                                            str += '<i class="material-icons green-text">remove_circle</i>';
                                            str += '<span style="bottom: 7px;margin-left: 8px;display: inline-block;position: relative;"> ' + val_d.name + '</span>';
                                            str += '</label>';
                                            str += '</div>';
                                            str += '</li>';
                                        }
                                    }
                                } else {
                                    str += '<li class="collection-item" title="' + val_d.description + '">' + val_d.name + '<span class="right"><i class="material-icons ' + iconColor_d + '">' + icon_d + '</i></span></li>';
                                }
                            }
                        });
                        str += '</ul>';
                        str += '</div>';
                        str += '</div>';

                        if (parseInt(i) > 0) {
                            if ((parseInt(i) + 1) % 2 == 0) {
                                str += '<div class="row hide-on-small-only"></div>';
                            }
                        }
                    });

                    $('#div_detail_akses_role').html(str);
                } else {
                    if (parseInt(obj['res']) === 99) {
                        $.alert({
                            title: 'FAILED!',
                            content: obj['message'],
                            type: 'red',
                            draggable: false,
                        });
                    }
                }
            }
        });
    }

    function getDataSelect_parent_user(elem, id = null) {
        var param = null;
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('user_management/getDataSelect_parent_user'); ?>",
            data: param,
            dataType: "json",
            cache: false,
            success: function(data) {
                var obj = data;
                if (parseInt(obj['res']) === 1) {
                    var str = '';
                    if (id != null) {
                        str += '<option value="" disabled>Choose your option</option>';
                    } else {
                        str += '<option value="" disabled selected>Choose your option</option>';
                    }

                    if (id == '0') {
                        str += '<option value="0" selected>UNSET</option>';
                    } else {
                        str += '<option value="0">UNSET</option>';
                    }

                    $.each(obj['data'], function(i, val) {
                        if (id != null) {
                            if (id.includes(val.id)) {
                                str += '<option selected value="' + val.id + '">' + val.first_name + ' ' + val.last_name + '-' + val.nik + '</option>';
                            } else {
                                str += '<option value="' + val.id + '">' + val.first_name + ' ' + val.last_name + '-' + val.nik + '</option>';
                            }
                        } else {
                            str += '<option value="' + val.id + '">' + val.first_name + ' ' + val.last_name + '-' + val.nik + '</option>';
                        }
                    });

                    elem.html(str).select2();
                } else {
                    if (parseInt(obj['res']) === 99) {
                        $.alert({
                            title: 'FAILED!',
                            content: obj['message'],
                            type: 'red',
                            draggable: false,
                        });
                    }
                }
            }
        });
    }

    function getDataSelect_position(elem, id = null) {
        var param = null;
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('user_management/getDataSelect_position'); ?>",
            data: param,
            dataType: "json",
            cache: false,
            success: function(data) {
                var obj = data;
                if (parseInt(obj['res']) === 1) {
                    var str = '';
                    if (id != null) {
                        str += '<option value="" disabled>Choose your option</option>';
                    } else {
                        str += '<option value="" disabled selected>Choose your option</option>';
                    }

                    $.each(obj['data'], function(i, val) {
                        if (id != null) {
                            if (id.includes(val.id)) {
                                str += '<option data-department="' + val.department + '" data-division="' + val.division + '" selected value="' + val.id + '">' + val.code + ' - ' + val.name + '</option>';
                                $("#department").val(val.department);
                                $("#division").val(val.division);
                            } else {
                                str += '<option data-department="' + val.department + '" data-division="' + val.division + '" value="' + val.id + '">' + val.code + ' - ' + val.name + '</option>';
                            }
                        } else {
                            str += '<option data-department="' + val.department + '" data-division="' + val.division + '" value="' + val.id + '">' + val.code + ' - ' + val.name + '</option>';
                        }
                    });

                    elem.html(str).select2();
                } else {
                    if (parseInt(obj['res']) === 99) {
                        $.alert({
                            title: 'FAILED!',
                            content: obj['message'],
                            type: 'red',
                            draggable: false,
                        });
                    }
                }
            }
        });
    }

    function getDataSelect_role(elem, id = null) {
        var param = null;
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('user_management/getDataSelect_role'); ?>",
            data: param,
            dataType: "json",
            cache: false,
            success: function(data) {
                var obj = data;
                if (parseInt(obj['res']) === 1) {
                    var str = '';
                    if (id != null) {
                        str += '<option value="" disabled>Choose your option</option>';
                    } else {
                        str += '<option value="" disabled selected>Choose your option</option>';
                    }

                    $.each(obj['data'], function(i, val) {
                        if (id != null) {
                            if (id.includes(val.id)) {
                                str += '<option selected value="' + val.id + '">' + val.name + '</option>';
                            } else {
                                str += '<option value="' + val.id + '">' + val.name + '</option>';
                            }
                        } else {
                            str += '<option value="' + val.id + '">' + val.name + '</option>';
                        }
                    });

                    elem.html(str).select2();
                } else {
                    if (parseInt(obj['res']) === 99) {
                        $.alert({
                            title: 'FAILED!',
                            content: obj['message'],
                            type: 'red',
                            draggable: false,
                        });
                    }
                }
            }
        });
    }

    function getDataSelect_province(elem, id = null) {
        var param = null;
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('user_management/getDataSelect_province'); ?>",
            data: param,
            dataType: "json",
            cache: false,
            success: function(data) {
                var obj = data;
                if (parseInt(obj['res']) === 1) {
                    var str = '';
                    if (id != null) {
                        str += '<option value="" disabled>Choose your option</option>';
                    } else {
                        str += '<option value="" disabled selected>Choose your option</option>';
                    }

                    $.each(obj['data'], function(i, val) {
                        if (id != null) {
                            if (id.includes(val.prov_id)) {
                                str += '<option selected value="' + val.prov_id + '">' + val.prov_name + '</option>';
                            } else {
                                str += '<option value="' + val.prov_id + '">' + val.prov_name + '</option>';
                            }
                        } else {
                            str += '<option value="' + val.prov_id + '">' + val.prov_name + '</option>';
                        }
                    });

                    elem.html(str).select2();
                } else {
                    if (parseInt(obj['res']) === 99) {
                        $.alert({
                            title: 'FAILED!',
                            content: obj['message'],
                            type: 'red',
                            draggable: false,
                        });
                    }
                }
            }
        });
    }

    function getDataSelect_city(elem, id = null, id_parent = null) {
        var param = {
            'prov_id': id_parent
        };
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('user_management/getDataSelect_city'); ?>",
            data: param,
            dataType: "json",
            cache: false,
            success: function(data) {
                var obj = data;
                if (parseInt(obj['res']) === 1) {
                    var str = '';
                    if (id != null) {
                        str += '<option value="" disabled>Choose your option</option>';
                    } else {
                        str += '<option value="" disabled selected>Choose your option</option>';
                    }

                    $.each(obj['data'], function(i, val) {
                        if (id != null) {
                            if (id.includes(val.city_id)) {
                                str += '<option selected value="' + val.city_id + '">' + val.city_name + '</option>';
                            } else {
                                str += '<option value="' + val.city_id + '">' + val.city_name + '</option>';
                            }
                        } else {
                            str += '<option value="' + val.city_id + '">' + val.city_name + '</option>';
                        }
                    });

                    elem.html(str).select2();
                } else {
                    if (parseInt(obj['res']) === 99) {
                        $.alert({
                            title: 'FAILED!',
                            content: obj['message'],
                            type: 'red',
                            draggable: false,
                        });
                    }
                }
            }
        });
    }

    function getDataSelect_district(elem, id = null, id_parent = null) {
        var param = {
            'city_id': id_parent
        };
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('user_management/getDataSelect_district'); ?>",
            data: param,
            dataType: "json",
            cache: false,
            success: function(data) {
                var obj = data;
                if (parseInt(obj['res']) === 1) {
                    var str = '';
                    if (id != null) {
                        str += '<option value="" disabled>Choose your option</option>';
                    } else {
                        str += '<option value="" disabled selected>Choose your option</option>';
                    }

                    $.each(obj['data'], function(i, val) {
                        if (id != null) {
                            if (id.includes(val.dis_id)) {
                                str += '<option selected value="' + val.dis_id + '">' + val.dis_name + '</option>';
                            } else {
                                str += '<option value="' + val.dis_id + '">' + val.dis_name + '</option>';
                            }
                        } else {
                            str += '<option value="' + val.dis_id + '">' + val.dis_name + '</option>';
                        }
                    });

                    elem.html(str).select2();
                } else {
                    if (parseInt(obj['res']) === 99) {
                        $.alert({
                            title: 'FAILED!',
                            content: obj['message'],
                            type: 'red',
                            draggable: false,
                        });
                    }
                }
            }
        });
    }

    function getDataSelect_subdistrict(elem, id = null, id_parent = null) {
        var param = {
            'dis_id': id_parent
        };
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('user_management/getDataSelect_subdistrict'); ?>",
            data: param,
            dataType: "json",
            cache: false,
            success: function(data) {
                var obj = data;
                if (parseInt(obj['res']) === 1) {
                    var str = '';
                    if (id != null) {
                        str += '<option value="" disabled>Choose your option</option>';
                    } else {
                        str += '<option value="" disabled selected>Choose your option</option>';
                    }

                    $.each(obj['data'], function(i, val) {
                        if (id != null) {
                            if (id.includes(val.subdis_id)) {
                                str += '<option selected value="' + val.subdis_id + '">' + val.subdis_name + '</option>';
                            } else {
                                str += '<option value="' + val.subdis_id + '">' + val.subdis_name + '</option>';
                            }
                        } else {
                            str += '<option value="' + val.subdis_id + '">' + val.subdis_name + '</option>';
                        }
                    });

                    elem.html(str).select2();
                } else {
                    if (parseInt(obj['res']) === 99) {
                        $.alert({
                            title: 'FAILED!',
                            content: obj['message'],
                            type: 'red',
                            draggable: false,
                        });
                    }
                }
            }
        });
    }

    function getDataSelect_postalcode(elem, id = null, id_parent = null) {
        var param = {
            'subdis_id': id_parent
        };
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('user_management/getDataSelect_postalcode'); ?>",
            data: param,
            dataType: "json",
            cache: false,
            success: function(data) {
                var obj = data;
                if (parseInt(obj['res']) === 1) {
                    var str = '';
                    if (id != null) {
                        str += '<option value="" disabled>Choose your option</option>';
                    } else {
                        str += '<option value="" disabled selected>Choose your option</option>';
                    }

                    $.each(obj['data'], function(i, val) {
                        if (id != null) {
                            if (id.includes(val.postal_id)) {
                                str += '<option selected value="' + val.postal_id + '">' + val.postal_code + '</option>';
                            } else {
                                str += '<option value="' + val.postal_id + '">' + val.postal_code + '</option>';
                            }
                        } else {
                            str += '<option value="' + val.postal_id + '">' + val.postal_code + '</option>';
                        }
                    });

                    elem.html(str).select2();
                } else {
                    if (parseInt(obj['res']) === 99) {
                        $.alert({
                            title: 'FAILED!',
                            content: obj['message'],
                            type: 'red',
                            draggable: false,
                        });
                    }
                }
            }
        });
    }

    function resetSelect(elem, msg = null) {
        var str = '';
        if (msg != null) {
            str += '<option value="" disabled selected>' + msg + '</option>';
        } else {
            str += '<option value="" disabled selected>Choose your option</option>';
        }

        elem.html(str).select2();
    }

    function submit(form) {
        var param = new FormData($(form)[0]);

        $.ajax({
            type: "POST",
            url: "<?php echo base_url('user_management/submit'); ?>",
            data: param,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
                var obj = $.parseJSON(data);

                if (parseInt(obj['res']) === 1) {
                    $.alert({
                        title: 'Information',
                        content: 'Data SUCCEEDED to submit',
                        type: 'green',
                        draggable: false,
                        buttons: {
                            ok: function() {
                                $('.main_content').fadeIn();
                                $('.form_content').fadeOut().empty();
                                get_main_table();
                            },
                        }
                    });
                } else {
                    if (parseInt(obj['res']) === 99) {
                        $.alert({
                            title: 'FAILED!',
                            content: obj['message'],
                            type: 'red',
                            draggable: false,
                        });
                    }
                }
            }
        });
    }

    function submit_ResetPsw(form) {
        var param = new FormData($(form)[0]);

        $.ajax({
            type: "POST",
            url: "<?php echo base_url('user_management/submit_ResetPsw'); ?>",
            data: param,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
                var obj = $.parseJSON(data);

                if (parseInt(obj['res']) === 1) {
                    $.alert({
                        title: 'Information',
                        content: 'Data SUCCEEDED to submit',
                        type: 'green',
                        draggable: false,
                        buttons: {
                            ok: function() {
                                $('.main_content').fadeIn();
                                $('.form_content').fadeOut().empty();
                                get_main_table();
                            },
                        }
                    });
                } else {
                    if (parseInt(obj['res']) === 99) {
                        $.alert({
                            title: 'FAILED!',
                            content: obj['message'],
                            type: 'red',
                            draggable: false,
                        });
                    }
                }
            }
        });
    }

    function set_is_trash(id, username, nik, value) {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('user_management/set_is_trash'); ?>",
            data: {
                id: id,
                value: value
            },
            cache: false,
            success: function(data) {
                var obj = $.parseJSON(data);

                if (parseInt(obj['res']) === 1) {
                    var content_words = 'Data (' + username + ' - ' + nik + ') SUCCEEDED set to UNTRASHED';
                    if (value == '1') {
                        content_words = 'Data (' + username + ' - ' + nik + ') SUCCEEDED set to TRASHED';
                    }

                    $.alert({
                        title: 'Information',
                        content: content_words,
                        type: 'green',
                        draggable: false,
                        buttons: {
                            ok: function() {
                                get_main_table();
                            },
                        }
                    });
                } else {
                    if (parseInt(obj['res']) === 99) {
                        if (value == '1') {
                            $("input[name=cb_is_trash_" + id + "]").prop('checked', false);
                        } else {
                            $("input[name=cb_is_trash_" + id + "]").prop('checked', true);
                        }

                        $.alert({
                            title: 'FAILED!',
                            content: obj['message'],
                            type: 'red',
                            draggable: false,
                        });
                    }
                }
            }
        });
    }

    function set_is_active(id, username, nik, value) {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('user_management/set_is_active'); ?>",
            data: {
                id: id,
                value: value
            },
            cache: false,
            success: function(data) {
                var obj = $.parseJSON(data);

                if (parseInt(obj['res']) === 1) {
                    var content_words = 'Data (' + username + ' - ' + nik + ') SUCCEEDED set to NOT ACTIVE';
                    if (value == '1') {
                        content_words = 'Data (' + username + ' - ' + nik + ') SUCCEEDED set to ACTIVE';
                    }

                    $.alert({
                        title: 'Information',
                        content: content_words,
                        type: 'green',
                        draggable: false,
                        buttons: {
                            ok: function() {
                                get_main_table();
                            },
                        }
                    });
                } else {
                    if (parseInt(obj['res']) === 99) {
                        if (value == '1') {
                            $("input[name=cb_is_active_" + id + "]").prop('checked', false);
                        } else {
                            $("input[name=cb_is_active_" + id + "]").prop('checked', true);
                        }

                        $.alert({
                            title: 'FAILED!',
                            content: obj['message'],
                            type: 'red',
                            draggable: false,
                        });
                    }
                }
            }
        });
    }

    function set_is_open(id, value) {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('user_management/set_is_open'); ?>",
            data: {
                id: id,
                value: value
            },
            cache: false,
            success: function(data) {
                var obj = $.parseJSON(data);

                if (parseInt(obj['res']) === 1) {
                    return true;
                }
            }
        });
    }

    $(document).ready(function() {
        get_main_table();
        getFilter_is_active();
        getFilter_is_trash();
        getFilter_sex();
        getFilter_position();

    });

    $(function() {
        $(document).on('click', '#btn_add', function(event) {
            getForm();
        });
    });

    $(function() {
        $(document).on('click', '#btn_back', function(event) {
            set_is_open($('#id').val(), '0');
            $('.main_content').fadeIn();
            $('.form_content').fadeOut().empty();
            $('.form_content');
        });
    });

    $(function() {
        $(document).on('click', '#btn_filter_search', function(event) {
            get_main_table();
        });
    });

    $(function() {
        $(document).on('change', 'input[id^=cb_akses_h]', function(event) {
            var id = $(this).attr('data-id');
            if ($(this).is(":checked")) {
                $("input[id=cb_akses_h-" + id + "]").parent("label").parent("div").parent("li").addClass('green');
                $("input[id=cb_akses_h-" + id + "]").parent("label").parent("div").parent("li").removeClass('red');
                $("input[id^=cb_akses_d-" + id + "-]").prop('checked', true);
            } else {
                if (!$(this).is(":checked")) {
                    $("input[id=cb_akses_h-" + id + "]").parent("label").parent("div").parent("li").addClass('red');
                    $("input[id=cb_akses_h-" + id + "]").parent("label").parent("div").parent("li").removeClass('green');
                    $("input[id^=cb_akses_d-" + id + "-]").prop('checked', false);
                }
            }
        });
    });

    $(function() {
        $(document).on('change', 'input[id^=cb_akses_d]', function(event) {
            var id_h = $(this).attr('data-id_h');
            var id_d = $(this).attr('data-id_d');
            var count_d = $("input[id^=cb_akses_d-" + id_h + "-]:checked").length;

            if (count_d > 0) {
                $("input[id=cb_akses_h-" + id_h + "]").prop('checked', true);
                $("input[id=cb_akses_h-" + id_h + "]").parent("label").parent("div").parent("li").addClass('green');
                $("input[id=cb_akses_h-" + id_h + "]").parent("label").parent("div").parent("li").removeClass('red');
            } else {
                $("input[id=cb_akses_h-" + id_h + "]").prop('checked', false);
                $("input[id=cb_akses_h-" + id_h + "]").parent("label").parent("div").parent("li").addClass('red');
                $("input[id=cb_akses_h-" + id_h + "]").parent("label").parent("div").parent("li").removeClass('green');
            }
        });
    });

    $(function() {
        $(document).on('change', '#id_position', function(event) {
            var department = $(this).find(':selected').attr('data-department');
            var division = $(this).find(':selected').attr('data-division');
            $("#department").val(department);
            $("#division").val(division);
        });
    });

    $(function() {
        $(document).on('change', '#id_role', function(event) {
            getData_role_akses($(this).val(), $('#submit_type').val());
        });
    });

    $(function() {
        $(document).on('change', '#id_province', function(event) {
            getDataSelect_city($('#id_city'), null, $(this).val());
            resetSelect($('#id_district'), "Choose \"City\" First");
            resetSelect($('#id_subdistrict'), "Choose \"District\" First");
            resetSelect($('#id_postalcode'), "Choose \"Subdistrict\" First");
        });
    });

    $(function() {
        $(document).on('change', '#id_city', function(event) {
            getDataSelect_district($('#id_district'), null, $(this).val());
            resetSelect($('#id_subdistrict'), "Choose \"District\" First");
            resetSelect($('#id_postalcode'), "Choose \"Subdistrict\" First");
        });
    });

    $(function() {
        $(document).on('change', '#id_district', function(event) {
            getDataSelect_subdistrict($('#id_subdistrict'), null, $(this).val());
            resetSelect($('#id_postalcode'), "Choose \"Subdistrict\" First");
        });
    });

    $(function() {
        $(document).on('change', '#id_subdistrict', function(event) {
            getDataSelect_postalcode($('#id_postalcode'), null, $(this).val());
        });
    });

    $(function() {
        $(document).on('click', '#btn_submit', function() {

            $("#form_submit").validate({
                rules: {
                    nik: {
                        required: true,
                        digits: true,
                        minlength: 7,
                    },
                    first_name: {
                        required: true,
                        minlength: 3,
                    },
                    last_name: {
                        required: true
                    },
                    short_name: {
                        required: true,
                        minlength: 5,
                        maxlength: 10
                    },
                    username: {
                        required: true,
                        minlength: 10,
                        maxlength: 20
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    id_position: {
                        required: true
                    },
                    id_role: {
                        required: true
                    },
                    address: {
                        required: true,
                        minlength: 10,
                        maxlength: 100
                    },
                    id_province: {
                        required: true
                    },
                    id_city: {
                        required: true
                    },
                    id_district: {
                        required: true
                    },
                    id_subdistrict: {
                        required: true
                    },
                    id_postalcode: {
                        required: true
                    },
                    sex: {
                        required: true
                    },
                    phone: {
                        required: true,
                        digits: true,
                        minlength: 10,
                    },
                    birth_place: {
                        required: true
                    },
                    birth_date: {
                        required: true
                    },
                },
                errorClass: 'invalid',
                validClass: "valid",
                ignore: "",
                errorPlacement: function(error, element) {
                    var ss = element.attr("id");
                    var ele = document.getElementById(ss);
                    var ele_tag = ele.tagName;

                    if (ele_tag === 'SELECT') {
                        $(element)
                            .parent('div')
                            .siblings("label[for='" + element.attr("id") + "']")
                            .append(error.css({
                                "margin-left": "7px",
                                "color": "red"
                            }));
                    } else {
                        $(element)
                            .siblings("label[for='" + element.attr("id") + "']")
                            .append(error.css({
                                "margin-left": "7px",
                                "color": "red"
                            }));
                    }

                },
                errorElement: "span",
                success: function(error, element) {},
                submitHandler: function(form) {
                    $.confirm({
                        draggable: false,
                        title: 'Confirmation',
                        content: 'Are you sure to Submit this data?',
                        buttons: {
                            cancel: function() {},
                            confirm: function() {
                                submit(form);
                            },
                        }
                    });
                }
            });
        });
    });

    $(function() {
        $(document).on('click', '#btn_submit_ResetPsw', function() {

            $("#form_submit").validate({
                rules: {
                    password: {
                        required: true,
                        minlength: 6,
                    },
                    password2: {
                        equalTo: "#password"
                    },

                },
                errorClass: 'invalid',
                validClass: "valid",
                ignore: "",
                errorPlacement: function(error, element) {
                    var ss = element.attr("id");
                    var ele = document.getElementById(ss);
                    var ele_tag = ele.tagName;

                    if (ele_tag === 'SELECT') {
                        $(element)
                            .parent('div')
                            .siblings("label[for='" + element.attr("id") + "']")
                            .append(error.css({
                                "margin-left": "7px",
                                "color": "red"
                            }));
                    } else {
                        $(element)
                            .siblings("label[for='" + element.attr("id") + "']")
                            .append(error.css({
                                "margin-left": "7px",
                                "color": "red"
                            }));
                    }

                },
                errorElement: "span",
                success: function(error, element) {},
                submitHandler: function(form) {
                    $.confirm({
                        draggable: false,
                        title: 'Confirmation',
                        content: 'Are you sure to Submit this data?',
                        buttons: {
                            cancel: function() {},
                            confirm: function() {
                                submit_ResetPsw(form);
                            },
                        }
                    });
                }
            });
        });
    });

    $(function() {
        $(document).on('change', '.is_trash_checkbox', function() {

            var id = $(this).attr('data-id');
            var username = $(this).attr('data-username');
            var nik = $(this).attr('data-nik');
            var value = '0';
            var confirm_words = 'Are you sure to set this User (' + username + ' - ' + nik + ') UNTRASHED?';
            if ($(this).is(":checked")) {
                value = '1';
                confirm_words = 'Are you sure to set this User (' + username + ' - ' + nik + ') TRASHED?';
            }

            $.confirm({
                draggable: false,
                title: 'Confirmation',
                content: confirm_words,
                buttons: {
                    cancel: function() {
                        if (value == '1') {
                            $("input[name=cb_is_trash_" + id + "]").prop('checked', false);
                        } else {
                            $("input[name=cb_is_trash_" + id + "]").prop('checked', true);
                        }
                    },
                    confirm: function() {
                        set_is_trash(id, username, nik, value);
                    },
                }
            });
        });
    });

    $(function() {
        $(document).on('change', '.is_active_checkbox', function() {

            var id = $(this).attr('data-id');
            var username = $(this).attr('data-username');
            var nik = $(this).attr('data-nik');
            var value = '0';
            var confirm_words = 'Are you sure to set this User (' + username + ' - ' + nik + ') NOT ACTIVE?';
            if ($(this).is(":checked")) {
                value = '1';
                confirm_words = 'Are you sure to set this User (' + username + ' - ' + nik + ') ACTIVE?';
            }

            $.confirm({
                draggable: false,
                title: 'Confirmation',
                content: confirm_words,
                buttons: {
                    cancel: function() {
                        if (value == '1') {
                            $("input[name=cb_is_active_" + id + "]").prop('checked', false);
                        } else {
                            $("input[name=cb_is_active_" + id + "]").prop('checked', true);
                        }
                    },
                    confirm: function() {
                        set_is_active(id, username, nik, value);
                    },
                }
            });
        });
    });

    $(function() {
        $(document).on('click', '.is_open_btn', function(event) {
            var id = $(this).attr('data-id');
            var value = '0';
            var confirm_words = 'Are you sure to set to ACCESSIBLE ?';


            $.confirm({
                draggable: false,
                title: 'Confirmation',
                content: confirm_words,
                buttons: {
                    cancel: function() {},
                    confirm: function() {
                        set_is_open(id, value);
                        get_main_table();
                    },
                }
            });
        });
    });
</script>