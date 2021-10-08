$(document).ready(function () {




});


$('#update_category_form').submit(function (e) {
    e.preventDefault();

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: "update_category",
        data: $('#update_category_form').serialize(),
        dataType: "json",
        success: function (res) {
            $('#update_category_modal').modal('hide');
            location.reload();
        },
        error: function (err) {
            $('#update_category_modal').modal('hide');
        }
    });

});

function getCategoryInfo(id) {

    $('#update_category_modal').modal('show');

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: "get_category/" + id,
        data: "data",
        dataType: "json",
        success: function (res) {

            $('#category_id').val(res.id);
            $('#category_info').val(res.category);
            $('#description_id').val(res.descriptions);


        },
        error: function (err) {
        
            console.log(err, "error");
        }
    });
}

function deleteCategory(id) {
    $('#delete_prompt_modal').modal('show');

    $("#yes_delete").click(function () {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: "delete_category/" + id,
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






