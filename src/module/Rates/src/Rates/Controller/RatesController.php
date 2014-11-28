<?php
namespace Rates\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Rates\Model\Rates;
use Rates\Form\RatesForm;
use Rates\Model\Profile;
use Rates\Form\ProfileForm;
use Zend\Validator\File\Size;


class RatesController extends AbstractActionController
{
    protected $ratesTable;
    protected $ratesCatTable;
    
    public function getRatesTable()
    {
        if (!$this->ratesTable) {
            $sm = $this->getServiceLocator();
            $this->ratesTable = $sm->get('Rates\Model\RatesTable');
        }
        return $this->ratesTable;
    }

    public function getRatesCatTable()
    {
        if (!$this->ratesCatTable) {
            $sm = $this->getServiceLocator();
            $this->ratesCatTable = $sm->get('Rates\Model\RatesCatTable');
        }
        return $this->ratesCatTable;
    }

    public function indexbycatAction()
    {
        $cat_id = (int) $this->params()->fromRoute('id', 0);
        if (!$cat_id) {
            return $this->redirect()->toRoute('ratescat');
        }

        $ratesCat = $this->getRatesCatTable()->getRatesCat($cat_id);

        $a = $this->getRatesTable()->fetchAllByCat($cat_id);
        return new ViewModel(array(
            'ratesList' => $a,
            'ratesCat' => $ratesCat,
        ));
    }

	public function addAction()
    {
		$cat_id = (int) $this->params()->fromRoute('id', 0);
        $name = $this->params()->fromRoute('info', '');
		$price = (float) $this->params()->fromRoute('price', 0);
		$rate = new Rates();
		$rate->id = 0;
		$rate->shop_id = 0;
		$rate->cat_id = $cat_id;
		$rate->info = $name;
		$rate->price = $price;
		$rate->order_num = 0;
        $this->getRatesTable()->saveRates($rate);
		return $this->redirect()->toRoute('ratescat');
    }
	
 /*   public function addAction()
    {
        $cat_id = (int) $this->params()->fromRoute('id', 0);
        if (!$cat_id) {
            return $this->redirect()->toRoute('ratescat');
        }
        $form = new RatesForm();
        $form->get('price')->setValue('0');
        $form->get('shop_id')->setValue('0');
        $form->get('cat_id')->setValue($cat_id);
        $form->get('info')->setValue('');
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $rates = new Rates();
            $form->setInputFilter($rates->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $rates->exchangeArray($form->getData());
                $this->getRatesTable()->saveRates($rates);
                // Redirect to list of ratess
                return $this->redirect()->toRoute('ratescat',array('action' => 'index','id' => $cat_id,));
            }
        }
        $ratesCat = $this->getRatesCatTable()->getRatesCat($cat_id);
        return array(   'form' => $form, 'ratesCat' => $ratesCat);
        // Redirect to list of ratess by category
        // return $this->redirect()->toRoute('ratescat',array('action' => 'index','id' => $cat_id,));
    }*/

 /*   public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('rates', array(
                'action' => 'add'
            ));
        }
        $rates = $this->getRatesTable()->getRates($id);

        $form  = new RatesForm();
        $form->bind($rates);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($rates->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getRatesTable()->saveRates($form->getData());

                // Redirect to list of ratess
                return $this->redirect()->toRoute('ratescat',array('action' => 'index','id' => $form->getData()->cat_id,));
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }*/
	
	public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
		$info = $this->params()->fromRoute('info', '');
		
        $rate = $this->getRatesTable()->getRates($id);
		$rate->info = $info;
		$this->getRatesTable()->saveRates($rate);
		return $this->redirect()->toRoute("ratescat");
    }
	
	public function ajaxeditAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
		$info = $this->params()->fromRoute('info', '');
		
        $rate = $this->getRatesTable()->getRates($id);
		$rate->info = $info;
		$this->getRatesTable()->saveRates($rate);
	//	return $this->redirect()->toRoute("ratescat");
    }
	
	public function ajaxeditpriceAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
		$price = $this->params()->fromRoute('price', 0);
		
        $rate = $this->getRatesTable()->getRates($id);
		$rate->price = $price;
		$this->getRatesTable()->saveRates($rate);
	//	return $this->redirect()->toRoute("ratescat");
    }
	
	public function editpriceAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
		$price = $this->params()->fromRoute('price', 0);
		
        $rate = $this->getRatesTable()->getRates($id);
		$rate->price = $price;
		$this->getRatesTable()->saveRates($rate);
		return $this->redirect()->toRoute("ratescat");
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('rates');
        }

 /*       $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            $rates=$this->getRatesTable()->getRates($id);
            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getRatesTable()->deleteRates($id);
            }

            // Redirect to list of ratess
            return $this->redirect()->toRoute('rates',array('action' => 'indexbycat','id' => $rates->cat_id,));
        }

        return array(
            'id'    => $id,
            'rates' => $this->getRatesTable()->getRates($id)
        );*/

		$this->getRatesTable()->deleteRates($id);
		return $this->redirect()->toRoute('ratescat');
    }

}
