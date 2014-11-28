<?php
namespace Rates\Model;

use Zend\Db\TableGateway\TableGateway;

class RatesTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function fetchAllByCat($id)
    {
        $resultSet = $this->tableGateway->select(array('cat_id' => $id));
        return $resultSet;
    }

	public function update($cat_id, $rate_name, $rate_price){
		$rate_obj = new Rates();
		
		$rate_obj->id = 0;
		$rate_obj->shop_id = 0;
		$rate_obj->cat_id = $cat_id;
		$rate_obj->info = $rate_name;
		$rate_obj->price = $rate_price;
		
		$rate_id = $this->saveRates($rate_obj);
		
		return $rate_id;
	}
	
	public function deleteAll(){
		return $this->tableGateway->delete(array());
	}
	
    public function getRates($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveRates(Rates $rates)
    {
        $data = array(
            'shop_id' => $rates->shop_id,
            'cat_id'  => $rates->cat_id,
            'info' => $rates->info,
            'price' => $rates->price,
        );

        $id = (int)$rates->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
			return $this->tableGateway->getLastInsertValue();
        } else {
            if ($this->getRates($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteRates($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}