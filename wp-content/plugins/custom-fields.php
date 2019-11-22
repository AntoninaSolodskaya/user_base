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

function my_profile_new_fields_add(){ 
global $user_ID;

$phone = get_user_meta( $user_ID, "user_phone", true );
$adress = get_user_meta( $user_ID, "user_adress", true );
$male = get_user_meta( $user_ID, "user_male", true );
$female = get_user_meta( $user_ID, "user_female", true );
$status = get_user_meta( $user_ID, "user_status", true );
?>

<style>
    input{
        width: 50%;
    }
</style>

    <h3>Additional data</h3>
    <table class="form-table">
        <tr>
            <th><label>Phone</label></th>
            <td>
                <input type="text" name="custom[user_phone]" value="<?php echo $phone ?>"><br>
            </td>
        </tr>
        <tr>
            <th><label>Adress</label></th>
            <td>
                <input type="text" name="custom[user_adress]" value="<?php echo $adress ?>"><br>
            </td>
        </tr>
        <tr>
            <th><label>Male</label></th>
            <td>
                <input type="radio" name="user_male" value="male" <?php echo checked( $male, 'male' ); ?><br>
            </td>
        </tr>
        <tr>
            <th><label>Female</label></th>
            <td>
                <input type="radio" name="user_female" value="female" <?php echo checked( $female, 'female' ); ?><br>
            </td>
        </tr>
        <tr>
            <th><label>Family Status</label></th>
            <td>
                <input type="text" name="custom[user_status]" value="<?php echo $status ?>"><br>
            </td>
        </tr>
    </table>
<?php
}

function my_profile_new_fields_update() {
global $user_ID;
$config = array(
    "digest_alg" => "sha512",
    "private_key_bits" => 4096,
    "private_key_type" => OPENSSL_KEYTYPE_RSA,
);

    foreach($_POST['custom'] as $key => $val)
    {
        
        $val= empty($val) ? '' : $val;
        $res = openssl_pkey_new($config);
        openssl_pkey_export($res, $private_key);
        $public_key = openssl_pkey_get_details($res);
        $public_key = $public_key["key"];
        $text = $_POST['custom'];
        openssl_public_encrypt($val, $encrypted, $public_key);
        $encrypted_hex = bin2hex($encrypted);
        update_user_meta($user_ID, $key, $encrypted_hex);
            
       
        openssl_private_decrypt($encrypted, $decrypted, $private_key);
        
    } 
    update_user_meta( $user_ID, "user_male", $_POST['user_male'] );
    update_user_meta( $user_ID, "user_female", $_POST['user_female'] );
}
?>