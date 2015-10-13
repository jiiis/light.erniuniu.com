<?php

namespace Frontend\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Comment\Model\Comment;
use Zend\Mail;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;

class FrontendController extends AbstractActionController
{
    protected $sectionTable;
    protected $ratesCatTable;
    protected $ratesTable;
    protected $basicInfoTable;
    protected $serviceTable;
    protected $photoTable;
    protected $rateTable;
    protected $girlTable;
    protected $noticeTable;
    protected $commentTable;

    public function getServiceTable()
    {
        if (!$this->serviceTable) {
            $sm = $this->getServiceLocator();
            $this->serviceTable = $sm->get('Service\Model\ServiceTable');
        }
        return $this->serviceTable;
    }

    public function getCommentTable()
    {
        if (!$this->commentTable) {
            $sm = $this->getServiceLocator();
            $this->commentTable = $sm->get('Comment\Model\CommentTable');
        }
        return $this->commentTable;
    }

    public function getRatesCatTable()
    {
        if (!$this->ratesCatTable) {
            $sm = $this->getServiceLocator();
            $this->ratesCatTable = $sm->get('Rates\Model\RatesCatTable');
        }
        return $this->ratesCatTable;
    }

    public function getRatesTable()
    {
        if (!$this->ratesTable) {
            $sm = $this->getServiceLocator();
            $this->ratesTable = $sm->get('Rates\Model\RatesTable');
        }
        return $this->ratesTable;
    }

    public function getGirlTable()
    {
        if (!$this->girlTable) {
            $sm = $this->getServiceLocator();
            $this->girlTable = $sm->get('Girl\Model\GirlTable');
        }
        return $this->girlTable;
    }

    public function getPhotoTable()
    {
        if (!$this->photoTable) {
            $sm = $this->getServiceLocator();
            $this->photoTable = $sm->get('Photo\Model\PhotoTable');
        }
        return $this->photoTable;
    }

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

    public function getNoticeTable()
    {
        if (!$this->noticeTable) {
            $sm = $this->getServiceLocator();
            $this->noticeTable = $sm->get('Notice\Model\NoticeTable');
        }
        return $this->noticeTable;
    }

    //********************************************* Actions *************************************************

    public function warningAction()
    {
        $basicInfo = $this->getBasicInfoTable()->fetchAll();
        $viewModel = new ViewModel(array(
            'basic_info' => $basicInfo
        ));
        $viewModel->setTerminal(true);
        return $viewModel;
    }

    public function aboutusAction()
    {
        $homeInfoSectionObject = $this->getSectionTable()->getSectionByName("home");
        $homeSpecialSectionObject = $this->getSectionTable()->getSectionByName("home_special");
        $noticeObject = $this->getSectionTable()->getSectionByName("notice");
        $basicInfo = $this->getBasicInfoTable()->fetchAll();
        $girls = $this->getGirlTable()->fetchFirstNActiveGirlsByOrder(5);
        $girls_array = array();
        foreach ($girls as $girl) {
            $girls_array[] = $girl;
        }
        $notice_array = $this->getNoticeTable()->fetchNArrayByTime(6);
        $viewModel = new ViewModel(array(
            'basic_info' => $basicInfo,
            'home_info' => $homeInfoSectionObject,
            'home_special' => $homeSpecialSectionObject,
            'notice_text' => $noticeObject,
            'girls' => $girls_array,
            'notices' => $notice_array,
        ));
        $viewModel->setTerminal(true);
        return $viewModel;
    }

