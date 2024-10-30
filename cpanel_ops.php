<?php
/*
Plugin Name: Cpanel Operations
Version: 0.1
Description: Enables the wordpress admin or any other users that have the proper role assigned to control thier cpanel operations fro the wordpress site without going to the cpanel.
Author: JesoX
Author URI: http://wordpress.jesox.com
Plugin URI: http://wordpress.jesox.com/?p=22
*/

// Avoid name collisions.
if ( !class_exists('Cpanel_ops') ) :
class Cpanel_ops
{
// this variable will hold url to the plugin
var $plugin_url;
var $db_option = 'Cpanel_ops_Options';
// Initialize the plugin
function Cpanel_ops()
{
$this->plugin_url = trailingslashit( WP_PLUGIN_URL.'/'.dirname( plugin_basename(__FILE__) ));
// add options Page
add_action('admin_menu', array(&$this, 'admin_menu'));
}
// hook the options page
function admin_menu()
{
  add_options_page('Cpanel Options', 'Cpanel Operations',8, basename(__FILE__), array(&$this, 'handle_options'));
}
function display()
{
    echo $before_title . '<h3 style="color:#222222;font-weight:bold">Cpanel Operations</h3>'. $after_title.'</br>';
    echo '<ul>';
  echo '<li id="ftp_form"><a href = "'.WP_PLUGIN_URL.'/cpanel_ops/ftp_form.php" >Create ftp account</a></li>';
  echo '<li id="email_form"><a href ="'.WP_PLUGIN_URL.'/cpanel_ops/email_form.php" >Create email account </a></li>';
  echo '</ul>';
}
// handle plugin options
function get_options()
{

// get saved options
$saved = get_option($this->db_option);
// assign them
if (!empty($saved))
{
foreach ($saved as $key => $option)
$options[$key] = $option;
}
// update the options if necessary
if ($saved != $options)
update_option($this->db_option, $options);
//return the options
return $options;
}
// handle the options page
function handle_options()
{
$options = $this->get_options();
if ( isset($_POST['submit']))
{
$options = array();
$options['site_domain']=$_POST['site_domain'];
$options['site_skin']=$_POST['site_skin'];
$options['user_name']= $_POST['user_name'];
$options['password']= $_POST['password'];
$options['confirm_password']= $_POST['confirm_password'];
foreach($options as $key=>$value){
  if(empty($value)){
    switch($key){
      case 'site_domain':
        echo '<div class="updated fade"><p>Site Domain can\'t be left empty.</p></div>';
        exit ();
      case 'site_skin':
        echo '<div class="updated fade"><p>Site Skin can\'t be left empty.</p></div>';
          exit ();
      case 'user_name':
        echo '<div class="updated fade"><p>User Name can\'t be left empty.</p></div>';
          exit ();
      case 'password':
        echo '<div class="updated fade"><p>Password can\'t be left empty.</p></div>';
          exit ();
      case 'confirm_password':
        echo '<div class="updated fade"><p>Confirm Password can\'t be left empty.</p></div>';
          exit ();
    }
  }
}
if($options['password']!=$options['confirm_password']){
    echo '<div class="updated fade"><p>Passwords don\'t match.</p></div>';
      exit ();
}

update_option($this->db_option, $options);
echo '<div class="updated fade"><p>Plugin settings saved.</p></div>';
}
else{
 $_POST['site_domain']='';
 $POST['site_skin']='';
 $_POST['user_name']='';
 $_POST['password']='';
 $_POST['confirm_password']='';
}
// URL for form submit, equals our current page
$action_url = $_SERVER['REQUEST_URI'];
include('settings_form.php');
}
// function to call after plugin activation
function install()
{
  // set default options
$this->get_options();

}
}
else :
exit ("Class Cpanel_ops already declared!");
endif;
// create new instance of the class
$Cpanel_ops = new Cpanel_ops();
if (isset($Cpanel_ops))
{
  global $wp_roles;
 //new added code
add_role( 'cpanel_ops', 'Cpanel Operations', array( 'cpanel_settings' ) );
$wp_roles->add_cap( 'administrator', 'cpanel_settings' );
//$wp_roles->remove_cap( 'administrator', 'cpanel_settings' );
 //end of added code
// register the activation function by passing the reference to our instance
register_activation_hook( __FILE__, array(&$Cpanel_ops,'install') );
wp_register_sidebar_widget( 'cpanel_ops', 'Cpanel Operations',array(&$Cpanel_ops, 'display') );
}
?>