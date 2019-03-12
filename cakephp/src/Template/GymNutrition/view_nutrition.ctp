<?php
echo $this->Html->css('select2.css');
echo $this->Html->script('select2.min');
?>
<script>
$(document).ready(function() {
$(".mem_list").select2();
$(".date").datepicker({format:"yyyy-mm-dd"});
var box_height = $(".box").height();
var box_height = box_height + 100 ;
$(".content-wrapper").css("height",box_height+"px");
});
</script>
<section class="content">
	<br>
	<div class="col-md-12 box box-default">		
		<div class="box-header">
			<section class="content-header">
			  <h1>
				<i class="fa fa-leaf"></i>
				<?php echo $title;?>				
				<small><?php echo __("Nutrition Schedule");?></small>
			  </h1>
			  <ol class="breadcrumb">
				<a href="<?php echo $this->Gym->createurl("GymNutrition","nutritionList");?>" class="btn btn-flat btn-custom"><i class="fa fa-bars"></i> <?php echo __("Nutrition Schdule List");?></a>
			  </ol>
			</section>
		</div>
		<hr>		 
		<div class="box-body">
		<?php
			echo $this->Form->create("",["type"=>"file","class"=>"validateForm form-horizontal", "onsubmit"=>"return validate_multiselect()", "role"=>"form","action"=>"addnutritionSchedule"]);
			// echo $this->Form->create("addgroup",["type"=>"file","class"=>"validateForm form-horizontal","role"=>"form","onsubmit"=>"return validate_multiselect()"]);   "enctype"=>"multipart/form-data"
		?>
		<div class='form-group'>
			<label class="control-label col-md-3" for="email"><?php echo __("Select Member");?><span class="text-danger"> *</span></label>
			<?php 
				echo $this->Form->input("",["type"=>"hidden","label"=>false,"name"=>"user_id","value"=>$data['user_id']]);
			?>
			<div class="col-md-6" style="font-size: 20px">
				<?php 
					echo $data['user_name'];					
				?>
			</div>			
		</div>	
		<div class='form-group'>
			<label class="control-label col-md-3" for="email"><?php echo __("Start Date");?><span class="text-danger"> *</span></label>
			<div class="col-md-6">
				<?php 
					echo $this->Form->input("",["label"=>false,"value"=>$data['start_date'], "name"=>"start_date","class"=>"date validate[required] form-control"]);
				?>
			</div>	
		</div>
		<div class='form-group'>
			<label class="control-label col-md-3" for="email"><?php echo __("End Date");?><span class="text-danger"> *</span></label>
			<div class="col-md-6">
				<?php 
					echo $this->Form->input("",["label"=>false,"name"=>"expire_date","value"=>$data['expire_date'],"class"=>"date validate[required] form-control"]);
				?>
			</div>	
		</div>
		<div class="form-group">
			<label class="col-sm-1 control-label"></label>
			<div class="col-sm-10 border">
				<br>
				<div style="width:100%">					
					<label class="list-group-item bg-default"><?php echo __("Select Nutrition by Days");?></label>
					<input type="hidden" value="" name="day[]" value="" id="" data-val="day">
				    <!-- sunday -->
				    <div style="margin-top:10px">
				        <div class="col-md-12"><label style="font-size: 18px">Sunday</label></div>					        
				        <div class="col-md-4">					             
				             <div style="">
								<label class="control-label" for="email" style="margin-left: 10px">Image Upload : </label>      
						    	<input type="file" name="image1" class="form-control" style="width: 90%;margin-left:10px;margin-top:10px" />
							</div>
							<div style="margin:10px 0px 10px 30px">
								<?php $image = ($edit && !empty($data['image1'])) ? $data['image1'] : "meal.jpg"; ?>
				            	<?php echo "<img src='{$this->request->webroot}webroot/upload/{$image}' style='width:150px'>";?>
				            </div>
				        </div>
				        <div class="col-md-8">
				        	<label class="activity_title checkbox">				  							  		
                                <?php 
									echo $this->Form->input("Breakfast",["type"=>"checkbox","class"=>"nutrition_check", "checked","id"=>"breakfast_1"]);
								?>
							</label>							
							<textarea type="textarea" rows="2" name="breakfast1" id="text_breakfast_1" style="margin-left:40px;width:90%;"><?php echo $data['breakfast1'];?></textarea>
							
						    <label class="activity_title checkbox">						  		
						  		<?php 
									echo $this->Form->input("Lunch",["type"=>"checkbox","class"=>"nutrition_check","checked","id"=>"lunch_1"]);
								?>							
							</label>
							<textarea type="textarea" rows="2" name="lunch1" id="text_lunch_1" style="margin-left:40px;width:90%;"><?php echo $data['lunch1'];?></textarea>
							
						    <label class="activity_title checkbox">				  								  		
						  		<?php 
									echo $this->Form->input("Dinner",["type"=>"checkbox","class"=>"nutrition_check","checked","id"=>"dinner_1"]);
								?>							
							</label>
							<textarea type="textarea" rows="2" name="dinner1" id="text_dinner_1" style="margin-left:40px;width:90%;"><?php echo $data['dinner1'];?></textarea>
				        </div>							
		            </div>
                    <!-- monday -->
                    <div style="margin-top:10px">
				        <div class="col-md-12"><label style="font-size: 18px">Monday</label></div>					        
				        <div class="col-md-4">					             
				             <div style="">
								<label class="control-label" for="email" style="margin-left: 10px">Image Upload : </label>			        
						    	<input type="file" name="image2" class="form-control" style="width: 90%;margin-left:10px;margin-top:10px" />
							</div>
							<div style="margin:10px 0px 10px 30px">
								<?php $image = ($edit && !empty($data['image2'])) ? $data['image2'] : "meal.jpg"; ?>
				            	<?php echo "<img src='{$this->request->webroot}webroot/upload/{$image}' style='width:150px'>";?>
				            </div>
				        </div>
				        <div class="col-md-8">
				        	<label class="activity_title checkbox">				  							  		
                                <?php 
									echo $this->Form->input("Breakfast",["type"=>"checkbox","class"=>"nutrition_check", "checked","id"=>"breakfast_2"]);
								?>
							</label>							
							<textarea type="textarea" rows="2" name="breakfast2" id="text_breakfast_2" style="margin-left:40px;width:90%;"><?php echo $data['breakfast2'];?></textarea>
							
						    <label class="activity_title checkbox">						  		
						  		<?php 
									echo $this->Form->input("Lunch",["type"=>"checkbox","class"=>"nutrition_check","checked","id"=>"lunch_2"]);
								?>							
							</label>
							<textarea type="textarea" rows="2" name="lunch2" id="text_lunch_2" style="margin-left:40px;width:90%;"><?php echo $data['lunch2'];?></textarea>
							
						    <label class="activity_title checkbox">				  								  		
						  		<?php 
									echo $this->Form->input("Dinner",["type"=>"checkbox","class"=>"nutrition_check","checked","id"=>"dinner_2"]);
								?>							
							</label>
							<textarea type="textarea" rows="2" name="dinner2" id="text_dinner_2" style="margin-left:40px;width:90%;"><?php echo $data['dinner2'];?></textarea>
				        </div>							
		            </div>		            
			        <!-- tuesday -->
			            <div style="margin-top:10px">
				        <div class="col-md-12"><label style="font-size: 18px">Tuesday</label></div>					        
				        <div class="col-md-4">					             
				             <div style="">
								<label class="control-label" for="email" style="margin-left: 10px">Image Upload : </label>				        
						    	<input type="file" name="image3" class="form-control" style="width: 90%;margin-left:10px;margin-top:10px" />			    
							</div>
							<div style="margin:10px 0px 10px 30px">
								<?php $image = ($edit && !empty($data['image3'])) ? $data['image3'] : "meal.jpg"; ?>
				            	<?php echo "<img src='{$this->request->webroot}webroot/upload/{$image}' style='width:150px'>";?>
				            </div>
				        </div>
				        <div class="col-md-8">
				        	<label class="activity_title checkbox">				  							  		
                                <?php 
									echo $this->Form->input("Breakfast",["type"=>"checkbox","class"=>"nutrition_check", "checked","id"=>"breakfast_3"]);
								?>
							</label>							
							<textarea type="textarea" rows="2" name="breakfast3" id="text_breakfast_3" style="margin-left:40px;width:90%;"><?php echo $data['breakfast3'];?></textarea>
							
						    <label class="activity_title checkbox">						  		
						  		<?php 
									echo $this->Form->input("Lunch",["type"=>"checkbox","class"=>"nutrition_check","checked","id"=>"lunch_3"]);
								?>							
							</label>
							<textarea type="textarea" rows="2" name="lunch3" id="text_lunch_3" style="margin-left:40px;width:90%;"><?php echo $data['lunch3'];?></textarea>
							
						    <label class="activity_title checkbox">				  								  		
						  		<?php 
									echo $this->Form->input("Dinner",["type"=>"checkbox","class"=>"nutrition_check","checked","id"=>"dinner_3"]);
								?>							
							</label>
							<textarea type="textarea" rows="2" name="dinner3" id="text_dinner_3" style="margin-left:40px;width:90%;"><?php echo $data['dinner3'];?></textarea>
				        </div>								
		            </div>		
			        <!-- wednesday -->
			            <div style="margin-top:10px">
				        <div class="col-md-12"><label style="font-size: 18px">Wednesday</label></div>					        
				        <div class="col-md-4">					             
				             <div style="">
								<label class="control-label" for="email" style="margin-left: 10px">Image Upload : </label>				       
						    	<input type="file" name="image4" class="form-control" style="width: 90%;margin-left:10px;margin-top:10px" />			    
							</div>
							<div style="margin:10px 0px 10px 30px">
								<?php $image = ($edit && !empty($data['image4'])) ? $data['image4'] : "meal.jpg"; ?>
				            	<?php echo "<img src='{$this->request->webroot}webroot/upload/{$image}' style='width:150px'>";?>
				            </div>
				        </div>
				        <div class="col-md-8">
				        	<label class="activity_title checkbox">				  							  		
                                <?php 
									echo $this->Form->input("Breakfast",["type"=>"checkbox","class"=>"nutrition_check", "checked","id"=>"breakfast_4"]);
								?>
							</label>							
							<textarea type="textarea" rows="2" name="breakfast4" id="text_breakfast_4" style="margin-left:40px;width:90%;"><?php echo $data['breakfast4'];?></textarea>
							
						    <label class="activity_title checkbox">						  		
						  		<?php 
									echo $this->Form->input("Lunch",["type"=>"checkbox","class"=>"nutrition_check","checked","id"=>"lunch_4"]);
								?>							
							</label>
							<textarea type="textarea" rows="2" name="lunch4" id="text_lunch_4" style="margin-left:40px;width:90%;"><?php echo $data['lunch4'];?></textarea>
							
						    <label class="activity_title checkbox">				  								  		
						  		<?php 
									echo $this->Form->input("Dinner",["type"=>"checkbox","class"=>"nutrition_check","checked","id"=>"dinner_4"]);
								?>							
							</label>
							<textarea type="textarea" rows="2" name="dinner4" id="text_dinner_4" style="margin-left:40px;width:90%;"><?php echo $data['dinner4'];?></textarea>
				        </div>								
		            </div>		
			        <!-- thursday -->
			        <div style="margin-top:10px">
				        <div class="col-md-12"><label style="font-size: 18px">Thursday</label></div>					        
				        <div class="col-md-4">					             
				             <div style="">
								<label class="control-label" for="email" style="margin-left: 10px">Image Upload : </label>				        
						    	<input type="file" name="image5" class="form-control" style="width: 90%;margin-left:10px;margin-top:10px" />			    
							</div>
							<div style="margin:10px 0px 10px 30px">
								<?php $image = ($edit && !empty($data['image5'])) ? $data['image5'] : "meal.jpg"; ?>
				            	<?php echo "<img src='{$this->request->webroot}webroot/upload/{$image}' style='width:150px'>";?>
				            </div>
				        </div>
				        <div class="col-md-8">
				        	<label class="activity_title checkbox">				  							  		
                                <?php 
									echo $this->Form->input("Breakfast",["type"=>"checkbox","class"=>"nutrition_check", "checked","id"=>"breakfast_5"]);
								?>
							</label>							
							<textarea type="textarea" rows="2" name="breakfast5" id="text_breakfast_5" style="margin-left:40px;width:90%;"><?php echo $data['breakfast5'];?></textarea>
							
						    <label class="activity_title checkbox">						  		
						  		<?php 
									echo $this->Form->input("Lunch",["type"=>"checkbox","class"=>"nutrition_check","checked","id"=>"lunch_5"]);
								?>							
							</label>
							<textarea type="textarea" rows="2" name="lunch5" id="text_lunch_5" style="margin-left:40px;width:90%;"><?php echo $data['lunch5'];?></textarea>
							
						    <label class="activity_title checkbox">				  								  		
						  		<?php 
									echo $this->Form->input("Dinner",["type"=>"checkbox","class"=>"nutrition_check","checked","id"=>"dinner_5"]);
								?>							
							</label>
							<textarea type="textarea" rows="2" name="dinner5" id="text_dinner_5" style="margin-left:40px;width:90%;"><?php echo $data['dinner5'];?></textarea>
				        </div>								
		            </div>		
			        <!-- Friday -->
			        <div style="margin-top:10px">
				        <div class="col-md-12"><label style="font-size: 18px">Friday</label></div>					        
				        <div class="col-md-4">					             
				             <div style="">
								<label class="control-label" for="email" style="margin-left: 10px">Image Upload : </label>				        
						    	<input type="file" name="image6" class="form-control" style="width: 90%;margin-left:10px;margin-top:10px" />			    
							</div>
							<div style="margin:10px 0px 10px 30px">
								<?php $image = ($edit && !empty($data['image6'])) ? $data['image6'] : "meal.jpg"; ?>
				            	<?php echo "<img src='{$this->request->webroot}webroot/upload/{$image}' style='width:150px'>";?>
				            </div>
				        </div>
				        <div class="col-md-8">
				        	<label class="activity_title checkbox">				  							  		
                                <?php 
									echo $this->Form->input("Breakfast",["type"=>"checkbox","class"=>"nutrition_check", "checked","id"=>"breakfast_6"]);
								?>
							</label>							
							<textarea type="textarea" rows="2" name="breakfast6" id="text_breakfast_6" style="margin-left:40px;width:90%;"><?php echo $data['breakfast6'];?></textarea>
							
						    <label class="activity_title checkbox">						  		
						  		<?php 
									echo $this->Form->input("Lunch",["type"=>"checkbox","class"=>"nutrition_check","checked","id"=>"lunch_6"]);
								?>							
							</label>
							<textarea type="textarea" rows="2" name="lunch6" id="text_lunch_6" style="margin-left:40px;width:90%;"><?php echo $data['lunch6'];?></textarea>
							
						    <label class="activity_title checkbox">				  								  		
						  		<?php 
									echo $this->Form->input("Dinner",["type"=>"checkbox","class"=>"nutrition_check","checked","id"=>"dinner_6"]);
								?>							
							</label>
							<textarea type="textarea" rows="2" name="dinner6" id="text_dinner_6" style="margin-left:40px;width:90%;"><?php echo $data['dinner6'];?></textarea>
				        </div>								
		            </div>		
			        <!-- saturday -->
			        <div style="margin-top:10px">
				        <div class="col-md-12"><label style="font-size: 18px">Saturday</label></div>					        
				        <div class="col-md-4">					             
				             <div style="">
								<label class="control-label" for="email" style="margin-left: 10px">Image Upload : </label>				        
						    	<input type="file" name="image7" class="form-control" style="width: 90%;margin-left:10px;margin-top:10px" />			    
							</div>
							<div style="margin:10px 0px 10px 30px">
								<?php $image = ($edit && !empty($data['image7'])) ? $data['image7'] : "meal.jpg"; ?>
				            	<?php echo "<img src='{$this->request->webroot}webroot/upload/{$image}' style='width:150px'>";?>
				            </div>
				        </div>
				        <div class="col-md-8">
				        	<label class="activity_title checkbox">				  							  		
                                <?php 
									echo $this->Form->input("Breakfast",["type"=>"checkbox","class"=>"nutrition_check", "checked","id"=>"breakfast_7"]);
								?>
							</label>							
							<textarea type="textarea" rows="2" name="breakfast7" id="text_breakfast_7" style="margin-left:40px;width:90%;"><?php echo $data['breakfast7'];?></textarea>
							
						    <label class="activity_title checkbox">						  		
						  		<?php 
									echo $this->Form->input("Lunch",["type"=>"checkbox","class"=>"nutrition_check","checked","id"=>"lunch_7"]);
								?>							
							</label>
							<textarea type="textarea" rows="2" name="lunch7" id="text_lunch_7" style="margin-left:40px;width:90%;"><?php echo $data['lunch7'];?></textarea>
							
						    <label class="activity_title checkbox">				  								  		
						  		<?php 
									echo $this->Form->input("Dinner",["type"=>"checkbox","class"=>"nutrition_check","checked","id"=>"dinner_7"]);
								?>							
							</label>
							<textarea type="textarea" rows="2" name="dinner7" id="text_dinner_7" style="margin-left:40px;width:90%;"><?php echo $data['dinner7'];?></textarea>
				        </div>							
		            </div>		 					
				</div>
				
			</div>
		</div>	
		<hr>
		<div class="col-sm-offset-2 col-sm-8">
			<div class="form-group">
			<!--	<div class="col-md-8">
					<input type="button" value="<?php //echo __('Step-1 Add Nutrition');?>" name="sadd_workouttype" id="add_nutrition" class="btn btn-flat btn-success"/>
				</div> -->
			</div>
		</div>
		<div id="display_nutrition_list"></div>
		<div class="clear"></div>	
		<br><br>
		<div class="col-md-offset-2 col-sm-8 schedule-save-button">
        	
        	<input type="submit" value="<?php if($edit){ echo __('Update'); }else{ echo __('Save');}?>" name="save_workouttype" class="btn  btn-flat btn-success"/>
        </div>
		<input type="hidden" id="add_workout_url" value="<?php echo $this->request->base;?>/GymAjax/gmgt_add_workout">
		<div class='clear'>
		<br><br>
		<?php 
		$this->Form->end();
		
		if($edit)
		{
			foreach($nutrition_data as $data=>$row)
			{				
				foreach($row as $r)
				{
					if(is_array($r))
					{
						$days_array[$data]["start_date"] = $row["start_date"];
						$days_array[$data]["expire_date"] = $row["expire_date"];
						$day = $r["day_name"];
						$days_array[$data][$day][] = $r;
					}
				}					
			}
			
			// var_dump($days_array);
			foreach($days_array as $data=>$row)
			{?>
				<div class="panel panel-default workout-block" id="remove_panel_<?php echo $data;?>">				
				  <div class="panel-heading">
					<i class="fa fa-calendar"></i> <?php echo __("Start From")." <span class='work_date'>". date($this->Gym->getSettings("date_format"),strtotime($row["start_date"]))."</span> ".__("TO")." <span class='work_date'>".date($this->Gym->getSettings("date_format"),strtotime($row["expire_date"]))."</span>";?>
					<span class="del_nutrition_panel" del_id="<?php echo $data;?>" data-url="<?php echo $this->request->base;?>/GymAjax/deleteNutritionData/<?php echo $data;?>"><i class='fa fa-times-circle' aria-hidden="true"></i></span>
				  </div>
				  <br>
				<div class="work_out_datalist_header">
					<div class="col-md-4 col-sm-4">  
						<strong><?php echo __("Day name");?></strong>
					</div>
					<div class="col-md-8 col-sm-8 hidden-xs">						
						<span class="col-md-3 col-sm-3 col-xs-12"><?php echo __('Time');?></span>
	 					<span class="col-md-9 col-sm-9 col-xs-12"><?php echo __('Description');?></span>
					</div>
				</div>				
				<?php 
				foreach($row as $day=>$value)
				{
					if(is_array($value))
					{ 
					?>
						<div class="work_out_datalist">
						<div class="col-md-4 col-sm-4 day_name"><?php echo __($day);?></div>
						<div class="col-md-8 col-xs-8">
						<?php foreach($value as $r)
							{?>
							<div class="col-md-12">						
							<span class="col-md-3 col-sm-3 col-xs-12"><?php echo $r["nutrition_time"];?> </span>
							<span class="col-md-9 col-sm-9 col-xs-12"><?php echo $r["nutrition_value"];?> 
							</div>
						<?php } ?>
						</div>
						</div>
					<?php } 
				}?>				
				</div>
	  <?php } 
		}?>		
		<br><br>
		</div>
		<div class='overlay gym-overlay'>
			<i class='fa fa-refresh fa-spin'></i>
		</div>
	</div>
