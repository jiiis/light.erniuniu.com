<?php
namespace Service\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Service\Model\Service;
use Service\Form\ServiceForm;
use Service\Model\Profile;
use Service\Form\ProfileForm;
use Zend\Validator\File\Size;
use Service\Model\ServiceTable;


class ServiceController extends AbstractActionController{
    protected $serviceTable;

    public function getServiceTable(){
        if (!$this->serviceTable) {
            $sm = $this->getServiceLocator();
            $this->serviceTable = $sm->get('Service\Model\ServiceTable');
        }
        return $this->serviceTable;
    }

	//*****************************actions********************************
	public function indexAction(){
		return new ViewModel(array(
			'services' => $this->getServiceTable()->fetchAllServicesByOrder(),
		));
    }

    public function addAction(){
       
    }

    public function editAction(){
        
    }

    public function deleteAction(){
        
    }
	
	//******************************ajax actions*****************************
	
	public function ajaxreorderAction(){
		$order_string = $this->params()->fromRoute('order', '');
		$order_array = explode("-", $order_string);
		$order_num = 1;
		foreach($order_array as $service_id){
			$service = $this->getServiceTable()->getService($service_id);
			$service->order_num = $order_num;
			$this->getServiceTable()->saveService($service);
			$order_num++;
		}
	}
	
	public function ajaxeditnameAction(){
		$id = (int) $this->params()->fromRoute('id', 0);
		$name = $this->params()->fromRoute('content', '');
		
		$service = $this->getServiceTable()->getService($id);
		$service->name = $name;
		$this->getServiceTable()->saveService($service);
	}
	
	public function ajaxeditdescriptionAction(){
		$id = (int) $this->params()->fromRoute('id', 0);
		$description = $this->params()->fromRoute('content', '');
	//	$description = str_replace("\n", "<br />", str_replace(" ", "&nbsp;", $description));
		
		$service = $this->getServiceTable()->getService($id);
		$service->description = $description;
		$this->getServiceTable()->saveService($service);
	}
	
/*	public function ajaxaddAction(){
		$name = $this->params()->fromRoute('content', '');
		$description = $this->params()->fromRoute('contentplus', '');
		$service = new Service();
		$service->id = 0;
		$service->name = $name;
		$service->description = $description;
		$service->order_num = 0;
		$this->getServiceTable()->saveService($service); 
	}*/
	
	public function ajaxaddAction(){
		$request = $this->getRequest();
        $response = $this->getResponse();
		if($request->isPost()){
			$service = new Service();
			$service->id = 0;
			$service->name = $request->getPost("service_name");
			$service->description = $request->getPost("service_description");
			$service->order_num = 0;
			
			if(!$new_service_id = $this->getServiceTable()->saveService($service)){
				$response->setContent(\Zend\Json\Json::encode(array('response' => false)));
			}else{
				$response->setContent(\Zend\Json\Json::encode(array('response' => true, 'new_service_id' => $new_service_id)));
			}
		}
		return $response;
	}
	
	public function ajaxdeleteAction(){
		$request = $this->getRequest();
        $response = $this->getResponse();
		if($request->isPost()){
			$service_id = $request->getPost("service_id");
			if (!$this->getServiceTable()->deleteService($service_id))
                $response->setContent(\Zend\Json\Json::encode(array('response' => false)));
            else {
                $response->setContent(\Zend\Json\Json::encode(array('response' => true)));
            }
		}
		return $response;
	}
}
