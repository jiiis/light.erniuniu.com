<?php
namespace Notice\Model;


use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
use Zend\InputFilter\InputFilter;                 // <-- Add this import
use Zend\InputFilter\InputFilterAwareInterface;   // <-- Add this import
use Zend\InputFilter\InputFilterInterface;        // <-- Add this import

class Notice implements InputFilterAwareInterface
{
    public $id;
    public $name;
    public $type;
    public $oper_time;
    public $link_id;
    public $order_num;
    public $is_active;
    public $operator;
	public $description;
	public $comment;
	
    protected $inputFilter;

    public function exchangeArray($data)
    {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->name = (isset($data['name'])) ? $data['name'] : null;
        $this->type  = (isset($data['type'])) ? $data['type'] : null;
        $this->oper_time = (isset($data['oper_time'])) ? $data['oper_time'] : null;
        $this->link_id = (isset($data['link_id'])) ? $data['link_id'] : null;
        $this->order_num = (isset($data['order_num'])) ? $data['order_num'] : null;
        $this->is_active = (isset($data['is_active'])) ? $data['is_active'] : null;
        $this->operator = (isset($data['operator'])) ? $data['operator'] : null;
        $this->description = (isset($data['description'])) ? $data['description'] : null;
		$this->comment = (isset($data['comment'])) ? $data['comment'] : null;
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
                'name'     => 'type',
                'required' => false,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'Int',
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'oper_time',
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
                        ),
                    ),
                ),
            )));
			
			$inputFilter->add($factory->createInput(array(
                'name'     => 'link_id',
                'required' => false,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'Int',
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'order_num',
                'required' => false,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'Int',
                    ),
                ),
            )));
			
			$inputFilter->add($factory->createInput(array(
                'name'     => 'is_active',
                'required' => false,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'Int',
                    ),
                ),
            )));


            $inputFilter->add($factory->createInput(array(
                'name'     => 'operator',
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
                        ),
                    ),
                ),
            )));
            
            $inputFilter->add($factory->createInput(array(
                'name'     => 'description',
                'required' => false, 
            )));
			
			$inputFilter->add($factory->createInput(array(
                'name'     => 'comment',
                'required' => false, 
            )));
			
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}