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
    var def_trashGrid_dt_obj = {
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
                        "url": "<?php echo base_url('artikel_tag/get_main_table'); ?>",
                        "type": "POST",
                       "data": function(data) {
                            data.fil_tag_text = $('#fil_tag_text').val();
                            // data.tag_alias = $('#tag_alias').val();
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
                            'id': 'btn_trash',
                            'class': 'btn cyan darken-2',
                            'style': 'margin-top:15px;margin-bottom: 15px;'
                        }
                        } ],
                }

            )
        );
    }

    function get_trash_table() {
        //datatables
        if ($.fn.DataTable.isDataTable('#trashGrid')) {
            $('#trashGrid').DataTable().destroy();
        }
        
        $('#trashGrid').DataTable(
            $.extend(
                def_trashGrid_dt_obj, {
                    "processing": true,
                    "serverSide": true,
                    "pageLength": 20,
                    "pagingType": "full_numbers",
                    "order": [],
                    "ajax": {
                        "url": "<?php echo base_url('artikel_tag/get_trash_table'); ?>",
                        "type": "POST",
                    //    "data": function(data) {
                    //         data.fil_tag_text = $('#fil_tag_text').val();
                    //         // data.tag_alias = $('#tag_alias').val();
                    //         data.fil_is_publish = $('#fil_is_publish').val();
                    //         data.fil_is_trash = $('#fil_is_trash').val();
                    //     }

                    },
                    dom: 'Bftip',
                    buttons: [{
                        text: 'Back',
                        attr: {
                            'data-mode': "form",
                            'id': 'btn_backtrash',
                            'class': 'btn cyan darken-2',
                            'style': 'margin-top:15px;margin-bottom: 15px;'
                        }
                    }, {
                        text: 'UnTrash',
                        attr: {
                            'data-mode': "form",
                            'id': 'btn_untrash',
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
            url: "<?php echo base_url('artikel_tag/getForm'); ?>",
            data: param,
            cache: false,
            success: function(data) {
                var obj = $.parseJSON(data);
                if (parseInt(obj['res']) === 1) {
                    $('.form_content').empty().html(obj['content']).fadeIn();
                    $('.main_content').fadeOut();
                    $('#submit_type').val(mode);

                        if (mode == 'add') {
                            $('#title_form').html('Add New Tag');
                            getDataSelect_is_publish($('#is_publish'));
                        } else {
                        if (mode == 'edit') {
                            $('#title_form').html('Edit Tag');
                            getDataEdit(id, mode);
                        } else {
                            $('#title_form').html('Detail Tag');
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
            url: "<?php echo base_url('artikel_tag/getDataView'); ?>",
            data: param,
            cache: false,
            success: function(data) {
                var obj = $.parseJSON(data);

                if (parseInt(obj['res']) === 1) {

                    $('#id').val(obj['data']['artikel'].id);
                    $('#tag_text').val(obj['data']['artikel'].tag_text);
                    $('#tag_alias').val(obj['data']['artikel'].tag_alias);
                    // $('#description').val(obj['data']['artikel'].description);
                    // M.textareaAutoResize($('#description'));
                    $('#is_trash').val(obj['data']['artikel'].is_trash);
                    $('#is_publish').val(obj['data']['artikel'].is_publish);

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
            url: "<?php echo base_url('artikel_tag/getDataEdit'); ?>",
            data: param,
            cache: false,
            success: function(data) {
                var obj = $.parseJSON(data);

                if (parseInt(obj['res']) === 1) {
                    $('#id').val(obj['data']['artikel'].id);
                    $('#tag_text').val(obj['data']['artikel'].tag_text);
                    $('#tag_alias').val(obj['data']['artikel'].tag_alias);
                    // $('#description').val(obj['data']['artikel'].description);
                    // M.textareaAutoResize($('#artikel_tag'));
                    getDataSelect_is_trash($('#is_trash'), obj['data']['artikel'].is_trash);
                    getDataSelect_is_publish($('#is_publish'), obj['data']['artikel'].is_publish);
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

    function getPublish(mode, id) {
       
        var param = {
            'mode':mode,
            'id': id
        };
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('artikel_tag/postPublish'); ?>",
            data: param,
            cache: false,
            success: function(data) {
                var obj = $.parseJSON(data);

                if (parseInt(obj['res']) === 1) {
                    $.alert({
                        title: 'Information',
                        content: 'Data SUCCEEDED',
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

    function Trash(id) {
       
       var param = {
           'id': id
       };
       console.log(param);
       $.ajax({
           type: "POST",
           url: "<?php echo base_url('artikel_tag/postTrash'); ?>",
           data: param,
           cache: false,
           success: function(data) {
               var obj = $.parseJSON(data);

               if (parseInt(obj['res']) === 1) {
                   $.alert({
                       title: 'Information',
                       content: 'Data SUCCEEDED to trash',
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

   function unTrash(id) {
       
       var param = {
           'id': id
       };
    //    console.log(id);
       $.ajax({
           type: "POST",
           url: "<?php echo base_url('artikel_tag/postunTrash'); ?>",
           data: param,
           cache: false,
           success: function(data) {
               var obj = $.parseJSON(data);

               if (parseInt(obj['res']) === 1) {
                   $.alert({
                       title: 'Information',
                       content: 'Data SUCCEEDED Untrash',
                       type: 'green',
                       draggable: false,
                       buttons: {
                           ok: function() {
                               $('.main_content').fadeIn();
                               $('.form_content').fadeOut().empty();
                               get_trash_table();
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

   function unTrashAll(id) {
       
       var param = {
           'id': id
       };
    //    console.log(id);
       $.ajax({
           type: "POST",
           url: "<?php echo base_url('artikel_tag/postunTrashAll'); ?>",
           data: param,
           cache: false,
           success: function(data) {
               var obj = $.parseJSON(data);

               if (parseInt(obj['res']) === 1) {
                   $.alert({
                       title: 'Information',
                       content: 'Data SUCCEEDED Untrash',
                       type: 'green',
                       draggable: false,
                       buttons: {
                           ok: function() {
                               $('.main_content').fadeIn();
                               $('.form_content').fadeOut().empty();
                               get_trash_table();
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



    function submit(form) {
        var param = new FormData($(form)[0]);

        $.ajax({
            type: "POST",
            url: "<?php echo base_url('artikel_tag/submit'); ?>",
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
        getFilter_is_publish();
        getFilter_is_trash1();
        get_trash_table();

        
       
    });

    function getFilter_is_trash1() {
        var str = '';

        str += '<option value="1">Trash</option>';
        str += '<option value="0">Untrash</option>';


        $('#fil_is_trash').html(str).select2({
            closeOnSelect: false
        });

        //Check All / Uncheck All
        // $('#fil_is_trash').siblings('ul').prepend('<li class="sm_select_all"><span>Check All</span></li>');
    }

    function getFilter_is_publish() {
        var str = '';

        str += '<option value="1">YES</option>';
        str += '<option value="0">NO</option>';


        $('#fil_is_publish').html(str).select2({
            closeOnSelect: false
        });

        //Check All / Uncheck All
        // $('#fil_is_trash').siblings('ul').prepend('<li class="sm_select_all"><span>Check All</span></li>');
    }


    $(function() {
        $(document).on('click', '#btn_add', function(event) {
            getForm();
        });
    });

    

    $(function() {
        $(document).on('click', '#btn_trash', function(event) {
            // getFormTrash();
            window.location = '<?php echo base_url('artikel_tag/getTrash'); ?>';
            
        });
    });

    $(function() {
        $(document).on('click', '#btn_backtrash', function(event) {
            // getFormTrash();
            window.location = '<?php echo base_url('artikel_tag'); ?>';
            
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
            // console.log(get_main_table);
            get_main_table();
        });
    });

    $(function() {
        $(document).on('change', '#tag_text', function(event) {
            var text = $(this).val();
            $('#tag_alias').val(text.replace(' ', '-'));
        });
    });

    $(function() {
        $(document).on('click', '#btn_untrash', function(event) {

            //var atLeastOneIsChecked = $('input:checkbox:checked').length > 0;
            
            if ($('input[name="chk[]"]:checked').length > 0) {

                var data = [];
                $.each($("input[name='chk[]']:checked"), function(){ 
                    data.push($(this).data('id'));          
                });
                unTrashAll(data);
    
            }else{
                $.alert({
                            title: 'FAILED!',
                            content: 'UNCHEKED DATA',
                            type: 'red',
                            draggable: false,
                        });
            }

            
        });
    });

    


    

    $(document).ready(function(){
        $('#select-all').click(function(event) {   
            if(this.checked) {
                // Iterate each checkbox
                $(':checkbox').each(function() {
                    this.checked = true;                        
                });
            } else {
                $(':checkbox').each(function() {
                    this.checked = false;                       
                });
            }
        });

        
    });

    $(function() {
        $(document).on('click', '#btn_submit', function() {

            $("#form_submit").validate({
                rules: {
                    tag_alias: {
                        required: true
                    },
                    tag_text: {
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