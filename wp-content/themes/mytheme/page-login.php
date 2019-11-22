
<?php  

$args = array(  
    'redirect' => home_url(),   
    'id_username' => 'user',  
    'id_password' => 'pass',  
);  
wp_login_form( $args );


function redirect_login_page() {
    $login_page  = home_url( '/login/' );
    $page_viewed = basename($_SERVER['REQUEST_URI']);

    if( $page_viewed == "wp-login.php" && $_SERVER['REQUEST_METHOD'] == 'GET') {
        wp_redirect($login_page);
        exit;
    }   
}
add_action('init','redirect_login_page');


?>