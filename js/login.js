$('.login-input').on('focus', function() {
    $('.login').addClass('focused');
});

$('.login').on('submit', function(e) {
    e.preventDefault();
    var $email = $('#email');
    var $pass = $('#password');

    var regex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if (!regex.test($email.val())) {
        $email.addClass('has-error');
    }
    $('.login').removeClass('focused').addClass('loading');
});