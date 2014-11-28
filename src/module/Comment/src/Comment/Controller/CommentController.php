<?php
namespace Comment\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Comment\Model\Comment;
use Comment\Form\CommentForm;
use Comment\Model\Profile;
use Comment\Form\ProfileForm;
use Zend\Validator\File\Size;
use Girl\Model\GirlTable;
use Girl\Model\Girl;


class CommentController extends AbstractActionController
{
    protected $commentTable;
	protected $girlTable;

public function getGirlTable()
    {
        if (!$this->girlTable) {
            $sm = $this->getServiceLocator();
            $this->girlTable = $sm->get('Girl\Model\GirlTable');
        }
        return $this->girlTable;
    }

    
    public function getCommentTable()
    {
        if (!$this->commentTable) {
            $sm = $this->getServiceLocator();
            $this->commentTable = $sm->get('Comment\Model\CommentTable');
        }
        return $this->commentTable;
    }

    public function indexAction()
    {
	$girl_name_and_comment_array = array();
        $allCommentResultSet = $this->getCommentTable()->fetchAll();
		foreach($allCommentResultSet as $singleCommentObject){
			$girlName = $this->getGirlTable()->getGirl($singleCommentObject->link_id)->name;
			$girl_name_and_comment_array[] = array('girlName'=>$girlName, 'commentObject'=>$singleCommentObject);
		}
        return new ViewModel(array(
			'girl_name_and_comment_array' => $girl_name_and_comment_array,
        ));
    }

    public function addAction()
    {
        $form = new CommentForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $comment = new Comment();
            $form->setInputFilter($comment->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $comment->exchangeArray($form->getData());
                $this->getCommentTable()->saveComment($comment);
                // Redirect to list of comments
                return $this->redirect()->toRoute('comment');
            }
        }
        return array('form' => $form);
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('comment', array(
                'action' => 'add'
            ));
        }
        $comment = $this->getCommentTable()->getComment($id);

        $form  = new CommentForm();
        $form->bind($comment);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($comment->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getCommentTable()->saveComment($form->getData());

                // Redirect to list of comments
                return $this->redirect()->toRoute('comment');
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
            return $this->redirect()->toRoute('comment');
        }

  /*      $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getCommentTable()->deleteComment($id);
            }

            // Redirect to list of comments
            return $this->redirect()->toRoute('comment');
        }

        return array(
            'id'    => $id,
            'comment' => $this->getCommentTable()->getComment($id)
        );*/
		$this->getCommentTable()->deleteComment($id);
		return $this->redirect()->toRoute('comment');
    }
	
	public function ajaxdeleteAction(){
		$request = $this->getRequest();
        $response = $this->getResponse();
		if($request->isPost()){
			$comment_id = $request->getPost("comment_id");
			if (!$this->getCommentTable()->deleteComment($comment_id))
                $response->setContent(\Zend\Json\Json::encode(array('response' => false)));
            else {
                $response->setContent(\Zend\Json\Json::encode(array('response' => true)));
            }
		}
		return $response;
	}
	
	public function ajaxeditcontentAction(){
		$id = (int) $this->params()->fromRoute('id', 0);
		$content = $this->params()->fromRoute('param0', '');
		
		$comment = $this->getCommentTable()->getComment($id);
		$comment->content = $content;
		$this->getCommentTable()->saveComment($comment);
	}
}
