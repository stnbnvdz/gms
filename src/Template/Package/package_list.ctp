<?php $session = $this->request->session()->read("User");?>
<script>
$(document).ready(function(){		
	$(".mydataTable").DataTable({
		"responsive": true,
		"order": [[ 1, "asc" ]],
		"aoColumns":[
	                  {"bSortable": false},
	                  {"bSortable": true},
	                  {"bSortable": true,"sWidth":"1%"},
	                  {"bSortable": true,"sWidth":"1%"},
	                  {"bSortable": true},	               
	                  {"bSortable": false,"visible":false}],
		"language" : {<?php echo $this->Gym->data_table_lang();?>}
	});
});		
</script>
<?php
if($session["role_name"] == "administrator" || $session["role_name"] == "staff_member" || $session["role_name"] == "accountant")
{ ?>
<script>

$(document).ready(function(){
	var table = $(".mydataTable").DataTable();
	table.column(5).visible( true );
});
</script>
<?php } ?>
<section class="content">
	<br>
	<div class="col-md-12 box box-default">
		<div class="box-header">
			<section class="content-header">
			  <h1>
				<i class="fa fa-bars"></i>
				<?php echo __("Package List");?>
				<small><?php echo __("Package");?></small>
			  </h1>
			  <?php
			if($session["role_name"] == "administrator" || $session["role_name"] == "staff_member")
			{ ?>
			  <ol class="breadcrumb">				
				<a href="<?php echo $this->Gym->createurl("Package","add");?>" class="btn btn-flat btn-custom"><i class="fa fa-plus"></i> <?php echo __("Package");?></a>
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
						<th><?php echo __("Package Name");?></th>						
						<th><?php echo __("Package Period");?></th>
						<th><?php echo __("");?></th>
						<th><?php echo __("Package Fee");?></th>
						<th><?php echo __("Action");?></th>
					</tr>
				</thead>
				<tbody>
				<?php
					foreach($package_data as $package)
					{
						$duration = $this->Gym->get_plan_duration($package->install_plan_id);					
						if(empty($duration))
						{
							$duration["number"] = "";
							$duration["duration"] = "";
						}
						$image = ($package->gmgt_packageimage !="") ? $package->gmgt_packageimage : "logo.png";
						echo "
						<tr id='row-{$package->id}'>
						<td><image src='".$this->request->base ."/upload/{$image}' class='membership-img img-circle'></td>
						<td>{$package->package_label}</td>						
						<td>{$package->package_length}</td>
						<td>{$duration['number']} {$duration['duration']}</td>
						<td>". $this->Gym->get_currency_symbol() ."{$package->package_amount}</td>
						<td>";
						echo " <a href='{$this->Gym->createurl("Package","editPackage")}/{$package->id}' title='Edit' class='btn btn-flat btn-primary' ><i class='fa fa-edit'></i></a>
						<a title='Delete' did='{$package->id}' class='del-membership btn btn-flat btn-danger' data-url='".$this->Gym->createurl("GymAjax","deletePackage")."'><i class='fa fa-trash-o'></i></a>";
/** 						if($session["role_name"] == "administrator")
						{ 
						echo " <a href='{$this->Gym->createurl("Package","viewActivity")}/{$package->id}' class='btn btn-flat btn-info'>".__("Activities")."</a>";	
						}
						echo "</td>
						</tr>
						";*/
					} 
				?>
				</tbody>
				<tfoot>
					<tr>
						<th><?php echo __("Photo");?></th>
						<th><?php echo __("Package Name");?></th>
						<th><?php echo __("Package Period");?></th>
						<th><?php echo __("");?></th>
						<th><?php echo __("Package Amount");?></th>
						<th><?php echo __("Action");?></th>
					</tr>
				</tfoot>
			</table>			
		</div>
			<div class="overlay gym-overlay">
				<i class="fa fa-refresh fa-spin"></i>
			</div>		
		</div>		
	</div>
</section>
