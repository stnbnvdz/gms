<?php $session = $this->request->session()->read("User");?>
<script>
$(document).ready(function(){		
	var table = $(".mydataTable").DataTable({
		"responsive": true,
		"order": [[ 1, "asc" ]],
		"aoColumns":[
	                  {"bSortable": false},
	                  {"bSortable": true},
	                  {"bSortable": false},
	                  {"bSortable": true},
	                  {"bSortable": true},	                           
	                  {"bSortable": false,"visible":false}],
	"language" : {<?php echo $this->Gym->data_table_lang();?>}	
	}); 
	
});		
</script>
<?php
if($session["role_name"] == "administrator")
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
				<?php echo __("Coach List");?>
				<small><?php echo __("Coach Member");?></small>
			  </h1>
			  <?php
				if($session["role_name"] == "administrator")
				{ ?>
			  <ol class="breadcrumb">
				<a href="<?php echo $this->Gym->createurl("CoachMembers","addCoach");?>" class="btn btn-flat btn-custom"><i class="fa fa-plus"></i> <?php echo __("Add Coach");?></a>
				<a class="btn btn-flat btn-custom" onclick="coachPrint()"><i class="fa fa-print"></i> <?php echo __("Print");?></a>
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
					<th><?php echo __("Coach Name");?></th>
					<th><?php echo __("Role");?></th>					
					<th><?php echo __("Coach Email");?></th>					
					<th><?php echo __("Mobile No.");?></th>					
					<th><?php echo __("Action");?></th>
				</tr>
			</thead>
			<tbody>
			<?php			
			foreach($data as $row)
			{				
				echo "
				<tr>					
					<td><img src='".$this->request->webroot ."upload/{$row['image']}' class='membership-img img-circle'></img></td>
					<td>{$row['first_name']} {$row['last_name']}</td>
					<td>{$row['gym_role']['name']}</td>
					<td>{$row['email']}</td>
					<td>{$row['mobile']}</td>
					<td>
					<a href='".$this->Gym->createurl('CoachMembers','editStaff')."/{$row['id']}' class='btn btn-flat btn-primary' title='Edit'><i class='fa fa-edit'></i></a>
					<a href='".$this->Gym->createurl('CoachMembers','deleteStaff')."/{$row['id']}' class='btn btn-flat btn-danger' title='Delete' onClick=\"return confirm('Are you sure you want to delete?')\"><i class='fa fa-trash-o'></i></a>
					</td>
				</tr>
				";
			} 
			?>
			</tbody>
			<tfoot>
				<tr>
					<th><?php echo __("Photo");?></th>
					<th><?php echo __("Coach Name");?></th>
					<th><?php echo __("Role");?></th>					
					<th><?php echo __("Coach Member Email");?></th>					
					<th><?php echo __("Mobile No.");?></th>					
					<th><?php echo __("Action");?></th>
				</tr>
			</tfoot>
		</table>
		</div>	
		<div class="overlay gym-overlay">
		  <i class="fa fa-refresh fa-spin"></i>
		</div>
	</div>
</section>
<script>
function coachPrint() {
	document.title = "Coach Report";
  window.print();

}

</script>