<?php
namespace Girl\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Girl\Model\Girl;
use Girl\Form\GirlForm;
use Zend\Validator\File\Size;

header("Cache-Control: no-cache");

class GirlController extends AbstractActionController
{
    protected $girlTable;
	protected $sectionTable;
	protected $noticeTable;
    
    public function getGirlTable()
    {
        if (!$this->girlTable) {
            $sm = $this->getServiceLocator();
            $this->girlTable = $sm->get('Girl\Model\GirlTable');
        }
        return $this->girlTable;
    }

	public function getSectionTable(){
        if (!$this->sectionTable) {
            $sm = $this->getServiceLocator();
            $this->sectionTable = $sm->get('BasicInfo\Model\SectionTable');
        }
        return $this->sectionTable;
    }
	
	public function getPhotoTable()
    {
        if (!$this->photoTable) {
            $sm = $this->getServiceLocator();
            $this->photoTable = $sm->get('Photo\Model\PhotoTable');
        }
        return $this->photoTable;
    }
	
	public function getNoticeTable()
    {
        if (!$this->noticeTable) {
            $sm = $this->getServiceLocator();
            $this->noticeTable = $sm->get('Notice\Model\NoticeTable');
        }
        return $this->noticeTable;
    }
	
    public function indexAction()
    {
        $girls = $this->getGirlTable()->fetchAllGirlsByOrder();
	//	$girlText = $this->getSectionTable()->getSectionByName("girls");
        return new ViewModel(array(
            'girls' => $girls,
	//		'girl_text' => $girlText,
        ));
    }
	
/*	public function ajaxreorderAction(){
		$order_string = $this->params()->fromRoute('param0', '');
		$order_array = explode("-", $order_string);
		$order_num = 1;
		foreach($order_array as $girl_id){
			$girl = $this->getGirlTable()->getGirl($girl_id);
			$girl->order_num = $order_num;
			$this->getGirlTable()->saveGirlSpecial($girl);
			$order_num++;
		}
	}*/
	
	public function ajaxReorderAction(){
		$request = $this->getRequest();
        $response = $this->getResponse();
		
		if($request->isPost()){
			$order_string = $request->getPost("order_string");
			$order_array = explode("-", $order_string);
			$order_num = 1;
			foreach($order_array as $girl_id){
				$girl = $this->getGirlTable()->getGirl($girl_id);
				$girl->order_num = $order_num;
				$this->getGirlTable()->saveGirlSpecial($girl);
				$order_num++;
			}
		}
		
		$response->setContent(\Zend\Json\Json::encode(array('response' => true)));
		return $response;
	}
	
	public function ajaxdeleteAction(){
		$request = $this->getRequest();
        $response = $this->getResponse();
		
		if($request->isPost()){
			$girl_id = (int) $request->getPost("girl_id");
			$girl = $this->getGirlTable()->getGirl($girl_id);
			$girl_name = $girl->name;
			if (!$this->getGirlTable()->deleteGirl($girl_id))
                $response->setContent(\Zend\Json\Json::encode(array('response' => false)));
            else {
				/* // 这里出现很奇怪的问题，这里的如果用到其他module里面的table模型时，ajax就不返回值
				$photo_array = $this->getPhotoTable()->getPhotosByGirlId($girl_id);
				foreach($photo_array as $photo){
					// 删除数据库上的图片记录
					$this->getPhotoTable()->deletePhoto($photo->id);
					
					// 删除服务器上的图片文件
					$pict_url = $photo->pict_url;
					$thumb_url = $photo->thumb_url;
					unlink(dirname(__DIR__).'/../../../../public/'.$pict_url);
					unlink(dirname(__DIR__).'/../../../../public/'.$thumb_url);
				}*/
				
				
				
                $response->setContent(\Zend\Json\Json::encode(array(
					'response' => true,
					'girl_name' => $girl_name,
				)));
            }
		}
		return $response;
	}
	
	public function ajaxHideShowAction(){
		$request = $this->getRequest();
        $response = $this->getResponse();
		$response_flag = true;
		if($request->isPost()){
			$girl_id = $request->getPost("girl_id");
			$command = $request->getPost("command");
			$girl = $this->getGirlTable()->getGirl($girl_id);
			if($command == "inactive"){
				$girl->is_active = 0;
			}elseif($command == "active"){
				if($girl->can_be_active != 0){
					$girl->is_active = 1;
				}else{
					$response_flag = false;
				}
				
			}
			
			if(!$response_flag){
				$response->setContent(\Zend\Json\Json::encode(array(
					'response' => false,
					'status' => "cannot_be_active",
					'girl_name' => $girl->name,
				)));
			}else{
				if($this->getGirlTable()->saveGirlSpecial($girl) != "updated"){
					$response->setContent(\Zend\Json\Json::encode(array(
						'response' => false,
						'status' => "cannot_save",
						)));
				}else{
					$new_girl = $this->getGirlTable()->getGirl($girl_id);
					$response->setContent(\Zend\Json\Json::encode(array(
						'response' => true,
						'is_active' => $new_girl->is_active,
						'girl_name' => $new_girl->name,
						)));
				}
			}
		}
		return $response;
	}
	
	public function ajaxsetnewAction(){
		$request = $this->getRequest();
        $response = $this->getResponse();
		if($request->isPost()){
			$girl_id = $request->getPost("girl_id");
			$girl = $this->getGirlTable()->getGirl($girl_id);
			$girl_is_new = (int) $girl->is_new;
			if($girl_is_new == 0){
				$girl->is_new = 1;
			}else{
				$girl->is_new = 0;
			}
			
			if($this->getGirlTable()->saveGirlSpecial($girl) != "updated"){
				$response->setContent(\Zend\Json\Json::encode(array('response' => false)));
			}else{
				$response->setContent(\Zend\Json\Json::encode(array('response' => true, 'is_new' => $girl->is_new, 'girl_id' => $girl_id)));
			}
		}
		return $response;
	}
	
