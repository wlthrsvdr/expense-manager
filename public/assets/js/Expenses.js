$(document).ready(function () {

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: "get_categories",
        data: "data",
        dataType: "json",
        success: function (data) {

            var expense_category = '';


            for (var i = 0, len = data.length; i < len; ++i) {
                expense_category += '<option value="' + data[i].category + '"' +
                    ((data[i].category === $('#category_field_hidden').val()) ?
                        'selected="selected"' : '') +
                    '> ' + data[i]
                        .category +
                    '</option>';

            }

            $('#expense_category_id').append(expense_category);


        },
        error: function (err) {
            console.log(err);
        }
    });


});


$('#update_expense_form').submit(function (e) {
    e.preventDefault();

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: "update_expense",
        data: $('#update_expense_form').serialize(),
        dataType: "json",
        success: function (res) {
            $('#update_expense_modal').modal('hide');
            location.reload();
        },
        error: function (err) {
            // $('#update_expense_modal').modal('hide');

        }
    });

});

function getExpenseInfo(id) {

    $('#update_expense_modal').modal('show');


    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: "get_expense/" + id,
        data: "data",
        dataType: "json",
        success: function (res) {
            $('#expense_id').val(res.id);
            $('#category_field_hidden').val(res.expense_category);
            $('#amount_id').val(res.amount);
            $('#date_id').val(res.date);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "GET",
                url: "get_categories",
                data: "data",
                dataType: "json",
                success: function (data) {

                    var expense_category = '';


                    for (var i = 0, len = data.length; i < len; ++i) {
                        expense_category += '<option value="' + data[i].category + '"' +
                            ((data[i].category === res.expense_category) ?
                                'selected="selected"' : '') +
                            '> ' + data[i]
                                .category +
                            '</option>';

                    }

                    $('#expense_category_update_id').append(expense_category);


                },
                error: function (err) {
                    console.log(err);
                }
            });


        },
        error: function (err) {
            console.log(err, "error");
        }
    });
}

function deleteExpense(id) {

    $('#delete_prompt_modal').modal('show');

    $("#yes_delete").click(function () {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: "delete_expense/" + id,
            data: "data",
            dataType: "json",
            success: function (res) {
                console.log(res, "res")
                $('#delete_prompt_modal').modal('hide');
                location.reload();
            },
            error: function (err) {
                console.log(err, "err")
            }
        });
    });

    $("#cancel_delete").click(function () {
        $('#delete_prompt_modal').modal('hide');
    });

}






