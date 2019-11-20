<?php
/*
Plugin Name: Дополнительные поля для профиля
Description: Добавляет новые поля в профиль пользователя.
Version: 1.0
*/

### дополнительные данные на странице профиля
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
                <input type="text" name="user_phone" value="<?php echo $phone ?>"><br>
            </td>
        </tr>
        <tr>
            <th><label>Adress</label></th>
            <td>
                <input type="text" name="user_adress" value="<?php echo $adress ?>"><br>
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
                <input type="text" name="user_status" value="<?php echo $status ?>"><br>
            </td>
        </tr>
    </table>
<?php
}


function my_profile_new_fields_update(){
    global $user_ID;
    update_user_meta( $user_ID, "user_phone",$_POST['user_phone'] );
    update_user_meta( $user_ID, "user_adress", $_POST['user_adress'] );
    update_user_meta( $user_ID, "user_male", $_POST['user_male'] );
    update_user_meta( $user_ID, "user_female", $_POST['user_female'] );
    update_user_meta( $user_ID, "user_status", $_POST['user_status'] );
}
