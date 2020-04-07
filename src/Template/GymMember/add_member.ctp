<?php
echo $this->Html->css('bootstrap-multiselect');
echo $this->Html->script('bootstrap-multiselect');

$inquiry_date = (($edit)?$data['inquiry_date']:'');
$i_date = date("Y-m-d",strtotime($inquiry_date));
$inquiry_date = (($i_date != '1970-01-01' && $inquiry_date != '' )?$inquiry_date:'');

$trial_end_date = (($edit)?$data['trial_end_date']:'');
$t_date = date("Y-m-d",strtotime($trial_end_date));
$trial_end_date = (($t_date != '1970-01-01' && $trial_end_date != '' )?$trial_end_date:'');

$first_pay_date = (($edit)?$data['first_pay_date']:'');
$p_date = date("Y-m-d",strtotime($first_pay_date));
$first_pay_date = (($p_date != '1970-01-01' && $first_pay_date != '' )?$first_pay_date:'');

?>
<script type="text/javascript">
$(document).ready(function() {
	<?php
	if($edit)
	{?>
	$( ".dob" ).datepicker( "setDate", new Date("<?php echo date($this->Gym->getSettings("date_format"),strtotime($data['birth_date'])); ?>" ));
	$( ".mem_valid_from" ).datepicker( "setDate", new Date("<?php echo date($this->Gym->getSettings("date_format"),strtotime($data['membership_valid_from'])); ?>" ));
	<?php } ?>
	<?php 
	if($edit && $inquiry_date != '')
	{?>
	$( ".inquiry_date" ).datepicker( "setDate", new Date("<?php echo date($this->Gym->getSettings("date_format"),strtotime($inquiry_date)); ?>" ));
	<?php } ?>
	
	<?php 
	if($edit && $trial_end_date != '')
	{?>
	$( ".trial_end_date" ).datepicker( "setDate", new Date("<?php echo date($this->Gym->getSettings("date_format"),strtotime($trial_end_date)); ?>" ));
	<?php } ?>
	
	<?php 
	if($edit && $first_pay_date != '')
	{?>
	$( ".first_pay_date" ).datepicker( "setDate", new Date("<?php echo date($this->Gym->getSettings("date_format"),strtotime($first_pay_date)); ?>" ));
	<?php } ?>
	
	$('.group_list').multiselect({
		includeSelectAllOption: true	
	});
	
	var box_height = $(".box").height();
	var box_height = box_height + 500 ;
	$(".content-wrapper").css("height",box_height+"px");
	
	$('.class_list').multiselect({
		includeSelectAllOption: true		
	});
	
	$(".date").datepicker( "option", "dateFormat", "<?php echo $this->Gym->dateformat_PHP_to_jQueryUI($this->Gym->getSettings("date_format")); ?>" );
	$(".mem_valid_from").on("change",function(ev){
				// var ajaxurl = document.location + "/GymAjax/get_membership_end_date";
				var ajaxurl = $("#mem_date_check_path").val();
				var date = ev.target.value;	
				var membership = $(".membership_id option:selected").val();		
				if(membership != "")
				{
					var curr_data = { date : date, membership:membership};
					$(".valid_to").val("Calculating date..");
					$.ajax({
							url :ajaxurl,
							type : 'POST',
							data : curr_data,
							success : function(response)
									{
										// $(".valid_to").val($.datepicker.formatDate('<?php echo $this->Gym->getSettings("date_format"); ?>',new Date(response)));
										$(".valid_to").val(response);
										// alert(response);
										// console.log(response);
									}
						});
				}else{
					$(".valid_to").val("Select Membership");
				}
			});	
	$(".content-wrapper").css("height","2600px");	


//Package
$(".date").datepicker( "option", "dateFormat", "<?php echo $this->Gym->dateformat_PHP_to_jQueryUI($this->Gym->getSettings("date_format")); ?>" );
$(".package_valid_from").on("change",function(ev){
				// var ajaxurl = document.location + "/GymAjax/get_membership_end_date";
				var ajaxurl = $("#package_date_check_path").val();
				var date = ev.target.value;	
				var package = $(".package_id option:selected").val();		
				if(package != "")
				{
					var curr_data = { date : date, package:package};
					$(".package_valid_to").val("Calculating date..");
					$.ajax({
							url :ajaxurl,
							type : 'POST',
							data : curr_data,
							success : function(response)
									{
										// $(".valid_to").val($.datepicker.formatDate('<?php echo $this->Gym->getSettings("date_format"); ?>',new Date(response)));
										$(".package_valid_to").val(response);
										// alert(response);
										// console.log(response);
									}
						});
				}else{
					$(".package_valid_to").val("Select Package");
				}
			});	
	$(".content-wrapper").css("height","2600px");	
});

