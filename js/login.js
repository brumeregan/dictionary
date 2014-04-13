/**
 * Created by Korvin on 10.04.14.
 */

function switchLoginRegistration()
{
    $('#login').toggleClass('hidden');
    $('#register').toggleClass('hidden');
    $("#login_form, #password_form").trigger('reset');

}


function login2() { //Ajax отправка формы
    var msg = $("#login_form").serialize()
    msg += "&submit=login";
   $.ajax({
        type: "POST",
        url: "./auth.php",
        data: msg,
       error:  function(xhr, str){
           alert("Возникла ошибка!");
       },
        success: function(data) {
            if (data == "error") {
                $('.output').addClass('alert-danger');
                $('.output').each(function(e){(this).innerText = "Неправильные логин или/и пароль.";});
            }
            else
                document.location.href = './dictionary.php';
        }

    });
}

function register() { //Ajax отправка формы
    var msg = $("#register_form").serialize()
    msg += "&submit=register";
    $.ajax({
        type: "POST",
        url: "./auth.php",
        data: msg,
        error:  function(xhr, str){
            alert("Возникла ошибка!");
        },
        success: function(data) {
            if (data == "successful")
            {
                switchLoginRegistration();
                $('.output').removeClass('alert-danger').addClass('alert-success');
                $('.output').each(function(e){(this).innerText = "Регистрация прошла успешно!";});
            }
            else
            {
                $('.output').addClass('alert-danger');
                $('.output').each(function(e){(this).innerText = $.trim(data);});
            }
        }

    });
}