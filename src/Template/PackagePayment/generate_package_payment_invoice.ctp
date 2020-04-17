<?php
echo $this->Html->css('select2.css');
echo $this->Html->script('select2.min');
?>
<script>
$(document).ready(function(){
$(".pack_list").select2();
// $(".mem_valid_from").datepicker( "option", "dateFormat", "yy-mm-dd" );
$(".mem_valid_from").datepicker( "option", "dateFormat", "<?php echo $this->Gym->dateformat_PHP_to_jQueryUI($this->Gym->getSettings("date_format")); ?>" );
<?php
if($edit)
{?>
$( ".mem_valid_from" ).datepicker( "setDate", new Date("<?php echo date($this->Gym->getSettings("date_format"),strtotime($data['package_start_date'])); ?>" ));
<?php } ?>
// $(".mem_valid_from").datepicker({format: 'yyyy-mm-dd'}).on("change",function(ev){
$(".mem_valid_from").on("change",function(ev){
				
				var ajaxurl = $("#mem_date_check_path").val();
				var date = ev.target.value;	
				var package = $(".gen_package_id option:selected").val();			
				if(package != "")
				{
					var curr_data = { date : date, package:package};
					$(".valid_to").val("Calculating date..");
					$.ajax({
							url :ajaxurl,
							type : 'POST',
							data : curr_data,
							success : function(response){
								// $(".valid_to").val($.datepicker.formatDate('<?php echo $this->Gym->getSettings("date_format"); ?>',new Date(response)));
								$(".valid_to").val(response);								
							}
						});
				}else{
					$(".valid_to").val("Select Package");
				}
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
					<?php echo __("Generate Payment Invoice");?>
					<small><?php echo __("Payment");?></small>
				</h1>
				<ol class="breadcrumb">
					<a href="<?php echo $this->Gym->createurl("PackagePayment","packagePaymentList");?>" class="btn btn-flat btn-custom"><i class="fa fa-bars"></i> <?php echo __("Package Payment List");?></a>
				</ol>
			</section>
		</div>
		<hr>
		<div class="box-body">		
		<form name="payment_form" action="" method="post" class="form-horizontal validateForm" id="payment_form">
        <input type="hidden" name="action" value="insert">
		<input type="hidden" name="pp_id" value="0">
		<input type="hidden" name="created_by" value="1">
		<div class="form-group">
			<label class="col-sm-2 control-label" for="day"><?php echo __("Member");?><span class="text-danger">*</span></label>	
			<div class="col-sm-8">
				<?php
				if($edit)
				{
					echo $this->Form->input("",["type"=>"hidden","name"=>"user_id","label"=>false,"class"=>"form-control","value"=>$data["member_id"]]);
				}
				echo $this->Form->select("user_id",$members,["default"=>($edit)?$data["member_id"]:"","empty"=>__("Select Member"),"class"=>"pack_list","required"=>"true",($edit)?"disabled":""]);
				?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="package"><?php echo __("Package");?><span class="text-danger">*</span></label>
			<div class="col-sm-8">
				<?php echo $this->Form->select("package_id",$package,["default"=>($edit)?$data["package_id"]:"","empty"=>__("Select Package"),"class"=>"form-control gen_package_id","data-url"=>$this->request->base . "/GymAjax/get_amount_by_packages"]);?>		
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="total_package_amount"><?php echo __("Total Amount");?><span class="text-danger">*</span></label>
			<div class="col-sm-8">
				<div class='input-group'>
					<span class='input-group-addon'><?php echo $this->Gym->get_currency_symbol();?></span>
					<input id="total_package_amount" class="form-control validate[required,custom[number]]" type="text" value="<?php echo ($edit)?$data["package_amount"]:"";?>" name="package_amount" readonly="">
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="begin_date"><?php echo __("Package Valid From");?><span class="text-danger">*</span></label>
			<div class="col-sm-3">
				<?php echo $this->Form->input("",["label"=>false,"name"=>"package_valid_from","class"=>"form-control validate[required] mem_valid_from","value"=>($edit)?date($this->Gym->getSettings("date_format"),strtotime($data["package_start_date"])):""]); ?>				
			</div>
			<div class="col-sm-1 text-center">	<?php echo __("To");?>			</div>
			<div class="col-sm-4">
				<?php echo $this->Form->input("",["label"=>false,"name"=>"package_valid_to","class"=>"form-control validate[required] valid_to","value"=>(($edit)?date($this->Gym->getSettings("date_format"),strtotime($data['package_end_date'])):''),"readonly"=>true]);
				?>
			</div>
		</div>		
		<div class="col-sm-offset-2 col-sm-8">
        	<input type="submit" value="Save" name="<?php echo __("save_package_payment");?>" class="btn btn-flat btn-success">
        </div>
		</form>
			
		<input type="hidden" value="<?php echo $this->request->base;?>/GymAjax/get_package_end_date" id="mem_date_check_path">
		
		
		
		<!-- END -->
		</div>
		<div class='overlay gym-overlay'>
			<i class='fa fa-refresh fa-spin'></i>
		</div>
	</div>
</section>