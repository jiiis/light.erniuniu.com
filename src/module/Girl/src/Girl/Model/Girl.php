<?php
namespace Girl\Model;


use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
use Zend\InputFilter\InputFilter;                 // <-- Add this import
use Zend\InputFilter\InputFilterAwareInterface;   // <-- Add this import
use Zend\InputFilter\InputFilterInterface;        // <-- Add this import

class Girl implements InputFilterAwareInterface
{
    public $id;
    public $shop_id;
    public $name;
    public $description;
    public $email;
    public $phone;
    public $thumb_url;
    public $roster;
    public $from_nation;
	public $order_num;
	public $age;
	public $is_active;
	public $can_be_active;
	public $is_new;
	public $star_text;
    protected $inputFilter;

    public function exchangeArray($data)
    {
        
        $this->id     = (isset($data['id'])) ? $data['id'] : null;
        $this->name = (isset($data['name'])) ? $data['name'] : null;
        $this->description  = (isset($data['description'])) ? $data['description'] : null;
        $this->email = (isset($data['email'])) ? $data['email'] : null;
        $this->phone = (isset($data['phone'])) ? $data['phone'] : null;
        $this->shop_id = (isset($data['shop_id'])) ? $data['shop_id'] : null;
        $this->thumb_url = (isset($data['thumb_url'])) ? $data['thumb_url'] : "/images/default_girl.jpg";
        $this->roster = (isset($data['roster'])) ? $data['roster'] : null;
        $this->from_nation = (isset($data['from_nation'])) ? $data['from_nation'] : null;
		$this->order_num = (isset($data['order_num'])) ? $data['order_num'] : null;
		$this->age = (isset($data['age'])) ? $data['age'] : null;
		$this->is_active = (isset($data['is_active'])) ? $data['is_active'] : null;
		$this->can_be_active = (isset($data['can_be_active'])) ? $data['can_be_active'] : null;
		$this->is_new = (isset($data['is_new'])) ? $data['is_new'] : null;
		$this->star_text = (isset($data['star_text'])) ? $data['star_text'] : null;
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new Exception("Not used");
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
    
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                'name'     => 'id',
                'required' => false,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'name',
                'required' => false,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
						//	'max'      => 100,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'description',
                'required' => false,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                        //	'max'      => 120,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'email',
                'required' => false,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                         //   'max'      => 100,
                        ),
                    ),
                ),
            )));
			
			$inputFilter->add($factory->createInput(array(
                'name'     => 'star_text',
                'required' => false,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                         //   'max'      => 5,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'phone',
                'required' => false,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                        //    'max'      => 100,
                        ),
                    ),
                ),
            )));
			
			$inputFilter->add($factory->createInput(array(
                'name'     => 'age',
                'required' => false,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                        //    'max'      => 5,
                        ),
                    ),
                ),
            )));


            $inputFilter->add($factory->createInput(array(
                'name'     => 'from_nation',
                'required' => false,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                         //   'max'      => 20,
                        ),
                    ),
                ),
            )));
            
            $inputFilter->add($factory->createInput(array(
                'name'     => 'roster',
                'required' => false, 
            )));
			
			$inputFilter->add($factory->createInput(array(
                'name'     => 'is_active',
                'required' => true, 
            )));
			
			$inputFilter->add($factory->createInput(array(
                'name'     => 'can_be_active',
                'required' => true, 
            )));
			
			$inputFilter->add($factory->createInput(array(
                'name'     => 'is_new',
                'required' => true, 
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}