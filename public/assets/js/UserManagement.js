$(document).ready(function () {


    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: "get_user_role",
        data: "data",
        dataType: "json",
        success: function (data) {

            var user_roles = '';


            for (var i = 0, len = data.length; i < len; ++i) {
                user_roles += '<option value="' + data[i].role + '"' +
                    ((data[i].role === $('#role_field_hidden').val()) ?
                        'selected="selected"' : '') +
                    '> ' + data[i]
                        .role +
                    '</option>';

            }

            $('#user_role_id').append(user_roles);


        },
        error: function (err) {
            console.log(err);
        }
    });

});


$('#update_user_form').submit(function (e) {
    e.preventDefault();

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: "update_user",
        data: $('#update_user_form').serialize(),
        dataType: "json",
        success: function (res) {
            $('#update_user_modal').modal('hide');
            location.reload();
        },
        error: function (err) {

        }
    });

});

function getUserInfo(id) {

    $('#update_user_modal').modal('show');

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: "get_user/" + id,
        data: "data",
        dataType: "json",
        success: function (res) {

            $('#users_id').val(res.id);
            $('#update_name').val(res.name);
            $('#update_email').val(res.email);
            $('#update_password').val(res.password);
            $('#update_user_role').val(res.user_role);
      

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "GET",
                url: "get_user_role",
                data: "data",
                dataType: "json",
                success: function (data) {

                    var user_roles = '';


                    for (var i = 0, len = data.length; i < len; ++i) {
                        user_roles += '<option value="' + data[i].role + '"' +
                            ((data[i].role === res.user_role) ?
                                'selected="selected"' : '') +
                            '> ' + data[i]
                                .role +
                            '</option>';

                    }

                    $('#user_role_update_id').append(user_roles);


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

function deleteUser(id) {
  
    $('#delete_prompt_modal').modal('show');

    $("#yes_delete").click(function () {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: "delete_user/" + id,
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




