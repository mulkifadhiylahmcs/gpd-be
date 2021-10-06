<script type="text/javascript">
    var form;
    var def_mainGrid_dt_obj = {
        fixedHeader: {
            header: true,
            headerOffset: $('#navbar_fixed').height()
        },
        pageLength: 20,
        searching: true,
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
                        "url": "<?php echo base_url('mst_location/get_main_table'); ?>",
                        "type": "POST",
                        "data": function(data) {
                            data.fil_province = $('#fil_province').val();
                            data.fil_city = $('#fil_city').val();
                            data.fil_district = $('#fil_district').val();
                            data.fil_subdistrict = $('#fil_subdistrict').val();
                            data.fil_postalcode = $('#fil_postalcode').val();
                        }
                    },
                    dom: 'ftip',

                }
            )
        ).on('draw', function() {
            set_akses_clientside();
        });
    }


    $(document).ready(function() {
        get_main_table();
    });


    $(function() {
        $(document).on('click', '#btn_filter_search', function(event) {
            get_main_table();
        });
    });
</script>