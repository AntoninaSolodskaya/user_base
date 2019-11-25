<?php
/*
Plugin Name: Additional fields for the profile
Description: Adds new fields to the user profile
Version: 1.0
*/
add_action('show_user_profile', 'my_profile_new_fields_add');
add_action('edit_user_profile', 'my_profile_new_fields_add');
add_action('personal_options_update', 'my_profile_new_fields_add');

add_action('personal_options_update', 'my_profile_new_fields_update');
add_action('edit_user_profile_update', 'my_profile_new_fields_update');

add_action('personal_options_update', 'my_profile_new_fields_encrypt');
add_action('edit_user_profile_update', 'my_profile_new_fields_encrypt');


function my_profile_new_fields_add(){ 
    global $user_ID;
    // $pass = md5('australia');
    $config = array(
        "digest_alg" => "sha512",
        "private_key_bits" => 4096,
        "private_key_type" => OPENSSL_KEYTYPE_RSA,
    );
    
    function decrypt($string) {
        
        $res = openssl_pkey_new($config);
        
        openssl_pkey_export($res, $private_key);
        
        $public_key = openssl_pkey_get_details($res);
        $public_key = $public_key["key"];
        // $string = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, base64_decode($string), MCRYPT_MODE_ECB));
        // return $string;
        openssl_private_decrypt($string, $decrypted, $private_key);
        return $decrypted;
    }

        $phone = decrypt(get_user_meta( $user_ID, 'user_phone', 1 ));
        echo "Phone: " . $phone;
        var_dump(get_user_meta( $user_ID, 'user_phone', 1 ));
        $adress = decrypt(get_user_meta( $user_ID, 'user_adress', 1 ));
    
?>
<style>
    input{
        width: 50%;
    }
</style>

<h3>Additional data</h3>
<p>Your Phone 
    <label>
        <input type="text" name="custom_input[user_phone]" value="<?php echo $phone;?>">
    </label>
</p>
<p>Your Adress
    <label>
        <input type="text" name="custom_input[user_adress]" value="<?php echo $adress; ?>">
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

function my_profile_new_fields_encrypt() {
    global $user_ID;
    // $pass = md5('australia');

    $config = array(
        "digest_alg" => "sha512",
        "private_key_bits" => 4096,
        "private_key_type" => OPENSSL_KEYTYPE_RSA,
    );
    
    function encrypt($string) {
        $res = openssl_pkey_new($config);
        
        $public_key = openssl_pkey_get_details($res);
        $public_key = $public_key["key"];
        openssl_public_encrypt($string, $encrypted, $public_key);
        // $encrypted_hex = bin2hex($encrypted);
        echo "Encrypted: " . $encrypted;
        return $encrypted;
    }
    // function encrypt($string, $key) {
    //     $string = rtrim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $string, MCRYPT_MODE_ECB)));
    //     return $string;
    // }
    foreach($_POST['custom_input'] as $key => $val)
    {
        update_user_meta( $user_ID, $key, encrypt($val) );
    } 

}

?>