/** 
function validate_multiselect()
{		
		var classes = $("#class_list").val();
		if(classes == null)
		{
			alert("Please Select Class or Add class class first.");
			return false;
		}else{
			return true;
		}		
}
*/
</script>
<section class="content">
	<br>
	<div class="col-md-12 box box-default">		
		<div class="box-header">
			<section class="content-header">
			  <h1>
				<i class="fa fa-user"></i>
				<?php echo $title;?>
				<small><?php echo __("Member");?></small>
			  </h1>
			  <ol class="breadcrumb">
				<a href="<?php echo $this->Gym->createurl("GymMember","memberList");?>" class="btn btn-flat btn-custom"><i class="fa fa-bars"></i> <?php echo __("Members List");?></a>
			  </ol>
			</section>
		</div>
		<hr>
		<div class="box-body">
		<?php				
			echo $this->Form->create("addgroup",["type"=>"file","class"=>"validateForm form-horizontal","role"=>"form","onsubmit"=>"return validate_multiselect()"]);
			echo "<fieldset><legend>". __('Personal Information')."</legend>";
						
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Member ID").'</label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"member_id","class"=>"form-control","disabled"=>"disabled","id"=>"member_id","value" => (($edit)?$data['member_id']:$member_id)]);
			echo "</div>";	
			echo "</div>";
			
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("First Name").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"first_name","class"=>"form-control validate[required]","id"=>"fname","value"=>(($edit)?$data['first_name']:'')]);
			echo "</div>";	
			echo "</div>";	
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Middle Name").'</label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"middle_name","class"=>"form-control","id"=>"mname","value"=>(($edit)?$data['middle_name']:'')]);
			echo "</div>";	
			echo "</div>";	
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Last Name").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"last_name","class"=>"form-control validate[required]","id"=>"lname","value"=>(($edit)?$data['last_name']:'')]);
			echo "</div>";	
			echo "</div>";	
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Gender").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6 checkbox">';
			$radio = [
						['value' => 'male', 'text' => __('Male')],
						['value' => 'female', 'text' => __('Female')]
					];
			echo $this->Form->radio("gender",$radio,['default'=>($edit)?$data["gender"]:"male"]);			
			echo "</div>";	
			echo "</div>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Date of birth").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"birth_date","class"=>"form-control date dob validate[required] ","value"=>(($edit)?date($this->Gym->getSettings("date_format"),strtotime($data['birth_date'])):'')]);
			echo "</div>";	
			echo "</div>";
		
			echo "</fieldset>";
						
			echo "<fieldset><legend>". __('Contact Information')."</legend>";
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Address").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"address","class"=>"form-control validate[required]","id"=>"address","value"=>(($edit)?$data['address']:'')]);
			echo "</div>";	
			echo "</div>";	
/** 			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("City").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"city","class"=>"form-control validate[required]","id"=>"city","value"=>(($edit)?$data['city']:'')]);
			echo "</div>";	
			echo "</div>";*/
