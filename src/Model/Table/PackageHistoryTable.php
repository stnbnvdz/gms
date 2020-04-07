<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\Validation\Validator;

Class PackageHistoryTable extends Table{
	
	public function initialize(array $config)
	{
		$this->addBehavior('Timestamp');
		
		$this->belongsTo("Package",["foreignKey"=>"selected_package"]);
	}
}