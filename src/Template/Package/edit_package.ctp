<section class="content">
	<br>
	<div class="col-md-12 box box-default">		
		<div class="box-header">
			<section class="content-header">
			  <h1>
				<?php echo __("Add Package");?>
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
			
			echo $this->Form->create($package,["type"=>"file","class"=>"validateForm"]);
			echo "<div class='form-group'>";			
			echo $this->Form->input(__("Package Name"),["name"=>"package_label","class"=>"form-control validate[required]","value"=>$package_data['package_label']]);
			echo "</div>";
			
			echo "<div class='form-group col-md-10 no-padding'>";			
			echo $this->Form->label(__("Package Category"));				
			echo $this->Form->select("package_cat_id",$categories,["default"=>$package_data["package_cat_id"],"empty"=>__("Select Category"),"class"=>"form-control validate[required] cat_list"]);
			echo "</div>";	
									
			echo "<div class='form-group col-md-2'>";
			echo $this->Form->label(" ");
			echo $this->Form->button(__("Add Category"),["class"=>"form-control add_category btn btn-success btn-flat","type"=>"button","data-url"=>$this->Gym->createurl("GymAjax","addCategory")]);
			echo "</div>";				
			
			echo "<div class='form-group'>";	
			echo $this->Form->input(__("Package Period"),["name"=>"package_length","class"=>"form-control validate[required]","value"=>$package_data['package_length']]);
			echo "</div>";

			echo "<div class='form-group'>";
			echo $this->Form->input(__("Package Amount"),["name"=>"package_fee","class"=>"form-control validate[required]","value"=>$package_data['package_fee']]);
			echo "</div>";	
			
			echo "<div class='form-group'>";
			echo $this->Form->label(__("Package Description"));
			echo $this->Form->textarea("package_description",["rows"=>"15","class"=>"form-control textarea","value"=>$package_data['package_description']]);
			echo "</div>";
			
			echo "<div class='form-group'>";
			echo $this->Form->label(__("Package Image"));
			echo $this->Form->file("gmgt_packageimage",["class"=>"form-control"]);
			echo "</div>";			
			
			$url =  (isset($package_data['gmgt_packageimage']) && $package_data['gmgt_packageimage'] != "") ? $this->request->webroot ."/upload/" . $package_data['gmgt_packageimage'] : $this->request->webroot ."/upload/logo.png";
			echo "<img src='{$url}'>";
			echo "<br><br>";
			
			echo $this->Form->button("Update Package",['class'=>"btn btn-primary","name"=>"add_package"]);
			echo $this->Form->end();
			// echo "<br><br><br>";
		?>
	
		</div>	
		<div class="overlay gym-overlay">
		  <i class="fa fa-refresh fa-spin"></i>
		</div>
	</div>
</section>