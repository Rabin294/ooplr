<?php
require_once 'core/init.php';

// Enabling error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// echo Config::get('mysql/host');

// DB::getInstance();


 $user = DB::getInstance()->update('users',3, array(
     'password' => 'newpassword',
     'name' => 'New Name'
   
 ));


if(Session::exists('success')){
    echo Session::flash ('success');
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
   
    
</body>
</html>