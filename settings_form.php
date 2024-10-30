<?php
global $current_user;
$current_user = wp_get_current_user();
if(current_user_can('cpanel_settings')):
?>
<form name="input" action="<?php echo $action_url ?>" method="post">
  <h2>Cpanel Operations</h2>
   <div>
     <p style="font-size: 16px;">Configure Cpanel:</p>
    <label>Site domain name:</label><br>
    <input name="site_domain" type="text" size="60" ><br>
    <label style="font-size: 14px">Please enter your cpanel domain name without www part ex:(example.com)</label><br>
    <label>Site Skin:</label><br>
    <input name="site_skin" type="text" size="60" ><br>
    <label style="font-size: 14px">Please enter your cpanel skin name (the part underlined /frontend/__/index.html in your cpanel home url)</label><br>
    <label>Cpanel username:</label><br>
    <input name="user_name" type="text" size="60" ><br>
    <label style="font-size: 14px">Please enter your cpanel user name</label><br>
    <label>Password:</label><br>
    <input name="password" type="password" size="60" ><br>
    <label>Confirm password:</label><br>
    <input name="confirm_password" type="password" size="60" ><br>
    <p style="font-size: 14px">Enter your cpanel account password, and enter it again to confirm it</p>
  </div>
   <button type="submit" value="Submit" name="submit">Save Configuration</button>
  <button type="reset" value="Reset" name="reset">Reset to defaults</button>
</form>
<?php else : echo "You don't have the required roles to use this option.";
endif;
?>