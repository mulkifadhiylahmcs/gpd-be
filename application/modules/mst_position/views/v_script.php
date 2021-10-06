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
                targets: [2, 4]
            },
            {
                responsivePriority: 3,
                targets: [3]
            },
            {
                targets: [3],
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
                        "url": "<?php echo base_url('mst_position/get_main_table'); ?>",
                        "type": "POST",
                        "data": function(data) {
                            data.fil_code = $('#fil_code').val();
                            data.fil_name = $('#fil_name').val();
                            data.fil_department = $('#fil_department').val();
                            data.fil_division = $('#fil_division').val();
                            data.fil_position_parent = $('#fil_position_parent').val();
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
            url: "<?php echo base_url('mst_position/getForm'); ?>",
            data: param,
            cache: false,
            success: function(data) {
                var obj = $.parseJSON(data);
                if (parseInt(obj['res']) === 1) {
                    $('.form_content').empty().html(obj['content']).fadeIn();
                    $('.main_content').fadeOut();
                    $('#submit_type').val(mode);


                    if (mode == 'add') {
                        $('#title_form').html('Add New Position');
                        getDataSelect_is_active($('#is_active'));
                        getDataSelect_department($('#id_department'));
                        getDataSelect_division($('#id_division'));
                        getDataSelect_parent_position($('#id_parent'));
                    } else {
                        if (mode == 'edit') {
                            $('#title_form').html('Edit Position');
                            getDataEdit(id, mode);
                        } else {
                            $('#title_form').html('Detail Position');
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
            url: "<?php echo base_url('mst_position/getDataView'); ?>",
            data: param,
            cache: false,
            success: function(data) {
                var obj = $.parseJSON(data);

                if (parseInt(obj['res']) === 1) {

                    $('#id').val(obj['data']['position'].id);
                    $('#code').val(obj['data']['position'].code);
                    $('#name').val(obj['data']['position'].name);
                    $('#description').val(obj['data']['position'].description);
                    M.textareaAutoResize($('#description'));
                    $('#is_active').val(obj['data']['position'].is_active);
                    $('#id_department').val(obj['data']['position'].department);
                    $('#id_division').val(obj['data']['position'].division);
                    $('#id_parent').val(obj['data']['position'].parent);
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
            url: "<?php echo base_url('mst_position/getDataEdit'); ?>",
            data: param,
            cache: false,
            success: function(data) {
                var obj = $.parseJSON(data);

                if (parseInt(obj['res']) === 1) {
                    $('#id').val(obj['data']['position'].id);
                    $('#code').val(obj['data']['position'].code);
                    $('#name').val(obj['data']['position'].name);
                    $('#description').val(obj['data']['position'].description);
                    M.textareaAutoResize($('#description'));

                    getDataSelect_department($('#id_department'), obj['data']['position'].id_department);
                    getDataSelect_division($('#id_division'), obj['data']['position'].id_division, obj['data']['position'].id_department);
                    getDataSelect_parent_position($('#id_parent'), obj['data']['position'].id_parent, obj['data']['position'].id_department, obj['data']['position'].id_division);
                    getDataSelect_is_active($('#is_active'), obj['data']['position'].is_active);
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

    function getDataSelect_department(elem, id = null) {
        var param = null;
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('mst_position/getDataSelect_department'); ?>",
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

                    if (id == 0) {
                        str += '<option value="0" selected>UNSET</option>';
                    } else {
                        str += '<option value="0">UNSET</option>';
                    }

                    $.each(obj['data'], function(i, val) {
                        if (id != null) {
                            if (id.includes(val.id)) {
                                str += '<option selected value="' + val.id + '">' + val.code + ' - ' + val.name + '</option>';
                            } else {
                                str += '<option value="' + val.id + '">' + val.code + ' - ' + val.name + '</option>';
                            }
                        } else {
                            str += '<option value="' + val.id + '">' + val.code + ' - ' + val.name + '</option>';
                        }
                    });

                    elem.html(str);
                    elem.select2();
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

    function getDataSelect_division(elem, id = null, id_department = null) {
        var param = {
            'id_department': id_department
        };
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('mst_position/getDataSelect_division'); ?>",
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

                    if (id == 0) {
                        str += '<option value="0" selected>UNSET</option>';
                    } else {
                        str += '<option value="0">UNSET</option>';
                    }

                    $.each(obj['data'], function(i, val) {
                        if (id != null) {
                            if (id.includes(val.id)) {
                                str += '<option selected value="' + val.id + '">' + val.code + ' - ' + val.name + '</option>';
                            } else {
                                str += '<option value="' + val.id + '">' + val.code + ' - ' + val.name + '</option>';
                            }
                        } else {
                            str += '<option value="' + val.id + '">' + val.code + ' - ' + val.name + '</option>';
                        }
                    });

                    elem.html(str);
                    elem.select2();
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

    function getDataSelect_parent_position(elem, id = null, id_department = null, id_division = null) {
        var param = {
            'id_department': id_department
        };
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('mst_position/getDataSelect_parent_position'); ?>",
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

                    if (id == 0) {
                        str += '<option value="0" selected>UNSET</option>';
                    } else {
                        str += '<option value="0">UNSET</option>';
                    }

                    $.each(obj['data'], function(i, val) {
                        if (id != null) {
                            if (id.includes(val.id)) {
                                str += '<option selected value="' + val.id + '">' + val.code + ' - ' + val.name + '</option>';
                            } else {
                                str += '<option value="' + val.id + '">' + val.code + ' - ' + val.name + '</option>';
                            }
                        } else {
                            str += '<option value="' + val.id + '">' + val.code + ' - ' + val.name + '</option>';
                        }
                    });

                    elem.html(str);
                    elem.select2();
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
            url: "<?php echo base_url('mst_position/submit'); ?>",
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
        getFilter_department();
        getFilter_division();
        getFilter_position_parent();
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
        });
    });

    $(function() {
        $(document).on('click', '#btn_filter_search', function(event) {
            get_main_table();
        });
    });

    $(function() {
        $(document).on('click', '#btn_submit', function() {

            $("#form_submit").validate({
                rules: {
                    code: {
                        required: true
                    },
                    name: {
                        required: true
                    },
                    description: {
                        required: true
                    },
                    id_department: {
                        required: true
                    },
                    id_division: {
                        required: true
                    },
                    id_parent: {
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