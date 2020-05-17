<?php

// 0) Register new user

$saveUser = 'user.txt';
// 1) validate user email
$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
if (!$email) {
    echo "Wrong user email";
    include_once 'register-form.php';
    exit;
};
require_once 'info.php';
if(array_key_exists($email, $users)){
    
    echo "Sorry, user with this email has already been registered";
    include_once 'register-form.php';
    exit;
}

// 2) check user password
$password = filter_var($_POST['password'], FILTER_DEFAULT);
if (empty($password) || mb_strlen($password) < 10) {
    echo "Wrong or empty user password";
    include_once 'register-form.php';
    exit;
}


// 3) Generte password hash 
$passHash = password_hash(
    $password,
    PASSWORD_DEFAULT
);

if (false === $passHash) {
    echo "System error occured...";
    include_once 'register-form.php';
    exit;
}


/**
 * 4) Create and save user accout
 *      Save it into the file...
 */
file_put_contents( $saveUser,
    implode(';', [
        "$email", "$passHash\n"
    ]),
    FILE_APPEND | LOCK_EX
);

// 5) redirect to the login form
header("HTTP/1.1 302 Redirect");
header("Location: login-form.php");

