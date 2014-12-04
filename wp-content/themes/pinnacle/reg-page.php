<?php get_template_part('templates/page', 'header'); ?>
<?php
/**
 * Template Name:Reg-Page
 * Author: Kaining Yang
 */
  
if( !empty($_POST['ludou_reg']) ) {
  $error = '';
  $sanitized_user_login = sanitize_user( $_POST['user_login'] );
  $user_email = apply_filters( 'user_registration_email', $_POST['user_email'] );

  // Check the username
  if ( $sanitized_user_login == '' ) {
    $error .= '<strong>wrong</strong>:Please Enter UserName<br />';
  } elseif ( ! validate_username( $sanitized_user_login ) ) {
    $error .= '<strong>Wrong</strong>:Please Enter Valid UserName<br />。';
    $sanitized_user_login = '';
  } elseif ( username_exists( $sanitized_user_login ) ) {
    $error .= '<strong>Wrong</strong>:UserName Already Existed, Please Choose Another One<br />';
  }

  // Check the e-mail address
	$name = substr($user_email, 0, strlen($user_email) - 8);
	$handle = fopen("http://directory.uci.edu/index.php?uid=$name&form_type=plaintext", "rb"); 
	$contents = stream_get_contents($handle); 
	fclose($handle); 
  if ( substr($user_email, -8) != '@uci.edu' or substr($contents,0,5) == "Error") {
    $error .= '<strong>Wrong</strong>:Please Enter UCI-Email Adress<br />';
  } elseif ( ! is_email( $user_email ) ) {
    $error .= '<strong>wrong</strong>:Invalid Email Adress<br />';
    $user_email = '';
  } elseif ( email_exists( $user_email ) ) {
    $error .= '<strong>Wrong</strong>:Email Already Existed, Please Choose Another One<br />';
  }
    
  // Check the password
  if(strlen($_POST['user_pass']) < 6)
    $error .= '<strong>Wrong</strong>: Too Short<br />';
  elseif($_POST['user_pass'] != $_POST['user_pass2'])
    $error .= '<strong>Wrong</strong>: Please Enter the Same Password<br />';
      
    if($error == '') {
    $user_id = wp_create_user( $sanitized_user_login, $_POST['user_pass'], $user_email );
	echo "Please ignore the warning message, you have successfully signed up!";

    if ( ! $user_id ) {
      $error .= sprintf( '<strong>Wrong</strong>:无法完成您的注册请求... 请联系<a href=\"mailto:%s\">管理员</a>！<br />', get_option( 'admin_email' ) );
    }
    else if (!is_user_logged_in()) {
      $user = get_userdatabylogin($sanitized_user_login);
      $user_id = $user->ID;
  
      // 自动登录
      wp_set_current_user($user_id, $user_login);
      wp_set_auth_cookie($user_id);
    }
  }
}
?>
	
    <div id="content" class="container">
   	<div class="row">
    <div class="main <?php echo kadence_main_class(); ?>" role="main">
    	<div class="postclass pageclass clearfix">
			<?php while (have_posts()) : the_post(); ?>
<?php the_content(); ?>
<?php if(!empty($error)) {
 echo '<p class="ludou-error">'.$error.'</p>';
}
if (!is_user_logged_in()) { ?>
<form name="registerform" method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>" class="ludou-reg">
    <p>
      <label for="user_login">Username<br />
        <input type="text" name="user_login" tabindex="1" id="user_login" class="input" value="<?php if(!empty($sanitized_user_login)) echo $sanitized_user_login; ?>" />
      </label>
    </p>

    <p>
      <label for="user_email">UCI Email (If you don't have an UCI Email)<?php printf( __( '<a href="%s" title="Register for a new account">Click Here</a>', 'buddypress' ), bp_get_signup_page() ); ?><br />
        <input type="text" name="user_email" tabindex="2" id="user_email" class="input" value="<?php if(!empty($user_email)) echo $user_email; ?>" size="25" />
      </label>
    </p>
    
    <p>
      <label for="user_pwd1">Password<br />
        <input id="user_pwd1" class="input" tabindex="3" type="password" tabindex="21" size="25" value="" name="user_pass" />
      </label>
    </p>
    
    <p>
      <label for="user_pwd2">Confirm Password<br />
        <input id="user_pwd2" class="input" tabindex="4" type="password" tabindex="21" size="25" value="" name="user_pass2" />
      </label>
    </p>
    
    <p class="submit">
      <input type="hidden" name="ludou_reg" value="ok" />
      <button class="button button-primary button-large" type="submit">Submit</button>
    </p>
</form>
<?php } else {
 echo '<p class="ludou-error">You have successfully signed up!</p>';
} ?>

<?php endwhile; ?>
		</div>
</div><!-- /.main -->