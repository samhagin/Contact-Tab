<form action="#" name="shct_form" id="shct_form">
<div id="ct_intro">
<?php echo esc_html($intro); ?>
</div>
<br />
        <input name="ct_name" onfocus="if(this.value == 'Name'){this.value = '';}" type="text" onblur="if(this.value == ''){this.value='Name';}" id="ct_name" value="Name" size="30">
        <br clear="all"/>
        <input name="ct_subject" onfocus="if(this.value == 'Subject'){this.value = '';}" type="text" onblur="if(this.value == ''){this.value='Subject';}" id="ct_subject" value="Subject" size="30">
 
         <input name="ct_email" onfocus="if(this.value == 'Email'){this.value = '';}" type="text" onblur="if(this.value == ''){this.value='Email';}" id="ct_email" value="Email" size="30">
        <br clear="all"/>

       <br clear="all"/>

        <textarea  name="ct_message" id="ct_message" rows="6"  onFocus="if(this.value == 'Type a Message/Comment..') {this.value = ''}; return false;">Type a Message/Comment..</textarea>
        <br clear="all"/><br clear="all"/>

<?php if($options['captcha'] == 'yes') { ?>
      <img src="<?php echo plugins_url('/contact-tab-pro/'); ?>get_captcha.php" alt="" id="ct_captcha" style="text-align: center" />
<img src="<?php echo plugins_url('/contact-tab-pro/'); ?>images/refresh.png" alt="" id="ct_refresh" width="25" height="25" style="background-color: <?php echo $bgcolor ?>"/>
                <br clear="all"/>
                <label id="ent_code" class="shct">Enter the code above:</label><br clear="all"/>
                <input name="ct_code" type="text" id="ct_code">
        <br clear="all" /><br />
<input type="hidden" id="ct_pageurl" value="" name="ct_pageurl" />
<input type="text" id="ct_null" value="" name="ct_null" style="display: none"/>
<?php } ?>
        <input value="Send" type="submit" id="ct_send" style="border-radius: 5px;" />
<br clear="all" />
<br clear="all" />
        <script>document.getElementById('ct_pageurl').value = window.location.href;</script>
</form>
<?php if($social == 'yes') {
global $shct_path;
if(!empty($facebook)) echo '<a class="ct_social" target="_blank" href="http://'.$facebook.'"><img src='.$shct_path.'images/facebook.png /></a> ';
if(!empty($twitter))  echo '<a class="ct_social" target="_blank" href="http://twitter.com/'.$twitter.'"><img src='.$shct_path.'images/twitter.png /></a> ';
if(!empty($linkedin)) echo '<a class="ct_social" target="_blank" href="http://'.$linkedin.'"><img src='.$shct_path.'images/linkedin.png /></a> ';
if(!empty($google)) echo '<a class="ct_social" target="_blank" href="http://'.$google.'"><img src='.$shct_path.'images/google.png /></a> ';
}
?>