/**			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("State").'</label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"state","class"=>"form-control","id"=>"state","value"=>(($edit)?$data['state']:'')]);
			echo "</div>";	
			echo "</div>";
 			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Zip code").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"zipcode","class"=>"form-control validate[required]","id"=>"zipcode","value"=>(($edit)?$data['zipcode']:'')]);
			echo "</div>";	
			echo "</div>";
*/			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Mobile Number").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo '<div class="input-group">';
			echo '<div class="input-group-addon">+'.$this->Gym->getCountryCode($this->Gym->getSettings("country")).'</div>';
			echo $this->Form->input("",["label"=>false,"name"=>"mobile","class"=>"form-control validate[required]","id"=>"mobile","value"=>(($edit)?$data['mobile']:'')]);
			echo "</div>";	
			echo "</div>";	
			echo "</div>";	
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Phone").'</label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"phone","class"=>"form-control","id"=>"phone","value"=>(($edit)?$data['phone']:'')]);
			echo "</div>";	
			echo "</div>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Email").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"email","class"=>"form-control validate[required,custom[email]]","id"=>"email","value"=>(($edit)?$data['email']:'')]);
			echo "</div>";	
			echo "</div>";			
			echo "</fieldset>";
			
					
			echo "<fieldset><legend>". __('Physical Information')."</legend>";
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Weight").'</label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"weight","class"=>"form-control","placeholder"=>$this->Gym->getSettings("weight"),"id"=>"weight","value"=>(($edit)?$data['weight']:'')]);
			echo "</div>";	
			echo "</div>";

			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Height").'</label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"height","class"=>"form-control","placeholder"=>__($this->Gym->getSettings("height")),"id"=>"height","value"=>(($edit)?$data['height']:'')]);
			echo "</div>";	
			echo "</div>";	
/** 		
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Chest").'</label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"chest","class"=>"form-control","placeholder"=>__($this->Gym->getSettings("chest")),"id"=>"chest","value"=>(($edit)?$data['chest']:'')]);
			echo "</div>";	
			echo "</div>";	
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Waist").'</label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"waist","class"=>"form-control","placeholder"=>__($this->Gym->getSettings("waist")),"id"=>"waist","value"=>(($edit)?$data['waist']:'')]);
			echo "</div>";	
			echo "</div>";	
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Thigh").'</label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"thigh","class"=>"form-control","placeholder"=>__($this->Gym->getSettings("thing")),"id"=>"thigh","value"=>(($edit)?$data['thing']:'')]);
			echo "</div>";	
			echo "</div>";	
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Arms").'</label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"arms","class"=>"form-control","placeholder"=>__($this->Gym->getSettings("arms")),"id"=>"arms","value"=>(($edit)?$data['arms']:'')]);
			echo "</div>";	
			echo "</div>";	
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Fat").'</label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"fat","class"=>"form-control","placeholder"=>__($this->Gym->getSettings("fat")),"id"=>"fat","value"=>(($edit)?$data['fat']:'')]);
			echo "</div>";	
			echo "</div>";	
			echo "</fieldset>";
*/							
			echo "<fieldset><legend>". __('Login Information')."</legend>";
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Username").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"username","class"=>"form-control validate[required]","id"=>"username","value"=>(($edit)?$data['username']:''),"readonly"=> (($edit)?true:false)]);
			echo "</div>";	
			echo "</div>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Password").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';
			echo $this->Form->password("",["label"=>false,"name"=>"password","class"=>"form-control validate[required]","value"=>(($edit)?$data['password']:'')]);
			echo "</div>";	
			echo "</div>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Display Image").'</label>';
			echo '<div class="col-md-4">';
			echo $this->Form->file("image",["class"=>"form-control"]);
			$image = ($edit && !empty($data['image'])) ? $data['image'] : "Thumbnail-img.png";
			echo "<br><img src='{$this->request->webroot}webroot/upload/{$image}'>";
			echo "</div>";	
			echo "</div>";			
			echo "</fieldset>";
								
			echo "<fieldset><legend>". __('More Information')."</legend>";			

