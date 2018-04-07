$(document).ready( function () {
    //var name = $('#name').val();
    $('[data-toggle="tooltip"]').tooltip();
    var d = {
        name: $('#name').val(),
            email: $('#email').val(),
            message: $('#message').val()
    };
    function response (data, form) {
        $(form).before(data.msg);
        if (data.hasOwnProperty('fields')) {
            for (var field in data.fields) {
                if (data.fields.hasOwnProperty(field)) {
                    var s = data.fields[field];
                    $('#' + data.fields[field]).parent().addClass('has-error');
                }
            }
        }
        if (data.hasOwnProperty('post')) {
            $('.comment').last().after(data.post);
        }
        if (data.hasOwnProperty('logged_in')) {
            setTimeout(function () {
                location.reload();
            }, 1500);
        }
    }
    $('#send').click(function (e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: base_url+"gb/send",
            dataType: 'json',
            data: { name: $('#name').val(), email: $('#email').val(), message: $('#message').val() },
            success: function (data) {
                response(data, '.container form');
            },
            error: function( data, status, error ) {
                console.log(data);
                console.log(status);
                console.log(error);
            }
        })
    });
    $('input, textarea').focus(function () {
        if ($(this).parent().hasClass('has-error')) {
            $(this).parent().removeClass('has-error');
        }
    });
    $('#login').click(function (e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: base_url+"gb/login",
            dataType: 'json',
            data: { username: $('#username').val(), password: $('#password').val() },
            success: function (data) {
                response(data, '#login-modal form')
            },
            error: function( data, status, error ) {
                console.log(data);
                console.log(status);
                console.log(error);
            }
        })
    });
    $('.delete').click(function (e) {
        e.preventDefault();
        var id = this.id.slice(4);
        var elem = $(this);
        $.ajax({
            type: "POST",
            url: base_url+"gb/delete/"+id,
            dataType: 'json',
            success: function (data) {
                response(data, '#posts');
                elem.parents('.comment').remove();
            },
            error: function( data, status, error ) {
                console.log(data);
                console.log(status);
                console.log(error);
            }
        })
    })
});