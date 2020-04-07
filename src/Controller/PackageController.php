<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;

Class PackageController extends AppController
{	
	public function initialize()
    {
        parent::initialize();		
		// var_dump($this->request);die;
		$this->loadComponent('Csrf');
        $this->loadComponent('RequestHandler');		
		$this->loadComponent("GYMFunction");
    }

    public function add()
	{			
		$this->set("package",null);			
		$this->set("edit",false);		
		$this->set("title",__("Add Package"));		

		$package = $this->Package->newEntity();
		if($this->request->is("post"))
		{
			$ext = $this->GYMFunction->check_valid_extension($this->request->data('gmgt_packageimage')['name']);
			if($ext != 0)
			{
				$new_name = $this->GYMFunction->uploadImage($this->request->data["gmgt_packageimage"]);
				$this->request->data["gmgt_packageimage"] =  $new_name;
				$this->request->data["created_date"] = date("Y-m-d");			
				$this->request->data["[package]_class"] = json_encode($this->request->data["package_class"]);
				
				
				if(!isset($this->request->data["limit_days"]))
				{
					$this->request->data["limit_days"]=null;
					$this->request->data["limitation"]=null;
				}
				$package = $this->Package->patchEntity($package,$this->request->data());
					
				if($this->Package->save($package))
				{
					$this->Flash->success(__("Success! Record Saved Successfully"));
					return $this->redirect(["action"=>"packageList"]);
				}else{
					$this->Flash->error(__("Error! There was an error while saving,Please try again later."));
				}
			}
			else{
				$this->Flash->error(__("Invalid File Extension, Please Retry."));
				return $this->redirect(["action"=>"add"]);
			}
		}
	}

  public function packageList()
	{
		$package_data = $this->Package->find("all")->toArray();   
  	$this->set("package_data",$package_data);
	
  }

  public function editPackage($id)
	{	$this->set("edit",true);	
		$this->set("package",null);
		$this->set("title",__("Edit Package"));	
		
		$package_data = $this->Package->get($id)->toArray();
  
		$package_class = json_decode($package_data["package_class"]);
		
		$this->set('categories',$catgories);
		$this->set("package_data",$package_data);
		$this->set("package_class",$package_class);
		
		if($this->request->is("post"))
		{
			$ext = $this->GYMFunction->check_valid_extension($this->request->data('gmgt_packageimage')['name']);
			if($ext != 0)
			{
				$row = $this->Package->get($id);
				if($this->request->data['gmgt_packageimage']['name'] != "")
				{
					$new_name = $this->GYMFunction->uploadImage($this->request->data["gmgt_packageimage"]);
					$this->request->data["gmgt_packageimage"] =  $new_name;
				}
				if(!isset($this->request->data["limit_days"]))
				{
					$this->request->data["limit_days"]=null;
					$this->request->data["limitation"]=null;
				}
				$this->request->data["package_class"] = json_encode($this->request->data["package_class"]);
				
				$package = $this->Package->patchEntity($row,$this->request->data);
				if($this->Package->save($package))
				{
					$this->Flash->success(__("Success! Record Updated Successfully"));
					return $this->redirect(["action"=>"packageList"]);
				}else{
					$this->Flash->error(__("Error! There was an error while updating,Please try again later."));
				}
			}else{
				$this->Flash->error(__("Invalid File Extension, Please Retry."));
				return $this->redirect(["action"=>"edit-package",$id]);
			}
		}
		$this->render("add");
	}

  public function viewActivity($mid)
	{
	$activities_list = $this->Package->Activity->find("list",["keyField"=>"id","valueField"=>"title"]);
		$activities_list = $activities_list->toArray();
		
		$selected_activities = $this->Package->Package_Activity->find("list",["keyField"=>"id","valueField"=>"activity_id"])->where(["package_id"=>$mid]);
		$selected_activities = array_values($selected_activities->toArray());
		
		$assigned_activities = $this->Package->Package_Activity->find("all")->where(["package_id"=>$mid])->contain(["Activity"])->select($this->Package->Package_Activity);
		$assigned_activities = $assigned_activities->select(["Activity.cat_id","Activity.assigned_to"])->hydrate(false)->toArray();
		
		$this->set("activities",$activities_list);
		$this->set("selected_activities",$selected_activities);
		$this->set("assigned_activities",$assigned_activities);	

		if($this->request->is("post"))
		{
			$package_activity = TableRegistry::get("Package_Activity");			
			$data = $this->request->data;
			$delete_row= $package_activity->deleteAll(["package_id"=>$data['package_id']]);
			$save_data = array();
			foreach($data["activity_id"] as $activity)
			{				
				$save_data[] = ["package_id"=>$data["package_id"],"activity_id"=>$activity,"created_date"=>date("Y-m-d")];
			}			
			$rows = $package_activity->newEntities($save_data);
			foreach($rows as $row)
			{
				$package_activity->save($row);
			}
			$this->Flash->Success(__("Success! Activity Successfully Assigned."));
			return $this->redirect($this->here);
		}		
  }

  public function deleteActivity($id)
	{
		$row = $this->Package->Package_Activity->get($id);		
		if($this->Package->Package_Activity->delete($row))
		{
			$this->Flash->Success(__("Success! Activity Unassigned Successfully."));
			return $this->redirect($this->referer());
		}
	}

  public function isAuthorized($user)
	{
		$role_name = $user["role_name"];
		$curr_action = $this->request->action;	
		$members_actions = ["packageList"];
		$staff_acc_actions = ["packageList","add","editPackage"];
		switch($role_name)
		{			
			CASE "member":
				if(in_array($curr_action,$members_actions))
				{return true;}else{return false;}
			break;
			
			CASE "staff_member":
				if(in_array($curr_action,$staff_acc_actions))
				{return true;}else{ return false;}
			break;
			
			CASE "accountant":
				if(in_array($curr_action,$staff_acc_actions))
				{return true;}else{return false;}
			break;
		}
		
		return parent::isAuthorized($user);
	}

}