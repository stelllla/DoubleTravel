var $loginInput = document.getElementsByClassName('login-input')[0];
var $login = document.getElementsByClassName('login')[0];

$loginInput.addEventListener('focus', function () {
    $login.classList.add('focused');
});

 var $email = document.getElementById('email');
 var $pass = document.getElementById('password');
 var $firstName = document.getElementById('first_name');
 var $lastName = document.getElementById('last_name');
 var $title = document.getElementById('login-title');

    var $signup = document.getElementById('signup-btn');
    $signup.addEventListener('click', function (e) {

        if ($title.innerHTML == "Login") {
            $title.innerHTML = "Sign up";
            $signup.innerHTML = "Login";

            $firstName.classList.remove('hidden');
            $lastName.classList.remove('hidden');
        } else {
            $title.innerHTML = "Login";
            $signup.innerHTML = "Sign up now";

            $firstName.classList.add('hidden');
            $lastName.classList.add('hidden');
        }
    });

 $login.addEventListener('submit', function(e) {

     $email.classList.remove('has-error');
     $pass.classList.remove('has-error');

    var regex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if (!$email.value || !regex.test($email.value)) {
        $email.classList.add('has-error');
        return false;
    }
    if (!$pass.value) {
        $pass.classList.add('has-error');
        return false;
    }
     if (!$firstName.value) {
         $firstName.classList.add('has-error');
         return false;
     }
     if (!$lastName.value) {
         $lastName.classList.add('has-error');
         return false;
     }
    $login.classList.remove('focused').add('loading');
});