/**			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Interested Area").'</label>';
			echo '<div class="col-md-6">';			
			echo @$this->Form->select("intrested_area",$interest,["default"=>$data['intrested_area'],"empty"=>__("Select Interest"),"class"=>"form-control interest_list"]);
			echo "</div>";	
			echo '<div class="col-md-2">';
			echo "<a href='javascript:void(0)' class='btn btn-flat btn-default interest-list' data-url='{$this->request->base}/GymAjax/interestList'>".__("Add/Remove")."</a>";
			echo "</div>";	
			echo "</div>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Source").'</label>';
			echo '<div class="col-md-6">';			
			echo @$this->Form->select("g_source",$source,["default"=>$data['g_source'],"empty"=>__("Select Source"),"class"=>"form-control source_list"]);
			echo "</div>";	
			echo '<div class="col-md-2">';
			echo "<a href='javascript:void(0)' class='btn btn-flat btn-default source-list' data-url='{$this->request->base}/GymAjax/sourceList'>".__("Add/Remove")."</a>";
			echo "</div>";	
			echo "</div>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Referred By").'</label>';
			echo '<div class="col-md-6">';			
			echo @$this->Form->select("referrer_by",$referrer_by,["default"=>$data['referrer_by'],"empty"=>__("Select Staff Member"),"class"=>"form-control"]);
			echo "</div>";	
			echo '<div class="col-md-2">';
			echo "<a href='{$this->request->base}/StaffMembers/addStaff/' class='btn btn-flat btn-default'>".__("Add Staff")."</a>";
			echo "</div>";	
			echo "</div>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Inquiry Date").'</label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"inquiry_date","class"=>"form-control inquiry_date date","value"=>$inquiry_date]);
			echo "</div>";	
			echo "</div>";
			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Trial End Date").'</label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"trial_end_date","class"=>"form-control trial_end_date date","value"=>$trial_end_date]);
			echo "</div>";	
			echo "</div>";*/
			?>
			
			<div class="form-group">
				<div class="control-label col-md-2">
					<label><?php echo __("Member Type");?></label>
				</div>
				<div class="col-md-6">
					<label class="radio-inline"><input type="radio" name="member_type" value="Member" class="membership_status_type" <?php echo ($edit && $data['member_type'] == "Member") ? "checked":"checked";?>><?php echo __("Member");?></label>
					<label class="radio-inline"><input type="radio" name="member_type" value="Non-member" class="membership_status_type" <?php echo ($edit && $data['member_type'] == "Non-member") ? "checked":"";?>><?php echo __("Non-member");?></label>
					<label class="radio-inline"><input type="radio" name="member_type" value="Prospect" class="membership_status_type" <?php echo ($edit && $data['member_type'] == "Prospect") ? "checked":"";?>><?php echo __("Prospect");?></label>
				</div>
			</div>	
			
			<?php

			echo "<div class='form-group class-member'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Select Coach").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';			
			echo @$this->Form->select("assign_staff_mem",$staff,["default"=>$data['assign_staff_mem'],"empty"=>__("Select Coach"),"class"=>"form-control validate[required]"]);
			echo "</div>";	
			echo '<div class="col-md-2">';
			echo "<a href='{$this->request->base}/CoachMembers/addCoach/' class='btn btn-flat btn-default'>".__("Add Coach")."</a>";
			echo "</div>";	
			echo "</div>";
						
			//Group ginawang class na lang				
			echo "<div class='form-group class-member'>";
			echo '<label class="control-label col-md-2" for="email">'. __("Class").'</label>';
			echo '<div class="col-md-8">';			
			echo @$this->Form->select("assign_group",$groups,["default"=>json_decode($data['assign_group']),"multiple"=>"multiple","class"=>"form-control group_list"]);
			echo "</div>";
			echo "</div>";

			echo "<div class='form-group class-member non-member'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Membership").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';			
			echo @$this->Form->select("selected_membership",$membership,["default"=>$data['selected_membership'],"empty"=>__("Select Membership"),"class"=>"form-control validate[required] membership_id"]);
			echo "</div>";	
			echo '<div class="col-md-2">';
			echo "<a href='{$this->request->base}/Membership/add/' class='btn btn-flat btn-default'>".__("Add Membership")."</a>";
			echo "</div>";	
			echo "</div>";				

