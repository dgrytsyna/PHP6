<?php
$info = file_get_contents('user.txt');
$account = explode("\n", $info);
$users = array();
foreach($account as $us){
    list($em, $pas) = explode(';', $us);
    $users[$em] = $pas;
  
};