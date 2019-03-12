<?php 
echo $this->Html->css('bootstrap-multiselect');
echo $this->Html->script('bootstrap-multiselect');
?>
<script type="text/javascript">
$(document).ready(function() {
	$('#specialization').multiselect({
		includeSelectAllOption: true	
	});
	// $(".dob").datepicker({format: '<?php echo $this->Gym->getSettings("date_format"); ?>'});
	$(".dob").datepicker({format:"yyyy-mm-dd"});
	var box_height = $(".box").height();
	var box_height = box_height + 500 ;
	$(".content-wrapper").css("height",box_height+"px");
});

</script>
<section class="content">
	<br>
	<div class="col-md-12 box box-default">		
		<div class="box-header">
			<section class="content-header">			
			  <h1>		
				<i class="fa fa-user"></i>
				<?php echo $title;?>
				<small><?php echo __("Testimonial");?></small>
			  </h1>
			  <ol class="breadcrumb">
				<a href="<?php echo $this->Gym->createurl("GymTestimonials","TestimonialsList");?>" class="btn btn-flat btn-custom"><i class="fa fa-bars"></i> <?php echo __("Testimonial List");?></a>
			  </ol>
			</section>
		</div>
		<hr>
		<div class="box-body">			
			<?php	
			echo $this->Form->create("addgroup",["type"=>"file","class"=>"validateForm form-horizontal","role"=>"form"]);
			echo "<fieldset><legend>". __('Testimonial Information')."</legend>";
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Name").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"name","class"=>"form-control validate[required]","value"=>(($edit)?$data['name']:'')]);
			echo "</div>";	
			echo "</div>";			
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Testimonial").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"description","class"=>"form-control validate[required]", "maxlength"=>"200","value"=>(($edit)?$data['description']:'')]);			
			echo "</div>";	
			echo "</div>";			
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Upload Image").'<span class="text-danger"></span></label>';
			echo '<div class="col-md-4">';
			echo $this->Form->file("photo",["class"=>"form-control"]);
			$image = ($edit && !empty($data['photo'])) ? $data['photo'] : "logo.png";
			echo "<br><img src='{$this->request->webroot}webroot/upload/{$image}' style='width:400px'>";
			echo "</div>";	
			echo "</div>";			
			echo "</fieldset>";
			
			echo $this->Form->button(__("Submit Testimonial"),['class'=>"btn btn-flat btn-primary","name"=>"add_group"]);
			echo $this->Form->end();
			?>				
		</div>	
		<div class="overlay gym-overlay">
		  <i class="fa fa-refresh fa-spin"></i>
		</div>
	</div>
	<br>
</section>