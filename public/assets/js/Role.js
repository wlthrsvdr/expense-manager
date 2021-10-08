$(document).ready(function () {




});


$('#update_role_form').submit(function (e) {
    e.preventDefault();

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: "update_role",
        data: $('#update_role_form').serialize(),
        dataType: "json",
        success: function (res) {
            $('#update_role_modal').modal('hide');
            location.reload();
        },
        error: function (err) {

        }
    });

});

function getRoleInfo(id) {

    $('#update_role_modal').modal('show');

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: "get_role/" + id,
        data: "data",
        dataType: "json",
        success: function (res) {

            $('#role_id').val(res.id);
            $('#role_info').val(res.role);
            $('#description_id').val(res.description);


        },
        error: function (err) {
            console.log(err, "error");
        }
    });
}

function deleteRole(id) {
    console.log(id, "iddd")
    $('#delete_prompt_modal').modal('show');

    $("#yes_delete").click(function () {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: "delete_role/" + id,
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