</section>
<script>

	 jQuery("body").on("click", "#add_nutrition", function(event){
		 var count = $("#display_nutrition_list div").length;
		
		
		 var day = '';
		 var activity = '';
		 var check_val = '';
		 jsonObj1 = [];
		 jsonObj2 = [];
		 jsonObj = [];
		
		 $(":checkbox:checked").each(function(o){
			
			  var chkID = $(this).attr("id");
			  var check_val = $(this).attr("data-val");
				
			  if(check_val == 'day')
			  {
				  //day += ' ' + chkID;
				  day += add_day(chkID,chkID);
				  item = {}
			        item ["day_name"] =chkID;
			       
			        jsonObj1.push(item);
			        //$(this).prop("disabled", true);
			  }
			  if(check_val == 'nutrition_time')
			  {
				  activity_name = $(this).attr("id");
				 if(activity_name == 'dinner')
				{
					 activity_name = 'Dinner';
				}
				 if(activity_name == 'breakfast')
					{
						 activity_name = 'Break Fast';
					}
				 if(activity_name == 'lunch')
					{
						 activity_name = 'Lunch';
					}
				  item = {};
			        item ["activity"] = {"activity":activity_name,"value":$("#valtxt_"+chkID).val()};
				  activity += add_nutrition(activity_name,chkID);
				 
			       
				  
			        jsonObj2.push(item);
			        
			  }
			  $(this).prop('checked', false);
			 
			 // $("#"+chkID+"summ").removeAttr("disabled");
			  /* ... */
			  jsonObj = {"days":jsonObj1,"activity":jsonObj2};
			});
		    
		    if(jsonObj2.length == 3){ 
				var curr_data = {
							action: 'gmgt_add_nutrition',
							data_array: jsonObj,			
							dataType: 'json'
							};
							
				$.ajax({
					type : "POST",
					data : curr_data,
					url : "<?php echo $this->request->base . "/GymAjax/gym_add_nutrition"?>",
					success :function(response) {		
								var list_workout =  nutrition_list(day,activity,count,response);
								$("#display_nutrition_list").append(list_workout);
					}				
				});	
	        }else{
                 alert("Please Select All Nutrition to add on selected days."); 
	        }
	}); 
	
	 $(".nutrition_check").change(function(){
		id = $(this).attr('id');		
		if($(this).is(":checked"))
		{ 
		    $("#text_"+id).show();			 
		}else{
			$("#text_"+id).hide();			 
		}
	 });
	  function add_day(day,id)
	 {
		 var string = '';
		 string = '<span id="'+id+'">'+day+'</span>, ';
		 string += '<input type="hidden" name="day[day]['+day+']" value="'+day+'">';
		 return string;
	 }
	  function add_nutrition(activity,id)
	 {
		 var string = '';
		 var sets = '';
		 var reps = '';
		 nutrition = $("#valtxt_"+id).val();		
		 // string += '<div id="'+id+'" class="nutrition_title"><strong>'+activity+' </strong></div>';
		 // string += '<div id="value_'+id+'" class="nutrition_value"> '+nutrition+' </div>';		 
		 string += '<div id="'+id+'" class="nutrition_title"><strong>'+activity+' </strong>: '+nutrition+'</div>';
		 
		
		 nutrition = $("#valtxt_"+id).val('');
		
		 return string;
	 }
	 function nutrition_list(day,activity,id,response)
		{
			var string = '';
			string += "<div class='activity border' id='block_"+id+"'>";
			string += '<div class="col-md-4">'+day+'</div>';
			string += '<div class="col-md-6">'+activity +'</div>';
			string += '<span>'+ response+'</span>';
			string += "<div id='"+id+"' class='removethis col-md-2'><span class='badge badge-delete pull-right'>X</span></div></div>";
			return string;
		}
 jQuery("body").on("click", ".removethis", function(event){
		// alert("hello");
		 var chkID = $(this).attr("id");
		 $("#block_"+chkID).remove();
	 });
</script>