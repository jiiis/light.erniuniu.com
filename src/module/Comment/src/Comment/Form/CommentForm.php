<?php
namespace Comment\Form;

use Zend\Form\Form;

class CommentForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('comment');
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
            'options' => array(
                'label' => 'shop id',
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
            'name' => 'poster',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Your Name:',
            ),
        ));
        $this->add(array(
            'name' => 'content',
            'attributes' => array(
                'type'  => 'textarea',
            ),
            'options' => array(
                'label' => 'Your Message for Us:',
            ),
        ));
        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Your Email Address:',
            ),
        ));
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Send',
                'id' => 'submitbutton',
				'class' => 'comment_submit'
            ),
        ));
    }
}