    public function galleryAction()
    {
        $basicInfo = $this->getBasicInfoTable()->fetchAll();
        //	$girlTextSectionObject = $this->getSectionTable()->getSectionByName("girls");

        $girlPhotoArray = array();
        $girls = $this->getGirlTable()->fetchAllActiveGirlsByOrder();
        foreach ($girls as $girl) {
            $firstPhoto = $this->getPhotoTable()->getFirstPhotoByGirlId($girl->id);
            /*
            if(!$firstPhoto){
                continue;
            }*/
            $otherPhotos = $this->getPhotoTable()->getPhotosExceptFirstByOrderByGirlId($girl->id);

            $find = array('"', '[', ']');
            $girl_roster_old = explode(",", str_replace($find, '', $girl->roster)); // ["1","3","5","6","7"]
            $girl_roster = array(0, 0, 0, 0, 0, 0, 0); // [1, 0, 1, 0, 1, 1, 1]
            $weekdays = array("1", "2", "3", "4", "5", "6", "7");

            foreach ($weekdays as $key => $day_string) {
                if (in_array($day_string, $girl_roster_old)) {
                    $girl_roster[$key] = 1;
                }
            }

            $comments_array = array();
            $comments = $this->getCommentTable()->getCommentsByGirlId($girl->id);
            foreach ($comments as $comment) {
                $comments_array[] = $comment;
            }

            $girlPhotoArray[$girl->id] = array(
                'girl_id' => $girl->id,
                'girl_name' => $girl->name,
                'girl_age' => $girl->age,
                'girl_nationality' => $girl->from_nation,
                'girl_description' => $girl->description,
                'girl_thumb' => $girl->thumb_url,
                'girl_is_new' => $girl->is_new,
                'girl_star_text' => $girl->star_text,
                'girl_roster' => $girl_roster,
                'girl_photos' => array(
                    'first' => $firstPhoto,
                    'others' => $otherPhotos,
                ),
                'girl_comments' => $comments_array,
            );
        }

        $viewModel = new ViewModel(array(
            'basic_info' => $basicInfo,
            //	'girl_text' => $girlTextSectionObject,
            'girl_photo_array' => $girlPhotoArray,
        ));
        $viewModel->setTerminal(true);
        return $viewModel;
    }

    // 这个ratesAction会让同名的cat只显示一次
    /*	public function ratesAction(){
            $girls = $this->getGirlTable()->fetchFirstNActiveGirlsByOrder();
            $girls_array = array();
            foreach($girls as $girl){
                $girls_array[] = $girl;
            }
            $basicInfo = $this->getBasicInfoTable()->fetchAll();
            $ratesTextSectionObject = $this->getSectionTable()->getSectionByName("rates");
            $rateCats = array();
            $ratesCatList = $this->getRatesCatTable()->fetchAll();
            foreach($ratesCatList as $ratesCat){
                $ratesList = $this->getRatesTable()->fetchAllByCat($ratesCat->id);
                $rateCats["$ratesCat->name"] = $ratesList->buffer();
            }
            $viewModel = new ViewModel(array(
                'basic_info' => $basicInfo,
                'rates_text' => $ratesTextSectionObject,
                'rate_cats' => $rateCats,
                'girls' => $girls_array,
            ));
            $viewModel->setTerminal(true);
            return $viewModel;
        }*/

    public function pricesAction()
    {
        $girls = $this->getGirlTable()->fetchFirstNActiveGirlsByOrder();
        $girls_array = array();
        foreach ($girls as $girl) {
            $girls_array[] = $girl;
        }
        $basicInfo = $this->getBasicInfoTable()->fetchAll();
        $ratesTextSectionObject = $this->getSectionTable()->getSectionByName("rates");
        $rateCats = array();
        $ratesCatList = $this->getRatesCatTable()->fetchAllActive();
        foreach ($ratesCatList as $ratesCat) {
            $ratesList = $this->getRatesTable()->fetchAllByCat($ratesCat->id);
            $rateCats[] = array(
                "cat_name" => $ratesCat->name,
                "rate_list" => $ratesList->buffer(),
            );
        }
        $viewModel = new ViewModel(array(
            'basic_info' => $basicInfo,
            'rates_text' => $ratesTextSectionObject,
            'rate_cats' => $rateCats,
            'girls' => $girls_array,
        ));
        $viewModel->setTerminal(true);
        return $viewModel;
    }

    /*
    public function rosterAction(){
        ini_set("display_errors", 0);

        $girls = $this->getGirlTable()->fetchAllActiveGirlsByOrder();
        $roster= array();
        $roster["1"] = array();
        $roster["2"] = array();
        $roster["3"] = array();
        $roster["4"] = array();
        $roster["5"] = array();
        $roster["6"] = array();
        $roster["7"] = array();
        foreach ($girls as $girl) {
        // 当一个girl的roster为null时，不运行此次循环，否则会出notice
        // 但是下面的if语句进不去，很奇怪
        // 只能用ini_set("display_errors", 0);禁止输出notice
        //	if(!$girl->roster){
        //		continue;
        //	}

            $firstPhoto = $this->getPhotoTable()->getFirstPhotoByGirlId($girl->id);
            $otherPhotos = $this->getPhotoTable()->getPhotosExceptFirstByOrderByGirlId($girl->id);
            $girl_info = array("girl" => $girl, "first_photo" => $firstPhoto, "other_photos" => $otherPhotos);

            $find = array('"','[',']');
            $girl_roster=explode(",",str_replace($find,'',$girl->roster));

            foreach ($girl_roster as $wday) {
                if(is_array($roster[$wday])){
                    $roster[$wday][]=$girl_info;
                }
            }
        }
        // count the max deep
        $max_line=0;
        foreach ($roster as $key => $day)
            if(count($day)>$max_line)
                $max_line = count($day);

        $r_section = $this->getSectionTable()->fetchRosterData();


        $viewModel = new ViewModel(array('roster'=>$roster,'max_line'=>$max_line,'roster_data'=>$r_section, 'basic_info'=>$this->getBasicInfoTable()->fetchAll()));
        $viewModel->setTerminal(true);
        return $viewModel;
    //	return $this->simplePage(array('roster'=>$roster,'max_line'=>$max_line,'roster_data'=>$r_section));
    }
    */

