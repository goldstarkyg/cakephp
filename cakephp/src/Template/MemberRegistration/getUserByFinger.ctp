<?php
echo $this->Html->script('jQuery/jQuery-2.1.4.min.js');
echo $this->Html->script('jquery-ui.min');
echo $this->Html->css('bootstrap.min');

$is_rtl = $this->Gym->getSettings("enable_rtl");
if($is_rtl)
{
	echo $this->Html->css('bootstrap-rtl.min');
}
echo $this->Html->script('bootstrap/js/bootstrap.min.js');
echo $this->Html->css('plugins/datepicker/datepicker3');
echo $this->Html->script('datepicker/bootstrap-datepicker.js');
$dtp_lang = $this->gym->getSettings("datepicker_lang");
echo $this->Html->script("datepicker/locales/bootstrap-datepicker.{$dtp_lang}");
echo $this->Html->css('bootstrap-multiselect');
echo $this->Html->css('loaders.css');
echo $this->Html->script('bootstrap-multiselect');
echo $this->Html->css('validationEngine/validationEngine.jquery');
echo $this->Html->script('validationEngine/languages/jquery.validationEngine-en');
echo $this->Html->script('validationEngine/jquery.validationEngine'); 
?>
<style>
.content{   
   padding-bottom: 0;
}

body *{
	    font-family: "Roboto", sans-serif;
}
.datepicker.dropdown-menu {   
    max-width: 300px;
}
.form-control {
    height: 34px !important;
	font-size: 14px !important;
}
#form-head{
	color : #eee;
}
</style>
<script type="text/javascript">
 document.addEventListener('DOMContentLoaded', function () {
      document.querySelector('main').className += 'loaded';
    });

$(document).ready(function() {

   var finger_id = 1;
   $.ajax({
           url:'/MemberRegistration/getUserByFinger',
           type:'GET',
           data:'finger_id='+finger_id,
           success:function(msg){
                alert(msg);
           }
       });

   /* var attendance_id = setInterval(function(){
        $.ajax({
            url:'http://localhost:8008/fingerprintservice/login',
            type:'GET',
             crossDomain : true,
            data:'',
            success:function(msg){

            }
        });

    },1000);*/
});


function createCORSRequest(method, url) {
  var xhr = new XMLHttpRequest();
  if ("withCredentials" in xhr) {

    // Check if the XMLHttpRequest object has a "withCredentials" property.
    // "withCredentials" only exists on XMLHTTPRequest2 objects.
    xhr.open(method, url, true);

  } else if (typeof XDomainRequest != "undefined") {

    // Otherwise, check if XDomainRequest.
    // XDomainRequest only exists in IE, and is IE's way of making CORS requests.
    xhr = new XDomainRequest();
    xhr.open(method, url);

  } else {

    // Otherwise, CORS is not supported by the browser.
    xhr = null;

  }
  return xhr;
}
</script>
<section class="content">
	<br>
	<div class="col-md-3 box box-default">
	</div>
	<div class="col-md-6 box box-default">
		<div class="box-header">
			<section class="content-header">
			  <h3 id='form-head'>
				<i class="fa fa-user"></i>
				<?php echo __("Member Attendance");?>
			  </h3>			  
			</section>
		</div>
		<div class="panel" style="    height: 350px;     text-align: center;">
		<?php

			echo "<fieldset><legend>". __('')."</legend>";
			echo "<br>";
        ?>

        <div style="padding: 43px; color: #116d8c; font-size: 20px;">

         <div>Welcome to visit our gym! </div>
         <div>Please touch the device for attendance.</div>

         <main>
                  <div class="loaders">
                     <div class="loader">
                         <div class="loader-inner ball-scale-multiple">
                                   <div></div>
                                   <div></div>
                                   <div></div>
                                 </div>
                      </div>
                   </div>
                    </main>
        </div>
          <div style="height:40px;"></div>
        <?php
			echo "</fieldset>";


		?>
		<input type="hidden" value="<?php echo $this->request->base;?>/MemberRegistration/getMembershipEndDate/" id="mem_date_check_path">

		</div>
	</div>
</section>
 <script>
		$(".membership_status_type").change(function(){
			if($(this).val() == "Prospect" || $(this).val() == "Alumni" )
			{
				$(".class-member").hide("SlideDown");
				$(".class-member input,.class-member select").attr("disabled", "disabled");				
			}else{
				$(".class-member").show("SlideUp");
				$(".class-member input,.class-member select").removeAttr("disabled");	
				$("#available_classes").attr("disabled", "disabled");
			}
		});
	if($(".membership_status_type:checked").val() == "Prospect" || $(".membership_status_type:checked").val() == "Alumni")
	{ 
		$(".class-member").hide("SlideDown");
		$(".class-member input,.class-member select").attr("disabled", "disabled");		
	}
</script>