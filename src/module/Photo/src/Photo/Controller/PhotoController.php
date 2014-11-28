<?php
namespace Photo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Photo\Model\Photo;
use Photo\Form\PhotoForm;
use Photo\Model\Profile;
use Photo\Form\ProfileForm;
use Zend\Validator\File\Size;


class PhotoController extends AbstractActionController
{
    protected $photoTable;
    protected $girlTable;
    protected $girlForm;

    public function getPhotoTable()
    {
        if (!$this->photoTable) {
            $sm = $this->getServiceLocator();
            $this->photoTable = $sm->get('Photo\Model\PhotoTable');
        }
        return $this->photoTable;
    }

	public function ajaxReorderAction(){
		$request = $this->getRequest();
        $response = $this->getResponse();
		
		if($request->isPost()){
			$order_string = $request->getPost("order_string");
			$order_array = explode("-", $order_string);
			$order_num = 1;
			foreach($order_array as $photo_id){
				$photo = $this->getPhotoTable()->getPhoto($photo_id);
				$photo->order_num = $order_num;
				$this->getPhotoTable()->savePhoto($photo);
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
			$photo_id = (int) $request->getPost("photo_id");
			$photo = $this->getPhotoTable()->getPhoto($photo_id);
			$pict_url = $photo->pict_url;
			$thumb_url = $photo->thumb_url;
			
			
			$girl_id = (int) $photo->link_id;
			
			
			if (!$this->getPhotoTable()->deletePhoto($photo_id))
                $response->setContent(\Zend\Json\Json::encode(array('response' => false)));
            else {
				// 每删除一个photo都要重新进行排序
				$rest_photos = $this->getPhotoTable()->fetchAllByOrderByGirlId($girl_id);
				$order_num = 1;
				foreach($rest_photos as $temp_photo){
					$temp_photo->order_num = $order_num++;
					$this->getPhotoTable()->savePhoto($temp_photo);
				}
			
			
			
			
			
                $response->setContent(\Zend\Json\Json::encode(array(
					'response' => true,
					'pict_url' => $pict_url,
					'thumb_url' => $thumb_url,
				)));
            }
		}
		return $response;
	}
	
	public function ajaxafterphotodeleteAction(){
		$request = $this->getRequest();
		$response = $this->getResponse();
		
		if($request->isPost()){
			$photo_id = (int) $request->getPost("photo_id");
		//	$photo = $this->getPhotoTable()->getPhoto($photo_id); // 此photo已经删除，查询不到结果
			$pict_url = $request->getPost("photo_url");
			$thumb_url = $request->getPost("thumb_url");
			
			if($thumb_url != "/frontend/images/girls/thumbs/default_thumb.jpg"){
				unlink(dirname(__DIR__).'/../../../../public/'.$thumb_url);
			}
			unlink(dirname(__DIR__).'/../../../../public/'.$pict_url);
			
		//	$response->setContent(\Zend\Json\Json::encode(array('response' => true)));
		}
	//	return $response;
	}
	
	public function ajaxaftergirldeleteAction(){
		$request = $this->getRequest();
		$response = $this->getResponse();
		
		if($request->isPost()){
			$girl_id = (int) $request->getPost("girl_id");
			$photo_array = $this->getPhotoTable()->getPhotosByGirlId($girl_id);
			
			foreach($photo_array as $photo){
				// 删除数据库上的图片记录
				$this->getPhotoTable()->deletePhoto($photo->id);
				
				// 删除服务器上的图片文件
				$pict_url = $photo->pict_url;
				$thumb_url = $photo->thumb_url;
				if($thumb_url != "/frontend/images/girls/thumbs/default_thumb.jpg"){
					unlink(dirname(__DIR__).'/../../../../public/'.$thumb_url);
				}
				unlink(dirname(__DIR__).'/../../../../public/'.$pict_url);
			}
			
			$response->setContent(\Zend\Json\Json::encode(array('response' => true)));
		}
		
		return $response;
	}
	
    public function indexAction()
    {
        $a = $this->getPhotoTable()->fetchAll();
        return new ViewModel(array(
            'photos' => $a,
        ));
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('photo', array(
                'action' => 'add'
            ));
        }
        $photo = $this->getPhotoTable()->getPhoto($id);

        $form  = new PhotoForm();
        $form->bind($photo);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($photo->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getPhotoTable()->savePhoto($form->getData());

                // Redirect to list of photos
                return $this->redirect()->toRoute('photo', array(
                'action' => 'girlphoto'));
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function uploadfileAction()
    {
        $form = new ProfileForm();
		
        $request = $this->getRequest();  
        $photo = new Photo();
        $filename = strtotime("now");

     //   $link_id = $request->getPost()->link_id;

        if ($request->isPost()) {
            $profile = new Profile();
            $form->setInputFilter($profile->getInputFilter());
            
            $nonFile = $request->getPost()->toArray();
            $File    = $this->params()->fromFiles('fileupload');
			
            $data = array_merge(
                 $nonFile, //POST 
                 array('fileupload'=> $File['name']) //FILE...
             );
            //set data post and file ...    
            $form->setData($data);
             
            if ($form->isValid()) {
                $size = new Size(array('max'=>3000000));
                
                $adapter = new \Zend\File\Transfer\Adapter\Http(); 
                $adapter->setValidators(array($size), $File['name']);

                if (!$adapter->isValid()){
                    $dataError = $adapter->getMessages();
                    $error = array();
                    foreach($dataError as $key=>$row)
                    {
                        $error[] = $row;
                    }
                    $form->setMessages(array('fileupload'=>$error ));
                } else {
                    // $adapter->setDestination(dirname(__DIR__).'/../../../../public/images/girls');
                    $adapter->setDestination('/tmp');
                    if ($adapter->receive($File['name'])) {

						// 这种方法能够防止用户将图片的后缀手动改成假的
						$image_info = getImageSize($adapter->getfilename());
						$image_type_string = $image_info["mime"]; // "image/png" | "image/jpeg"
						if($image_type_string != "image/jpeg" && $image_type_string != "image/png"){
							unlink($adapter->getfilename());
							return $this->redirect()->toRoute('photo',array(
															'action'=>'girlphoto',
                                                            'id'=>$request->getPost()->link_id,
                                                            'param0'=>'upload_type_error'));
						}
					
                        $file_path = pathinfo($adapter->getfilename());
						copy($adapter->getfilename(), dirname(__DIR__).'/../../../../public/frontend/images/girls/'.$filename.".".$file_path ['extension']);
						
                        unlink($adapter->getfilename());
                        $profile->exchangeArray($form->getData());
                        
                        $photo->shop_id = $request->getPost()->shop_id;
                        $photo->type = $request->getPost()->type;
                        $photo->link_id = $request->getPost()->link_id;
                        $photo->order_num = $request->getPost()->order_num;
						
						$photo_num = count($this->getPhotoTable()->getPhotosByGirlId($photo->link_id));
						if($photo_num == 0){
							// 如果该girl本来没有图片，那么新加的图片自动设为default
							$photo->isfirstshow = 1;
						}else{
							$photo->isfirstshow = 0;
						}
						
						$photo->order_num = $photo_num + 1; 
						
                     //   $photo->isfirstshow = $request->getPost()->isfirstshow;
                        $photo->pict_url = '/frontend/images/girls/'.$filename.".".$file_path ['extension'];
                        $photo->thumb_url = "/frontend/images/girls/thumbs/default_thumb.jpg";
						$photo->is_cropped = 0;

                        $this->getPhotoTable()->savePhoto($photo);
                    }
                }  
            } 
        }

        if ($photo->type == 0) 
            return $this->redirect()->toRoute('photo',array('action'=>'girlphoto',
                                                            'id'=>$photo->link_id,
                                                            'param0'=>'upload_ok'));
        else
            return $this->redirect()->toRoute('photo');

    }

    public function girlphotoAction(){
        $girl_id = (int) $this->params()->fromRoute('id', 0);
        $message = $this->params()->fromRoute('param0', 0);
		
        $girl = $this->getGirlTable()->getGirl($girl_id);
        
        return new ViewModel(array(
            'photos' => $this->getPhotoTable()->fetchAllByOrderByGirlId($girl->id),
            'girl' => $girl,
			'message' => $message,
        ));
    }

    public function addAction(){ 
        $girl_id = (int) $this->params()->fromRoute('id', 0);
        $girl = $this->getGirlTable()->getGirl($girl_id);
        $form = new PhotoForm();
        $form->get('type')->setValue('0');
        $form->get('is_cropped')->setValue('0');
        $form->get('order_num')->setValue('0');
        $form->get('link_id')->setValue($girl->id);
        $form->get('shop_id')->setValue($girl->shop_id);

        return array('form' => $form,
                    'girl_name' => $girl->name,
                    );
    }

    public function getGirlTable()
    {
        if (!$this->girlTable) {
            $sm = $this->getServiceLocator();
            $this->girlTable = $sm->get('Girl\Model\GirlTable');
        }
        return $this->girlTable;
    }

    public function getGirlForm()
    {
        if (!$this->girlForm) {
            $sm = $this->getServiceLocator();
            $this->girlForm = $sm->get('Girl\Form\GirlForm');
        }
        return $this->girlForm;
    }

    public function setFirstShowAction()
    {
        $photo_id = (int) $this->params()->fromRoute('id', 0);
        $photo = $this->getPhotoTable()->getPhoto($photo_id);

        $girl = $this->getGirlTable()->getGirl($photo->link_id);
        $girl->thumb_url = $photo->thumb_url;

		if($photo->is_cropped == 0){
			$girl->can_be_active = 0;
			$girl->is_active = 0;
		}else{
			$girl->can_be_active = 1;
			$girl->is_active = 1;
		}
		
        $this->getGirlTable()->saveInterGirl($girl);

        $photolist = $this->getPhotoTable()->fetchByGirlId($photo->link_id);
        foreach ($photolist as $aPhoto) {
            if($aPhoto->isfirstshow == 1){
                $aPhoto->isfirstshow = 0;
                $this->getPhotoTable()->savePhoto($aPhoto);
            }
        }
        $photo->isfirstshow = 1;
        $this->getPhotoTable()->savePhoto($photo);

        if ($photo->type == 0) 
            return $this->redirect()->toRoute('photo',array('action'=>'girlphoto',
                                                            'id'=>$photo->link_id,
                                                            'param0'=>'default_ok'));
        else
            return $this->redirect()->toRoute('photo');
    }

    public function cropAction(){
        $photo_id = (int) $this->params()->fromRoute('id', 0);
        if(!$photo_id){
           return $this->redirect()->toRoute('girl');
        }
        $photo = $this->getPhotoTable()->getPhoto($photo_id);
        $girl = $this->getGirlTable()->getGirl($photo->link_id);

        /*
        $request = $this->getRequest();
        if($request->isPost()){
            $data = $request->getPost()->toArray();
            $this->cropThumbPict($data);
        }*/

        return array('id'=>$photo_id,
                    'photo' => $photo,
                    'girl' => $girl,);
    }

    public function cropthumbpictAction(){
        $jpeg_quality = 100;
		$png_quality = 9;
        $file_path=dirname(__DIR__)."/../../../../public";
        $web_path="/frontend/images/girls/thumbs/";

        // crop image
        $request = $this->getRequest();
        $data = $request->getPost()->toArray();
        $photo = $this->getPhotoTable()->getPhoto($data['photo_id']);
		
        $pict_uri = $file_path.$photo->pict_url;
		$thumb_url_old = $photo->thumb_url;
		
		// 这种方法能够防止用户将图片的后缀手动改成假的
		$image_info = getImageSize($pict_uri);
		$image_type_string = $image_info["mime"]; // "image/png" | "image/jpeg"
		if($image_type_string == "image/jpeg"){
			$img_r = imagecreatefromjpeg($pict_uri);
			$dst_r = ImageCreateTrueColor($data['w'], $data['h']);
		
			imagecopyresampled($dst_r, $img_r, 0, 0, $data['x'], $data['y'], $data['w'], $data['h'], $data['w'], $data['h']);
			$thumb_url = $web_path."tb_".strtotime("now").".jpg";
			imagejpeg($dst_r, $file_path.$thumb_url , $jpeg_quality);
		}elseif($image_type_string == "image/png"){
			$img_r = imagecreatefrompng($pict_uri);
			$dst_r = ImageCreateTrueColor($data['w'], $data['h']);
			
			imagecopyresampled($dst_r, $img_r, 0, 0, $data['x'], $data['y'], $data['w'], $data['h'], $data['w'], $data['h']);
			$thumb_url = $web_path."tb_".strtotime("now").".png";
			
			imagepng($dst_r, $file_path.$thumb_url , $png_quality);
		}
        
        $photo->thumb_url = $thumb_url;
		$photo->is_cropped = 1;
		
        $this->getPhotoTable()->savePhoto($photo);

        $girl = $this->getGirlTable()->getGirl($photo->link_id);

        // change girl's thumb picture
        if($photo->isfirstshow){
            $girl->thumb_url = $photo->thumb_url;
			
			// 只有当default的图片被crop之后，该girl才变成active
			$girl->can_be_active = 1;
			$girl->is_active = 1;
        }
		
	//	$girl->is_active = 1;
        $this->getGirlTable()->saveInterGirl($girl);

		
		// 删除原来的thumb
		if($thumb_url_old != "/frontend/images/girls/thumbs/default_thumb.jpg"){
			unlink($file_path.$thumb_url_old);
		}
		
        return array(   'photo' => $photo, 
                        'girl' => $girl); 
    }
	
	public function cropRedirectAction(){
        $jpeg_quality = 100;
		$png_quality = 9;
        $file_path=dirname(__DIR__)."/../../../../public";
        $web_path="/frontend/images/girls/thumbs/";

        // crop image
        $request = $this->getRequest();
        $data = $request->getPost()->toArray();
        $photo = $this->getPhotoTable()->getPhoto($data['photo_id']);
		
        $pict_uri = $file_path.$photo->pict_url;
		$thumb_url_old = $photo->thumb_url;
		
		// 这种方法能够防止用户将图片的后缀手动改成假的
		$image_info = getImageSize($pict_uri);
		$image_type_string = $image_info["mime"]; // "image/png" | "image/jpeg"
		if($image_type_string == "image/jpeg"){
			$img_r = imagecreatefromjpeg($pict_uri);
			$dst_r = ImageCreateTrueColor($data['w'], $data['h']);
		
			imagecopyresampled($dst_r, $img_r, 0, 0, $data['x'], $data['y'], $data['w'], $data['h'], $data['w'], $data['h']);
			$thumb_url = $web_path."tb_".strtotime("now").".jpg";
			imagejpeg($dst_r, $file_path.$thumb_url , $jpeg_quality);
		}elseif($image_type_string == "image/png"){
			$img_r = imagecreatefrompng($pict_uri);
			$dst_r = ImageCreateTrueColor($data['w'], $data['h']);
			
			imagecopyresampled($dst_r, $img_r, 0, 0, $data['x'], $data['y'], $data['w'], $data['h'], $data['w'], $data['h']);
			$thumb_url = $web_path."tb_".strtotime("now").".png";
			
			imagepng($dst_r, $file_path.$thumb_url , $png_quality);
		}
        
        $photo->thumb_url = $thumb_url;
		$photo->is_cropped = 1;
		
        $this->getPhotoTable()->savePhoto($photo);

        $girl = $this->getGirlTable()->getGirl($photo->link_id);

        // change girl's thumb picture
        if($photo->isfirstshow){
            $girl->thumb_url = $photo->thumb_url;
			
			// 只有当default的图片被crop之后，该girl才变成active
			$girl->can_be_active = 1;
			$girl->is_active = 1;
        }
		
	//	$girl->is_active = 1;
        $this->getGirlTable()->saveInterGirl($girl);

		
		// 删除原来的thumb
		if($thumb_url_old != "/frontend/images/girls/thumbs/default_thumb.jpg"){
			unlink($file_path.$thumb_url_old);
		}
		
        return $this->redirect()->toRoute('photo',array('action'=>'girlphoto','id'=>$photo->link_id,'param0'=>'crop_ok'));
    }

    public function deletebygirlAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        $girl_id = (int) $this->params()->fromRoute('param0', 0);

        if($girl_id){
            $girl=$this->getGirlTable()->getGirl($girl_id);
        }

        $request = $this->getRequest();

        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $girl_id = (int) $request->getPost('girl_id');
                $id = (int) $request->getPost('id');
                $this->getPhotoTable()->deletePhoto($id);
            }


            // Redirect to list of photos
            return $this->redirect()->toRoute('photo',array('action'=>'girlphoto','id'=>$girl_id,'param0'=>'girl'));
        }

        return array(
            'action'=>'girlphoto',
            'id'    => $id,
            'param0'=>'girl',
            'photo' => $this->getPhotoTable()->getPhoto($id),
            'girl'=>$girl,
        );
    }

}
