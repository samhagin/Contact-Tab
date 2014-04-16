<?php
/*
Plugin Name: Contact Tab Pro
Plugin URI: http://webwiki.co/contact-tab
Description: Contact side tab for WordPress with Captcha
Author: Sam Hagin
Version: 1.8
Author URI: http://webwiki.co
*/

$table_name = $wpdb->prefix . "contact_tab";

//export to CSV
if(isset($_POST['ctexport'])) {

// output headers so that the file is downloaded rather than displayed
$fileName = 'emails.csv';
 
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header('Content-Description: File Transfer');
header("Content-type: text/csv");
header("Content-Disposition: attachment; filename={$fileName}");
header("Expires: 0");
header("Pragma: public");

$fh = @fopen( 'php://output', 'w' );
 
global $wpdb;
$query = "SELECT * FROM $table_name";
$results = $wpdb->get_results( $query, ARRAY_A );

$headerDisplayed = false;
 
foreach ( $results as $data ) {
    // Add a header row if it hasn't been added yet
    if ( !$headerDisplayed ) {
        // Use the keys from $data as the titles
        fputcsv($fh, array_keys($data));
        $headerDisplayed = true;
    }
 
    // Put the data into the stream
    fputcsv($fh, $data);
}
// Close the file
fclose($fh);
exit;
}


//do not display erros
ini_set('display_errors', '0');

//table, id, subject, from/reply to, message, date
global $shct_path, $options,$ctap_options;
$shct_path = plugins_url('/contact-tab-pro/');

global $shct_version;
$shct_version = "1.8";


function shct_activate() {
  global $wpdb;
   global $shct_version;
 $table_name = $wpdb->prefix . "contact_tab";
 $sql = "CREATE TABLE $table_name (
  id mediumint(9) NOT NULL AUTO_INCREMENT,
  time text NOT NULL,
  name tinytext NOT NULL,
  subject text NOT NULL,
  email text NOT NULL,
  message text NOT NULL,
  ip text NOT NULL,
  url text NOT NULL,
  replied INT NOT NULL,
  UNIQUE KEY id (id)
    );";

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
dbDelta($sql);

add_option('shct_version', $shct_version);
}


function ct_gen() {
	$contact_tab_settings = array(
		'admin_email' => get_option('admin_email'),
		'cc_email'    => '',
		'subject'     => get_option('blogname'),
	        'position'    => 'left', 
		'captcha'     => 'yes',
		'social'      => 'no',
		'facebook'    => '',
		'twitter'     => '',
		'linkedin'    => '',
		'google'      => '',
		'thx'         => 'Your message has been submitted',
                'red'         => 'no',
                'redurl'      => '',
		'auto'	      => 'no',	

        );
           add_option( 'contact_tab_settings', $contact_tab_settings);
}
 
function ct_ap() {	
	$contact_tab_ap = array(
		'tabcolor' => '3B5998',
		 'bgcolor' => '3B5998',
		     'txt' => '000',
		  'bcolor' => '2441ff', 
		  'tabtxt' => 'fff',
		    'etxt' => 'CC0000',
		    'stxt' => '009900',

	);

		add_option('contact_tab_ap', $contact_tab_ap);
}
function shct_load_scripts() {
global $shct_path;
wp_enqueue_script('jquery');
}

function shct_adminscripts() {
global $shct_path;
wp_enqueue_script('ctjscolor', $shct_path. 'jscolor/jscolor.js');
wp_enqueue_script('jquery');
wp_enqueue_script('contacttabjs', $shct_path. 'js/contacttab.js');
wp_enqueue_script('tablesorter', $shct_path. 'js/jquery.tablesorter.min.js');
wp_enqueue_script('easing', $shct_path. 'js/jquery.easing.min.js');
wp_enqueue_style('contacttabcss', $shct_path. 'css/contacttab.css');
wp_enqueue_script('jquery-ui-accordion');
wp_enqueue_style('jquery-ui.css', $shct_path. 'css/jquery-ui.css');
}



