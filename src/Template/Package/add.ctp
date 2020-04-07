<?php
echo $this->Html->css('bootstrap-multiselect');
echo $this->Html->script('bootstrap-multiselect');
?>
<script type="text/javascript">

$(document).ready(function() {	
$('.class_list').multiselect({
		includeSelectAllOption: true
		// onChange: function(element, checked) {
				// alert("called");
              // $(element).closest('.multiselect').valid();
        // },		
	});
/*
	$('.validateForm').validate({
	rules: {
		class_list: "required",		
    },
	ignore: ':hidden:not(".multiselect")',

        highlight: function(element) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function(element) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        errorElement: 'span',
        errorClass: 'help-block small',
        errorPlacement: function(error, element) {
            if(element.parent('.input-group').length) {
				error.insertAfter(element.parent());
            } else {
				element.closest('.form-group').append(error);
            }
        },
        submitHandler: function() {
            alert('valid form');
            return false;
        }
    });
	*/
	
});
/** 
function validate_multiselect()
	{		
			var classes = $(".class_list").val();			
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
				<i class="fa fa-users"></i>
				<?php echo $title;?>
				<small><?php echo __("Package");?></small>
			  </h1>
			  <ol class="breadcrumb">
				<a href="<?php echo $this->Gym->createurl("Package","packageList");?>" class="btn btn-flat btn-custom"><i class="fa fa-bars"></i> <?php echo __("Package List");?></a>
			  </ol>
			</section>
		</div>
		<hr>
		<div class="box-body">
		<?php
			// $this->Form->templates([
				// 'inputContainer' => '<div class="form-group">{{content}}</div>',
			// ]);
			echo $this->Form->create($package,["type"=>"file","class"=>"validateForm form-horizontal","onsubmit"=>"return validate_multiselect()"]);
			echo "<div class='form-group'>";
			echo "<label class='control-label col-md-3'>".__("Package Name")."<span class='text-danger'> *</span></label>";
			echo "<div class='col-md-8'>";
			echo $this->Form->input("",["label"=>false,"name"=>"package_label","class"=>"form-control validate[required]","value"=>($edit)?$package_data['package_label']:""]);
			echo "</div>";
			echo "</div>";
			
			echo "<div class='form-group'>";
			echo "<label class='control-label col-md-3'>".__("Package Period")."<span class='text-danger'> *</span></label>";
			echo "<div class='col-md-8'>";
			echo "<div class='input-group'>";	
			echo "<span class='input-group-addon'>".__('No. of Days')."</span>";
			echo $this->Form->input("",["label"=>false,"name"=>"package_length","class"=>"form-control validate[required]","value"=>($edit)?$package_data['package_length']:""]);
			echo "</div>";
			echo "</div>";
			echo "</div>";
			?>
	
			<?php	
			echo "<div class='form-group'>";
			echo "<label class='control-label col-md-3'>".__("Package Amount")."<span class='text-danger'> *</span></label>";
			echo "<div class='col-md-8'>";
			echo "<div class='input-group'>";	
			echo "<span class='input-group-addon'>".$this->Gym->get_currency_symbol()."</span>";
			echo $this->Form->input("",["label"=>false,"name"=>"package_amount","class"=>"form-control validate[required]","value"=>($edit)?$package_data['package_amount']:""]);
			echo "</div>";
			echo "</div>";
			echo "</div>";
			
			echo "<div class='form-group'>";
			echo "<label class='control-label col-md-3'>".__("Package Description")."</label>";
			echo "<div class='col-md-8'>";			
			echo $this->Form->textarea("package_description",["rows"=>"15","class"=>"form-control textarea","value"=>($edit)?$package_data['package_description']:""]);
			echo "</div>";
			echo "</div>";
			
			echo "<div class='form-group'>";
			echo "<label class='control-label col-md-3'>".__("Package Image")."</label>";
			echo "<div class='col-md-8'>";
			echo $this->Form->file("gmgt_packageimage",["class"=>"form-control"]);
			echo "</div>";			
			echo "</div>";	
			
			$url =  (isset($package_data['gmgt_packageimage']) && $package_data['gmgt_packageimage'] != "") ? $this->request->webroot ."/upload/" . $package_data['gmgt_packageimage'] : $this->request->webroot ."/upload/logo.png";
			echo "<div class='col-md-offset-3'>";
			echo "<img src='{$url}'>";
			echo "</div>";
			echo "<br><br>";
			
			echo "<br>";
			echo "<div class='col-md-offset-3'>";
			echo $this->Form->button(__("Save Package"),['class'=>"btn btn-flat btn-success","name"=>"add_package"]);
			echo "</div>";	
			echo $this->Form->end();
			echo "<br>";
			// echo "<br><br><br>";
		?>	
		</div>	
		<div class="overlay gym-overlay">
		  <i class="fa fa-refresh fa-spin"></i>
		</div>
	</div>
</section>

<!-- script>
$(".check_limit").change(function(){
	if($(this).val() == "Limited")
	{
		$(".div_limit input,.div_limit select").removeAttr("disabled");
		$(".div_limit").show("fast");
	}else{
		$(".div_limit").hide("fast");
		// $(".div_limit input,.div_limit select").val("");
		$(".div_limit input,.div_limit select").attr("disabled", "disabled");		
	}
});
</script -->
