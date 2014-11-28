<?php
namespace BasicInfo\Form;

use Zend\Form\Form;

class SectionForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('section');
        $this->setAttribute('method', 'post');
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
            'name' => 'name',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Name',
            ),
        ));
        $this->add(array(
            'name' => 'type',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Type',
            ),
        ));
        $this->add(array(
            'name' => 'flag',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Flag',
            ),
        ));
        $this->add(array(
            'name' => 'content',
            'attributes' => array(
                'type'  => 'textarea',                
                'rows' => '10',
                'cols' => '80',
			'class'=>'ckeditor',
			'id'=>'editor1',
            ),
            'options' => array(
                'label' => 'Content',

            ),
        ));
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        ));
    }
}