function shct_options(){
	add_menu_page('Contact Tab', 'Contact Tab', 'manage_options', __FILE__, 'contact_tab_settings', plugins_url('/images/contact-tab.gif', __FILE__));
 	add_submenu_page(__FILE__, 'Appearance', 'Appearance', 'manage_options', 'contact-tab-ap', 'contact_tab_ap');
	add_submenu_page(__FILE__, 'Messages', 'Messages', 'manage_options', 'contact-tab-msg', 'contact_tab_msg');
	add_submenu_page(__FILE__, 'Documentation', 'Documentation', 'manage_options', 'contact-tab-docs', 'contact_tab_docs');
}

function ct_fixie8() {if (strpos($_SERVER['HTTP_USER_AGENT'],"MSIE 8")) {header("X-UA-Compatible: IE=7");}}

add_action('admin_menu', 'shct_options');
add_action('admin_init', 'shct_settings');
add_action('wp_enqueue_scripts', 'shct_load_scripts');
add_action('wp_footer', 'shct_tab');
add_action('admin_enqueue_scripts','shct_adminscripts');
add_action('send_headers', 'ct_fixie8');
register_activation_hook(__FILE__, 'ct_gen');
register_activation_hook(__FILE__, 'ct_ap');
register_activation_hook(__FILE__, 'shct_activate');


function shct_settings() {
	register_setting('contact_tab_settings', 'contact_tab_settings');
	register_setting('contact_tab_ap', 'contact_tab_ap');
	register_setting('contact_tab_msg', 'contact_tab_msg');
	register_setting('contact_tab_docs', 'contact_tab_help');
}

