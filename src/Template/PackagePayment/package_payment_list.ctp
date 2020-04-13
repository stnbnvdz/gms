<?php $session = $this->request->session()->read("User");?>
<script type="text/javascript">
	 $( function() {
    $( document ).tooltip();
  } );
$(document).ready(function() {
	jQuery(".expense_form").validationEngine();
	jQuery('#payment_list').DataTable({
		"responsive": true,
		"order": [[ 0, "asc" ]],
		"aoColumns":[
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true,"sWidth":"1"},
	                  {"bSortable": true,"sWidth":"5px"},
	                  {"bSortable": true,"sWidth":"5px"},
	                  {"bSortable": true,"sWidth":"5px"},
	                  {"bSortable": true,"sWidth":"5px"},
	                  {"bSortable": true},
	                  {"bSortable": false}],
	"language" : {<?php echo $this->Gym->data_table_lang();?>}	
		});
} );
</script>
<section class="content">
	<br>
	<div class="col-md-12 box box-default">		
		<div class="box-header">
			<section class="content-header">
				<h1>
					<i class="fa fa-plus"></i>
					<?php echo __("Package Payment List");?>
					<small><?php echo __("Payment");?></small>
				</h1>
				 <?php
				if($session["role_name"] == "administrator")
				{ ?>
				<ol class="breadcrumb">
					
				<a href="<?php echo $this->Gym->createurl("PackagePayment","generatePackagePaymentInvoice");?>" class="btn btn-flat btn-custom"><i class="fa fa-bars"></i> <?php echo __("Generate Payment Invoice");?></a>
					
				<a class="btn btn-flat btn-custom" onclick="PaymentListPrint()"><i class="fa fa-print"></i> <?php echo __("Print");?></a>
				</ol>
			<?php } ?>
			</section>
		</div>
		<hr>
		<div class="box-body">
		 <table id="payment_list" class="testt table table-striped" cellspacing="0" width="100%">
        	<thead>
            <tr>
				<th><?php  echo __( 'Title', 'gym_mgt' ) ;?></th>
				<th><?php  echo __( 'Member Name', 'gym_mgt' ) ;?></th>
				<th><?php  echo __( 'Amount', 'gym_mgt' ) ;?></th>
				<th><?php  echo __( 'Paid Amount', 'gym_mgt' ) ;?></th>
				<th><?php  echo __( 'Due Amount', 'gym_mgt' ) ;?></th>
				<th><?php  echo __( 'Package Start Date', 'gym_mgt' ) ;?></th>
				<th><?php  echo __( 'Package End Date', 'gym_mgt' ) ;?></th>
				<th><?php  echo __( 'Payment Status', 'gym_mgt' ) ;?></th>
				<th id="btn-action"><?php  echo __( 'Action', 'gym_mgt' ) ;?></th>
            </tr>
			</thead>
			<tbody>
				<?php
				if(!empty($data))
				{
					foreach($data as $row)
					{
						// $due = ($row['membership_amount']- $row['paid_amount'])+($row['membership']['signup_fee']);
						$due = ($row['package_amount']- $row['package_paid_amount']);
						
						echo "<tr>
								<td>{$row['package']['package_label']}</td>
								<td>{$row['gym_member']['first_name']} {$row['gym_member']['last_name']}</td>
								<td>".$this->Gym->get_currency_symbol()." {$row['package_amount']}</td>
								<td>".$this->Gym->get_currency_symbol()." {$row['package_paid_amount']}</td>
								<td>".$this->Gym->get_currency_symbol()." {$due}</td>
								<td>".date($this->Gym->getSettings("date_format"),strtotime($row["package_start_date"]))."</td>
								<td>".date($this->Gym->getSettings("date_format"),strtotime($row["package_end_date"]))."</td>
								<td><span class='bg-primary pay_status'>". __($this->Gym->get_package_paymentstatus($row['pp_id']))."<span></td>
								<td id='btn-action'>
								<a href='javascript:void(0)' class='btn btn-flat btn-default amt_pay' data-url='".$this->request->base ."/GymAjax/gymPackagePay/{$row['pp_id']}'>".__('Pay')."</a>
								<a href='javascript:void(0)' class='btn btn-flat btn-info view_invoice' data-url='".$this->request->base ."/GymAjax/viewPackageInvoice/{$row['pp_id']}'><i class='fa fa-eye'></i></a>";
								if($session["role_name"] == "administrator")
								{
									echo " <a href='".$this->request->base ."/PackagePayment/PackageEdit/{$row['pp_id']}' class='btn btn-flat btn-primary' title='Edit'><i class='fa fa-edit'></i></a>
									<a href='".$this->request->base ."/PackagePayment/deletePackagePayment/{$row['pp_id']}' class='btn btn-flat btn-danger' onclick=\"return confirm('Are you sure,You want to delete this record?')\"><i class='fa fa-trash'></i></a>";
								}
								echo "</td>
						</tr>";
					}
				}
				?>
			</tbody>
			<tfoot>
            <tr>
				<th><?php  echo __( 'Title', 'gym_mgt' ) ;?></th>
				<th><?php  echo __( 'Member Name', 'gym_mgt' ) ;?></th>
				<th><?php  echo __( 'Amount', 'gym_mgt' ) ;?></th>
				<th><?php  echo __( 'Paid Amount', 'gym_mgt' ) ;?></th>
				<th><?php  echo __( 'Due Amount', 'gym_mgt' ) ;?></th>
				<th><?php  echo __( 'Package Start Date', 'gym_mgt' ) ;?></th>
				<th><?php  echo __( 'Package End Date', 'gym_mgt' ) ;?></th>
				<th><?php  echo __( 'Payment Status', 'gym_mgt' ) ;?></th>
				<th id="btn-action"><?php  echo __( 'Action', 'gym_mgt' ) ;?></th>
            </tr>
			</tfoot>
			</table>
		
		<!-- END -->
		</div>
		<div class='overlay gym-overlay'>
			<i class='fa fa-refresh fa-spin'></i>
		</div>
	</div>
</section>
<script>
function PaymentListPrint() {
	document.title = "Payment Report";
  window.print();

}

</script>