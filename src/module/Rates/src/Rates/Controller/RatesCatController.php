<?php
namespace Rates\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use BasicInfo\Form\SectionForm;

class RatesCatController extends AbstractActionController
{
    protected $ratesCatTable;
	protected $sectionTable;
	protected $ratesTable;

 public function getRatesTable()
    {
        if (!$this->ratesTable) {
            $sm = $this->getServiceLocator();
            $this->ratesTable = $sm->get('Rates\Model\RatesTable');
        }
        return $this->ratesTable;
    }
	
 public function getSectionTable()
    {
        if (!$this->sectionTable) {
            $sm = $this->getServiceLocator();
            $this->sectionTable = $sm->get('BasicInfo\Model\SectionTable');
        }
        return $this->sectionTable;
    }
    
    public function getRatesCatTable()
    {
        if (!$this->ratesCatTable) {
            $sm = $this->getServiceLocator();
            $this->ratesCatTable = $sm->get('Rates\Model\RatesCatTable');
        }
        return $this->ratesCatTable;
    }

//************************************* indexAction() ******************************************
	
	public function indexAction(){
		$text_info = $this->getSectionTable()->fetchRatesData();
		
		$cat_active_array = array();
		$cat_inactive_array = array();
		$cat_result_set = $this->getRatesCatTable()->fetchAll();
		foreach($cat_result_set as $cat){
			$rate_result_set = $this->getRatesTable()->fetchAllByCat($cat->id);
			$rate_array = array();
			foreach($rate_result_set as $rate){
				$rate_array[$rate->id] = $rate;
			}
			
			if($cat->is_active == 1){
				$cat_active_array[$cat->id] = array(
					'cat_obj' => $cat,
					'rate_array' => $rate_array,
				);
			}elseif($cat->is_active == 0){
				$cat_inactive_array[$cat->id] = array(
					'cat_obj' => $cat,
					'rate_array' => $rate_array,
				);
			}
		}
		
		return new ViewModel(array(
			'text_info' => $text_info,
			'cat_active' => $cat_active_array,
			'cat_inactive' => $cat_inactive_array,
		));
	}
	
	public function ajaxUpdateAction(){
		$request = $this->getRequest();
        $response = $this->getResponse();
		
		if($request->isPost()){
			$cats = $request->getPost()->toArray();
			$update_status = true;
			
			$this->getRatesCatTable()->deleteAll();
			$this->getRatesTable()->deleteAll();
			
			foreach($cats as $cat){
				$cat_name = $cat['cat_name'];
				$is_active = (int) $cat['is_active'];
				$rate_array = $cat['rate_list'];
				
				if(!$cat_id = $this->getRatesCatTable()->update($cat_name, $is_active)){
					$update_status = false;
				}
				
				
				foreach($rate_array as $rate){
					$rate_name = $rate['rate_name'];
					$rate_price = $rate['rate_price'];
					
					if(!$this->getRatesTable()->update($cat_id, $rate_name, $rate_price)){
						$update_status = false;
					}
				}
			}
			
			$response->setContent(\Zend\Json\Json::encode(array(
				'status' => $update_status,
			)));
		}
		
		return $response;
	}
	
	public function editratesinfoAction(){
		$id = 4;
		$section = $this->getSectionTable()->getSection($id);

		$form  = new SectionForm();
		$form->bind($section);
		$form->get('submit')->setAttribute('value', 'Save');

			$request = $this->getRequest();
			if ($request->isPost()) {
			 $form->setInputFilter($section->getInputFilter());
			 $form->setData($request->getPost());

			 if ($form->isValid()) {
				$this->getSectionTable()->saveSection($form->getData());

				// Redirect to list of albums
				return $this->redirect()->toRoute('ratescat');
			 }
		  }

		 return array(
		 'id' => $id,
		 'form' => $form,
		);
	}
}