function contact_tab_settings() {
if (!current_user_can('manage_options'))  {
                wp_die( __('You do not have sufficient permissions to access this page.') );

           }

?>
<div class="wrap">

                <!-- Display Plugin Icon, Header, and Description -->
                <div class="icon32" id="icon-options-general"><br></div>
                <h2>Contact Tab</h2>
		<h3>General Settings</h3>
                <p></p>

                <!-- Beginning of the Plugin Options Form -->
                <form method="post" action="options.php">
                        <?php settings_fields('contact_tab_settings'); ?>
                        <?php $options = get_option('contact_tab_settings'); ?>
                        <!-- Table Structure Containing Form Controls -->
                        <table class="form-table">
 <tr valign="top">
        <th scope="row">Default Email:</th>
        <td><input type="text" name="contact_tab_settings[admin_email]" value="<?php echo $options['admin_email']; ?>" />
		<br /><span style="color:#666666;margin-left:2px;">Default contact email address</span></td>
        </tr>


 <tr valign="top">
        <th scope="row">Forward To:</th>
        <td><input type="text" name="contact_tab_settings[cc_email]" value="<?php echo $options['cc_email']; ?>" />
                <br /><span style="color:#666666;margin-left:2px;">Additional email address 
to forward mail to</span></td>
        </tr>

 <tr valign="top">
        <th scope="row">Forward To Cell Phone:</th>
        <td><input type="text" name="contact_tab_settings[number]" value="<?php echo $options['number']; ?>" />
	<select name="contact_tab_settings[mobile]">
	<option value="txt.att.net" <?php selected( $options['mobile'], 'txt.att.net'); ?>>AT&T</option>

	<option value="cingularme.com" <?php selected( $options['mobile'], 'cingularme.com'); ?>>Cingular</option>

	<option value="mms.mycricket.com" <?php selected( $options['mobile'], 'mms.mycricket.com'); ?>>Cricket</option>

	<option value="message.alltel.com" <?php selected( $options['mobile'], 'message.alltel.com'); ?>>Alltell</option>

	<option value="myboostmobile.com" <?php selected( $options['mobile'], 'myboostmobile.com'); ?>>Boost Mobile</option>

	<option value="messaging.nextel.com" <?php selected( $options['mobile'], 'messaging.nextel.com'); ?>>Nextel</option>

	<option value="messaging.sprintpcs.com" <?php selected( $options['mobile'], 'messaging.sprintpcs.com'); ?>>Sprint</option>

	<option value="tmomail.net" <?php selected( $options['mobile'], 'tmomail.net'); ?>>T-Mobile</option>

	<option value="email.uscc.net" <?php selected( $options['mobile'], 'email.uscc.net'); ?>>US Cellular</option>

	<option value="vtext.com" <?php selected( $options['mobile'], 'vtext.com'); ?>>Verizon</option>

	<option value="vmobl.com" <?php selected( $options['mobile'], 'vmobl.com'); ?>>Virgin Mobile</option>
	</select>

                <br /><span style="color:#666666;margin-left:2px;">Enter your phone number and select your cell phone carrier from the list</span></td>
        </tr>


<tr valign="top">
        <th scope="row">Email Subject:</th>
        <td><input type="text" name="contact_tab_settings[subject]" value="<?php echo $options['subject']; ?>" />
                <br /><span style="color:#666666;margin-left:2px;">Subject for email sent</span></td>
        </tr>

<tr valign="top">
        <th scope="row">Intro Text:</th>
        <td><textarea rows="5" cols="50" name="contact_tab_settings[intro]"><?php echo $options['intro']; ?></textarea>
<br /><span style="color:#666666;margin-left:2px;">Message shown before contact form</span></td>
<tr />

<tr valign="top">
        <th scope="row">Slide Out/In Effect:</th>
<td><select name="contact_tab_settings[effect]">
<option value='linear' <?php selected( $options['effect'], 'linear'); ?> > linear </option>
<option value='easeInSine' <?php selected( $options['effect'], 'easeInSine'); ?> > easeInSine </option>
<option value='easeOutSine' <?php selected( $options['effect'], 'easeOutSine'); ?> > easeOutSine </option>
<option value='easeInOutSine' <?php selected( $options['effect'], 'easeInOutSine'); ?> > easeInOutSine </option>
<option value='easeInQuad' <?php selected( $options['effect'], 'easeInQuad'); ?> > easeInQuad </option>
<option value='easeOutQuad' <?php selected( $options['effect'], 'easeOutQuad'); ?> > easeOutQuad </option>
<option value='easeInOutQuad' <?php selected( $options['effect'], 'easeInOutQuad'); ?> > easeInOutQuad </option>
<option value='easeInCubic' <?php selected( $options['effect'], 'easeInCubic'); ?> > easeInCubic </option>
<option value='easeOutCubic' <?php selected( $options['effect'], 'easeOutCubic'); ?> > easeOutCubic </option>
<option value='easeInOutCubic' <?php selected( $options['effect'], 'easeInOutCubic'); ?> > easeInOutCubic </option>
<option value='easeInQuart' <?php selected( $options['effect'], 'easeInQuart'); ?> > easeInQuart </option>
<option value='easeOutQuart' <?php selected( $options['effect'], 'easeOutQuart'); ?> > easeOutQuart </option>
<option value='easeInOutQuart' <?php selected( $options['effect'], 'easeInOutQuart'); ?> > easeInOutQuart </option>
<option value='easeInQuint' <?php selected( $options['effect'], 'easeInQuint'); ?> > easeInQuint </option>
<option value='easeOutQuint' <?php selected( $options['effect'], 'easeOutQuint'); ?> > easeOutQuint </option>
<option value='easeInOutQuint' <?php selected( $options['effect'], 'easeInOutQuint'); ?> > easeInOutQuint </option>
<option value='easeInExpo' <?php selected( $options['effect'], 'easeInExpo'); ?> > easeInExpo </option>
<option value='easeOutExpo' <?php selected( $options['effect'], 'easeOutExpo'); ?> > easeOutExpo </option>
<option value='easeInOutExpo' <?php selected( $options['effect'], 'easeInOutExpo'); ?> > easeInOutExpo </option>
<option value='easeInCirc' <?php selected( $options['effect'], 'easeInCirc'); ?> > easeInCirc </option>
<option value='easeOutCirc' <?php selected( $options['effect'], 'easeOutCirc'); ?> > easeOutCirc </option>
<option value='easeInOutCirc' <?php selected( $options['effect'], 'easeInOutCirc'); ?> > easeInOutCirc </option>
<option value='easeInElastic' <?php selected( $options['effect'], 'easeInElastic'); ?> > easeInElastic </option>
<option value='easeOutElastic' <?php selected( $options['effect'], 'easeOutElastic'); ?> > easeOutElastic </option>
<option value='easeInOutElastic' <?php selected( $options['effect'], 'easeInOutElastic'); ?> > easeInOutElastic </option>
<option value='easeInBack' <?php selected( $options['effect'], 'easeInBack'); ?> > easeInBack </option>
<option value='easeOutBack' <?php selected( $options['effect'], 'easeOutBack'); ?> > easeOutBack </option>
<option value='easeInOutBack' <?php selected( $options['effect'], 'easeInOutBack'); ?> > easeInOutBack </option>
<option value='easeInBounce' <?php selected( $options['effect'], 'easeInBounce'); ?> > easeInBounce </option>
<option value='easeOutBounce' <?php selected( $options['effect'], 'easeOutBounce'); ?> > easeOutBounce </option>
<option value='easeInOutBounce' <?php selected( $options['effect'], 'easeInOutBounce'); ?> > easeInOutBounce </option>
</select>


<tr valign="top">
        <th scope="row">Contact Tab Position:</th>
<td><select name="contact_tab_settings[position]">
                        <option value='left' <?php selected( $options['position'], 'left'); ?> >Left</option>
                        <option value='right' <?php selected( $options['position'], 'right'); ?> >Right</option>
			<option value='tright' <?php selected( $options['position'], 'tright'); ?> >Top Right</option>
			<option value='tleft' <?php selected( $options['position'], 'tleft'); ?> >Top Left</option>	
			<option value='bright' <?php selected( $options['position'], 'bright'); ?> >Bottom Right</option>
			<option value='bleft' <?php selected( $options['position'], 'bleft'); ?> >Bottom Left</option>
</select>

<br /><span style="color:#666666;margin-left:2px;">Position of Tab</span></td>
        </tr>

<tr valign="top">
        <th scope="row">Show captcha:</th>
<td><select name="contact_tab_settings[captcha]">
                        <option value='yes' <?php selected( $options['captcha'], 'yes'); ?> >Yes</option>
                        <option value='no' <?php selected( $options['captcha'], 'no'); ?> >No</option>
        </select><br />
<span style="color:#666666;margin-left:2px;">Requires GD library and FreeType</span></td></tr>

<tr valign="top">
        <th scope="row">Show Social Networking Icons:</th>
<td><select name="contact_tab_settings[social]">
                        <option value='yes' <?php selected( $options['social'], 'yes'); ?> >Yes</option>
                        <option value='no' <?php selected( $options['social'], 'no'); ?> >No</option>
        </select><br />
<span style="color:#666666;margin-left:2px;">Show icons for Facebook, Twitter, LinkedIn & Google+, all links below should be entered without the preceeding http://</span></td></tr>

<tr valign="top">
        <th scope="row">Facebook:</th>
        <td><input type="text" name="contact_tab_settings[facebook]" value="<?php echo $options['facebook']; ?>" />
                <br /><span style="color:#666666;margin-left:2px;">Facebook page or profile link</span></td>
        </tr>

<tr valign="top">
        <th scope="row">Twitter:</th>
        <td><input type="text" name="contact_tab_settings[twitter]" value="<?php echo $options['twitter']; ?>" />
                <br /><span style="color:#666666;margin-left:2px;">Twitter username</span></td>
        </tr>

<tr valign="top">
        <th scope="row">LinkedIn:</th>
        <td><input type="text" name="contact_tab_settings[linkedin]" value="<?php echo $options['linkedin']; ?>" />
                <br /><span style="color:#666666;margin-left:2px;">LinkedIn Profile link</span></td>
        </tr>

<tr valign="top">
        <th scope="row">Google+:</th>
        <td><input type="text" name="contact_tab_settings[google]" value="<?php echo $options['google']; ?>" />
                <br /><span style="color:#666666;margin-left:2px;">Google+ link</span></td>
        </tr>


<tr valign="top">
        <th scope="row">Redirect After Submit:</th>
<td><select name="contact_tab_settings[red]">
                        <option value='yes' <?php selected( $options['red'], 'yes'); ?> >Yes</option>
                        <option value='no' <?php selected( $options['red'], 'no'); ?> >No</option>
        </select><br />
<span style="color:#666666;margin-left:2px;">Option to redirect after form submit</span>

<tr valign="top">
        <th scope="row">Redirect URL:</th>
        <td><input type="text" name="contact_tab_settings[redurl]" value="<?php echo $options['redurl']; ?>" />
                <br /><span style="color:#666666;margin-left:2px;">URL to redirect to after successful submission of form</span></td>
        </tr>


<tr valign="top">
        <th scope="row">Thank you message:</th>
        <td><textarea rows="5" cols="50" name="contact_tab_settings[thx]"><?php echo $options['thx']; ?></textarea>
<br /><span style="color:#666666;margin-left:2px;">Message shown after form has been submitted,can be text or HTML</span></td>
<tr />

<tr valign="top">
        <th scope="row">Send autorespond message:</th>
<td><select name="contact_tab_settings[auto]">
                        <option value='yes' <?php selected( $options['auto'], 'yes'); ?> >Yes</option>
                        <option value='no' <?php selected( $options['auto'], 'no'); ?> >No</option>
        </select><br />
<span style="color:#666666;margin-left:2px;">Option to send autoresponder message</span>

<tr valign="top">
        <th scope="row">Autoresponder message:</th>
        <td><textarea rows="5" cols="50" name="contact_tab_settings[am]"><?php echo $options['am']; ?></textarea>
<br /><span style="color:#666666;margin-left:2px;">Message sent to anyone who sends email to you,can be text or HTML</span></td>

</table>
<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>
</form>
</div>
<?php
}

