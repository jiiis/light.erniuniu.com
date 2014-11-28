<?php
namespace Notice\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class NoticeController extends AbstractActionController
{
    protected $noticeTable;
    
    public function getNoticeTable()
    {
        if (!$this->noticeTable) {
            $sm = $this->getServiceLocator();
            $this->noticeTable = $sm->get('Notice\Model\NoticeTable');
        }
        return $this->noticeTable;
    }
	
	public function indexAction(){
	
	}
	
	public function ajaxRateNoticeAction(){
		date_default_timezone_set('Australia/Sydney');
	
		$request = $this->getRequest();
        $response = $this->getResponse();
		
		if($request->isPost()){
			$rate_cat_id = $request->getPost("cat_id");
			$rate_cat_name = $request->getPost("cat_name");
			
			$notice_array = array(
				'name' => 'rate_change_'.$rate_cat_id,
				'type' => 5,
				'oper_time' => date("Y-m-d H:i:s"),
				'link_id' => $rate_cat_id,
				'order_num' => 0,
				'is_active' => 1,
				'operator' => 'default',
				'description' => 'The rates for '.$rate_cat_name.' has changed!',
				'comment' => 'default',
			);
			
			$this->getNoticeTable()->deleteNoticeByParams(array(
				'type' => 5,
				'link_id' => $rate_cat_id,
			));
			
			if(!$new_notice_id = $this->getNoticeTable()->createNotice($notice_array)){
				$response->setContent(\Zend\Json\Json::encode(array('response' => false)));
			}else{
				$response->setContent(\Zend\Json\Json::encode(array('response' => true)));
			}
		}
		
		return $response;
	}
	
	public function ajaxFetchNoticesAction(){
		$request = $this->getRequest();
        $response = $this->getResponse();
		
		if($request->isPost()){
			$notice_array = $this->getNoticeTable()->fetchNArrayByTime(6);
			
			if(!count($notice_array)){
				$response->setContent(\Zend\Json\Json::encode(array('response' => false)));
			}else{
				$response->setContent(\Zend\Json\Json::encode(array(
					'response' => true,
					'notice_array' => $notice_array,
				)));
			}
		}
		
		return $response;
	}
	
	public function ajaxDeleteAction(){
		$request = $this->getRequest();
        $response = $this->getResponse();
		
		if($request->isPost()){
			$link_id = $request->getPost("link_id");
			$type = $request->getPost("type");
			$params = array(
				'type' => $type,
				'link_id' => $link_id,
			);
			if(!$this->getNoticeTable()->deleteNoticeByParams($params)){
				$response->setContent(\Zend\Json\Json::encode(array('response' => false)));
			}else{
				$response->setContent(\Zend\Json\Json::encode(array('response' => true)));
			}
		}
		
		return $response;
	}
}
