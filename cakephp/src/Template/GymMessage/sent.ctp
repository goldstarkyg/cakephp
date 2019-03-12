<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script>
$(document).ready(function(){		
	$(".mydataTable").DataTable({
		"responsive": true,
		//"order": [[ 1, "asc" ]],
		"aoColumns":[	                  
	                  {"bSortable": false},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},	                  
	                  {"bSortable": false,"visible":true}],
		"language" : {<?php echo $this->Gym->data_table_lang();?>}
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
				<?php echo __("Sent Messages");?>
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
				<h2 class="no-margin"><?php echo __("Sent Messages");?></h2>
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
			<div class="mailbox-content">			    
			 	<table class="mydataTable table table-striped">
			 		<thead>
			 			<tr> 
			 			    <th></th>					
							<th class="hidden-xs"><span><?php echo __("Message To");?></span></th>				
							<th><?php echo __("Subject");?></th>
							<th><?php echo __("Description");?></th>
							<th><?php echo __("Date");?></th>
							<th><?php echo __("Action");?></th>
			 			</tr>
			 		</thead>
			 		<tbody> 		
			 		<?php
					if(!empty($messages))
					{
                        $messages_length = count($messages);
                        $num = 0;					    
						foreach($messages as $message)
						{
						    $subject = base64_decode($message['subject'], 'utf-8');
                            $message_body= base64_decode($message['message_body'], 'utf-8');
							echo "<tr>
							    <td><input type='checkbox' id='message_{$num}' value='{$message['id']}'></td>
								<td>{$message["GymMember"]['first_name']} {$message["GymMember"]['last_name']}</td>
								<td><a href='".$this->request->base ."/GymMessage/viewSentMessage/{$message['id']}'>{$subject}</a></td>
								<td>{$message_body}</td>
								<td>{$message['date']}</td>
								<td>";
							echo "<a href='{$this->request->base}/GymMessage/deleteMessage/{$message['id']}' title='Delete' class='btn btn-flat btn-danger' 	 onClick=\"return confirm('Are you sure,You want to delete this message?');\"><i class='fa fa-trash-o'></i>&nbsp;&nbsp;Delete</a>";
							echo "</td>
							</tr>";
							$num++;
						}
					}
					else{
						echo "<tr><td colspan='4'><i>Your inbox is empty.</i></td></tr>";
					}
					?>
			 		</tbody>
			 	</table>
			 	<input type='hidden' id='messages_length' value='<?php echo $messages_length; ?>'>
			 </div>
			 <div class="message-options">
			        <input type='hidden' id='all_delete_url' value='<?php echo $this->request->base . "/GymMessage/deleteMessageAll/";?>'>
					<a class="btn btn-flat btn-danger" href="javascript:messages_delete();" onclick="return confirm('Do you really want to delete selected messages?');"><i class="fa fa-trash m-r-xs"></i> <?php echo __("Delete Messages");?></a> 
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
    function messages_delete(){
        var url = $("#all_delete_url").val();
        var messages_length = $("#messages_length").val();
        var ids = "";
        var flag = 0;
        for(var i = 0; i < messages_length; i++){
           var checked = $("#message_"+i.toString()).is(":checked");
           if(checked){
                var id = $("#message_"+i.toString()).val();
                if(flag == 0){
                     ids =  id.toString();
                }else{
                     ids = ids + "," + id.toString(); 
                }
                flag = 1;
           }
           
        }
        if(ids == ""){
            alert("Messages not selected !");
        }else{
            url = url + ids;
            window.location.href = url;     
            //alert(url);
        }
        

    }
</script>