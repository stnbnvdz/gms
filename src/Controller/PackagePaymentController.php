<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Gmgt_paypal_class;

class PackagePaymentController extends AppController
{	
	public function initialize()
	{
		parent::initialize();
		require_once(ROOT . DS .'vendor' . DS  . 'paypal' . DS . 'paypal_class.php');
		$this->loadComponent("GYMFunction");
	}

    public function packagePaymentList()
    {	
		$new_session = $this->request->session();
		$session = $this->request->session()->read("User");
		if($session["role_name"] == "member")
		{			
			$pdata = $this->PackagePayment->find("all")->contain(["Package","GymMember"])->where(["GymMember.id"=>$session["id"]])->hydrate(false)->toArray();
		}
		else{
			$pdata = $this->PackagePayment->find("all")->contain(["Package","GymMember"])->hydrate(false)->toArray();
		}
		$this->set("data",$pdata);
		
		if($this->request->is("post"))
		{			
			$pp_id = $this->request->data["pp_id"];
			$row = $this->PackagePayment->get($pp_id);			
			if($this->request->data["package_payment_method"] == "Paypal" && $session["role_name"] == "member")
			{				
				// var_dump($row->member_id);die;
				$pp_id = $this->request->data["pp_id"];
				$user_id = $row->member_id;
				$package_id = $row->package_id;
				$custom_var = $pp_id;			
				$user_info = $this->PackagePayment->GymMember->get($user_id);
				
				$new_session->write("Payment.pp_id",$pp_id);
				$new_session->write("Payment.amountt",$this->request->data["amountt"]);
				
				// var_dump($user_info);die;
				require_once(ROOT . DS .'vendor' . DS  . 'paypal' . DS . 'paypal_process.php');
			}
			else{
				$row->package_paid_amount = $row->package_paid_amount + $this->request->data["amountt"];
				$this->PackagePayment->save($row);
				
				$hrow = $this->PackagePayment->PackagePaymentHistory->newEntity();
				$pdata['pp_id'] = $pp_id;
				$pdata['amountt'] = $this->request->data["amountt"];
				$pdata['package_payment_method'] = $this->request->data["package_payment_method"];
				$pdata['package_paid_by_date'] = date("Y-m-d");
				$pdata['package_created_by'] = $session["id"];
				$pdata['package_transaction_id'] = "";
				
				$hrow = $this->PackagePayment->PackagePaymentHistory->patchEntity($hrow,$pdata);						
				if($this->PackagePayment->PackagePaymentHistory->save($hrow))
				{
					$this->Flash->success(__("Success! Payment Added Successfully."));
				}
			}
			return $this->redirect(["action"=>"packagePaymentList"]);
		}
    }
	
	public function generatePackagePaymentInvoice()
	{
		$this->set("edit",false);
		$members = $this->PackagePayment->GymMember->find("list",["keyField"=>"id","valueField"=>"name"])->where(["role_name"=>"member"]);
		$members = $members->select(["id","name"=>$members->func()->concat(["first_name"=>"literal"," ","last_name"=>"literal"])])->hydrate(false)->toArray();
		$this->set("members",$members);
		
		$package = $this->PackagePayment->Package->find("list",["keyField"=>"id","valueField"=>"package_label"]);
		$this->set("package",$package);
		
		if($this->request->is('post'))
		{			
			$mid = $this->request->data["user_id"];
			$package_start_date = date("Y-m-d",strtotime($this->request->data["package_valid_from"]));
			$package_end_date = date("Y-m-d",strtotime($this->request->data["package_valid_to"]));
			$row = $this->PackagePayment->newEntity();
			$pdata["member_id"] = $mid;
			$pdata["package_id"] = $this->request->data["package_id"];
			$pdata["package_amount"] = $this->request->data["package_amount"];
			$pdata["package_paid_amount"] = 0;
			$pdata["package_start_date"] = $package_start_date;
			$pdata["package_end_date"] = $package_end_date;
			$pdata["package_status"] = "Continue";
			$pdata["package_payment_status"] = 0;
			$pdata["package_created_date"] = date("Y-m-d");
			$row = $this->PackagePayment->patchEntity($row,$pdata);
			$this->PackagePayment->save($row);			
			################## MEMBER's Current Membership Change ##################
			$member_data = $this->PackagePayment->GymMember->get($mid);
			$member_data->selected_package = $this->request->data["package_id"];
			$member_data->package_valid_from = $package_start_date;
			$member_data->package_valid_to = $package_end_date;
			$this->PackagePayment->GymMember->save($member_data);
			#####################Add Membership History #############################
			$mem_histoty = $this->PackagePayment->PackageHistory->newEntity();
			$hdata["member_id"] = $mid;
			$hdata["selected_package"] = $this->request->data["package_id"];
			$hdata["package_valid_from"] = $package_start_date;
			$hdata["package_valid_to"] = $package_end_date;
			$hdata["package_created_date"] = date("Y-m-d");
			$hdata = $this->PackagePayment->PackageHistory->patchEntity($mem_histoty,$hdata);
			if($this->PackagePayment->PackageHistory->save($mem_histoty))
			{
				$this->Flash->success(__("Success! Payment Added Successfully."));	
				return $this->redirect(["action"=>"packagePaymentList"]);
			}
		}
	}
	
