<?php
session_start();

$GLOBALS['config']=array(
    'mysql'=>array(
        'host'=> 'localhost',
        'username'=> 'root',
        'password'=> '',
        'db'=>'OOP'

    ),
    'remember'=>array(
        'cookie_name'=>'hash',
        'cookie_expiry'=>604800
    ),
    'session'=>array(
        'session_name'=>'user',
        'token_name'=>'token'
    )
);

spl_autoload_register(function($class){
    require_once 'classes/' . $class . '.php';
});
require_once 'functions/sanitize.php';

if(Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))){
    $hash = Cookie::get(Config::get('remember/cookie_name'));
    $hashCkeck = DB::getInstance()->get('users_session', array(
        'hash', '=', $hash));

        if($hashCkeck->count()){
           $user = new User($hashCkeck->first()->user_id);
           $user->login();
        }
}