    public function timetableAction()
    {
        $girls = $this->getGirlTable()->fetchAllActiveGirlsByOrder();
        $girl_array = array();
        foreach ($girls as $girl) {
            $firstPhoto = $this->getPhotoTable()->getFirstPhotoByGirlId($girl->id);
            $otherPhotos = $this->getPhotoTable()->getPhotosExceptFirstByGirlId($girl->id);

            $find = array('"', '[', ']');
            $girl_roster_old = explode(",", str_replace($find, '', $girl->roster)); // ["1","3","5","6","7"]
            $girl_roster = array(0, 0, 0, 0, 0, 0, 0); // [1, 0, 1, 0, 1, 1, 1]
            $weekdays = array("1", "2", "3", "4", "5", "6", "7");

            foreach ($weekdays as $key => $day_string) {
                if (in_array($day_string, $girl_roster_old)) {
                    $girl_roster[$key] = 1;
                }
            }

            $girl_info = array(
                "girl" => $girl,
                "first_photo" => $firstPhoto,
                "other_photos" => $otherPhotos,
                "working_days" => $girl_roster
            );

            $girl_array[] = $girl_info;
        }

        $viewModel = new ViewModel(array(
            'basic_info' => $this->getBasicInfoTable()->fetchAll(),
            'girl_array' => $girl_array
        ));
        $viewModel->setTerminal(true);

        return $viewModel;
    }

    public function employmentAction()
    {
        /*
            include(dirname(__DIR__)."/Controller/class.phpmailer.php");
            $mail = new PHPMailer();

                $mail->IsSMTP();
                $mail->Host = "smtp.sina.com";
                $mail->SMTPAuth = true;
                $mail->Username = "yngwie_bevan@sina.com";
                $mail->Password = "yngwie123";

                $mail->From = "yngwie_bevan@sina.com";
                $mail->FromName = "Chang Hao";
                $mail->AddAddress("yngwie.chang@gmail.com");

                $mail->IsHTML(true);

                $mail->Subject = "Gong Ai NiuNiu!";
                $mail->Body = "<h1>jibaGong loves erNiuNiu!</h1>";

                $mail->IsHTML(true);

                $mail->Subject = "Gong Ai NiuNiu!";
                $mail->Body = "<h1>jibaGong loves erNiuNiu!</h1>";

                $mail->Send();*/

        $basicInfo = $this->getBasicInfoTable()->fetchAll();
        $employmentTextSectionObject = $this->getSectionTable()->getSectionByName("employment");
        $viewModel = new ViewModel(array(
            'basic_info' => $basicInfo,
            'employment_text' => $employmentTextSectionObject,
        ));
        $viewModel->setTerminal(true);
        return $viewModel;
    }

    public function contactusAction()
    {
        $basicInfo = $this->getBasicInfoTable()->fetchAll();
        $contactTextSectionObject = $this->getSectionTable()->getSectionByName("contactus");
        $viewModel = new ViewModel(array(
            'basic_info' => $basicInfo,
            'contactus_text' => $contactTextSectionObject,
        ));
        $viewModel->setTerminal(true);
        return $viewModel;
    }

    //********************************************* Ajax *************************************************

    public function ajaxcommentAction()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();

        date_default_timezone_set("Australia/Sydney");