	public function packageEdit($eid)
    {
		$this->set("edit",true);
		$members = $this->PackagePayment->GymMember->find("list",["keyField"=>"id","valueField"=>"name"])->where(["role_name"=>"member"]);
		$members = $members->select(["id","name"=>$members->func()->concat(["first_name"=>"literal"," ","last_name"=>"literal"])])->hydrate(false)->toArray();
		$this->set("members",$members);
		
		$package = $this->PackagePayment->Package->find("list",["keyField"=>"id","valueField"=>"package_label"]);
		$this->set("package",$package);
				
		$pdata = $this->PackagePayment->get($eid);
		$this->set("data",$pdata->toArray());
		// var_dump($data->toArray());die;
		
		if($this->request->is("post"))
		{					
			$mid = $this->request->data["user_id"];
			$package_start_date = date("Y-m-d",strtotime($this->request->data["package_valid_from"]));
			$package_end_date = date("Y-m-d",strtotime($this->request->data["package_valid_to"]));
		
			$row = $this->PackagePayment->get($eid);
			$row->member_id = $mid;
			$row->package_id = $this->request->data["package_id"];
			$row->package_amount= $this->request->data["package_amount"];
			$row->package_paid_amount = 0;
			$row->package_start_date = $package_start_date;
			$row->package_end_date = $package_end_date;
			$row->package_status = "Continue";
			$this->PackagePayment->save($row);
			###############################################################
			$member_data = $this->PackagePayment->GymMember->get($mid);
			$member_data->selected_package = $this->request->data["package_id"];
			$member_data->package_valid_from = $package_start_date;
			$member_data->package_valid_to = $package_end_date;
			$this->PackagePayment->GymMember->save($member_data);
			###########################################################
			$this->Flash->success(__("Success! Record Updated Successfully."));	
			return $this->redirect(["action"=>"packagePaymentList"]);
		}
		$this->render("generatePackagePaymentInvoice");		
    }
	
	public function deletePackagePayment($pp_id)
	{
		$row = $this->PackagePayment->get($pp_id);
		if($this->PackagePayment->delete($row))
		{
			$this->Flash->success(__("Success! Payment Record Deleted Successfully."));	
			return $this->redirect(["action"=>"packagePaymentList"]);
		}	
	}
	
	public function incomePackageList()
    {
		$pdata = $this->PackagePayment->GymIncomeExpense->find("all")->contain(["GymMember"])->where(["invoice_type"=>"income"])->hydrate(false)->toArray();
		$this->set("data",$pdata);	
    }
	
