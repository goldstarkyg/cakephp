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

function startFingerPrint(){
    $.ajax({
       url:'http://localhost:8008/fingerprintservice/login',
       type:'GET',
        crossDomain : true,
       data:'',
       success:function(template_id){
           if(template_id != ""){

               $("#detected_text").css('display','block');
                  setTimeout(function(){
                          $("#detected_text").css('display','none');
                 },3000);

                $.ajax({
                            url:'/dev/MemberRegistration/getUserByFinger',
                            type:"POST",
                            data:{
                            'id':template_id
                            },
                            success:function(response){
                               if(response != "")
                                      $("#userinfo_section").html(response);
                                setTimeout(function(){
                                        startFingerPrint();
                                 },500);

                            }
                    });
           }
           else{
               setTimeout(function(){
                      startFingerPrint();
                 },500);
           }
       },
       error: function(msg){
            $("#userinfo_section").html("<div style='font-size:20px; color:red;'>Please make sure the device connection!</div>");
            setTimeout(function(){
                  startFingerPrint();
             },500);
       }

   });
}
$(document).ready(function() {
    startFingerPrint();
});

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
		<div class="panel" style="   text-align: center;">
		<?php

			echo "<fieldset><legend>". __('')."</legend>";
			echo "<br>";
        ?>

        <div style="padding: 20px; color: #116d8c; font-size: 20px;">

         <div>Welcome to visit our gym! </div>
         <div>Please touch the device for attendance.</div>
         <div style=" color: red;text-align: center; display: none; font-size: 35px; margin-top: 20px;" id="detected_text">Detected! </div>
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
        <div id="userinfo_section"></div>
        <div style="height:30px;"></div>
        <?php
			echo "</fieldset>";
		?>

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