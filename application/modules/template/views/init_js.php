<script type="text/javascript">
    var preloader = document.getElementById("loading");

    var data_akses_header;
    var data_akses_detail;

    function loading() {
        preloader.style.display = 'none';
    }

    $(document).ajaxStart(function() {
        $("#loading").show();
    }).ajaxStop(function() {
        $("#loading").hide();
    }).ajaxError(function() {
        $("#loading").hide();
    });

    $(document).ready(function() {
        set_menu_active();
        set_akses();
        $('.sidenav').sidenav();
        $('.collapsible').collapsible();
        $('.dropdown-trigger').dropdown({
            'constrainWidth': false,
            'coverTrigger': false,
        });
        $('.tooltipped').tooltip();

        // $('.modal').modal();
        // $('select').select2();
        // $('textarea').characterCounter();
        // $('.materialboxed').materialbox();


    });

    // $(function() {
    //     $('body').on('DOMNodeInserted', '.fixedHeader-floating', function() {
    //         console.log('asdasdasd');
    //     });
    // });



    // $(function() {
    //     $(document).on('click', 'li.sm_select_all', function() {
    //         var jq_elem = $(this),
    //             jq_elem_span = jq_elem.find('span'),
    //             select_all = jq_elem_span.text() == 'Check All',
    //             set_text = select_all ? 'Uncheck All' : 'Check All';
    //         jq_elem_span.text(set_text);
    //         jq_elem.siblings('li').filter(function() {
    //             return $(this).find('input').prop('checked') != select_all;
    //         }).click();
    //     });
    // });


    // $(function() {
    //     $(document).on('change', 'select[multiple]', function() {
    //         var value = $(this).val();
    //         var jqr_elem = $(this);
    //         if (value.length > 0) {
    //             $(this).children('option').each(function() {
    //                 if ($(this).val() == '') {
    //                     $(this).attr('selected', false);
    //                     jqr_elem.siblings('li.disabled').removeClass('selected').click();
    //                 }
    //             });
    //         } else {
    //             $(this).children('option').each(function() {
    //                 if ($(this).val() == '') {
    //                     $(this).attr('selected', true);
    //                     jqr_elem.siblings('li.disabled').addClass('selected').click();
    //                 }
    //             });
    //         }


    //         $(this).select2();
    //         $(this).siblings('input').click();
    //     });
    // });

    var captcha;
    var def_date_obj = {
        showClearBtn: true,
        autoClose: true,
        format: 'yyyy-mm-dd',
        firstDay: true,
        i18n: {
            months: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
            monthsShort: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            weekdays: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
            weekdaysShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
            weekdaysAbbrev: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
        }
    };

    function do_logout() {

        $.confirm({
            draggable: false,
            title: 'Confirmation',
            content: 'Are you sure to LOGOUT?',
            buttons: {
                cancel: function() {},
                confirm: function() {
                    window.location = "<?php echo base_url('auth/do_logout'); ?>";
                },
            }
        });
    }

    function keypress_Numeric(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (!(evt.shiftKey == false && (charCode == 46 || charCode == 8 || charCode == 37 || charCode == 39 || (charCode >= 48 && charCode <= 57)))) {
            evt.preventDefault();
        }
    }

    function number_format(number, decimals, decPoint, thousandsSep) {

        number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
        var n = !isFinite(+number) ? 0 : +number;
        var prec = !isFinite(+decimals) ? 0 : Math.abs(decimals);
        var sep = (typeof thousandsSep === 'undefined') ? ',' : thousandsSep;
        var dec = (typeof decPoint === 'undefined') ? '.' : decPoint;
        var s = '';

        var toFixedFix = function(n, prec) {
            if (('' + n).indexOf('e') === -1) {
                return +(Math.round(n + 'e+' + prec) + 'e-' + prec)
            } else {
                var arr = ('' + n).split('e');
                var sig = '';
                if (+arr[1] + prec > 0) {
                    sig = '+';
                }
                return (+(Math.round(+arr[0] + 'e' + sig + (+arr[1] + prec)) + 'e-' + prec)).toFixed(prec);
            }
        };

        // @todo: for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec).toString() : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }

        return s.join(dec);
    }

    function timeDiff(date1, date2, interval) {
        var second = 1000,
            minute = second * 60,
            hour = minute * 60,
            day = hour * 24,
            week = day * 7;
        date1 = new Date(date1);
        date2 = new Date(date2);
        var timediff = date2 - date1;
        if (isNaN(timediff)) {
            return NaN;
        }
        switch (interval) {
            case "years":
                return date2.getFullYear() - date1.getFullYear();
            case "months":
                return (
                    (date2.getFullYear() * 12 + date2.getMonth()) -
                    (date1.getFullYear() * 12 + date1.getMonth())
                );
            case "weeks":
                return Math.floor(timediff / week);
            case "days":
                return Math.floor(timediff / day);
            case "hours":
                return Math.floor(timediff / hour);
            case "minutes":
                return Math.floor(timediff / minute);
            case "seconds":
                return Math.floor(timediff / second);
            default:
                return undefined;
        }
    }

    function dateNow() {
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = today.getFullYear();

        today = yyyy + '-' + mm + '-' + dd;
        return today;
    }

    function YeardateNow() {
        var today = new Date();
        var yyyy = today.getFullYear();

        today = yyyy;
        return today;
    }

    function MonthdateNow() {
        var today = new Date();
        var mm = String(today.getMonth() + 1);

        today = mm;
        return today;
    }

    function DaydateNow() {
        var today = new Date();
        var dd = String(today.getDate());

        today = dd;
        return today;
    }

    function formatNumber(n) {
        // format number 1000000 to 1,234,567
        return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
    }

    function keyupblur_Currency(input2, blur) {
        // appends $ to value, validates decimal side
        // and puts cursor back in right position.
        var input = $(input2);
        // get input value
        var input_val = input.val();

        // don't validate empty input
        if (input_val === "") {
            input_val = '0.00'
        }

        // original length
        var original_len = input_val.length;

        // initial caret position
        var caret_pos = input.prop("selectionStart");

        // check for decimal
        if (input_val.indexOf(".") >= 0) {

            // get position of first decimal
            // this prevents multiple decimals from
            // being entered
            var decimal_pos = input_val.indexOf(".");

            // split number by decimal point
            var left_side = input_val.substring(0, decimal_pos);
            var right_side = input_val.substring(decimal_pos);

            // add commas to left side of number
            left_side = formatNumber(left_side);

            // validate right side
            right_side = formatNumber(right_side);

            // On blur make sure 2 numbers after decimal
            if (blur === "blur") {
                right_side += "00";
            }

            // Limit decimal to only 2 digits
            right_side = right_side.substring(0, 2);

            // join number by .
            input_val = left_side + "." + right_side;

        } else {
            // no decimal entered
            // add commas to number
            // remove all non-digits
            input_val = formatNumber(input_val);
            //			input_val = input_val;

            // final formatting
            if (blur === "blur") {
                $x = input_val.replace(/,(?=.*\.\d+)/g, '');
                if (parseInt($x) > 0) {
                    input_val = formatNumber(input_val);
                    input_val += ".00";
                }
            }
        }

        // send updated string to input
        input.val(input_val);

        // put caret back in the right position
        var updated_len = input_val.length;
        caret_pos = updated_len - original_len + caret_pos;
        input[0].setSelectionRange(caret_pos, caret_pos);
    }

    function str_padding(pad, user_str, pad_pos) {
        if (typeof user_str === 'undefined') {
            return pad;
        }
        if (pad_pos == 'l') {
            return (pad + user_str).slice(-pad.length);
        } else {
            return (user_str + pad).substring(0, pad.length);
        }
    }

    function inArray(needle, haystack) {
        var length = haystack.length;
        for (var i = 0; i < length; i++) {
            if (typeof haystack[i] == 'object') {
                if (arrayCompare(haystack[i], needle)) {
                    return true;
                }
            } else {
                if (haystack[i] == needle) {
                    return true;
                }
            }
        }
        return false;
    }

    function set_menu_active() {
        var act = '<?php echo isset($active) ? $active : ""; ?>';
        var sub_act = '<?php echo isset($sub_active) ? $sub_active : ""; ?>';

        if (act.length > 0) {
            $("#mob-" + act).addClass('active');
        }

        if (sub_act.length > 0) {
            $("#mob-" + sub_act).addClass('active');
        }
    }

    function set_akses() {
        var act = '<?php echo isset($active) ? $active : ""; ?>';
        var sub_act = '<?php echo isset($sub_active) ? $sub_active : ""; ?>';


        $.ajax({
            type: "POST",
            url: "<?php echo base_url('auth/get_akses_header'); ?>",
            data: null,
            cache: false,
            success: function(data) {
                var obj = $.parseJSON(data);

                if (parseInt(obj['res']) === 1) {
                    data_akses_header = obj['data'];
                    $.each(obj['data'], function(i, val) {
                        if (val.is_active == '0') {
                            $("#mob-" + val.code + " > a").addClass("disabled");
                        } else {
                            $("#mob-" + val.code + " > a").removeClass("disabled");
                        }


                        if (val.code == act || val.code == sub_act) {
                            var param = {
                                'akses_h_id': val.id
                            };

                            $.ajax({
                                type: "POST",
                                url: "<?php echo base_url('auth/get_akses_detail'); ?>",
                                data: param,
                                cache: false,
                                success: function(data) {
                                    var obj = $.parseJSON(data);

                                    if (parseInt(obj['res']) === 1) {
                                        data_akses_detail = obj['data'];
                                        $.each(obj['data'], function(i_d, val_d) {
                                            var element = val_d.element;
                                            var selector = val_d.selector;
                                            var selector_by = val_d.selector_by;
                                            var action_type = val_d.action_type;
                                            var is_active = parseInt(val_d.is_active);
                                            var is_active_bool = Boolean(is_active);

                                            var e = $(String(element + selector_by + selector));
                                            if (selector_by == 'data-akses') {
                                                $(String(element + '[' + selector_by + '=' + selector + ']'));
                                            }


                                            switch (action_type) {
                                                case 'disable':
                                                    e.attr("disabled", is_active_bool);
                                                    break;
                                                case 'visible':
                                                    if (is_active_bool) {
                                                        e.style.visibility = 'visible';
                                                    } else {
                                                        e.style.visibility = 'hidden';
                                                    }
                                                    break;
                                                case 'readonly':
                                                    e.readOnly = is_active_bool;
                                                    break;
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

    function set_akses_clientside() {
        $.each(data_akses_header, function(i, val) {
            if (val.is_active == '0') {
                $("#mob-" + val.code + " > a").addClass("disabled");
            } else {
                $("#mob-" + val.code + " > a").removeClass("disabled");
            }
        });

        $.each(data_akses_detail, function(i_d, val_d) {
            var element = val_d.element;
            var selector = val_d.selector;
            var selector_by = val_d.selector_by;
            var action_type = val_d.action_type;
            var is_active = parseInt(val_d.is_active);
            var is_active_bool = Boolean(is_active);

            var e = $(String(element + selector_by + selector));
            if (selector_by == 'data-akses') {
                $(String(element + '[' + selector_by + '=' + selector + ']'));
            }

            switch (action_type) {
                case 'disable':
                    e.attr("disabled", is_active_bool);
                    break;
                case 'visible':
                    if (is_active_bool) {
                        e.style.visibility = 'visible';
                    } else {
                        e.style.visibility = 'hidden';
                    }
                    break;
                case 'readonly':
                    e.readOnly = is_active_bool;
                    break;
            }

        });
    }

    function getDataSelect_is_active(elem, id = null) {
        var str = '';
        if (id != null) {
            str += '<option value="" disabled>Choose your option</option>';
        } else {
            str += '<option value="" disabled selected>Choose your option</option>';
        }

        if (id != null) {
            if (id == 1) {
                str += '<option selected value="1">ACTIVE</option>';
                str += '<option value="0">NOT ACTIVE</option>';
            } else {
                str += '<option value="1">ACTIVE</option>';
                str += '<option selected value="0">NOT ACTIVE</option>';
            }
        } else {
            str += '<option value="1">ACTIVE</option>';
            str += '<option value="0">NOT ACTIVE</option>';
        }


        elem.html(str).select2();
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
                str += '<option selected value="1">TRASHED</option>';
                str += '<option value="0">UNTRASHED</option>';
            } else {
                str += '<option value="1">TRASHED</option>';
                str += '<option selected value="0">UNTRASHED</option>';
            }
        } else {
            str += '<option value="1">TRASHED</option>';
            str += '<option value="0">UNTRASHED</option>';
        }


        elem.html(str).select2();
    }

    function getDataSelect_sex(elem, id = null) {
        var str = '';
        if (id != null) {
            str += '<option value="" disabled>Choose your option</option>';
        } else {
            str += '<option value="" disabled selected>Choose your option</option>';
        }

        if (id != null) {
            if (id == 1) {
                str += '<option selected value="Male">Male</option>';
                str += '<option value="Female">Female</option>';
            } else {
                str += '<option value="Male">Male</option>';
                str += '<option selected value="Female">Female</option>';
            }
        } else {
            str += '<option value="Male">Male</option>';
            str += '<option value="Female">Female</option>';
        }


        elem.html(str).select2();
    }

    //======================GET DATA FILTER==================//



    function getFilter_is_active() {
        var str = '';

        str += '<option value="1">ACTIVE</option>';
        str += '<option value="0">NOT ACTIVE</option>';


        $('#fil_is_active').html(str).select2({
            closeOnSelect: false
        });

        //Check All / Uncheck All
        // $('#fil_is_active').siblings('ul').prepend('<li class="sm_select_all"><span>Check All</span></li>');
    }

    function getFilter_is_trash() {
        var str = '';

        str += '<option value="1">TRASHED</option>';
        str += '<option value="0">UNTRASHED</option>';


        $('#fil_is_trash').html(str).select2({
            closeOnSelect: false
        });

        //Check All / Uncheck All
        // $('#fil_is_trash').siblings('ul').prepend('<li class="sm_select_all"><span>Check All</span></li>');
    }

    function getFilter_sex() {
        var str = '';

        str += '<option value="Male">Male</option>';
        str += '<option value="Female">Female</option>';


        $('#fil_sex').html(str).select2({
            closeOnSelect: false
        });

        //Check All / Uncheck All
        // $('#fil_sex').siblings('ul').prepend('<li class="sm_select_all"><span>Check All</span></li>');
    }

    function getFilter_department() {
        var param = null;
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('filter/getFilter_department'); ?>",
            data: param,
            dataType: "json",
            cache: false,
            success: function(data) {
                var obj = data;
                if (parseInt(obj['res']) === 1) {
                    var str = '';

                    $.each(obj['data'], function(i, val) {
                        str += '<option value="' + val.id + '">' + val.code + ' - ' + val.name + '</option>';
                    });

                    $('#fil_department').html(str).select2({
                        closeOnSelect: false
                    });

                    //Check All / Uncheck All
                    // $('#fil_department').siblings('ul').prepend('<li class="sm_select_all"><span>Check All</span></li>');

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

    function getFilter_division(id_department = null) {
        var param = {
            'id_department': id_department
        };
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('filter/getFilter_division'); ?>",
            data: param,
            dataType: "json",
            cache: false,
            success: function(data) {
                var obj = data;
                if (parseInt(obj['res']) === 1) {
                    var str = '';

                    $.each(obj['data'], function(i, val) {
                        str += '<option value="' + val.id + '">' + val.code + ' - ' + val.name + '</option>';
                    });

                    $('#fil_division').html(str).select2({
                        closeOnSelect: false
                    });

                    //Check All / Uncheck All
                    // $('#fil_division').siblings('ul').prepend('<li class="sm_select_all"><span>Check All</span></li>');

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

    function getFilter_position(id_department = null, id_division = null) {
        var param = {
            'id_department': id_department,
            'id_division': id_division
        };
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('filter/getFilter_position'); ?>",
            data: param,
            dataType: "json",
            cache: false,
            success: function(data) {
                var obj = data;
                if (parseInt(obj['res']) === 1) {
                    var str = '';

                    $.each(obj['data'], function(i, val) {
                        str += '<option value="' + val.id + '">' + val.code + ' - ' + val.name + '</option>';
                    });

                    $('#fil_position').html(str).select2({
                        closeOnSelect: false
                    });

                    //Check All / Uncheck All
                    // $('#fil_position').siblings('ul').prepend('<li class="sm_select_all"><span>Check All</span></li>');

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

    function getFilter_position_parent(id_department = null, id_division = null) {
        var param = {
            'id_department': id_department,
            'id_division': id_division
        };
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('filter/getFilter_position'); ?>",
            data: param,
            dataType: "json",
            cache: false,
            success: function(data) {
                var obj = data;
                if (parseInt(obj['res']) === 1) {
                    var str = '';

                    $.each(obj['data'], function(i, val) {
                        str += '<option value="' + val.id + '">' + val.code + ' - ' + val.name + '</option>';
                    });

                    $('#fil_position_parent').html(str).select2({
                        closeOnSelect: false
                    });

                    //Check All / Uncheck All
                    // $('#fil_position_parent').siblings('ul').prepend('<li class="sm_select_all"><span>Check All</span></li>');

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

    function getFilter_role() {
        var param = null;
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('filter/getFilter_role'); ?>",
            data: param,
            dataType: "json",
            cache: false,
            success: function(data) {
                var obj = data;
                if (parseInt(obj['res']) === 1) {
                    var str = '';

                    $.each(obj['data'], function(i, val) {
                        str += '<option value="' + val.id + '">' + val.code + ' - ' + val.name + '</option>';
                    });

                    $('#fil_role').html(str).select2({
                        closeOnSelect: false
                    });

                    //Check All / Uncheck All
                    // $('#fil_role').siblings('ul').prepend('<li class="sm_select_all"><span>Check All</span></li>');

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

    //======================GET DATA FILTER==================//
</script>