function contact_tab_ap() {
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );

	} 

	?>

		<div class="wrap"><div id="icon-options-general" class="icon32"><br></div>
	<h2>Appearance</h2></div>
<form method="post" action="options.php">
                        <?php settings_fields('contact_tab_ap'); ?>
                        <?php $ctap_options = get_option('contact_tab_ap'); ?>
                        <!-- Table Structure Containing Form Controls -->
                        <table class="form-table">
 <tr valign="top">
        <th scope="row">Tab Color:</th>
        <td><input class="ctcolor {adjust:false}" size="4" style="border: 0px" name="contact_tab_ap[tabcolor]" value="<?php echo $ctap_options['tabcolor']; ?>" />
                <br /><span style="color:#666666;margin-left:2px;">Tab color</span></td>
        </tr>

 <tr valign="top">
        <th scope="row">Background Color:</th>
        <td><input class="ctcolor {adjust:false}" size="4" style="border: 0px" name="contact_tab_ap[bgcolor]" value="<?php echo $ctap_options['bgcolor']; ?>" />
                <br /><span style="color:#666666;margin-left:2px;">Background color for contact form</span></td>
        </tr>



 <tr valign="top">
        <th scope="row">Form Text Color:</th>
        <td><input class="ctcolor {adjust:false}" size="4" style="border: 0px" name="contact_tab_ap[txt]" value="<?php echo $ctap_options['txt']; ?>" />
                <br /><span style="color:#666666;margin-left:2px;">Text color for form</span></td>
        </tr>

