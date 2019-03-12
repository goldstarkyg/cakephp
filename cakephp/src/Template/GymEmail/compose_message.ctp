<?php     
    echo $this->Html->css('editor/editor');
    echo $this->Html->script('editor/editor');
?>
<script>
$(document).ready(function(){	
    
    $("#body").Editor();	
});
</script>
<section class="content">
	<br>
	<div class="col-md-12 box box-default">		
		<div class="box-header">
			<section class="content-header">
			  <h1>
				<i class="fa fa-plus"></i>
				<?php echo __("Compose Email");?>
				<small><?php echo __("Email");?></small>
			  </h1>
			  <ol class="breadcrumb">
				<!-- <a href="<?php // echo $this->Gym->createurl("GymNotice","NoticeList");?>" class="btn btn-flat btn-custom"><i class="fa fa-bars"></i> <?php //echo __("Notice List");?></a> -->
			 </ol>
			</section>
		</div>
		<hr>
		<div class="box-body">
		<div class="row mailbox-header">
			<div class="col-md-2">
				<a class="btn btn-flat btn-success btn-block" href="<?php echo $this->request->base;?>/GymEmail/composeMessage/"><?php echo __("Compose");?></a>
			</div>
			<div class="col-md-6">
				<h2 class="no-margin"><?php echo __("Compose");?></h2>
			</div>
		</div>
			
			
		<div class="col-md-2 no-padding-left">
			<ul class="list-unstyled mailbox-nav">				
				<li>
				<a href="<?php echo $this->request->base;?>/GymEmail/sent"><i class="fa fa-sign-out"></i>&nbsp;<?php echo __("Sent");?></a></li>                                
			</ul>
		</div>
		<div class="col-md-10">
			 <script type="text/javascript">
			$(document).ready(function(){
				$('#message_form').validationEngine();
			});
			</script>
			<div class="mailbox-content">
			<h2></h2>
			<form name="class_form" action="" method="post" class="form-horizontal" id="message_form">
			<input type="hidden" name="action" value="insert">
			<div class="form-group">
				<label class="col-sm-2 control-label" for="to"><?php echo __("Email To");?> <span class="text-danger">*</span></label>
				<div class="col-sm-8">
					<?php $options = $this->Gym->get_member_list_for_message();
					echo $this->Form->select("receiver",$options,["class"=>"form-control text-input"]);
					?>
					
				</div>	
			</div>
			<div id="smgt_select_class" style="display:none">
				<div class="form-group">
					<label class="col-sm-2 control-label" for="sms_template"><?php echo __("Select Class");?></label>
					<div class="col-sm-8">				
						 <?php echo $this->Form->select("class_id",$classes,["empty"=>__("None"),"class"=>"form-control"]); ?>
					</div>
				</div>
			</div>
			<!-- <div class="form-group">
				<label class="col-sm-2 control-label" for="subject"><?php echo __("Subject");?> <span class="text-danger">*</span></label>
				<div class="col-sm-8">
				<input id="subject" class="form-control validate[required] text-input" type="text" name="subject">
				</div>
			</div> -->
			<div class="form-group">
				<label class="col-sm-2 control-label" for="subject"><?php echo __("Email Comment");?> <span class="text-danger">*</span></label>
				<div class="col-sm-8">
					<input type='hidden' name='message_body' id='message_body' value=''>
				  	<textarea name="body" id="body" class="form-control validate[required] text-input"></textarea>				  
				</div>							
			</div>											
			<!--	
			<div id="hmsg_message_sent" class="hmsg_message_none">
			<div class="form-group">
				<label class="col-sm-2 control-label" for="sms_template"><?php //echo __("SMS Text");?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<textarea name="sms_template" class="form-control validate[required]" maxlength="160"></textarea>
					<label><?php //echo __("Max 160 Character");?>.</label>
				</div>
			</div>
			</div> -->			
			<div class="form-group">
				<div class="col-sm-10">
					<div class="pull-right">
						<!-- <input type="submit" value="<?php echo __("Send Email"); ?>" name="save_message" class="btn btn-flat btn-success"> -->

						<a class="btn btn-flat btn-success" href="javascript:send_mail();" onclick="return confirm('Do you want to send Email?');"><?php echo __("Send Email");?></a> 
					</div>
				</div>



					
				
			</div>
			</form>				
			</div>
		</div>
		
		
		
		<!-- END -->
		</div>
		<div class='overlay gym-overlay'>
			<i class='fa fa-refresh fa-spin'></i>
		</div>
	</div>
</section>
<script>
    function send_mail(){        
        var mail_body = $(".Editor-editor").html();
        if(mail_body == ""){
        	alert("Please Input Mail Comment !");
        }else{
            
            $("#message_body").val(mail_body);        	
        	
            $("#message_form").submit();                 
        }
        

    }
</script>