<?php
require_once 'core/init.php';

// Enabling error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);



if(Session::exists('home')){
    echo '<p>' . Session::flash ('home') . '</p>';
}

$user = new User();
if ($user->isLoggedIn()) {
?>
    <p>Hello <a href="#"><?php echo escape($user->data()->username); ?></a></p>
    <ul>
        <li><a href="logout.php">Log out</a></li>
        <li><a href="update.php">Update details</a></li>
    </ul>
<?php
} else {
    echo '<p> You need to <a href="login.php">log in</a> or <a href="register.php">register</a></p>';
}


?>


