<?php
namespace Girl\Form;

use Zend\Form\Form;

class GirlForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('girl');
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
            'name' => 'age',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Age',
            ),
        ));
		
		$this->add(array(
            'name' => 'is_active',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Active',
            ),
        ));
		
		$this->add(array(
            'name' => 'can_be_active',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Can be active',
            ),
        ));
		
		$this->add(array(
            'name' => 'is_new',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'New',
            ),
        ));
		
        $this->add(array(
            'name' => 'order_num',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Order Number',
            ),
        ));
        $this->add(array(
            'name' => 'thumb_url',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'picture url',
            ),
        ));
        $this->add(array(
            'name' => 'description',
            'attributes' => array(
                'type'  => 'text',
                'maxlength' => '120',
            ),
            'options' => array(
                'label' => 'Description',

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
            'name' => 'from_nation',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'From Nation',
            ),
        ));
		$this->add(array(
            'name' => 'star_text',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Star Text',
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
        
        $this->add(array(
            'type' => 'Zend\Form\Element\MultiCheckbox',
            'name' => 'roster',
            'options' => array(
                'label' => 'Working day',
                'value_options' => array(
                    '1'=>'Monday',
                    '2'=>' Tuesday',
                    '3'=>' Wednesday',
                    '4'=>' Thursday',
                    '5'=>' Friday',
                    '6'=>' Satureday',
                    '7'=>' Sunday',
                ),
            ),
            'attributes' => array(
                'value' => '1' //set checked to '1'
            )
        ));


    }

    //IF YOU WILL WORK WITH DATABASE 
    //AND NEED bind() FORM FOR EDIT DATA, YOU NEED OVERRIDE
    //populateValues() FUNC LIKE THIS
    public function populateValues($data)
    {   
        foreach($data as $key=>$row)
        {
           if (is_array(@json_decode($row))){
                $data[$key] =   new \ArrayObject(\Zend\Json\Json::decode($row), \ArrayObject::ARRAY_AS_PROPS);
           }
        } 
        
        parent::populateValues($data);
    }
}