<?php
namespace App\Controller;
use Cake\App\Controller;

class GymTestimonialsController extends AppController
{
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent("GYMFunction");	
	}
	
	public function testimonialsList()
	{		
		$data = $this->GymTestimonials->find("all")->hydrate(false)->toArray();
		$this->set("data",$data);
	}
	
	public function addTestimonials()
	{
		$this->set("edit",false);
		$this->set("title",__("Add New Testimonial"));				
		
		if($this->request->is("post"))
		{
			$testimonials = $this->GymTestimonials->newEntity();
			
			$image = $this->GYMFunction->uploadImage($this->request->data['photo']);
			$this->request->data['photo'] = (!empty($image)) ? $image : "logo.png";
			$this->request->data['name'] = $this->request->data['name'];
			$this->request->data['description'] = $this->request->data['description'];
			$this->request->data['created_date'] = date("Y-m-d");			
			
			$testimonials = $this->GymTestimonials->patchEntity($testimonials,$this->request->data);
			if($this->GymTestimonials->save($testimonials))
			{
				$this->Flash->success(__("Success! Record Successfully Saved."));
				return $this->redirect(["action"=>"testimonialsList"]);
			}else
			{				
				if($testimonials->errors())
				{	
					foreach($testimonials->errors() as $error)
					{
						foreach($testimonials as $key=>$value)
						{
							$this->Flash->error(__($value));
						}						
					}
				}
			}
		}
	}
	
	public function editTestimonials($id)
	{
		$this->set("edit",true);
		$this->set("title",__("Edit Testimonial"));
		
		$data = $this->GymTestimonials->get($id)->toArray();
		$this->set("data",$data);
		$this->render("AddTestimonials");
		
		if($this->request->is("post"))
		{
			$row = $this->GymTestimonials->get($id);
			$this->request->data['name'] = $this->request->data['name'];
			$this->request->data['description'] = $this->request->data['description'];
			$image = $this->GYMFunction->uploadImage($this->request->data['photo']);
			if($image != "")
			{
				$this->request->data['photo'] = $image;
			}else{
				unset($this->request->data['photo']);
			}
			/* $this->request->data['image'] = (!empty($image)) ? $image : "logo.png";*/
			$update = $this->GymTestimonials->patchEntity($row,$this->request->data);
			if($this->GymTestimonials->save($update))
			{
				$this->Flash->success(__("Success! Record Updated Successfully."));
				return $this->redirect(["action"=>"testimonialsList"]);
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
		}
	}
	
	public function deleteTestimonials($id = null)
	{
		$row = $this->GymTestimonials->get($id);
		$this->GymTestimonials->delete($row,['atomic' => false]);
		$this->Flash->success(__("Success! Testimonial Deleted Successfully."));
		return $this->redirect($this->referer());
		// if($id != null)
		// {
		// 	$row = $this->GymTestimonials->get($id);
		// 	if($this->GetTestimonials->delete($row,['atomic' => false]))
		// 	{
		// 		$this->Flash->success(__("Success! Testimonial Deleted Successfully."));
		// 		return $this->redirect($this->referer());
		// 	}
		// }
			
		
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