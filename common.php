<style type="text/css">
#contact_tab a {
text-decoration: none;
}
#contact_tab input[type="text"] {
padding: 0px;
line-height: 0px;
}
#contact_tab #ct_code{
        width:100px;
        text-align: left;
        -webkit-border-radius: 10px;
        -moz-border-radius: 10px;
        border-radius: 10px;
        display:inline-block;

}

#contact_tab .ct_error{
color:#<?php echo $etxt; ?>;
font-size:14px;
font-style:italic;
text-align: center;
display: inline-block ;
font-weight: bold;
font-family: arial;
}
#contact_tab .ct_success{
color:#<?php echo $stxt ?>;
font-size:14px;
font-style:italic;
text-align: center ;
display: block;
font-weight: bold;
font-family: arial;
margin-left: 5px;
margin-right: 5px;
}

#contact_tab #ct_refresh{
        text-align: center;
        cursor:pointer;

}

#contact_tab #ct_name,#ct_email, #ct_subject{ display:inline-block; text-align: center;border:#CCCCCC 1px solid; width: 203px; -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px }

#contact_tab #ct_message{ display:inline-block; width:248px; height:100px;text-align: left;margin-bottom:3px; border:#CCCCCC 1px solid; -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px; overflow: auto;
 }

<?php if(!empty($intro)) { ?>
#ct_intro { 
padding: 20px;
padding-bottom: 0px;
text-align: center;
}
<?php } ?>

#contact_tab label.shct,#ct_intro { color: #<?php echo $txt ?> }
#contact_tab #ct_send{ border:#2A30C6 solid 1px; text-align: center ; background:#<?php echo $bcolor; ?>; color:#FFFFFF; padding: 5px;}

#contact_tab .ct_social { display: inline-block }

</style>

<script>

jQuery(document).ready(function() {

         jQuery('#ct_send').click(function() {

                        // name validation

                        var nameVal = jQuery("#ct_name").val();
                        if(nameVal == '' || nameVal.toLowerCase() == 'Name'.toLowerCase()) {

                                jQuery("#ct_name_error").html('');
                                jQuery("#ct_name").after('<label class="ct_error" id="ct_name_error">Please enter your name.</label>');
                                return false
                        }
                        else
                        {
                                jQuery("#ct_name_error").html('');
                        }

                        // subject validation
                        var subjectVal =jQuery("#ct_subject").val();
                        if(subjectVal == '' || subjectVal.toLowerCase() == 'Subject'.toLowerCase()) {

                                jQuery("#ct_subject_error").html('');
                                jQuery("#ct_subject").after('<label class="ct_error" id="ct_subject_error">Please enter a subject</label>');
                                return false
                        }
                        else
                        {
                                jQuery("#ct_subject_error").html('');
                        }
/// email validation

                        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
                        var emailaddressVal = jQuery("#ct_email").val();

                        if(emailaddressVal == '') {
                                jQuery("#ct_email_error").html('');
                                jQuery("#ct_email").after('<label class="ct_error" id="ct_email_error">Please enter your email address.</label>');
                                return false
                        }
                        else if(!emailReg.test(emailaddressVal)) {
                                jQuery("#ct_email_error").html('');
                                jQuery("#ct_email").after('<label class="ct_error" id="ct_email_error">Enter a valid email address.</label>');
                                return false

                        }
                        else
                        {
                                jQuery("#ct_email_error").html('');
                        }

     // message validation

                        var messageVal = jQuery("#ct_message").val();
                        var msglen = messageVal.length;
                        if(messageVal == '' || messageVal == 'Type a Message\/Comment..' ) {

                                jQuery("#ct_message_error").html('');
                                jQuery("#ct_message").after('<label class="ct_error" id="ct_message_error">Please type a message.</label>');
                                return false
                        }
                        else if( msglen < 10 ){
                        jQuery("#ct_message_error").html('');
                        jQuery("#ct_message").after('<label class="ct_error" id="ct_message_error">Message too short, please type some more..</label>');
                                return false

                        }
                        else
                        {
                                jQuery("#ct_message_error").html('');
                        }


                        jQuery.post("<?php echo plugins_url('/contact-tab-pro/'); ?>post.php", ( jQuery("#shct_form").serialize()), 
                         function(response){

                        if(response==1)
                        {
                                jQuery("#ct_after_submit").html('');
                                //jQuery("#after_submit").fadeOut(2000);
                                //change_captcha();
                               <?php if($captcha == 'yes') echo 'change_captcha();'; ?>
                                clear_form();
                                //jQuery("#Send").after('<br /><label class="success" id="after_submit">Your message has been submitted.</label>');
                                close_div();
                                close_tab();
<?php
if(!empty($redurl) && $red == 'yes') $redirect = 'window.location = "http://'.preg_replace('/http:\/\//i','',$redurl).'";';
echo $redirect;
?>

                        }
                        else
                        {
                                jQuery("#ct_after_submit").html('');
                                jQuery("#ct_send").after('<label class="ct_error" style="display: block" id="ct_after_submit">Error ! invalid captcha code .</label>');
                                //jQuery("#after_submit").fadeOut(20000);
                        }


                });

                return false;
         });

         // refresh captcha
         jQuery('img#ct_refresh').click(function() {

                        change_captcha();
                                jQuery("#ct_after_submit").fadeOut(2000);
         });

function change_captcha()
         {
                document.getElementById('ct_captcha').src="<?php echo plugins_url('/contact-tab-pro/'); ?>get_captcha.php?rnd=" + Math.random();
                jQuery("#ct_code").val('');
         }

         function clear_form()
         {
                jQuery("#ct_name").val('');
                jQuery("#ct_email").val('');
                jQuery("#ct_message").val('');
                jQuery("#ct_code").val('');
                jQuery("ct_subject").val(''); 
        }
function close_div()
        {
         jQuery("#ct_name,#ct_email,#ct_message,#ct_send,#ct_code,#ct_refresh,#ent_code,#ct_captcha,.ct_social,#ct_subject,#ct_intro").css('display', 'none');
         jQuery(".ct_form").prepend('<br /><label class="ct_success" style="margin-top:150px; text-align: center" id="ct_after_submit"><?php echo $thx; ?></label>');

        }
});
</script>