<tr valign="top">
        <th scope="row">Send Button Color:</th>
        <td><input class="ctcolor {adjust:false}" size="4" style="border: 0px" name="contact_tab_ap[bcolor]" value="<?php echo $ctap_options['bcolor']; ?>" />
                <br /><span style="color:#666666;margin-left:2px;">Color of send button</span></td>
        </tr>

<tr valign="top">
        <th scope="row">Tab text color:</th>
        <td><input class="ctcolor {adjust:false}" size="4" style="border: 0px" name="contact_tab_ap[tabtxt]" value="<?php echo $ctap_options['tabtxt']; ?>" />
                <br /><span style="color:#666666;margin-left:2px;">Text color for tab</span></td>
        </tr>

<tr valign="top">
        <th scope="row">Error message text color:</th>
        <td><input class="ctcolor {adjust:false}" size="4" style="border: 0px" name="contact_tab_ap[etxt]" value="<?php echo $ctap_options['etxt']; ?>" />
                <br /><span style="color:#666666;margin-left:2px;">Text color for error message</span></td>
        </tr>

<tr valign="top">
        <th scope="row">Success message text color:</th>
        <td><input class="ctcolor {adjust:false}" size="4" style="border: 0px" name="contact_tab_ap[stxt]" value="<?php echo $ctap_options['stxt']; ?>" />
                <br /><span style="color:#666666;margin-left:2px;">Text color for success message</span></td>
        </tr>


