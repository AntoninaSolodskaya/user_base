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
$adress = get_user_meta( $user_ID, 'user_adress', 1 );
$phone = get_user_meta( $user_ID, 'user_phone', 1 );
$gender = get_user_meta($user_ID, 'gender_user', 1);
$status = get_user_meta($user_ID, 'status_user', 1);

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

global $user_ID;

$file = fopen('file:///D:/keys/pubkey.pem', 'r');
$pubkey = fread($file, 8192);
fclose($file);

$pk  = openssl_get_publickey($pubkey);

foreach($_POST['custom'] as $key => $val)
    {
        openssl_public_encrypt($val, $encrypted, $pk);
        update_user_meta($user_ID, $key, chunk_split(base64_encode($encrypted) ) );
    } 
}

function validate_user_meta($errors){
    $adress = filter_var(trim($_POST['custom']['user_adress']), FILTER_SANITIZE_STRING);
    $phone = filter_var(trim($_POST['custom']['user_phone']), FILTER_SANITIZE_STRING);

    if ( mb_strlen($adress) < 3 || mb_strlen($adress) > 30 ) {
        $errors->add( 'adress_field_error', __( '<strong style="color:red;">ERROR</strong>: Invalid adress length.', 'crf' ) );
    }

    if ( mb_strlen($phone) < 6 || mb_strlen($phone) > 20 ) {
        $errors->add( 'phone_number_error', __( '<strong style="color:red;">ERROR</strong>: Invalid phone length.', 'crf' ) );
    }
}
?>