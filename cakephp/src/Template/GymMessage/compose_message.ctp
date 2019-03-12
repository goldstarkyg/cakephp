<?php 
    echo $this->Html->css('emoji/jquery.emojipicker');
    echo $this->Html->script('emoji/jquery.emojipicker'); 
    echo $this->Html->css('emoji/jquery.emojipicker.tw');
    echo $this->Html->script('emoji/jquery.emojis');
?>
  
<script>
$(document).ready(function(){
	$(".hasdatepicker").datepicker({ rtl: true});	

	$('#subject').emojiPicker();

	$('#create').click(function(e) {
        e.preventDefault();
        $('#message_body').emojiPicker({
           button: false
        });
        $('#message_body').emojiPicker('toggle');
    });    
});

</script>
<section class="content">
	<br>
	<div class="col-md-12 box box-default">		
		<div class="box-header">
			<section class="content-header">
			  <h1>
				<i class="fa fa-plus"></i>
				<?php echo __("Compose Message");?>
				<small><?php echo __("Message");?></small>
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
				<a class="btn btn-flat btn-success btn-block" href="<?php echo $this->request->base;?>/GymMessage/composeMessage/"><?php echo __("Compose");?></a>
			</div>
			<div class="col-md-6">
				<h2 class="no-margin"><?php echo __("Compose");?></h2>
			</div>
		</div>
			
			
		<div class="col-md-2 no-padding-left">
			<ul class="list-unstyled mailbox-nav">
				<li>
				<a href="<?php echo $this->request->base;?>/GymMessage/inbox"><i class="fa fa-inbox"></i>&nbsp;<?php echo __("Inbox");?> <span class="badge badge-success pull-right"><?php echo $unread_messages;?></span></a></li>
				<li>
				<a href="<?php echo $this->request->base;?>/GymMessage/sent"><i class="fa fa-sign-out"></i>&nbsp;<?php echo __("Sent");?></a></li>                                
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
				<label class="col-sm-2 control-label" for="to"><?php echo __("Message To");?> <span class="text-danger">*</span></label>
				<div class="col-sm-8">
					<?php $options = $this->Gym->get_member_list_for_message();
					echo $this->Form->select("receiver",$options,["class"=>"form-control text-input"]);
					?>
					
				</div>	
			</div>
			  <div id="smgt_select_class">
			<div class="form-group">
				<label class="col-sm-2 control-label" for="sms_template"><?php echo __("Select Class");?></label>
				<div class="col-sm-8">				
					 <?php echo $this->Form->select("class_id",$classes,["empty"=>__("None"),"class"=>"form-control"]); ?>
				</div>
			</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="subject"><?php echo __("Subject");?> <span class="text-danger">*</span></label>
				<div class="col-sm-8">
				<input id="subject" class="form-control validate[required] text-input" type="text" name="subject">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="subject"><?php echo __("Message Comment");?> <span class="text-danger">*</span></label>
				<div class="col-sm-8">
				  <textarea name="message_body" id="message_body" class="form-control validate[required] text-input"></textarea>

				  <input id="create" value="<?php echo __("Emoji Create"); ?>" class="btn btn-default" style="margin-top:10px;width:30%;"> 
				  
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
						<input type="submit" value="<?php echo __("Send Message"); ?>" name="save_message" class="btn btn-flat btn-success">
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