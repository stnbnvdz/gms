<?php
namespace App\Controller;
use Cake\App\Controller;

class GymStaffController extends AppController
{
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent("GYMFunction");
	}
	
	public function staffList()
	{		
		$data = $this->GymStaff->GymMember->find("all")->where(["GymMember.role_name"=>"accountant"])->hydrate(false)->toArray();
		$this->set("data",$data);	
	}
	
	public function addStaff()
	{
		$session = $this->request->session()->read("User");
		$this->set("edit",false);			
		$this->set("title",__("Add Staff"));
		
		if($this->request->is("post"))
		{
			$ext = $this->GYMFunction->check_valid_extension($this->request->data['image']['name']);
			if($ext != 0)
			{
				$accountant = $this->GymStaff->GymMember->newEntity();
				
				$image = $this->GYMFunction->uploadImage($this->request->data['image']);
				$this->request->data['image'] = (!empty($image)) ? $image : "logo.png";
				$this->request->data['birth_date'] = date("Y-m-d",strtotime($this->request->data['birth_date']));
				$this->request->data['created_date'] = date("Y-m-d");
				$this->request->data['created_by'] = $session["id"];
				$this->request->data['role_name'] = "accountant";
			
				$accountant = $this->GymStaff->GymMember->patchEntity($accountant,$this->request->data);
				if($this->GymStaff->GymMember->save($accountant))
				{
					$this->Flash->success(__("Success! Record Successfully Saved."));
					return $this->redirect(["action"=>"staffList"]);
				}else
				{				
					if($accountant->errors())
					{	
						foreach($accountant->errors() as $error)
						{
							foreach($error as $key=>$value)
							{
								$this->Flash->error(__($value));
							}						
						}
					}
				}
			}else{
				$this->Flash->error(__("Invalid File Extension, Please Retry."));
				return $this->redirect(["action"=>"add-staff"]);
			}
		}
	}
	
	public function editStaff($id)
	{
		$this->set("edit",true);
		$this->set("title",__("Edit Staff"));
		
		$data = $this->GymStaff->GymMember->get($id);			
		$this->set("data",$data->toArray());
		$this->render("addStaff");
		
		if($this->request->is("post"))
		{
			$ext = $this->GYMFunction->check_valid_extension($this->request->data['image']['name']);
			if($ext != 0)
			{
				$row = $this->GymStaff->GymMember->get($id);
				$this->request->data['birth_date'] = date("Y-m-d",strtotime($this->request->data['birth_date']));
				$image = $this->GYMFunction->uploadImage($this->request->data['image']);
				if($image != "")
				{
					$this->request->data['image'] = $image;
				}else{
					unset($this->request->data['image']);
				}			
				$update = $this->GymStaff->GymMember->patchEntity($row,$this->request->data);
				if($this->GymStaff->GymMember->save($update))
				{
					$this->Flash->success(__("Success! Record Updated Successfully."));
					return $this->redirect(["action"=>"staffList"]);
				}else
				{				
					if($update->errors())
					{	
						foreach($update->errors() as $error)
						{
							foreach($error as $key=>$value)
							{
								$this->Flash->error(__($value));
							}						
						}
					}
				}
			}else{
				$this->Flash->error(__("Invalid File Extension, Please Retry."));
				return $this->redirect(["action"=>"edit-staff",$id]);
			}
		}
	}
	
	public function deleteStaff($id)
	{
		$row = $this->GymStaff->GymMember->get($id);
		if($this->GymStaff->GymMember->delete($row))
		{
			$this->Flash->success(__("Success! Staff Deleted Successfully."));
			return $this->redirect($this->referer());
		}
	}
	
	public function isAuthorized($user)
	{
		$role_name = $user["role_name"];
		$curr_action = $this->request->action;
		$members_actions = ["staffList"];
		$staff_acc_actions = ["staffList"];
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