/** 			
			echo "<div class='form-group'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Class").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';			
			echo $this->Form->select("assign_class",($edit)?$classes:"",["default"=>($edit)?$member_class:"","class"=>"class_list form-control","id"=>"class_list","multiple"=>"multiple"]);
			echo "</div>";	
			echo '<div class="col-md-2">';
			echo "<a href='{$this->request->base}/ClassSchedule/addClass/' class='btn btn-flat btn-default'>".__("Add Class")."</a>";
			echo "</div>";	
			echo "</div>";
*/			
/** 
			if($edit)
			{
			?>
				<div class="form-group">
					<div class="control-label col-md-2">
						<label><?php echo __("Membership Status");?></label>
					</div>
					<div class="col-md-6">
						<label class="radio-inline"><input type="radio" name="membership_status" value="Continue" class="membership_status" <?php echo ($edit && $data['membership_status'] == "Continue") ? "checked":"";?>><?php echo __("Active");?></label>
						<label class="radio-inline"><input type="radio" name="membership_status" value="Expired" class="membership_status" <?php echo ($edit && $data['membership_status'] == "Expired") ? "checked":"";?>><?php echo __("Expired");?></label>
						<label class="radio-inline"><input type="radio" name="membership_status" value="Dropped" class="membership_status" <?php echo ($edit && $data['membership_status'] == "Dropped") ? "checked":"";?>><?php echo __("Dropped");?></label>
					</div>
				</div>	
			<?php
			}
*/			
			echo "<div class='form-group class-member non-member'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Membership Valid From").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-2">';
			echo $this->Form->input("",["label"=>false,"name"=>"membership_valid_from","class"=>"form-control validate[required] date mem_valid_from","value"=>(($edit && $data['membership_valid_from']!="")?date($this->Gym->getSettings("date_format"),strtotime($data['membership_valid_from'])):'')]);
			echo "</div>";
			echo '<div class="col-md-1 no-padding text-center">';
			echo "To";
			echo "</div>";
			echo '<div class="col-md-2">';
			echo $this->Form->input("",["label"=>false,"name"=>"membership_valid_to","class"=>"form-control validate[required] valid_to","value"=>(($edit && $data['membership_valid_to']!="")?date($this->Gym->getSettings("date_format"),strtotime($data['membership_valid_to'])):''),"readonly"=>true]);
			echo "</div>";
			echo "</div>";
			
			echo "<div class='form-group class-member non-member'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Membership Payment Date").'</label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"first_pay_date","class"=>"form-control first_pay_date date","value"=>(($edit)?date($this->Gym->getSettings("date_format"),strtotime($data['first_pay_date'])):'')]);
			echo "</div>";	
			echo "</div>";
			echo "</fieldset>";
