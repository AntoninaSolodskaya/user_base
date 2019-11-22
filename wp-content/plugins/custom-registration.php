<?php

/*
  Plugin Name: Custom Registration
  Description: Updates user rating based on number of posts.
  Version: 1.0
 */

function custom_registration_function() {
    if (isset($_POST['submit'])) {
        registration_validation(
        $_POST['username'],
        $_POST['password'],
        $_POST['email']
    );

// sanitize user form input
        global $username, $password, $email, $adress, $phone;
        $username = sanitize_user($_POST['username']);
        $password = esc_attr($_POST['password']);
        $email = sanitize_email($_POST['email']);
     
// call @function complete_registration to create the user
// only when no WP_error is found
        complete_registration(
        $username,
        $password,
        $email
        );
    }

    registration_form(
        $username,
        $password,
        $email
    );
}

function registration_form( $username, $password, $email ) {
echo '
    <style>
        .input-block {
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            flex-direction: column;
        }
        label {
            color: #ffffff;
        }
        input{
            width: 100%;
        }
        button {
            margin-bottom: 15px;
        }
    </style>
';

echo '
   
    <form action="' . $_SERVER['REQUEST_URI'] . '" method="post">
        <div class="input-block">
            <label for="username">Username <strong>*</strong></label>
            <input type="text" name="username" value="' . (isset($_POST['username']) ? $username : null) . '">
        </div>

        <div class="input-block">
            <label for="password">Password <strong>*</strong></label>
            <input type="password" name="password" value="' . (isset($_POST['password']) ? $password : null) . '">
        </div>

        <div class="input-block">
            <label for="email">Email <strong>*</strong></label>
            <input type="text" name="email" value="' . (isset($_POST['email']) ? $email : null) . '">
        </div>

        <button type="submit" name="submit" value="Register">Register</button>
        </form>
';
}

function registration_validation( $username, $password, $email )  {
    global $reg_errors;
    $reg_errors = new WP_Error;

    if ( empty( $username ) || empty( $password ) || empty( $email ) ) {
        $reg_errors->add('field', 'Required form field is missing');
    }

    if ( strlen( $username ) < 4 ) {
        $reg_errors->add('username_length', 'Username too short. At least 4 characters is required');
    }

    if ( username_exists( $username ) )
        $reg_errors->add('user_name', 'Sorry, that username already exists!');

    if ( !validate_username( $username ) ) {
        $reg_errors->add('username_invalid', 'Sorry, the username you entered is not valid');
    }

    if ( strlen( $password ) < 5 ) {
        $reg_errors->add('password', 'Password length must be greater than 5');
    }

    if ( !is_email( $email ) ) {
        $reg_errors->add('email_invalid', 'Email is not valid');
    }

    if ( email_exists( $email ) ) {
        $reg_errors->add('email', 'Email Already in use');
    }

    if ( is_wp_error( $reg_errors ) ) {

        foreach ( $reg_errors->get_error_messages() as $error ) {
            echo '<div style=color:red;>';
            echo '<strong>ERROR</strong>:';
            echo $error . '<br/>';

            echo '</div>';
        }
    }
}

function complete_registration() {
    global $reg_errors, $username, $password, $email;
    if ( count($reg_errors->get_error_messages()) < 1 ) {
        $userdata = array(
        'user_login' => $username,
        'user_email' => $email,
        'user_pass' =>  $password
		);
        $user = wp_insert_user( $userdata );
        echo 'Registration complete. Go to <a style=color:#ffffff; href="' . get_site_url() . '/wp-login.php">login page</a>.';   
	}
}
