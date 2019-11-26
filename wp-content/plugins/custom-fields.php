<?php
/*
Plugin Name: Additional fields for the profile
Description: Adds new fields to the user profile
Version: 1.0
*/
add_action('show_user_profile', 'my_profile_new_fields_add');
add_action('edit_user_profile', 'my_profile_new_fields_add');

add_action('personal_options_update', 'my_profile_new_fields_update');
add_action('edit_user_profile_update', 'my_profile_new_fields_update');

add_action('user_profile_update_errors', 'validate_user_meta');

function my_profile_new_fields_add(){ 
    global $user_ID;
?>
<style>
    input{
        width: 50%;
    }
</style>

<h3>Additional data</h3>
<p>Your Phone 
    <label for="phone"><?php esc_html_e( 'Phone number', 'crf' ); ?>>
        <input type="number" id="phone" name="custom_input[user_phone]" value="<?php echo get_user_meta( $user_ID, 'user_phone', 1 );?>">
    </label>
</p>
<p>Your Adress
    <label for="adress"><?php esc_html_e( 'Adress field', 'crf' ); ?>>
        <input type="text" id="adress" name="custom_input[user_adress]" value="<?php echo get_user_meta( $user_ID, 'user_adress', 1 ); ?>">
    </label>
</p>
<p>Gender<?php $gender = get_user_meta($user_ID, 'gender_user', 1); ?>
    <label>
        <input type="radio" name="custom[gender_user]" value="male" <?php checked( $gender, 'male' ); ?> /> 
        male
    </label>
    <label>
        <input type="radio" name="custom[gender_user]" value="female" <?php checked( $gender, 'female' ); ?> /> 
        female
    </label>
</p>
<p>Status<?php $status = get_user_meta($user_ID, 'status_user', 1); ?>
    <label>
        <input type="radio" name="custom[status_user]" value="married" <?php checked( $status, 'married' ); ?> /> 
        married
    </label>
    <label>
        <input type="radio" name="custom[status_user]" value="unmarried" <?php checked( $status, 'unmarried' ); ?> /> 
        unmarried
    </label>
</p>

<?php
}

function my_profile_new_fields_update() {
    global $user_ID;    
    foreach($_POST['custom'] as $key => $val)
    {
        $val= empty($val) ? '' : $val;
        update_user_meta($user_ID, $key, $val);
    } 
    foreach($_POST['custom_input'] as $key => $val)
    {   
        update_user_meta( $user_ID, $key, $val );
    } 
}

function validate_user_meta($errors){
    $adress = filter_var(trim($_POST['custom_input']['user_adress']), FILTER_SANITIZE_STRING);
    $phone = filter_var(trim($_POST['custom_input']['user_phone']), FILTER_SANITIZE_STRING);

    if ( mb_strlen($adress) < 3 || mb_strlen($adress) > 30 ) {
        $errors->add( 'adress_field_error', __( '<strong style="color:red;">ERROR</strong>: Invalid adress length.', 'crf' ) );
    }

    if ( mb_strlen($phone) < 6 || mb_strlen($phone) > 20 ) {
        $errors->add( 'phone_number_error', __( '<strong style="color:red;">ERROR</strong>: Invalid phone length.', 'crf' ) );
    }
    
}

?>
