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
                        "url": "<?php echo base_url('artikel_kategori/get_main_table'); ?>",
                        "type": "POST",
                       "data": function(data) {
                            data.id_parent = $('#fil_parent').val();
                            data.kategori_text = $('#fil_kategori_text').val();
                            data.fil_is_publish = $('#fil_is_publish').val();
                            data.fil_is_trash = $('#fil_is_trash').val();
                        }

                    },

                    dom: 'Bftip',
                    buttons: [{
                        text: 'Add',
                        attr: {
                            'data-mode': "form",
                            'id': 'btn_add',
                            'class': 'btn cyan darken-2',
                            'style': 'margin-top:15px;margin-bottom: 15px;'
                        }
                    }, {
                        text: 'Trash',
                        attr: {
                            'data-mode': "form",
                            'id': 'btn_add',
                            'class': 'btn cyan darken-2',
                            'style': 'margin-top:15px;margin-bottom: 15px;'
                        }
                        } ],
                }

            )
        );
    }



    function getForm(mode = 'add', id = null) {
        var param = {
            'mode': mode,
            'id': id
        };

        $.ajax({
            type: "POST",
            url: "<?php echo base_url('artikel_kategori/getForm'); ?>",
            data: param,
            cache: false,
            success: function(data) {
                var obj = $.parseJSON(data);
                if (parseInt(obj['res']) === 1) {
                    $('.form_content').empty().html(obj['content']).fadeIn();
                    $('.main_content').fadeOut();
                    $('#submit_type').val(mode);


                    if (mode == 'add') {
                        $('#title_form').html('Add New Kategori');
                        getDataSelect_is_publish($('#is_publish'));
                        getDataSelect_id_parent($('#id_parent'));
                    } else {
                        if (mode == 'edit') {
                            $('#title_form').html('Edit Kategori');
                            getDataEdit(id, mode);
                        } else {
                            $('#title_form').html('Detail Kategori');
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

    function getDataSelect_is_publish(elem, id = null) {
        var str = '';
        if (id != null) {
            str += '<option value="" disabled>Choose your option</option>';
        } else {
            str += '<option value="" disabled selected>Choose your option</option>';
        }

        if (id != null) {
            if (id == 1) {
                str += '<option selected value="1">YES</option>';
                str += '<option value="0">NO</option>';
            } else {
                str += '<option value="1">YES</option>';
                str += '<option selected value="0">NO</option>';
            }
        } else {
            str += '<option value="1">YES</option>';
            str += '<option value="0">NO</option>';
        }


        elem.html(str);
        elem.formSelect();
    }

    function getDataSelect_id_parent(elem, id = null) {
        var param = {
            'id': id
        };
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('artikel_kategori/getDataSelect_parent'); ?>",
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
                                str += '<option selected value="' + val.id + '">' + val.kategori_text + '</option>';
                            } else {
                                str += '<option value="' + val.id + '">' + val.kategori_text + '</option>';
                            }
                        } else {
                            str += '<option value="' + val.id + '">' + val.kategori_text + '</option>';
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

    function getDataSelect_is_trash(elem, id = null) {
        var str = '';
        if (id != null) {
            str += '<option value="" disabled>Choose your option</option>';
        } else {
            str += '<option value="" disabled selected>Choose your option</option>';
        }

        if (id != null) {
            if (id == 1) {
                str += '<option selected value="1">YES</option>';
                str += '<option value="0">NO</option>';
            } else {
                str += '<option value="1">YES</option>';
                str += '<option selected value="0">NO</option>';
            }
        } else {
            str += '<option value="1">YES</option>';
            str += '<option value="0">NO</option>';
        }


        elem.html(str);
        elem.formSelect();
    }

    function getDataView(id, mode) {
        var param = {
            'id': id
        };

        $.ajax({
            type: "POST",
            url: "<?php echo base_url('artikel_kategori/getDataView'); ?>",
            data: param,
            cache: false,
            success: function(data) {
                var obj = $.parseJSON(data);

                if (parseInt(obj['res']) === 1) {

                    $('#id').val(obj['data']['kategori'].id);
                    $('#kategori_text').val(obj['data']['kategori'].kategori_text);
                    $('#kategori_alias').val(obj['data']['kategori'].kategori_alias);
                    // $('#description').val(obj['data']['artikel'].description);
                    // M.textareaAutoResize($('#description'));
                    $('#id_parent').val(obj['data']['kategori'].id_parent);
                    $('#is_trash').val(obj['data']['kategori'].is_trash);
                    $('#is_publish').val(obj['data']['kategori'].is_publish);

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
            url: "<?php echo base_url('artikel_kategori/getDataEdit'); ?>",
            data: param,
            cache: false,
            success: function(data) {
                var obj = $.parseJSON(data);

                if (parseInt(obj['res']) === 1) {
                    $('#id').val(obj['data']['kategori'].id);
                    $('#kategori_text').val(obj['data']['kategori'].kategori_text);
                    $('#kategori_alias').val(obj['data']['kategori'].kategori_alias);
                    // $('#description').val(obj['data']['artikel'].description);
                    // M.textareaAutoResize($('#artikel_kategori'));
                    getDataSelect_id_parent($('#id_parent'), obj['data']['kategori'].id_parent);
                    getDataSelect_is_trash($('#is_trash'), obj['data']['kategori'].is_trash);
                    getDataSelect_is_publish($('#is_publish'), obj['data']['kategori'].is_publish);
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
            url: "<?php echo base_url('artikel_kategori/submit'); ?>",
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
        //getFilter_is_publish();
        getFilter_is_trash();
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
        $(document).on('change', '#kategori_text', function(event) {
            var text = $(this).val().toLowerCase();
            $('#kategori_alias').val(text.replace(' ', '-'));
        });
    });

    $(function() {
        $(document).on('click', '#btn_submit', function() {

            $("#form_submit").validate({
                rules: {
                    artikel_kategori: {
                        required: true
                    },
                    kategori_text: {
                        required: true
                    },
                    is_publish: {
                        required: true
                    },
                    // is_trash: {
                    //     required: true
                    // },
                },
                errorClass: 'invalid',
                validClass: "valid",
                ignore: ":hidden:not(select)",
                errorPlacement: function(error, element) {},
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