<?php
// filename : module/Photo/src/Photo/Form/ProfileForm.php
namespace Photo\Form;

use Zend\Form\Form;

class ProfileForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('Profile');
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype','multipart/form-data');
        
        $this->add(array(
            'name' => 'profilename',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Girl Photo:',
            ),
        ));

        
        $this->add(array(
            'name' => 'fileupload',
            'attributes' => array(
                'type'  => 'file',
            ),
            'options' => array(
                'label' => 'File Upload',
            ),
        )); 
        
        $this->add(array(
            'name' => 'girlid',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Girl id',
            ),
        )); 

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Upload Now'
            ),
        )); 
    }
}