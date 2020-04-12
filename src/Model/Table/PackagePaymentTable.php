<?php
namespace App\Model\Table;
use Cake\ORM\Table;


class PackagePaymentTable extends Table
{
	public function initialize(array $config)
	{
		$this->addBehavior("Timestamp");
		$this->primaryKey('pp_id');
		$this->belongsTo("GymMember");
		$this->belongsTo("Package",["foreignKey"=>"package_id"]);
		$this->belongsTo("PackagePayment",["foreignKey"=>"package_id"]);
		$this->belongsTo("PackagePaymentHistory");
		$this->belongsTo("PackageHistory");
		$this->belongsTo("PackagePayment",["foreignKey"=>"package_id"]);
		$this->belongsTo("PackagePaymentHistory");
		$this->belongsTo("PackageHistory");
		$this->belongsTo("GymIncomeExpense");
		$this->belongsTo("GymMember",["foreignKey"=>"member_id"]);
	}
}
