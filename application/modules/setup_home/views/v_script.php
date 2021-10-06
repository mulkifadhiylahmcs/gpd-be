<script type="text/javascript">
    $(document).ready(function() {

        $('#fil_start_date').datepicker(
            def_date_obj
        );

        $('#fil_end_date').datepicker(
            def_date_obj
        );

        var inputFrom = $('#fil_start_date').val();
        var inputTo = $('#fil_end_date').val();

        $('#fil_start_date').on('change', function() {
            inputFrom = $(this).val();
            if (new Date($(this).val()) > new Date(inputTo)) {
                $.alert({
                    title: 'FAILED!',
                    content: 'You must set  Start Date less than End Date',
                    type: 'red',
                    draggable: false,
                });


                $(this).val(inputTo);
                inputFrom = inputTo;
            }
        });

        $('#fil_end_date').on('change', function() {
            inputTo = $(this).val();
            if (new Date($(this).val()) < new Date(inputFrom)) {
                $.alert({
                    title: 'FAILED!',
                    content: 'You must set End Date less than Start Date',
                    type: 'red',
                    draggable: false,
                });

                $(this).val(inputFrom);
                inputTo = inputFrom;
            }
        });


    });
</script>