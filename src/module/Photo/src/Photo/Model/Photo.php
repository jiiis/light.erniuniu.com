<?php
namespace Photo\Model;


use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
use Zend\InputFilter\InputFilter;                 // <-- Add this import
use Zend\InputFilter\InputFilterAwareInterface;   // <-- Add this import
use Zend\InputFilter\InputFilterInterface;        // <-- Add this import

class Photo implements InputFilterAwareInterface
{
    public $id;
    public $shop_id;
    public $type;
    public $link_id;
    public $pict_url;
    public $isfirstshow;
    public $thumb_url;
    public $is_cropped;
	public $order_num;

    protected $inputFilter;

    public function exchangeArray($data)
    {
        $this->id     = (isset($data['id'])) ? $data['id'] : null;
        $this->shop_id = (isset($data['shop_id'])) ? $data['shop_id'] : null;
        $this->type  = (isset($data['type'])) ? $data['type'] : null;
        $this->link_id = (isset($data['link_id'])) ? $data['link_id'] : 0;
        $this->pict_url = (isset($data['pict_url'])) ? $data['pict_url'] : "";
        $this->isfirstshow = (isset($data['isfirstshow'])) ? $data['isfirstshow'] : 198;
        $this->thumb_url = (isset($data['thumb_url'])) ? $data['thumb_url'] : "";
        $this->is_cropped = (isset($data['is_cropped'])) ? $data['is_cropped'] : "";
        $this->order_num = (isset($data['order_num'])) ? $data['order_num'] : "";
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
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
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'shop_id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'type',
                'required' => true,
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'link_id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'pict_url',
                'required' => true,
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'isfirstshow',
                'required' => true,
            )));
			
			$inputFilter->add($factory->createInput(array(
                'name'     => 'is_cropped',
                'required' => true,
            )));
			
			$inputFilter->add($factory->createInput(array(
                'name'     => 'order_num',
                'required' => true,
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'thumb_url',
                'required' => true,
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
                            'max'      => 100,
                        ),
                    ),
                ),
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}