	public function addPackageIncome()
    {
		$session = $this->request->session()->read("User");
		$this->set("edit",false);
		$members = $this->PackagePayment->GymMember->find("list",["keyField"=>"id","valueField"=>"name"])->where(["role_name"=>"member"]);
		$members = $members->select(["id","name"=>$members->func()->concat(["first_name"=>"literal"," ","last_name"=>"literal"])])->hydrate(false)->toArray();
		$this->set("members",$members);		
		
		if($this->request->is("post"))
		{	
			$row = $this->PackagePayment->GymIncomeExpense->newEntity();
			$pdata = $this->request->data;
			$total_package_amount = null;
			foreach($pdata["income_amount"] as $amount)
			{$total_package_amount += $amount;}
			$pdata["total_package_amount"] = $total_package_amount;
			$pdata["entry"] = $this->get_entry_records($pdata);
			$pdata["receiver_id"] = $session["id"] ;//current userid;			
			$pdata["invoice_date"] = date("Y-m-d",strtotime($pdata["invoice_date"]));	
			$row = $this->PackagePayment->GymIncomeExpense->patchEntity($row,$pdata);			
			if($this->PackagePayment->GymIncomeExpense->save($row))
			{
				$this->Flash->success(__("Success! Record Saved Successfully."));	
				return $this->redirect(["action"=>"incomeList"]);
			}
		}
    }
	
	public function get_entry_records($pdata)
	{
		$all_income_entry=$pdata['income_entry'];
		$all_income_amount=$pdata['income_amount'];
		
		$entry_data=array();
		$i=0;
		foreach($all_income_entry as $one_entry)
		{
			$entry_data[]= array('entry'=>$one_entry,
						'amount'=>$all_income_amount[$i]);
				$i++;
		}
		return json_encode($entry_data);
	}
	
	public function incomeEdit($eid)
	{
		$this->set("edit",true);
		$members = $this->MembershipPayment->GymMember->find("list",["keyField"=>"id","valueField"=>"name"])->where(["role_name"=>"member"]);
		$members = $members->select(["id","name"=>$members->func()->concat(["first_name"=>"literal"," ","last_name"=>"literal"])])->hydrate(false)->toArray();
		$this->set("members",$members);
		
		$row = $this->MembershipPayment->GymIncomeExpense->get($eid);
		$this->set("data",$row->toArray());
		
		if($this->request->is("post"))
		{
			$pdata = $this->request->data;
			$total_package_amount = null;
			foreach($pdata["income_amount"] as $amount)
			{$total_package_amount += $amount;}
			$pdata["total_package_amount"] = $total_package_amount;
			$pdata["entry"] = $this->get_entry_records($pdata);				
			$pdata["invoice_date"] = date("Y-m-d",strtotime($pdata["invoice_date"]));	
			
			$row = $this->PackagePayment->GymIncomeExpense->patchEntity($row,$pdata);	
			if($this->PackagePayment->GymIncomeExpense->save($row))
			{
				$this->Flash->success(__("Success! Record Updated Successfully."));	
				return $this->redirect(["action"=>"incomeList"]);
			}
		}
		$this->render("addIncome");
	}
	
	public function deletePackageIncome($did)
    {
		$row = $this->PackagePayment->GymIncomeExpense->get($did);
		if($this->PackagePayment->GymIncomeExpense->delete($row))
		{
			$this->Flash->success(__("Success! Record Deleted Successfully."));	
			return $this->redirect($this->referer());
		}	
    }
	
	public function printPackageInvoice()
	{
		$id = $this->request->params["pass"][0];
		$invoice_type = $this->request->params["pass"][1];	
		$in_ex_table = TableRegistry::get("GymIncomeExpense");
		$setting_tbl = TableRegistry::get("GeneralSetting");	
		$income_data = array();
		$expense_data = array();
		$invoice_data = array();
		
		$sys_data = $setting_tbl->find()->select(["name","address","gym_logo","date_format","office_number","country"])->hydrate(false)->toArray();
		
		if($invoice_type == "income")
		{
			$income_data = $this->PackagePayment->GymIncomeExpense->find("all")->contain(["GymMember"])->where(["GymIncomeExpense.id"=>$id])->hydrate(false)->toArray();
			$this->set("income_data",$income_data[0]);		
			$this->set("expense_data",$expense_data);
			$this->set("invoice_data",$invoice_data);
		}
		else if($invoice_type == "expense")
		{
			$expense_data = $this->PackagePayment->GymIncomeExpense->find("all")->where(["GymIncomeExpense.id"=>$id])->select($this->PackagePayment->GymIncomeExpense);
			$expense_data = $expense_data->leftjoin(["GymMember"=>"gym_member"],
									["GymIncomeExpense.receiver_id = GymMember.id"])->select($this->PackagePayment->GymMember)->hydrate(false)->toArray();
			$expense_data[0]["gym_member"] = $expense_data[0]["GymMember"];
			unset($expense_data[0]["GymMember"]);	
			$this->set("income_data",$income_data);		
			$this->set("expense_data",$expense_data[0]);
			$this->set("invoice_data",$invoice_data);			
		}
		
		$this->set("sys_data",$sys_data[0]);
		
    }
	
