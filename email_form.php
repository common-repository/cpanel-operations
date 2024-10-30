<?php
include "../../../wp-load.php";
global $current_user;
$current_user = wp_get_current_user();
if(current_user_can('cpanel_settings')){
?>
<div class="email_form">
  <form action="<?php echo WP_PLUGIN_URL.'/cpanel_ops/email_form.php' ?>" method="post">
    <h2>Add E-mail accounts</h2>
    <p style="font-size: 16px;">Add a new email account</p><br>
     <label style="font-size: 14px">User Name:</label><br>
    <input name="user_name" type="text" size="60" ><br>
    <label style="font-size: 0.85em">Please enter the email account username</label><br>
    <label style="font-size: 14px">Password:</label><br>
    <input name="password" type="password" size="60" ><br>
    <label style="font-size: 14px">Confirm Password:</label><br>
    <input name="confirm_password" type="password" size="60" ><br>
    <p style="font-size: 0.85em">Enter your email account password, and enter it again to confirm it</p>
    <label style="font-size: 14px">Email Quota:</label><br>
    <input name="email_quota" type="text" size="60" value="20"><br>
    <label style="font-size: 0.85em">Please enter the email account quota (default 20mb)</label><br>
    <label style="font-size: 14px">Email home directory:</label><br>
    <input name="email_home" type="text" size="60"><br>
    <p style="font-size: 0.85em">Please enter the email domain that will be used after the @ sign (must be a domain that is included with your cpanel)</p><br>
    
    <button type="submit" value="Submit" name="submit">Submit</button>
    <button type="redirect" value="button" name="Back">Back</button>
  </form>
</div>
<?php
$options = array();
if ( isset($_POST['submit'])){
$options['user_name'] = $_POST['user_name'];
$options['password']   = $_POST['password'];
$options['confirm_password']   = $_POST['confirm_password'];
$options['email_quota'] = $_POST['email_quota'];
$options['email_home'] = $_POST['email_home'];
foreach($options as $key=>$value){
  if(empty($value)){
    switch($key){
      case 'user_name':
        echo '<div class="updated fade"><p>User name can\'t be left empty.</p></div>';
        exit ();
      case 'password':
        echo '<div class="updated fade"><p>Password can\'t be left empty.</p></div>';
          exit ();
      case 'confirm_password':
        echo '<div class="updated fade"><p>Confirm Password can\'t be left empty.</p></div>';
          exit ();
      case 'email_quota':
        echo '<div class="updated fade"><p>Email Quota can\'t be left empty.</p></div>';
          exit ();
      case 'email_home':
        echo '<div class="updated fade"><p>Email home Directory can\'t be left empty.</p></div>';
          exit ();
    }
  }
}
if($options['password']!=$options['confirm_password']){
    echo '<div class="updated fade"><p>Passwords don\'t match.</p></div>';
      exit ();
}
$oldoptions = get_option('Cpanel_ops_Options');
foreach($oldoptions as $key=>$value){
  if(empty($value)){
    switch($key){
      case 'site_domain':
        echo '<div class="updated fade"><p>Site Domain must be set first.</p></div>';
        exit ();
      case 'site_skin':
        echo '<div class="updated fade"><p>Site Skin must be site forst.</p></div>';
          exit ();
      case 'user_name':
        echo '<div class="updated fade"><p>Cpanel user name must be set first.</p></div>';
          exit ();
      case 'password':
        echo '<div class="updated fade"><p>Cpanel password wasn\'t provided.</p></div>';
          exit ();
    }
  }
}

 $url = 'http://'.$oldoptions['user_name'].':'.$oldoptions['password'].'@'.$oldoptions['site_domain'].':2082/frontend/'.$oldoptions['site_skin'].'/mail/doaddpop.html?';
    $url .= 'email='.$options['user_name'].'&domain='.$options['email_home'].'&password='.$options['password'].'&quota='.$options['email_quota'];
 $result = file_get_contents($url);
  if($result == FALSE) {
              echo '<div class="updated fade"><h3>account_create_error</h3><p>ERROR: Email Account not created. Please make sure you entered the correct information.</p></div>';
     }
     else{
          echo '<div class="updated fade"><p>Account created successfully.</p></div>';
     }
}
else if (isset($_POST['Back']))
{
  header('location:'. site_url());
}
}
else{
  echo "You don't have the required roles to use this option.";
}
?>