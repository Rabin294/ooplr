<?php
require_once 'core/init.php';

$user = new User();

if(!$user->isLoggedIn()){
    Redirect::to('index.php');
}

if(Input::exists()){
    if(Token::check(Input::get('token'))){
      
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'name' => array(
                'required' => true,
                'min'=> 2,
                'max' =>50
            )
        ));
        if($validation->passed()){
            try {
                $user->update(array(
                    'name' => Input::get('name')
                ));

                Session::flash('home', 'You details has been updated.');
                Redirect::to('index.php');


            } catch (Exception $e) {
                die($e->getMessage());
            }
        }else{
            foreach($validation->errors() as $error){
                echo $error, '<br>';
            }
        }
    }
}

?>

 
<form action="" method="POST">
   
    <div class="field">
        <label for="name">Name</label>
        <input type="text" name="name" id="name" value="<?php echo escape($user->data()->name)?>" >

        <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
        <input type="submit" value="Update">
    </div>
    
    
   

 </form>


 <!-- <div class="field">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" value="<?php echo escape(Input::get('username'))?>" autocomplete="off">
    </div>
    <div class="field">
        <label for="password">Choose a password</label>
        <input type="password" name="password" id="password">
    </div>
    <div class="field">
        <label for="password_again">Enter your password again</label>
        <input type="password" name="password_again" id="password_again">
    </div> -->