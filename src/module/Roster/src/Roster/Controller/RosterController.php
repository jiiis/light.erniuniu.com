<?php
namespace Roster\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Roster\Model\Roster;
use Roster\Form\RosterForm;
use Roster\Model\Profile;
use Roster\Form\ProfileForm;
use Zend\Validator\File\Size;
use BasicInfo\Model\Section;          
use BasicInfo\Form\SectionForm;
use Girl\Model\Girl;
use Girl\Form\GirlForm;


class RosterController extends AbstractActionController
{
    protected $rosterTable;
    protected $girlTable;
	protected $sectionTable;
    
 public function getSectionTable()
    {
        if (!$this->sectionTable) {
            $sm = $this->getServiceLocator();
            $this->sectionTable = $sm->get('BasicInfo\Model\SectionTable');
        }
        return $this->sectionTable;
    }

    public function getRosterTable()
    {
        if (!$this->rosterTable) {
            $sm = $this->getServiceLocator();
            $this->rosterTable = $sm->get('Roster\Model\RosterTable');
        }
        return $this->rosterTable;
    }

    public function getGirlTable(){
        if(!$this->girlTable){
            $sm = $this->getServiceLocator();
            $this->girlTable = $sm->get('Girl\Model\GirlTable');
        }
        return $this->girlTable;
    }

    public function indexAction()
    {
		$allGirlsResultSet = $this->getGirlTable()->fetchAllActiveGirlsByOrder();
		$allInfoArray = array();
		foreach($allGirlsResultSet as $singleGirlObject){
			$girl_id = $singleGirlObject->id;
			$girl_obj = $singleGirlObject;
			$form = new GirlForm();
			$form->bind($girl_obj);
			$form->get('submit')->setAttribute('value', 'save');
			/*
			$request = $this->getRequest();
			if ($request->isPost()) {
				echo "ok";
				exit();
				$form->setInputFilter($girl_obj->getInputFilter());
				$form->setData($request->getPost());

				if($form->isValid()){
					$this->getGirlTable()->saveGirl($form->getData());
				}
			}*/

			$allInfoArray[] = array('girl_id'=>$girl_id, 'girl_object'=>$girl_obj, 'girl_form'=>$form);
		}
        return new ViewModel(array(
			'allInfoArray' => $allInfoArray,
        ));
    }

	public function editrosterinfoAction(){
		$id = 5;
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
						return $this->redirect()->toRoute('roster');
					}
				}
	
       		 return array(
           	 'id' => $id,
           	 'form' => $form,
      	  	);
	}

	public function editgirlAction(){
		$id = (int) $this->params()->fromRoute('id', 0);
		$girl = $this->getGirlTable()->getGirl($id);
		$form  = new GirlForm();
		$form->bind($girl);

		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setInputFilter($girl->getInputFilter());
			$form->setData($request->getPost());

			if ($form->isValid()) {
				
				$this->getGirlTable()->saveGirl($form->getData());

				$girl_new = $this->getGirlTable()->getGirl($id);
				$girl_new->name = stripslashes($girl_new->name);
				$girl_new->age = stripslashes($girl_new->age);
				$girl_new->from_nation = stripslashes($girl_new->from_nation);
				$girl_new->description = stripslashes($girl_new->description);
				$this->getGirlTable()->saveGirlSpecial($girl_new);
				
				return $this->redirect()->toRoute('roster');
			}
			
		}
	}

    public function addAction()
    {
        $form = new RosterForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $roster = new Roster();
            $form->setInputFilter($roster->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $roster->exchangeArray($form->getData());
                $this->getRosterTable()->saveRoster($roster);
                // Redirect to list of rosters
                return $this->redirect()->toRoute('roster');
            }
        }
        return array('form' => $form);
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('roster', array(
                'action' => 'add'
            ));
        }
        $roster = $this->getRosterTable()->getRoster($id);

        $form  = new RosterForm();
        $form->bind($roster);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($roster->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getRosterTable()->saveRoster($form->getData());

                // Redirect to list of rosters
                return $this->redirect()->toRoute('roster');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('roster');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getRosterTable()->deleteRoster($id);
            }

            // Redirect to list of rosters
            return $this->redirect()->toRoute('roster');
        }

        return array(
            'id'    => $id,
            'roster' => $this->getRosterTable()->getRoster($id)
        );
    }
}