</table>

<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>
</form>
</div>

<?php } 

function contact_tab_msg(){
global $wpdb, $shct_path, $options;
$table_name = $wpdb->prefix . "contact_tab";

if(!isset($_GET["emailid"])) {
?>
<!--display messages-->
<div class="wrap"><div id="icon-options-general" class="icon32"><br></div>
        <h2>Messages</h2>
<form method="post" action="">
<table id="ct_emaillist" class='widefat' cellspacing='0'>
<thead>
<tr>
<th scope="col" class="check-column">
<input type="checkbox"></th>
<th scope="col">Status</th>
<th scope="col">Name</th>
<th scope="col">Email</th>
<th scope="col">Subject</th>
<th scope="col">Message</th>
<th scope="col">Date</th>
<th scope="col">IP</td>
<th scope="col">URL</th>
</tr>
</thead>
<tbody>
<?php

//number of results per page
$per_page = 25;

//total number of emails in database
$count = $wpdb->get_results("SELECT * from $table_name");
$total_results = $wpdb->num_rows;
$wpdb->get_results("SELECT * from $table_name where replied='0'");
$unread = $wpdb->num_rows;
echo '<h3><b>'.$total_results.' email(s) in Database, '.$unread.' unread</b></h3>';

$total_pages = ceil($total_results / $per_page);
$page = $_GET['paged'];        
if ($page == 0) $page = 1;					//if no page var is given, default to 1.
	$prev = $page - 1;							//previous page is page - 1
	$next = $page + 1;	       

 // display pagination
       echo "<p align='center'>"; 
        for ($i = 1; $i <= $total_pages; $i++)
        {
                
		echo "<a href='admin.php?page=contact-tab-msg&paged=$i' style='border:1px solid; text-decoration: none; padding: 3px; margin: 2px'>$i</a> ";
	
        }
		echo "</p><br />";
echo '<input name="ctdelete" value="Delete Selected" type="submit" class="button-secondary">&nbsp;&nbsp;';
echo '<input name="ctmark" value="Mark as Read" type="submit" class="button-secondary">&nbsp;&nbsp;';
echo '<input name="ctexport" value="Export Emails to CSV" type="submit" class="button-secondary">';
echo '<p></p>';
//if delete button is clicked
if (isset($_POST['ctdelete']))
{
	$delete = $_POST['ctcheck'];
	foreach( $delete as $todelete )
	{
	
        $wpdb->query("DELETE FROM $table_name WHERE id=$todelete");	

	}

}

//mark as read
if (isset($_POST['ctmark']))
{
        $mark = $_POST['ctcheck'];
        foreach( $mark as $tomark )
        {

        $wpdb->query("UPDATE $table_name SET replied=1 WHERE id=$tomark");

        }

}




// check if the 'page' variable is set in the URL
if (isset($_GET['paged']) && is_numeric($_GET['paged']))
        {
		$start= ($page - 1) * $per_page;        
        }
                else
                {
                        
                        $start = 0;
                }               
        
//get emails from database
$query = "SELECT * from $table_name order by time asc limit $start, $per_page";


 if($emails=$wpdb->get_results($query)){
       foreach($emails as $email) {
              ?>
              <tr id="post-31" class="alternate author-self status-publish" valign="top">
              <th scope="row" class="check-column"><input name="ctcheck[]" value="<?php echo $email->id;?>" type="checkbox"></th>
	      <td class="ct_status"><?php $status = $email->replied; 
              switch($status) {
		 case 0:
        	echo "Unread";
        	break;
    		case 1:
        	echo "Read";
        	break;
    		case 2:
        	echo "Replied";
        	break;
		}

		?></td>
              <td><a href="admin.php?page=contact-tab-msg&emailid=<?php echo $email->id;?>&replied=<?php echo $email->replied; ?>"><?php echo stripslashes_deep($email->name);?></a></td>
              <td><?php echo stripslashes_deep($email->email);?></td>
	      <td><?php echo stripslashes_deep($email->subject);?></td>
              <td><?php echo stripslashes_deep(mb_substr($email->message,0, 100));?></td>
              <td><?php echo $email->time?></td>
              <td><a href="http://whois.domaintools.com/<?php echo $email->ip; ?>" target=_blank><?php echo $email->ip; ?></a></td>
              <td><a href="<?php echo $email->url; ?>" target=_blank><?php echo $email->url;?></a></td>
              </tr>
                                <?php
                                        }
				}
                                ?>
</tbody>
</table>
</form>
</div>
<?php
}

//display email
if(isset($_GET["emailid"])){
$emailid = $_GET["emailid"];
$query = "SELECT * from $table_name WHERE id='$emailid'";
$get_email=$wpdb->get_row($query, ARRAY_A);
if(is_array($get_email)) extract($get_email);
?>
<div class="wrap">
<div id="icon-options-general" class="icon32"><br></div>
<h2>View Message</h2><br />
<form method="post" id="ct_rrmail" action="">
<input type="hidden" name="id" value="<?php echo $id;?>">
<table width="90%">
<tr><td>
<input type="text" id="email" name="email" size="30"  tabindex="1"  value="<?php echo $email;?>">
<input type="text" id="subject" name="subject" size="30"  tabindex="1"  value="Re: <?php echo stripslashes_deep($subject);?>">
<?php
$mod_message = <<<MSG
<br clear:none />
-----------Original Message ------------------------
From: {$email}
To: {$options['admin_email']}
Sent: $time
Subject: {$subject}

$message
-----------------------------------------------------
MSG;
     $args = array(
     "textarea_name" => "message",
     'force_br_newlines' => true,
     'wpautop' => 'true',	
     'width' => '50%',
     'remove_linebreaks' => 'false',
     'convert_newlines_to_brs' => 'true', 
     'textarea_rows' => '20',
     'forced_root_block' => '',
     'tinymce' => true );
     wp_editor( wpautop(stripslashes_deep($mod_message)), "ctmessage", $args );
?>
</td>
</tr>
<tr>
<td align="center">
<br /><a href="admin.php?page=contact-tab-msg"><input type="text" class="button-primary" value="Back to Message List" style="text-align:center"></a>
<input id="ct_submit" type="submit" name="submit" class="button-primary" value="Reply to Message" style="text-align:center">
</td>
</tr>
</table>
</form>
</div>
<?php

}

//update unread to read
if(isset($_GET['replied']) && $replied == '0') {
$wpdb->query("UPDATE $table_name SET replied=1 WHERE id='$emailid'");

	}

//reply and update status to replied
if(isset($_GET['emailid']) && $_POST['submit']) {
global $options;
extract($options);
$email = $_POST['email'];
$subject = $_POST['subject'];
$message = nl2br($_POST['message']);
$headers = "From: $admin_email \r\n";
$headers .= "Reply-To: $admin_email \r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

mail($email, stripslashes_deep($subject), htmlspecialchars_decode(stripslashes_deep($message)), $headers); 
$wpdb->query("UPDATE $table_name SET replied=2 WHERE id='$emailid'");?>
<script>window.location = "/wp-admin/admin.php?page=contact-tab-msg"</script>;
<?php
	}

}