	public function ajaxeditstarAction(){
		$request = $this->getRequest();
        $response = $this->getResponse();
		if($request->isPost()){
			$girl_id = $request->getPost("girl_id");
			$star_text = $request->getPost("star_text");
			$girl = $this->getGirlTable()->getGirl($girl_id);
			$girl->star_text = $star_text;
			
			if($this->getGirlTable()->saveGirlSpecial($girl) != "updated"){
				$response->setContent(\Zend\Json\Json::encode(array('response' => false)));
			}else{
				$response->setContent(\Zend\Json\Json::encode(array('response' => true, 'star_text' => $girl->star_text,)));
			}
		}
		return $response;
	}
	
	public function ajaxEditTextAction(){
		$request = $this->getRequest();
        $response = $this->getResponse();
		if($request->isPost()){
			$girl_id = $request->getPost("girl_id");
			$text_key = $request->getPost("text_key");
			$text_value = $request->getPost("text_value");
			$text_value = stripslashes($text_value);
			
			$girl = $this->getGirlTable()->getGirl($girl_id);
			switch($text_key){
				case "name":
					$girl->name = $text_value;
					break;
				case "age":
					$girl->age = $text_value;
					break;
				case "nationality":
					$girl->from_nation = $text_value;
					break;
				case "description":
					$girl->description = $text_value;
					break;
			}
			
			if($this->getGirlTable()->saveGirlSpecial($girl) != "updated"){
				$response->setContent(\Zend\Json\Json::encode(array('response' => false)));
			}else{
				$new_girl = $this->getGirlTable()->getGirl($girl_id);
				switch($text_key){
					case "name":
						$new_text = $new_girl->name;
						break;
					case "age":
						$new_text = $new_girl->age;
						break;
					case "nationality":
						$new_text = $new_girl->from_nation;
						break;
					case "description":
						$new_text = $new_girl->description;
						break;
				}
				
				$response->setContent(\Zend\Json\Json::encode(array(
					'response' => true,
					'new_text' => $new_text,
				)));
			}
		}
		return $response;
	}
	
	public function ajaxaddAction(){
		$request = $this->getRequest();
        $response = $this->getResponse();
		if($request->isPost()){
			$girl = new Girl();
			$girl->id = 0;
			$girl->shop_id = 0;
			$girl->email = "hotgirl@gmail.com";
			$girl->phone = "12345678";
			$girl->thumb_url = "/frontend/images/girls/thumbs/default_thumb.jpg";
			$girl->roster = "";
			$girl->name = stripslashes($request->getPost("girl_name"));
			$girl->age = stripslashes($request->getPost("girl_age"));
			$girl->from_nation = stripslashes($request->getPost("girl_nationality"));
			$girl->description = stripslashes($request->getPost("girl_description"));
			$girl->is_active = 0;
			$girl->can_be_active = 0;
			$girl->order_num = 1;
			$girl->is_new = 0;
			$girl->star_text = "new";
			
			$existing_girls = $this->getGirlTable()->fetchAllGirlsByOrder();
			foreach($existing_girls as $single_girl){
				$new_order_num = (int) $single_girl->order_num + 1;
				$single_girl->order_num = $new_order_num;
				$this->getGirlTable()->saveGirlSpecial($single_girl);
			}
			
			if(!$new_girl_id = $this->getGirlTable()->saveGirlSpecial($girl)){
				$response->setContent(\Zend\Json\Json::encode(array('response' => false)));
			}else{
				$new_girl = $this->getGirlTable()->getGirl($new_girl_id);
			
				$response->setContent(\Zend\Json\Json::encode(array(
					'response' => true,
					'new_girl_id' => $new_girl_id,
					'thumb_image' => $new_girl->thumb_url,
					'girl_name' => $new_girl->name,
					'girl_age' => $new_girl->age,
					'girl_nationality' => $new_girl->from_nation,
					'girl_description' => $new_girl->description,
				)));
			}
		}
		return $response;
	}

	public function ajaxAddNoticeAction(){
		date_default_timezone_set('Australia/Sydney');
	
		$request = $this->getRequest();
        $response = $this->getResponse();
		
		if($request->isPost()){
			$girl_id = $request->getPost("girl_id");
			$girl_name = $request->getPost("girl_name");
			$girl_age = $request->getPost("girl_age");
			$girl_nationality = $request->getPost("girl_nationality");
			$girl_description = $request->getPost("girl_description");
		
			$notice_array = array(
				'name' => 'girl_add_'.$girl_id,
				'type' => 1,
				'oper_time' => date("Y-m-d H:i:s"),
				'link_id' => $girl_id,
				'order_num' => 0,
				'is_active' => 1,
				'operator' => 'default',
				'description' => 'A new girl '.$girl_name.' has joined us!',
				'comment' => 'default',
			);
			if(!$new_notice_id = $this->getNoticeTable()->createNotice($notice_array)){
				$response->setContent(\Zend\Json\Json::encode(array('response' => false)));
			}else{
				$response->setContent(\Zend\Json\Json::encode(array('response' => true)));
			}
		}
		
		return $response;
	}
	
    public function showrosterAction(){
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('girl', array(
                'action' => 'add'
            ));
        }

        $girl = $this->getGirlTable()->getGirl($id);

        return array(
                'girl' => $girl,
                'form' => new GirlForm(),
            );
    }

    public function updaterosterAction(){
        $girls = $this->getGirlTable()->fetchAll();

        return array(
                'girls' =>$girls
            );
    }
}
