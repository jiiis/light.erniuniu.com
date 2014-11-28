<?php
namespace BasicInfo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use BasicInfo\Model\Section;          
use BasicInfo\Form\SectionForm;       

class SectionController extends AbstractActionController
{
    protected $sectionTable;
    
	public function editgirltextinfoAction(){
		$id = 9;
		$section = $this->getSectionTable()->getSectionByName("girls");

		$form  = new SectionForm();
		$form->bind($section);
		$form->get('submit')->setAttribute('value', 'Save');

		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setInputFilter($section->getInputFilter());
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$this->getSectionTable()->saveSection($form->getData());
				return $this->redirect()->toRoute('girl',array('action'=>'index'));
			}
		}

		 return array(
		 'id' => $id,
		 'form' => $form,
		);
	}
	
	public function contactusinfoAction(){
		return new ViewModel(array(
			'contactus_text' => $this->getSectionTable()->getSectionByName("contactus"),
		));
	}
	
	public function editratesinfoAction(){
		$id = 2;
		$section = $this->getSectionTable()->getSectionByName("rates");

		$form  = new SectionForm();
		$form->bind($section);
		$form->get('submit')->setAttribute('value', 'Save');

		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setInputFilter($section->getInputFilter());
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$this->getSectionTable()->saveSection($form->getData());
				return $this->redirect()->toRoute('rate',array('action'=>'index'));
			}
		}

		 return array(
		 'id' => $id,
		 'form' => $form,
		);
	}
	
	public function editcontactusinfoAction(){
		$id = 10;
		$section = $this->getSectionTable()->getSectionByName("contactus");

		$form  = new SectionForm();
		$form->bind($section);
		$form->get('submit')->setAttribute('value', 'Save');

		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setInputFilter($section->getInputFilter());
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$this->getSectionTable()->saveSection($form->getData());
				return $this->redirect()->toRoute('section',array('action'=>'contactusinfo'));
			}
		}

		 return array(
		 'id' => $id,
		 'form' => $form,
		);
	}
	
	public function homepageinfoAction(){
		return new ViewModel(array(
			'welcome_text' => $this->getSectionTable()->getSectionByName("home"),
		//	'special_text' => $this->getSectionTable()->getSectionByName("home_special"),
		));
	}
	public function edithomepageinfoAction(){
		$id = 3;
		$section = $this->getSectionTable()->getSectionByName("home");

		$form  = new SectionForm();
		$form->bind($section);
		$form->get('submit')->setAttribute('value', 'Save');

		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setInputFilter($section->getInputFilter());
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$this->getSectionTable()->saveSection($form->getData());
				return $this->redirect()->toRoute('section',array('action'=>'homepageinfo'));
			}
		}

		 return array(
		 'id' => $id,
		 'form' => $form,
		);
	}
	
	public function edithomespecialinfoAction(){
		$id = 8;
		$section = $this->getSectionTable()->getSectionByName("home_special");

		$form  = new SectionForm();
		$form->bind($section);
		$form->get('submit')->setAttribute('value', 'Save');

		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setInputFilter($section->getInputFilter());
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$this->getSectionTable()->saveSection($form->getData());
				return $this->redirect()->toRoute('section',array('action'=>'homepageinfo'));
			}
		}

		 return array(
		 'id' => $id,
		 'form' => $form,
		);
	}
	
	public function serviceinfoAction(){
		return new ViewModel(array(
			'serviceOptionInfo' => $this->getSectionTable()->getSectionByName("service_option"),
			'serviceGirlInfo' => $this->getSectionTable()->getSectionByName("service_girl"),
		));
	}
	
	public function employmentinfoAction(){
		return new ViewModel(array(
			'employmentInfo' => $this->getSectionTable()->fetchEmployData(),
		));
	}

	public function editserviceoptioninfoAction(){
	//	$id = (int) $this->params()->fromRoute('id', 0);
		$id = 8;
       		
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
				return $this->redirect()->toRoute('section',array('action'=>'serviceinfo'));
			}
		}

		 return array(
		 'id' => $id,
		 'form' => $form,
		);
	}
	
	public function editservicegirlinfoAction(){
	//	$id = (int) $this->params()->fromRoute('id', 0);
		$id = 9;
       		
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
				return $this->redirect()->toRoute('section',array('action'=>'serviceinfo'));
			}
		}

		 return array(
		 'id' => $id,
		 'form' => $form,
		);
	}
	
	public function editemploymentinfoAction(){
	//	$id = (int) $this->params()->fromRoute('id', 0);
		$id = 6;
       		
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
                			return $this->redirect()->toRoute('section',array('action'=>'employmentinfo'));
           		 }
      		  }
	
       		 return array(
           	 'id' => $id,
           	 'form' => $form,
      	  	);
	}

    public function indexAction()
    {
        return new ViewModel(array(
            'sections' => $this->getSectionTable()->fetchAll(),
        ));
    }

    // Add content to this method:
    public function addAction()
    {
        $form = new SectionForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $section = new Section();
            $form->setInputFilter($section->getInputFilter());
            $form->setData($request->getPost());

            // var_dump($this->$request);
            // var_dump($this->params());
            $filter = $form->getInputFilter();
            $contentRawValue = $filter->getRawValue('content');

            $data = $request->getPost();
            if ($form->isValid()) {
                $section->exchangeArray($form->getData());
                $this->getSectionTable()->saveSection($section);
                return $this->redirect()->toRoute('section');
            }
        }else{
            $form->get('type')->setValue(0);
            $form->get('shop_id')->setValue(0);
        }
        return array('form' => $form);
    }

    public function ownereditAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('section', array(
                'action' => 'add'
            ));
        }
        $sectionName = $this->params()->fromRoute('param0', 'home');

        $section = $this->getSectionTable()->getSection($id,$sectionName);

        $form  = new SectionForm();
        $form->bind($section);
        $form->get('submit')->setAttribute('value', 'Save');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($section->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getSectionTable()->saveSection($form->getData());
                return $this->redirect()->toRoute('shopinfo');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
            'sectionName' => $sectionName,
        );
    }

    // Add content to the following method:
    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('section');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getSectionTable()->deleteSection($id);
            }

            // Redirect to list of sections
            return $this->redirect()->toRoute('section');
        }

        return array(
            'id'    => $id,
            'section' => $this->getSectionTable()->getSection($id)
        );
    }

    // module/Section/src/Section/Controller/SectionController.php:
    public function getSectionTable()
    {
        if (!$this->sectionTable) {
            $sm = $this->getServiceLocator();
            $this->sectionTable = $sm->get('BasicInfo\Model\SectionTable');
        }
        return $this->sectionTable;
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('section', array(
                'action' => 'add'
            ));
        }
        $sectionName = $this->params()->fromRoute('param0', 'home');

        $section = $this->getSectionTable()->getSection($id,$sectionName);

        $form  = new SectionForm();
        $form->bind($section);
        $form->get('submit')->setAttribute('value', 'Save');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($section->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getSectionTable()->saveSection($form->getData());
                return $this->redirect()->toRoute('shopinfo');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
            'sectionName' => $sectionName,
        );
    }

    public function editbynameAction(){
        $name = $this->params()->fromRoute('param0', '');
        if (!$name) {
            return $this->redirect()->toRoute('shopinfo');
        }

        $section = $this->getSectionTable()->getSectionByName($name);

        $form  = new SectionForm();
        $form->bind($section);
        $form->get('submit')->setAttribute('value', 'Save');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($section->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getSectionTable()->saveSection($form->getData());
                return $this->redirect()->toRoute('shopinfo');
            }
        }else{

        }

        return array(
            'id' => $section->id,
            'form' => $form,
            'sectionName' => $section->name,
        );        
    }

}

