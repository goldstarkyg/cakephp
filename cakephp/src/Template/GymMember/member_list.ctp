<?php $session = $this->request->session()->read("User");


?>

  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<script>
$(document).ready(function(){		
	$(".mydataTable").DataTable({
		"responsive": true,
		"order": [[ 1, "asc" ]],
		"aoColumns":[
	                  {"bSortable": false},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},	                           
	                  {"bSortable": false,"visible":false},
	                  {"bSortable": false,"visible":false}],
		"language" : {<?php echo $this->Gym->data_table_lang();?>}
	});
});

function openCardInfo(id,user_image,card_image,first_name,last_name,gender,birth_date,address){
	$('.ui-dialog').show();
	$('.ui-widget-overlay').show();

	var base_url = $("#base_url").val();
	$('#card_href').attr('href', base_url + '/GymMember/printCard/' + id);

    if(card_image == '' || card_image == null)
        card_image="card-image.png";
    var img = '../webroot/upload/'+card_image;
    $('.ui-dialog').css('z-index','100001'); 

    if(user_image == '' || user_image == null)
        user_image="logo.png";
    var photo = '../webroot/upload/'+user_image;
    $('#user_img').attr('src',photo);  

    $('#card_content').css({'background':'url(' + img + ') no-repeat','background-size'  : '470px 250px'});  
    $('#name').text(first_name+" "+last_name);
    $('#birthday').text(birth_date);
    $('#gender').text(gender);
    $('#address').text(address);
    $( "#CardDialog" ).dialog({
	     'width':'500px',
	      modal: true,
	      resizable: false,
	});
    
    
 }

</script>


<style>
    .member-card-info{
        position: absolute;    
        top: 50px;
        left: 150px;
        width: 310px;
        font-size: 17px;
        color: #364453;
    }
    .member-card-info-item{
     margin-bottom:2px;
     }

</style>

<?php
if($session["role_name"] == "administrator" || $session["role_name"] == "member" || $session["role_name"] == "staff_member")
{ ?>
<script>
var finger_regist_flag = true;
$(document).ready(function(){
	var table = $(".mydataTable").DataTable();
	table.column(7).visible( true );
});
function startCatch(userid){
    if(!finger_regist_flag)
        return;

    var base_url = $("#base_url").val();
    $.ajax({
           url:'http://localhost:8008/fingerprintservice/register',
           type:'GET',
           crossDomain : true,
           data:'',
           success:function(template_id){
               if(template_id != ""){
                    $.ajax({
	                        url:base_url+'/MemberRegistration/setUserByFinger',
	                        type:"POST",
	                        data:{
	                        'id':template_id,
	                        'userid':userid
	                        },
	                        success:function(response){
	                             $("#msg-result").text("Successfully registered!");
	                             $("#msg-result-div").css("display","block");

	                        },
	                        error: function(res){
	                            $("#msg-result").text("Please try again!");
	                             $("#msg-result-div").css("display","block");
	                            setTimeout(function(){
	                               startCatch(userid);
	                            },2000);
	                        }
                        });
               }
               else{
                    setTimeout(function(){
                        startCatch(userid);
                    },2000);
               }
           },
           error: function(msg){
               $("#msg-result").text("Please make sure the device connection!");
               $("#msg-result-div").css("display","block");
                   setTimeout(function(){
                       startCatch(userid);
                   },2000);
           }
       });
}
function finger_reg(userid,f_name,l_name){
finger_regist_flag = true;
  $("#f_username").text(f_name+" "+l_name);
  $( "#finger_reg" ).dialog({
         'width':'50%',
          modal: true,
          resizable: false,
          closeOnEscape: false,
            open: function(event, ui) {
              $(".ui-dialog-titlebar-close", ui.dialog | ui).hide();
            }
   });
    startCatch(userid);
}
function closeDialog(){
finger_regist_flag = false;
   $("#msg-result-div").css("display","none");

 $( "#finger_reg" ).dialog('close');
}
</script>


<?php } 

if($session["role_name"] == "administrator")
{?>
<script>
$(document).ready(function(){
	var table = $(".mydataTable").DataTable();
	table.column(8).visible( true );
});
function closeCardDialog(){
	$('.ui-dialog').hide();
	$('.ui-widget-overlay').hide();
}
</script>
<?php } ?>

<div id="CardDialog" title="User Information" style="display:none;">
    <input type="hidden" id="base_url" value="<?php echo $this->request->base ?>">
    <img id='user_img' src='' style='position:absolute; width:104px;height: 144px; top:48px; left:24px'/>
	<div id='card_content' style='width:100%;min-height: 250px;'>
	    <div class='member-card-info' >
	        <div class='member-card-info-item'>
	         <span>Name: </span> <span id='name'></span>
	        </div>
	        <div class='member-card-info-item'>
	            <span>Birthday: </span> <span id='birthday'></span>
	        </div>
	        <div class='member-card-info-item'>
	            <span>Gender: </span> <span id='gender'></span>
	        </div>

	        <div class='member-card-info-item'>
	            <span>Address: </span> <span id='address'></span>
	        </div>
	    </div>
	</div>    
    <div class="modal-footer">
		<div class="print-button">
			<a id="card_href" target="_blank" class="btn btn-flat btn-success" onClick="closeCardDialog();" style="border-radius: 5px;padding-bottom: 5px;"><?php echo __("Print"); ?></a>
		</div>					
	</div>
