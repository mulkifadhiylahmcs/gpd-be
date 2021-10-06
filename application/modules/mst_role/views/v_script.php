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
                targets: [1, -1]
            },
            {
                responsivePriority: 2,
                targets: [2, 3]
            },
            {
                targets: [2],
                render: $.fn.dataTable.render.ellipsis(50, true)
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
                        "url": "<?php echo base_url('mst_role/get_main_table'); ?>",
                        "type": "POST",
                        "data": function(data) {
                            data.fil_name = $('#fil_name').val();
                            data.fil_description = $('#fil_description').val();
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
            url: "<?php echo base_url('mst_role/getForm'); ?>",
            data: param,
            cache: false,
            success: function(data) {
                var obj = $.parseJSON(data);
                if (parseInt(obj['res']) === 1) {
                    $('.form_content').empty().html(obj['content']).fadeIn();
                    $('.main_content').fadeOut();
                    $('#submit_type').val(mode);


                    if (mode == 'add') {
                        $('#title_form').html('Add New Role');
                        getDataSelect_is_active($('#is_active'));
                        getData_role_akses(id, mode);
                    } else {
                        if (mode == 'edit') {
                            $('#title_form').html('Edit Role');
                            getDataEdit(id, mode);
                        } else {
                            $('#title_form').html('Detail Role');
                            getDataView(id, mode);
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
            url: "<?php echo base_url('mst_role/getDataView'); ?>",
            data: param,
            cache: false,
            success: function(data) {
                var obj = $.parseJSON(data);

                if (parseInt(obj['res']) === 1) {

                    $('#id').val(obj['data']['role'].id);
                    $('#name').val(obj['data']['role'].name);
                    $('#description').val(obj['data']['role'].description);
                    M.textareaAutoResize($('#description'));
                    $('#is_active').val(obj['data']['role'].is_active);

                    getData_role_akses(id, mode);
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
            url: "<?php echo base_url('mst_role/getDataEdit'); ?>",
            data: param,
            cache: false,
            success: function(data) {
                var obj = $.parseJSON(data);

                if (parseInt(obj['res']) === 1) {
                    $('#id').val(obj['data']['role'].id);
                    $('#name').val(obj['data']['role'].name);
                    $('#description').val(obj['data']['role'].description);
                    M.textareaAutoResize($('#description'));

                    getDataSelect_is_active($('#is_active'), obj['data']['role'].is_active);
                    getData_role_akses(id, mode);
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
            url: "<?php echo base_url('mst_role/getData_role_akses'); ?>",
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

                        // str += '<li class="collection-item " title="asdasdsd">asdasdasdsd<span class="right"><i class="material-icons red-text">remove_circle</i></span></li>';
                        // str += '<li class="collection-item " title="asdasdsd">asdasdasdsd<span class="right"><i class="material-icons red-text">remove_circle</i></span></li>';
                        // str += '<li class="collection-item " title="asdasdsd">asdasdasdsd<span class="right"><i class="material-icons red-text">remove_circle</i></span></li>';
                        

                        // str += '</li>';
                        // str += '<li class="collection-item no-padding">';
                        // str += '<div class="switch" style="margin-left: 7px;margin-top: 3px;">';
                        // str += '<label class="grey-text text-darken-3">';
                        // str += '<i class="material-icons red-text">check_circle</i>';
                        // str += '<input id="cb_akses_d-' + val.id + '-1" name="cb_akses_d-' + val.id + '-1" data-id_h="' + val.id + '" data-id_d="1" type="checkbox">';
                        // str += '<span class="lever" style="bottom: 10px; margin: 0 5px;"></span>';
                        // str += '<i class="material-icons green-text">remove_circle</i>';
                        // str += '<span style="bottom: 7px;margin-left: 8px;display: inline-block;position: relative;"> aaaaaaa</span>';
                        // str += '</label>';
                        // str += '</div>';
                        // str += '</li>';

                        // str += '</li>';
                        // str += '<li class="collection-item no-padding">';
                        // str += '<div class="switch" style="margin-left: 7px;margin-top: 3px;">';
                        // str += '<label class="grey-text text-darken-3">';
                        // str += '<i class="material-icons red-text">check_circle</i>';
                        // str += '<input id="cb_akses_d-' + val.id + '-2" name="cb_akses_d-' + val.id + '-2" data-id_h="' + val.id + '" data-id_d="2" type="checkbox">';
                        // str += '<span class="lever" style="bottom: 10px; margin: 0 5px;"></span>';
                        // str += '<i class="material-icons green-text">remove_circle</i>';
                        // str += '<span style="bottom: 7px;margin-left: 8px;display: inline-block;position: relative;"> aaaaaaa</span>';
                        // str += '</label>';
                        // str += '</div>';
                        // str += '</li>';

                        // str += '</li>';
                        // str += '<li class="collection-item no-padding">';
                        // str += '<div class="switch" style="margin-left: 7px;margin-top: 3px;">';
                        // str += '<label class="grey-text text-darken-3">';
                        // str += '<i class="material-icons red-text">check_circle</i>';
                        // str += '<input id="cb_akses_d-' + val.id + '-3" name="cb_akses_d-' + val.id + '-3" data-id_h="' + val.id + '" data-id_d="2" type="checkbox">';
                        // str += '<span class="lever" style="bottom: 10px; margin: 0 5px;"></span>';
                        // str += '<i class="material-icons green-text">remove_circle</i>';
                        // str += '<span style="bottom: 7px;margin-left: 8px;display: inline-block;position: relative;"> aaaaaaa</span>';
                        // str += '</label>';
                        // str += '</div>';
                        // str += '</li>';


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

    function submit(form) {
        var param = new FormData($(form)[0]);

        $.ajax({
            type: "POST",
            url: "<?php echo base_url('mst_role/submit'); ?>",
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

    $(document).ready(function() {
        get_main_table();
        getFilter_is_active();


    });

    $(function() {
        $(document).on('click', '#btn_add', function(event) {
            getForm();
        });
    });

    $(function() {
        $(document).on('click', '#btn_back', function(event) {
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
        $(document).on('click', '#btn_submit', function() {

            $("#form_submit").validate({
                rules: {
                    name: {
                        required: true
                    },
                    description: {
                        required: true
                    },
                    is_active: {
                        required: true
                    },
                },
                errorClass: 'invalid',
                validClass: "valid",
                ignore: ":hidden:not(select)",
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
</script>