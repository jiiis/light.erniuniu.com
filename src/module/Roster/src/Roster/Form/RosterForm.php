<?php
namespace Rates\Form;

use Zend\Form\Form;

class RatesForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('rates');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        $this->add(array(
            'name' => 'shopId',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'shop id',
            ),
        ));
        $this->add(array(
            'name' => 'service',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'service',
            ),
        ));
        $this->add(array(
            'name' => 'serviceType',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'service type',
            ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'isTitle',
            'attributes' => array(
                'value'  => '1',
            ),
            'options' => array(
                'label' => 'Level',
                'value_options' => array(
                    '1' => 'Service Title',
                    '0' => 'Service Detail',
                    ),
            ),
        ));
        $this->add(array(
            'name' => 'parentId',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Upper Level Id',
            ),
        ));
        $this->add(array(
            'name' => 'price',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Price',
            ),
        ));
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Go',
                'id' => 'submitbutton',
				'title' => 'Click to save the change.',
            ),
        ));
    }
}