	public function packagePaymentSuccess()
	{
		$payment_data = $this->request->session()->read("Payment");
		$session = $this->request->session()->read("User");
		$feedata['pp_id']=$payment_data["pp_id"];
		$feedata['amountt']=$payment_data['amountt'];
		$feedata['package_payment_method']='Paypal';		
		$feedata['package_paid_by_date']=date("Y-m-d");		
		$feedata['package_created_by']=$session["id"];
		$row = $this->PackagePayment->PackagePaymentHistory->newEntity();
		$row = $this->PackagePayment->PackagePaymentHistory->patchEntity($row,$feedata);
		if($this->PackagePayment->PackagePaymentHistory->save($row))
		{
			$row = $this->PackagePayment->get($payment_data["pp_id"]);
			$row->package_paid_amount = $row->package_paid_amount + $payment_data['amountt'];
			$this->PackagePayment->save($row);
		}
		
		$session = $this->request->session();
		$session->delete('Payment');
		
		$this->Flash->success(__("Success! Payment Successfully Completed."));
		return $this->redirect(["action"=>"packagePaymentList"]);
	}
	
	public function packageIpnFunction()
	{
		if($this->request->is("post"))
		{
			$trasaction_id  = $_POST["txn_id"];
			$custom_array = explode("_",$_POST['custom']);
			$feedata['pp_id']=$custom_array[1];
			$feedata['amountt']=$_POST['mc_gross_1'];
			$feedata['package_payment_method']='Paypal';	
			$feedata['package_transaction_id']=$package_transaction_id ;
			$feedata['package_created_by']=$custom_array[0];
			//$log_array		= print_r($feedata, TRUE);
			//wp_mail( 'bhaskar@dasinfomedia.com', 'gympaypal', $log_array);
			$row = $this->PackagePayment->PackagePaymentHistory->newEntity();
			$row = $this->PackagePayment->PackagePaymentHistory->patchEntity($row,$feedata);
			if($this->PackagePayment->PackagePaymentHistory->save($row))
			{
				$this->Flash->success(__("Success! Payment Successfully Completed."));
			}
			else{
				$this->Flash->error(__("Paypal Payment IPN save failed to DB."));
			}
			return $this->redirect(["action"=>"packagePaymentList"]);
			//require_once SMS_PLUGIN_DIR. '/lib/paypal/paypal_ipn.php';
		}
	}
	
	public function isAuthorized($user)
	{
		$role_name = $user["role_name"];
		$curr_action = $this->request->action;
		$members_actions = ["packagePaymentList","packagePaymentSuccess","packageIpnFunction"];
		$staff_actions = ["packagePaymentList","addIncome","incomeList","expenseList","addExpense","incomeEdit","expenseEdit"];
		$acc_actions = ["packagePaymentList","addIncome","incomeList","expenseList","addExpense","incomeEdit","expenseEdit","printInvoice","deleteIncome"];
		switch($role_name)
		{			
			CASE "member":
				if(in_array($curr_action,$members_actions))
				{return true;}else{return false;}
			break;
			
			CASE "staff_member":
				if(in_array($curr_action,$staff_actions))
				{return true;}else{ return false;}
			break;
			
			CASE "accountant":
				if(in_array($curr_action,$acc_actions))
				{return true;}else{return false;}
			break;
		}		
		return parent::isAuthorized($user);
	}
}