/** 
			if($edit)
			{
			?>
				<div class="form-group">
					<div class="control-label col-md-2">
						<label><?php echo __("Package Status");?></label>
					</div>
					<div class="col-md-6">
						<label class="radio-inline"><input type="radio" name="package_status" value="Continue" class="package_status" <?php echo ($edit && $data['package_status'] == "Continue") ? "checked":"";?>><?php echo __("Active");?></label>
						<label class="radio-inline"><input type="radio" name="package_status" value="Expired" class="package_status" <?php echo ($edit && $data['package_status'] == "Expired") ? "checked":"";?>><?php echo __("Expired");?></label>
						<label class="radio-inline"><input type="radio" name=package_status" value="Dropped" class="package_status" <?php echo ($edit && $data['package_status'] == "Dropped") ? "checked":"";?>><?php echo __("Dropped");?></label>
					</div>
				</div>	
			<?php
			}
*/
			//Package Type
			echo "<div class='form-group class-member'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Package Type").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-6">';			
			echo @$this->Form->select("selected_package",$package,["default"=>$data['selected_package'],"empty"=>__("Select Package"),"class"=>"form-control validate[required] package_id"]);
			echo "</div>";	
			echo '<div class="col-md-2">';
			echo "<a href='{$this->request->base}/Package/add/' class='btn btn-flat btn-default'>".__("Add Package")."</a>";
			echo "</div>";	
			echo "</div>";		

			echo "<div class='form-group class-member'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Package Valid From").'<span class="text-danger"> *</span></label>';
			echo '<div class="col-md-2">';
			echo $this->Form->input("",["label"=>false,"name"=>"package_valid_from","class"=>"form-control validate[required] date package_valid_from","value"=>(($edit && $data['package_valid_from']!="")?date($this->Gym->getSettings("date_format"),strtotime($data['package_valid_from'])):'')]);
			echo "</div>";
			echo '<div class="col-md-1 no-padding text-center">';
			echo "To";
			echo "</div>";
			echo '<div class="col-md-2">';
			echo $this->Form->input("",["label"=>false,"name"=>"package_valid_to","class"=>"form-control validate[required] package_valid_to","value"=>(($edit && $data['package_valid_to']!="")?date($this->Gym->getSettings("date_format"),strtotime($data['package_valid_to'])):''),"readonly"=>true]);
			echo "</div>";
			echo "</div>";

			echo "<div class='form-group class-member'>";	
			echo '<label class="control-label col-md-2" for="email">'. __("Package Payment Date").'</label>';
			echo '<div class="col-md-6">';
			echo $this->Form->input("",["label"=>false,"name"=>"first_pay_date","class"=>"form-control first_package_pay_date date","value"=>(($edit)?date($this->Gym->getSettings("date_format"),strtotime($data['first_package_pay_date'])):'')]);
			echo "</div>";	
			echo "</div>";
			echo "</fieldset>";

			//Save Member
			echo "<br>";
			echo $this->Form->button(__("Save Member"),['class'=>"col-md-offset-2 btn btn-flat btn-success","name"=>"add_member"]);
			echo $this->Form->end();
			
		?>
		<input type="hidden" value="<?php echo $this->request->base;?>/GymAjax/get_membership_end_date" id="mem_date_check_path">

		<input type="hidden" value="<?php echo $this->request->base;?>/GymAjax/get_package_end_date" id="package_date_check_path">

		<input type="hidden" value="<?php echo $this->request->base;?>/GymAjax/get_membership_classes" id="mem_class_url">
		</div>	
		
		<div class="overlay gym-overlay">
		  <i class="fa fa-refresh fa-spin"></i>
		</div>
	</div>
</section>
 <script>

$(".membership_status_type").change(function(){
	if($(this).val() == "Prospect") // || $(this).val() == "Non-member" 
	{
		$(".class-member").hide("SlideDown");
		$(".class-member input,.class-member select").attr("disabled", "disabled");				
	}else{
		$(".class-member").show("SlideUp");
		$(".class-member input,.class-member select").removeAttr("disabled");	
		$("#available_classes").attr("disabled", "disabled");
	}	
});
if($(".membership_status_type:checked").val() == "Prospect") // || $(".membership_status_type:checked").val() == "Non-member"
{ 
$(".class-member").hide("SlideDown");
$(".class-member input,.class-member select").attr("disabled", "disabled");		
}


//non member

$(".membership_status_type").change(function(){
	if($(this).val() == "Non-member") // || $(this).val() == "Non-member" 
	{
		$(".non-member").hide("SlideDown");
		$(".non-member input,.non-member select").attr("disabled", "disabled");				
	}else{
		$(".non-member").show("SlideUp");
		$(".non-member input,.non-member select").removeAttr("disabled");	
		$("#available_classes").attr("disabled", "disabled");
	}	
});
if($(".membership_status_type:checked").val() == "Non-member") // || $(".membership_status_type:checked").val() == "Non-member"
{ 
$(".non-member").hide("SlideDown");
$(".non-member input,.non-member select").attr("disabled", "disabled");		
}

$(".membership_status_type").change(function(){
	if($(this).val() == "Prospect") // || $(this).val() == "Non-member" 
	{
		$(".class-member").hide("SlideDown");
		$(".class-member input,.class-member select").attr("disabled", "disabled");				
	}
});


	
</script>