//reply
jQuery(document).ready(function(){
 jQuery('#ct_rrmail').submit(function() {
	jQuery(this).attr('disabled', true);
	email = jQuery('#email').val();
	subject = jQuery('#subject').val();
	message = jQuery('#message').val();
jQuery('#ct_rrmail').submit();
return false;
	});
});
//Sort table
jQuery(document).ready(function()
{	
	jQuery('#ct_emaillist').tablesorter();
}
);
//table colors
jQuery(document).ready(function() {

 jQuery('#ct_emaillist tbody tr').each(function() {

var status = jQuery(this).children('td').first().text();
if(status == 'Unread' ) {
 jQuery(this).addClass('ct_unread');
}
if ( status == 'Replied' ) {
 jQuery(this).addClass('ct_replied');
}
if ( status == 'Read' ) { 
 jQuery(this).addClass('ct_read');
}
	});
});
//documentation accordion
jQuery(document).ready(function() {
jQuery(function() {
        jQuery( "#ct_doc" ).accordion();
	 heightStyle: "content";
	collapsible: true
    });
});
