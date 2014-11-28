<?php
namespace BasicInfo\Form;

use Zend\Form\Form;

class BasicInfoForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('basicInfo');
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
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'shop id',
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
            'name' => 'email',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Email',
            ),
        ));
        $this->add(array(
            'name' => 'phone',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Phone',
            ),
        ));
        $this->add(array(
            'name' => 'address',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Address',
            ),
        ));
        $this->add(array(
            'name' => 'lattitude',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Latitude',
            ),
        ));
        $this->add(array(
            'name' => 'longitude',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Longitude',
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