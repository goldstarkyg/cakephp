<script>window.onload = function(){ window.print(); };</script>

    <h4><?php echo __('User Information');?> </h4>   
	<div id="CardDialog" title="User Information" style="position:relative; ">
	    <img id='user_img' src='/cakephp/webroot/upload/<?php echo $sys_data["card_image"];?>' style='position:absolute; width:470px;height: 250px; top:0px; left:0px'/>
	    <img id='user_img' src='/cakephp/webroot/upload/<?php echo $sys_data["image"];?>' style='position:absolute; width:104px;height: 144px; top:42px; left:10px'/>
		<div id='card_content' style='width:471px;min-height: 250px; border:solid 1px gray;'>
		    <div class='member-card-info' style="position: absolute;top: 50px;left: 150px;width: 310px;font-size: 17px;color: #364453;">
		        <div class='member-card-info-item'>
		         <span>Name: </span><span id='name'><?php echo $sys_data["first_name"];?>&nbsp;<?php echo $sys_data["last_name"];?></span>
		        </div>
		        <div class='member-card-info-item'>
		            <span>Birthday: </span> <span id='birthday'><?php echo $sys_data["birth_date"];?></span>
		        </div>
		        <div class='member-card-info-item'>
		            <span>Gender: </span> <span id='gender'><?php echo $sys_data["gender"];?></span>
		        </div>

		        <div class='member-card-info-item'>
		            <span>Address: </span> <span id='address'><?php echo $sys_data["address"];?></span>
		        </div>
		    </div>
		</div>    	    
	</div>
	<?php die; ?>