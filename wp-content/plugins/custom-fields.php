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
$userID = get_current_user_id();
$adress = get_user_meta( $userID, 'user_adress', 1 );
$phone = get_user_meta( $userID, 'user_phone', 1 );
$gender = get_user_meta( $userID, 'gender_user', 1 );
$status = get_user_meta( $userID, 'status_user', 1 );

$file = fopen('file:///D:/keys/privkey.pem', 'r');
$key = fread($file, 8192);
fclose($file);

openssl_private_decrypt(base64_decode($phone), $decrypt, $key);
openssl_private_decrypt(base64_decode($adress), $decrypt_adress, $key);
openssl_private_decrypt(base64_decode($gender), $decrypt_gender, $key);
openssl_private_decrypt(base64_decode($status), $decrypt_status, $key);
?>
<style>
    input {
        width: 50%;
    }
    label {
        margin-right: 20px;
    }
</style>

<h3>Additional data</h3>

<table class="form-table">
    <tr>
        <th><label>Your Phone</label></th>
        <td>
            <input type="text" name="custom[user_phone]" class="regular-text" value="<?php echo $decrypt; ?>"  />
        </td>
    </tr>
    <tr>
        <th><label>Your Adress</label></th>
        <td>
        <input type="text" name="custom[user_adress]" class="regular-text" value="<?php echo $decrypt_adress; ?>">
        </td>
    </tr>
    <tr>
        <th><label>Gender<?php echo $decrypt_gender ?></label></th>
        <td>
            <input type="radio" name="custom[gender_user]" class="tog" value="male" <?php checked( $decrypt_gender, 'male' ); ?> /> 
            <label>male</label>
        
            <input type="radio" name="custom[gender_user]" value="female" <?php checked( $decrypt_gender, 'female' ); ?> /> 
            <label>female</label>
        </td>
    </tr>
    <tr>
        <th><label>Status<?php echo $decrypt_status; ?></label></th>
        <td>
            <input type="radio" name="custom[status_user]" value="married" <?php checked( $decrypt_status, 'married' ); ?> /> 
            <label>married</label>
            <input type="radio" name="custom[status_user]" value="unmarried" <?php checked( $decrypt_status, 'unmarried' ); ?> /> 
            <label>unmarried</label>
        </td>
    </tr>
</table>

<?php
}
function my_profile_new_fields_update() {

$file = fopen('file:///D:/keys/pubkey.pem', 'r');
$pubkey = fread($file, 8192);
fclose($file);

$pk  = openssl_get_publickey($pubkey);
$userID = get_current_user_id();

foreach($_POST['custom'] as $key => $val)
    { 
        openssl_public_encrypt($val, $encrypted, $pk);
        update_user_meta($userID, $key, chunk_split(base64_encode($encrypted) ) );
    } 
}

?>