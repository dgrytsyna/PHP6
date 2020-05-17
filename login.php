<?php

// 0) Start sessions at the top of the our script
session_start();

// 1) Register new user
// $_POST['email'] = 'user@email.com';
// $_POST['pass'] = 'qwerty';

$saveUser = 'user.txt';
// 2) validate user email
$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
if (!$email) {
    echo "Wrong user email";
    include_once "login-form.php";
    exit;
}

// 3) check user password
$password = filter_var($_POST['password'], FILTER_DEFAULT);
if (empty($password) || mb_strlen($password) < 10) {
    echo "Wrong or empty user password";
    include_once "login-form.php";
    exit;
}

// 4) Find user account info
require_once 'info.php';
//print_r($users);
if (array_key_exists($email, $users)) {
    if (false === password_verify($password, $users[$email])) {
        echo "Sorry, wrong password";
        include_once "login-form.php";
    exit;
    } else {
      header("Location: logged.php");};
   
} else {
    echo "Sorry, but user with this email address not registered";
    include_once "login-form.php";
    exit;
}

/**
 * 5) 
 * RE-hash and RE-save user password again
 * IT will be your's HOME WORK
 * 
 * Use password_needs_rehash
 * See more at: https://www.php.net/manual/ru/function.password-needs-rehash
 */
$options = array('cost' => 11);

// Проверка сохраненного хеша с помощью пароля
if (password_verify($password, $users[$email])) {
    // Проверяем, не нужно ли использовать более новый алгоритм
    // или другую алгоритмическую стоимость
    if (password_needs_rehash($users[$email], PASSWORD_DEFAULT, $options)) {
        // Если таки да, перехешируем и сохраняем новый хеш
        $newHash = password_hash($password, PASSWORD_DEFAULT, $options);
        $users[$email] = $newHash;
        file_put_contents($saveUser, '');
        foreach($users as $email => $pass){
            file_put_contents( $saveUser,
            implode(';', [
                "$email", "$pass\n"
            ]),
            FILE_APPEND | LOCK_EX
        );
        }
    }
}
 
 /**
  * 6) Save a login state in the session
  * Sessions tomorrow
  * 
  * Output "You are logged in on the page"
  * 
  */
 $_SESSION['logged_in'] = 'yes'; 
 $_SESSION['email'] = $email;

