<?php 
    echo $this->Html->css('emoji/jquery.emojipicker');
    echo $this->Html->script('emoji/jquery.emojipicker'); 
    echo $this->Html->css('emoji/jquery.emojipicker.tw');
    echo $this->Html->script('emoji/jquery.emojis');

    echo $this->Html->css('editor/editor');
    echo $this->Html->script('editor/editor');

    $session = $this->request->session()->read("User");


?>

  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<script>
$(document).ready(function(){	
    //$('#subject').emojiPicker();
    $("#body").Editor();

	$(".mydataTable").DataTable({
		"responsive": true,
		"order": [[ 1, "asc" ]],
		"aoColumns":[
		              {"bSortable": false},
	                  {"bSortable": false},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true}],
		"language" : {<?php echo $this->Gym->data_table_lang();?>}
	});
});
</script>
<style>
     .dataTables_wrapper{ padding-top:20px; }
</style>

<section class="content">
	<br>
	<div class="col-md-12 box box-default">		
		<div class="box-header">
			<section class="content-header">
			  <h1>
				<i class="fa fa-bars"></i>
				<?php echo __("Email Send");?>				
			  </h1>
			   
			</section>
		</div>
		<hr>
		<div class="box-body">
			<!-- <div class="form-group">
				<label class="col-sm-2 control-label" for="subject"><?php echo __("Mail Subject");?> <span class="text-danger">*</span></label>
				<div class="col-sm-10">
				<input id="subject" class="form-control validate[required] text-input" type="text">
				</div>
			</div> -->
			<div class="form-group">
				<label class="col-sm-2 control-label" for="subject"><?php echo __("Mail Comment");?> <span class="text-danger">*</span></label>
				<div class="col-sm-10">
				  <textarea id="body" class="form-control validate[required] text-input"></textarea>				  
				</div>							
			</div>	
		<table class="mydataTable table table-striped">
			<thead>
				<tr>
					<th></th>
					<th><?php echo __("Photo");?></th>
					<th><?php echo __("Member Name");?></th>
					<th><?php echo __("Member ID");?></th>	
					<th><?php echo __("Email");?></th>				
					<th><?php echo __("Joining Date");?></th>					
					<th><?php echo __("Expire Date");?></th>					
					<th><?php echo __("Member Type");?></th>					
					<th><?php echo __("Membership Status");?></th>
				</tr>
			</thead>
			<tbody>
			<?php
			    $mail_length = count($data);
			    $num = 0;
				foreach($data as $row)
				{
					echo "<tr>
					<td><input type='checkbox' id='mail_{$num}' value='{$row['email']}'></td>
					<td><img src='{$this->request->base}/webroot/upload/{$row['image']}' class='membership-img img-circle'></td>
					<td>{$row['first_name']} {$row['last_name']}</td>
					<td>{$row['member_id']}</td>
					<td>{$row['email']}</td>
					<td>".(($row['membership_valid_from'] != '')?date($this->Gym->getSettings("date_format"),strtotime($row['membership_valid_from'])):'Null')."</td>
					<td>".(($row['membership_valid_to'] != '')?date($this->Gym->getSettings("date_format"),strtotime($row['membership_valid_to'])):'Null')."</td>
					<td>{$row['member_type']}</td>
					<td>{$row['membership_status']}</td>					
					</tr>";
					$num++;
				}
			?>
			</tbody>
			<tfoot>
				<tr>
					<th></th>
					<th><?php echo __("Photo");?></th>
					<th><?php echo __("Member Name");?></th>
					<th><?php echo __("Member ID");?></th>
					<th><?php echo __("Email");?></th>					
					<th><?php echo __("Joining Date");?></th>					
					<th><?php echo __("Expire Date");?></th>					
					<th><?php echo __("Member Type");?></th>					
					<th><?php echo __("Membership Status");?></th>
				</tr>
			</tfoot>
		</table>
		<input type='hidden' id='mail_length' value='<?php echo $mail_length; ?>'>

		<form name="class_form" action="" method="post" class="form-horizontal" id="mail_send_form">			
            <input type='hidden' name='mails_to' id='mails_to' value=''>
            <input type='hidden' name='mail_subject' id='mail_subject' value=''>
            <input type='hidden' name='mail_body' id='mail_body' value=''> 			
			<div class="message-options" style="margin-top: 10px;margin-bottom: 20px">		        
				<a class="btn btn-flat btn-danger" href="javascript:send_mail();" onclick="return confirm('Do you want to send Email to selected users?');"><i class="fa fa-commenting m-r-xs"></i> <?php echo __("Send Email");?></a> 
			</div>	
            
        </form>
		
		<div class="overlay gym-overlay">
		  <i class="fa fa-refresh fa-spin"></i>
		</div>
	</div>
</section>
<script>
    function send_mail(){        
        var mail_length = $("#mail_length").val();
        //var mail_subject = $("#subject").val();
        var mail_body = $(".Editor-editor").html();
        var ids = "";
        var flag = 0;

        for(var i = 0; i < mail_length; i++){
           var checked = $("#mail_"+i.toString()).is(":checked");
           if(checked){
                var id = $("#mail_"+i.toString()).val();
                if(flag == 0){
                     ids =  id.toString();
                }else{
                     ids = ids + "," + id.toString(); 
                }
                flag = 1;
           }
           
        }
        if(ids == ""){
            alert("Uesrs not selected !");
        }else if(mail_body == ""){
        	alert("Please Input Mail Comment !");
        }else{

            //$("#mail_subject").val(mail_subject);
            $("#mail_body").val(mail_body);        	

        	$("#mails_to").val(ids);
            $("#mail_send_form").submit();                 
        }
        

    }
</script>