function contact_tab_docs() {
if (!current_user_can('manage_options'))  {
                 wp_die( __('You do not have sufficient permissions to access this page.') );
 
            }
 
 ?>
 <div class="wrap">
 
                <!-- Display Plugin Icon, Header, and Description -->
                 <div class="icon32" id="icon-options-general"><br></div>
                 <h2>Documentation</h2>
                <p></p>
 
                 <!-- Beginning of the Plugin Options Form -->
                         <?php settings_fields('contact_tab_docs'); ?>
                         <?php $options = get_option('contact_tab_docs'); ?>
                         <!-- Table Structure Containing Form Controls -->
			<div id="ct_doc">
    <h3><b>General Settings</b></h3>
    <div>
        <ul>
	<li><b>Default email</b>- Emails will be delivered to this address anytime someone fills out the contact form. By default, this email is the same as the email you have for WordPress under Settings >> General.</li>
 <li><b>Forward to</b>- Email from the contact form can be forwarded to an additional email address</li>
 <li><b>Email Subject</b>- The subject for the email from the contact form, by default this is the same as the site title for your site found under Settings >> General</li>
 <li><b>Contact Tab Position</b>- The tab can be positioned to the left, right, top left, top right, bottom left or bottom right</li>
 <li><b>Show captcha</b>- Option to show captcha. If you wish to show the captcha, the server your site is running on must have GD and FreeType installed. If you are not sure if they are installed, please ask your web host</li>
 <li><b>Show Social Networking icons</b>- Show icons for Facebook, Twitter, LinkedIn & Google+, each link has to be entered without http://. For Twitter, all that is needed is the username and not the full URL</li>
 <li><b>Redirect After Submit</b>- This option redirects to the "Redirect URL" after the form has been submitted</li>
 <li><b>Redirect URL</b>- URL to redirect to after successful submission of form</li>
 <li><b>Thank you message</b>- Message shown after form has been submitted. You can add HTML if you wish</li>
	</ul>
    </div>
    <h3><b>Appearance</b></h3>
    <div>
 The images below show the various parts of the contact form to help you customize its color: <br />
<img src="<?php global $shct_path; echo $shct_path; ?>docs/contacttab-appearance.jpeg" /> 
<img src="<?php echo $shct_path; ?>docs/contacttab-thankyou.jpeg" />
	</div>
    <h3><b>Messages</b></h3>
    <div>
You can view, reply to and delete emails received from Contact Tab under this Sub Menu. For more information on managing messages, please refer to <a href="http://webwiki.co/contact-tab" target="_blank">http://webwiki.co/contact-tab</a>
<ul>
 <li><b>View Message</b>- Click on the link under the Name heading to read an email</li>
 <li><b>Reply to Message</b>- Click on the link under the Name heading to view the email, type your reply then click "Reply to Message"</li>
 <li><b>Delete Message</b>- Check the box next to the message, then click on "Delete Selected".</li>
 <li><b>Mark Message as read</b>- Check the box next to the message, then click on "Mark as Read".</li>
 <li><b>Sort Emails</b>- Emails can be sorted by clicking on the various headings, Status, Name, Email, Message, Date, IP & URL.</li>
        </ul>
 
 </div>
    <h3><b>Support</b></h3>
    <div>
        <p>
	For addition support, please refer to the forum at <a href="http://webwiki.co/forum" target="_blank">http://webwiki.co/forum</a> or email support@webwiki.co
        </p>
    </div>
</div>	

<?php 
}

