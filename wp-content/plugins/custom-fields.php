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

add_action('personal_options_update', 'my_profile_new_fields_encode');
add_action('edit_user_profile_update', 'my_profile_new_fields_encode');



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
    <label>
        <input type="text" name="extra[user_phone]" value="<?php echo get_user_meta($user_ID, 'user_phone', 1); ?>">
    </label>
</p>
<p>Your Adress
    <label>
        <input type="text" name="extra[user_adress]" value="<?php echo get_user_meta($user_ID, 'user_adress', 1); ?>">
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
}

function my_profile_new_fields_encode() {
    global $user_ID;
    $config = array(
        "digest_alg" => "sha512",
        "private_key_bits" => 4096,
        "private_key_type" => OPENSSL_KEYTYPE_RSA,
    );

    foreach($_POST['custom'] as $key => $val)
    {
        // echo $key . '=&gt;' . $val . '<br>';	
        $val= !empty($val) ? '' : $val;
        $res = openssl_pkey_new($config);
        openssl_pkey_export($res, $private_key);
        $public_key = openssl_pkey_get_details($res);
        $public_key = $public_key["key"];
        openssl_public_encrypt($val, $encrypted, $public_key);
        $encrypted_hex = bin2hex($encrypted);
    
        update_user_meta( $user_ID, "user_phone", $encrypted_hex );
        update_user_meta( $user_ID, "user_adress", $encrypted_hex );
        openssl_private_decrypt($encrypted, $decrypted, $private_key);
        
    } 
}
?>