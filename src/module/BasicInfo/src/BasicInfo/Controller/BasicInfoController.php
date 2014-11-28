<?php
namespace BasicInfo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use BasicInfo\Model\BasicInfo;          // <-- Add this import
use BasicInfo\Form\BasicInfoForm;       // <-- Add this import

class BasicInfoController extends AbstractActionController
{
    protected $basicInfoTable;
    protected $sectionTable;
    
    public function indexAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
 //      $homeInfo  =$this->getSectionTable()->fetchHomeData();
 //       $ratesInfo =$this->getSectionTable()->fetchRatesData();
 //     $rosterInfo=$this->getSectionTable()->fetchRosterData();
 //       $employInfo=$this->getSectionTable()->fetchEmployData();

        return new ViewModel(array(
            'basicInfo' => $this->getBasicInfoTable()->fetchByShopId($id),
        ));
    }


    public function showallAction(){
        return new ViewModel(array(
            'basicInfos' => $this->getBasicInfoTable()->fetchAll(),
        ));
    }

    public function addAction()
    {
        $form = new BasicInfoForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $basicInfo = new BasicInfo();
            $form->setInputFilter($basicInfo->getInputFilter());
            $form->setData($request->getPost());


            if ($form->isValid()) {
                $basicInfo->exchangeArray($form->getData());
                $this->getBasicInfoTable()->saveBasicInfo($basicInfo);

                // Redirect to list of basicInfos
                return $this->redirect()->toRoute('shopinfo',array('action' => 'showall'));
            }
        }else{
            $form->get('type')->setValue('unknow');
            $form->get('shop_id')->setValue(0);
        }
        return array('form' => $form);
    }

    // Add content to this method:
    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('shopinfo', array(
                'action' => 'add'
            ));
        }
        $basicInfo = $this->getBasicInfoTable()->getBasicInfo($id);

        $form  = new BasicInfoForm();
        $form->bind($basicInfo);
        $form->get('submit')->setAttribute('value', 'Save');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($basicInfo->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getBasicInfoTable()->saveBasicInfo($form->getData());

                // Redirect to list of basicInfos
                return $this->redirect()->toRoute('shopinfo');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    // Add content to the following method:
    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('basicInfo');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getBasicInfoTable()->deleteBasicInfo($id);
            }

            // Redirect to list of basicInfos
            return $this->redirect()->toRoute('basicInfo');
        }

        return array(
            'id'    => $id,
            'basicInfo' => $this->getBasicInfoTable()->getBasicInfo($id)
        );
    }

    // module/BasicInfo/src/BasicInfo/Controller/BasicInfoController.php:
    public function getBasicInfoTable()
    {
        if (!$this->basicInfoTable) {
            $sm = $this->getServiceLocator();
            $this->basicInfoTable = $sm->get('BasicInfo\Model\BasicInfoTable');
        }
        return $this->basicInfoTable;
    }

    public function getSectionTable()
    {
        if (!$this->sectionTable) {
            $sm = $this->getServiceLocator();
            $this->sectionTable = $sm->get('BasicInfo\Model\SectionTable');
        }
        return $this->sectionTable;
    }
}