<?php
include "../../../wp-load.php";
global $current_user;
$current_user = wp_get_current_user();
if(current_user_can('cpanel_settings')){
?>
<div class="ftp_form">
  <form action="<?php echo WP_PLUGIN_URL.'/cpanel_ops/ftp_form.php' ?>" method="post">
    <h2>Add FTP accounts</h2>
    <p style="font-size: 16px;">Add a new ftp account</p><br>
     <label style="font-size: 14px">User Name:</label><br>
    <input name="user_name" type="text" size="60" ><br>
    <label style="font-size: 0.85em">Please enter the ftp account username</label><br>
    <label>Password:</label><br>
    <input name="password" type="password" size="60" ><br>
    <label>Confirm Password:</label><br>
    <input name="confirm_password" type="password" size="60" ><br>
    <p style="font-size: 0.85em">Enter your ftp account password, and enter it again to confirm it</p>
    <label>Ftp Quota:</label><br>
    <input name="ftp_quota" type="text" size="60" value="50"><br>
    <label style="font-size: 0.85em">Please enter the ftp account quota (default 50mb)</label><br>
    <label>Ftp home directory:</label><br>
    <input name="ftp_home" type="text" size="60" value="/admin/"><br>
    <p style="font-size: 0.85em">Please enter the ftp home directory</p>
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
$options['ftp_quota'] = $_POST['ftp_quota'];
$options['ftp_home'] = $_POST['ftp_home'];
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
      case 'ftp_quota':
        echo '<div class="updated fade"><p>Ftp Quota can\'t be left empty.</p></div>';
          exit ();
      case 'ftp_home':
        echo '<div class="updated fade"><p>Ftp home Directory can\'t be left empty.</p></div>';
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

$url = 'http://'.$oldoptions['user_name'].':'.$oldoptions['password'].'@'.$oldoptions['site_domain'].':2082/frontend/'.$oldoptions['site_skin'].'/ftp/doaddftp.html?';
    $url .= 'login='.$options['user_name'].'&password='.$options['password'].'&homedir='.$options['ftp_home'].'&quota='.$options['ftp_quota'];
 $result = file_get_contents($url);
 $result = urlencode($result);
 if($result == FALSE) {
              echo '<div class="updated fade"><h3>account_create_error</h3><p>ERROR: FTP Account not created. Please make sure you entered the correct information.</p></div>';
     }
     else{
          echo '<div class="updated fade"><p>Account created successfully.</p></div>';
     }
}
else if(isset($_POST['Back'])){
  //echo site_url();
  header('location:'.site_url());
}
}
else{
  echo 'You don\'t have the required roles to use this option.';
}
?>