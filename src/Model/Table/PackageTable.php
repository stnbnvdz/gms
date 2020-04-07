<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\Validation\Validator;

Class PackageTable extends Table{
	
	public function initialize(array $config)
	{
		$this->addBehavior('Timestamp');
		$this->belongsTo("Category");
		$this->belongsTo("Activity");			
		$this->belongsTo("ClassSchedule");			
    $this->hasMany("Package_Activity",["foreignKey"=>"package_id"]);
	}
}