        if ($request->isPost()) {
            $comment = new Comment();
            $comment->id = 0;
            $comment->shop_id = 0;
            $comment->type = 0;
            $comment->link_id = $request->getPost("girl_id");
            $comment->poster = $request->getPost("customer_name");
            $comment->post_time = date("Y-m-d H:i:s");
            $comment->content = $request->getPost("customer_message");
            $comment->is_private = 0;
            $comment->email = $request->getPost("customer_email");

            if (!$new_comment_id = $this->getCommentTable()->saveComment($comment)) {
                $response->setContent(\Zend\Json\Json::encode(array('response' => false)));
            } else {
                $response->setContent(\Zend\Json\Json::encode(array(
                            'response' => true,
                            'comment_time' => $comment->post_time
                        )));
            }
        }
        return $response;
    }

    // Zend Mail
    /*
    public function ajaxmailAction(){
        $request = $this->getRequest();
        $response = $this->getResponse();

        if($request->isPost()){
            $website_domain = "SEXY9.com.au"; // 需要改

            $shop_info = $this->getBasicInfoTable()->getBasicInfo(1);
            $to = $shop_info->email;

            $customer_name = $request->getPost("customer_name");
            $customer_number = $request->getPost("customer_number");
            $customer_email = $request->getPost("customer_email");
            $customer_message = $request->getPost("customer_message");

            $subject=$customer_name." has left a message on ".$website_domain;
            $message="<html><head><title></title></head><body>";
            $message.="<h3>Here is a message from ".$website_domain."</h3>";
            $message.="<p>A guest has left a message on the website, please check it</p>";
            $message.="<hr />";
            $message.="Name: ".$customer_name."<br />";
            $message.="Contact Number: ".$customer_number."<br />";
            $message.="Contact Email: ".$customer_email."<br />";
            $message.="Message: ".$customer_message."<br />";
            $message.="</body></html>";

            $htmlMessage = new MimePart($message);
            $htmlMessage->type = "text/html";
            $body = new MimeMessage();
            $body->setParts(array($htmlMessage));

            $mailMessage = new Message();

            $mailMessage->addTo($to);
            $mailMessage->addFrom('auadult@163.com');
            $mailMessage->setSubject($subject);
            $mailMessage->setBody($body);

            $transport = new SmtpTransport();
            $options   = new SmtpOptions(array(
                'name'              => 'smtp.163.com',
                'host'              => 'smtp.163.com',
                'connection_class'  => 'login',
                'connection_config' => array(
                    'username' => 'auadult',
                    'password' => 'auadult92121479',
                ),
            ));
            $transport->setOptions($options);
            $result = $transport->send($mailMessage);

            if(!$result){
                $response->setContent(\Zend\Json\Json::encode(array('response' => false)));
            }else{
                $response->setContent(\Zend\Json\Json::encode(array('response' => true)));
            }
        }
        return $response;
    }
    */

    // Modified mail function, that sents mail from goddaddy mail server other than transport via remote mail service.
    public function ajaxmailAction()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();

        if ($request->isPost()) {
            $website_domain = "pantyhose-tights-nylons.com.au"; // 需要改

            $shop_info = $this->getBasicInfoTable()->getBasicInfo(1);
            $to = $shop_info->email;

            $customer_name = $request->getPost("customer_name");
            $customer_number = $request->getPost("customer_number");
            $customer_email = $request->getPost("customer_email");
            $customer_message = $request->getPost("customer_message");

            $subject = $customer_name . " has left a message on " . $website_domain;
            $message = "<html><head><title></title></head><body>";
            $message .= "<h3>Here is a message from " . $website_domain . "</h3>";
            $message .= "<p>A guest has left a message on the website, please check it</p>";
            $message .= "<hr />";
            $message .= "Name: " . $customer_name . "<br />";
            $message .= "Contact Number: " . $customer_number . "<br />";
            $message .= "Contact Email: " . $customer_email . "<br />";
            $message .= "Message: " . $customer_message . "<br />";
            $message .= "</body></html>";

            $htmlMessage = new MimePart($message);
            $htmlMessage->type = "text/html";
            $body = new MimeMessage();
            $body->setParts(array($htmlMessage));
            $mail = new Mail\Message();

            $mail->setBody($body);
            $mail->setFrom('pantyhose.tights.nylons@gmail.com', 'www.pantyhose-tights-nylons.com.au');
            $mail->addTo($to, 'Name o. recipient');
            $mail->setSubject($subject);

            $transport = new Mail\Transport\Sendmail();
            $result = $transport->send($mail);
            if (!$result) {
                $response->setContent(\Zend\Json\Json::encode(array('response' => false)));
            } else {
                $response->setContent(\Zend\Json\Json::encode(array('response' => true)));
            }
        }

        return $response;
    }
}
