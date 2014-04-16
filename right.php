<?php
ob_start();
require_once('common.php');
?>
<script>
jQuery(document).ready(function(){ 


	jQuery("a.ct_tab").toggle( 
				function () {
					jQuery(".ct_form").css('display', 'block'); 
 					jQuery(".ct_container").animate({marginRight: "0px"}) 
                }, 
                function () { 
					jQuery(".ct_container").animate({marginRight: "-300px"})  
				} 
		); 
        
}); 
</script> 
<style type="text/css">
#contact_tab .ct_container{
position: fixed;
_position: absolute;
top: 30px;
width: 330px; 
z-index: 10001;
right:0px;
margin-right: -300px;
}




#contact_tab a.ct_tab {
right: 255px;
*right: 300px;
right: 300px\0/;
background-color: #<?php echo $tabcolor; ?>;
text-decoration: none;
outline: 0;
float: right;
height: 30px;
width: 120px;
line-height: 30px;
text-align: center;
top: 120px;
*top: 90px;
top: 70px\0/;
position: absolute;
font-weight: bold;
font-family: arial;
font-size: 18px;
border-left: none;
-webkit-transform:rotate(270deg);
        -moz-transform:rotate(270deg);
        -o-transform: rotate(270deg);
filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3);
 -webkit-box-shadow: 0 1px 5px rgba(0,0,0,0.75);
  -moz-box-shadow: 0 1px 5px rgba(0,0,0,0.75);
  box-shadow: 0 1px 5px rgba(0,0,0,0.75);
border-radius: 10px 10px 0px 0px;
display: block;
}

#contact_tab .ct_form {
display: none;
float: right;
width: 300px;
background-color: #<?php echo $bgcolor; ?>;
 -webkit-box-shadow: 0 1px 5px rgba(0,0,0,0.75);
  -moz-box-shadow: 0 1px 5px rgba(0,0,0,0.75);
  box-shadow: 0 1px 5px rgba(0,0,0,0.75);
border-radius: 10px 0px 0px 10px;
overflow: hidden;
}

</style>

<div id="contact_tab">
<div class="ct_container">
<div class="ct_form">
<!--form-->

<script>

        function close_tab() {
                                        
	setTimeout( "jQuery('.ct_container').animate({marginRight: '-300px'});", 2000);
                                }
                


</script>

<div style="text-align: center">
<?php 
require_once('form.php');
?>
</div>
</div>
<a href="#" class="ct_tab" style="text-derocation: none; color: #<?php echo $tabtxt; ?>">Contact Us</a>
</div>
</div>
