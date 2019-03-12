<script type="text/javascript">
$(document).ready(function() {	
	$(".content-wrapper").css("min-height","1550px");
});
</script>
<section class="content">
	<br>
	<div class="col-md-12 box box-default">		
		<div class="box-header">
			<section class="content-header">
			  <h1>
				<i class="fa fa-calculator"></i>
				<?php echo __("Calculator BMI");?>				
			  </h1>			  
			</section>
		</div>
		<hr>
		<div class="box-body" style="display:inline-grid">	
		    <div style="margin-left:50px">
		        <?php echo "<br><img src='{$this->request->webroot}webroot/img/bmi.png'>";?>
		         
		    </div>
            <div style="height:1px; width:100%;background:#eee;margin-top:10px;margin-bottom:30px"></div>
		    <div class='form-group'>	
			<label class="control-label col-md-2" for="email">Weight ( kg )<span class="text-danger"></span></label>
			<div class="col-md-6">
			<input id="bmi_weight" type="number" class="form-control" value=""/>
			</div>	
			</div>
			
			<div class='form-group'>
			<label class="control-label col-md-2" for="email">Height ( m )</label>
			<div class="col-md-6">
			<input id="bmi_height" type="number" class="form-control" value=""/>
			</div>	
			</div>	
			
			<div class='form-group'>	
			<label class="control-label col-md-2" for="email">Result</label>
			<div class="col-md-6">
			<input id="bmi_result" readonly class="form-control" />
			</div>	
			</div>			
		    
		    <div class='form-group'>	
			<label class="control-label col-md-2" for="email"></label>
			<span id="bmi_txt" style="display:none;font-size:20px; font-weight:bold;color:white;margin-left:15px;padding:5px 10px;background:red;border-radius:5px;">			
			</span>	
			</div>	
		
			<div class="col-sm-offset-2 col-sm-8 row_bottom"  style="margin-bottom:50px;margin-top:10px">        	
	        	<input value="<?php echo __("Calculator");?>" onClick="calBMI();" name="save_access_right" class="btn btn-flat btn-success">
	        </div>	
		</div>
		<div class='overlay gym-overlay'>
			<i class='fa fa-refresh fa-spin'></i>
		</div>
	</div>
	<script>
         function calBMI(){
              $("#bmi_txt").show();
              h = $("#bmi_height").val();
              w = $("#bmi_weight").val();
              r1 = w / ( h * h );
              (Math.round( r1 * 100 )/100 ).toString();
			  r = r1.toFixed(2);
			  if(parseFloat(r) <18.5 ) {
                   $("#bmi_result").val(r);
                   $("#bmi_txt").text("Underweight");
			  }
			  if(parseFloat(r) >= 18.5 && parseFloat(r) < 25 ) {
                   $("#bmi_result").val(r);
                   $("#bmi_txt").text("Healthy weight");
			  }
			  if(parseFloat(r) >= 25 && parseFloat(r) < 30 ) {
                   $("#bmi_result").val(r);
                   $("#bmi_txt").text("Over weight");
			  }
			  if(parseFloat(r) >= 30 ) {
                   $("#bmi_result").val(r);
                   $("#bmi_txt").text("Obese");
			  }
              
         }
	</script>
</section>
