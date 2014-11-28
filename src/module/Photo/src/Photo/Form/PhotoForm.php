<?php
namespace Photo\Form;

use Zend\Form\Form;

class PhotoForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('photo');
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype','multipart/form-data');

        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));

        $this->add(array(
            'name' => 'shop_id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));

        $this->add(array(
            'name' => 'profilename',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Girl Photo',
            ),
        ));

        
        $this->add(array(
            'name' => 'fileupload',
            'attributes' => array(
                'type'  => 'file',
				'value' => 'Select File'
            ),
            'options' => array(
                'label' => 'File Upload',
            ),
        )); 

        $this->add(array(
            'name' => 'type',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));

        $this->add(array(
            'name' => 'link_id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));

		$this->add(array(
            'name' => 'is_cropped',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
		
		$this->add(array(
            'name' => 'order_num',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Upload File',
                'id' => 'submitbutton',
            ),
        ));
    }
}