</div>

<div id="finger_reg" title="Finger Register" style="display:none;    padding: 50px; font-size: 29px; text-align: center;padding-bottom: 82px; color: #2564a5;
">

    <div>
    <div style="    text-align: left; font-size: 19px;  color: #e60707; margin-bottom: 29px;"><span>Name: </span> <span id="f_username"></span></div>
        <div>Please swipe the device at least 4 times for register ...</div>
    </div>

    <div id="msg-result-div" style="margin-top: 23px; color: red; display: none;">
        <span id="msg-result"></span>
    </div>
    <div style="position: absolute; right: 11px;bottom: 6px; font-size: 16px;">
    <button onclick="closeDialog();" > Close</button>
    </div>
</div>

<section class="content">
	<br>
	<div class="col-md-12 box box-default">		
		<div class="box-header">
			<section class="content-header">
			  <h1>
				<i class="fa fa-bars"></i>
				<?php echo __("Members List");?>
				<small><?php echo __("Member");?></small>
			  </h1>
			   <?php
				if($session["role_name"] == "administrator")
				{ ?>
			  <ol class="breadcrumb">
				<a href="<?php echo $this->Gym->createurl("GymMember","addMember");?>" class="btn btn-flat btn-custom"><i class="fa fa-plus"></i> <?php echo __("Add Member");?></a>
			  </ol>
			   <?php } ?>
			</section>
		</div>
		<hr>
		<div class="box-body">
		<table class="mydataTable table table-striped">
			<thead>
				<tr>
					<th><?php echo __("Photo");?></th>
					<th><?php echo __("Member Name");?></th>
					<th><?php echo __("Member ID");?></th>					
					<th><?php echo __("Joining Date");?></th>					
					<th><?php echo __("Expire Date");?></th>					
					<th><?php echo __("Member Type");?></th>					
					<th><?php echo __("Membership Status");?></th>					
					<th><?php echo __("Action");?></th>
					<th><?php echo __("Status");?></th>
				</tr>
			</thead>
			<tbody>
			<?php
				foreach($data as $row)
				{
					echo "<tr>
					<td><img src='{$this->request->base}/webroot/upload/{$row['image']}' class='membership-img img-circle'></td>
					<td>{$row['first_name']} {$row['last_name']}</td>
					<td>{$row['member_id']}</td>
					<td>".(($row['membership_valid_from'] != '')?date($this->Gym->getSettings("date_format"),strtotime($row['membership_valid_from'])):'Null')."</td>
					<td>".(($row['membership_valid_to'] != '')?date($this->Gym->getSettings("date_format"),strtotime($row['membership_valid_to'])):'Null')."</td>
					<td>{$row['member_type']}</td>
					<td>{$row['membership_status']}</td>
					<td>
					<a href='javascript:openCardInfo({$row['id']},\"{$row['image']}\",\"{$row['card_image']}\",\"{$row['first_name']}\",\"{$row['last_name']}\",\"{$row['gender']}\",\"{$row['birth_date']}\",\"{$row['address']}\");' title='View' class='btn btn-flat btn-info'><i class='glyphicon glyphicon-credit-card'></i></a>

						<a href='{$this->request->base}/GymMember/viewMember/{$row['id']}' title='View' class='btn btn-flat btn-info'><i class='fa fa-eye'></i></a>";
					if($session["role_name"] == "administrator")
					{	
					echo " <a href='{$this->request->base}/GymMember/editMember/{$row['id']}' title='Edit' class='btn btn-flat btn-primary'><i class='fa fa-edit'></i></a>

						<a href='{$this->request->base}/GymMember/deleteMember/{$row['id']}' title='Delete' class='btn btn-flat btn-danger' onClick=\"return confirm('Are you sure,You want to delete this record?');\"><i class='fa fa-trash-o'></i></a>";
						echo " <a href='javascript:finger_reg({$row['id']},\"{$row['first_name']}\",\"{$row['last_name']}\");' title='Finger Register' class='btn btn-flat btn-default'> Finger Register</a>";

					}
					echo " <a href='{$this->request->base}/GymMember/viewAttendance/{$row['id']}' title='Attendance' class='btn btn-flat btn-default'><i class='fa fa-eye'></i> Attendance</a>";

					echo "</td>
						  <td>";
						if($row["activated"] == 0)
						{
							echo "<a class='btn btn-success btn-flat' onclick=\"return confirm('Are you sure,you want to activate this account?');\" href='".$this->request->base ."/GymMember/activateMember/{$row['id']}'>".__('Activate')."</a>";
						}else{
							echo "<span class='btn btn-flat btn-default'>".__('Activated')."</span>";
						}
					echo "</td>
					</tr>";
				}
			?>
			</tbody>
			<tfoot>
				<tr>
					<th><?php echo __("Photo");?></th>
					<th><?php echo __("Member Name");?></th>
					<th><?php echo __("Member ID");?></th>					
					<th><?php echo __("Joining Date");?></th>					
					<th><?php echo __("Expire Date");?></th>					
					<th><?php echo __("Member Type");?></th>					
					<th><?php echo __("Membership Status");?></th>					
					<th><?php echo __("Action");?></th>
					<th><?php echo __("Status");?></th>
				</tr>
			</tfoot>
		</table>
		</div>	
		<div class="overlay gym-overlay">
		  <i class="fa fa-refresh fa-spin"></i>
		</div>
	</div>
</section>