function shct_tab() { 
	$ctap_options = get_option('contact_tab_ap');	
	global $shct_path, $options;
if(is_array($ctap_options) || is_array($options)) {
	extract($ctap_options);
	extract($options);
}
if ($position == 'left')
require_once('left.php');
else if ($position == 'right')
require_once('right.php');
else if ($position == 'tright' || $position == 'tleft' )
require_once('top.php');
else if ($position == 'bright' || $position == 'bleft' )
require_once('bottom.php');
}
	
$options = get_option('contact_tab_settings');
$ctap_options = get_option('contact_tab_ap');

//Dashboard Widget
function ct_dashboard_widget() {
global $wpdb;
$table_name = $wpdb->prefix . "contact_tab";
$count = $wpdb->get_results("SELECT * from $table_name");
$total_results = $wpdb->num_rows;
$wpdb->get_results("SELECT * from $table_name where replied='0'");
$unread = $wpdb->num_rows;
$wpdb->get_results("SELECT * from $table_name where replied='2'");
$replied = $wpdb->num_rows;
?>
<table>
<tr><td><a href="admin.php?page=contact-tab-msg" style="font-size: 14px; font-weight: bold"><?php echo $total_results; ?> Email(s)</a></td></tr>
<tr><td><a href="admin.php?page=contact-tab-msg" style="font-size: 14px; font-weight: bold; color: red"><?php echo $unread; ?> Unread</a></td></tr>
<tr><td><a href="admin.php?page=contact-tab-msg" style="font-size: 14px; font-weight: bold; color: green"><?php echo $replied; ?> Replied</a></td></tr>
</table>
<?php
} 

// Create the function use in the action hook
function ct_add_dashboard_widgets() {
	wp_add_dashboard_widget('ct_dashboard_widget', 'Contact Tab', 'ct_dashboard_widget');	
} 

add_action('wp_dashboard_setup', 'ct_add_dashboard_widgets' );
