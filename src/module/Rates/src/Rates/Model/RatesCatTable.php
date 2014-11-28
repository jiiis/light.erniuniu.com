<?php
namespace Rates\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Update;

class RatesCatTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {        
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll(){
        $resultSet = $this->tableGateway->select(function(Select $select){
			$select->order('order_num');
		});
        return $resultSet;
    }
	
	public function fetchAllActive(){
		$resultSet = $this->tableGateway->select(array(
			'is_active' => 1,
		));
		return $resultSet;
	}

	public function update($cat_name, $is_active){
		$cat_obj = new RatesCat();
		
		$cat_obj->id = 0;
		$cat_obj->shop_id = 0;
		$cat_obj->name = $cat_name;
		$cat_obj->order_num = 0;
		$cat_obj->is_active = $is_active;
		
		$cat_id = $this->saveRatesCat($cat_obj);
		
		return $cat_id;
	}
	
	public function deleteAll(){
		// 此处delete方法里面必须放一个array（即使是删除所有）
		// 否则controller里面的ajax将不返回值
		return $this->tableGateway->delete(array());
	}
	
// 取出所有rates cat的order_num，并存入一个数组
	public function fetchAllOrdersToArray(){
		$catOrderArray = array();
		$catResultSet = $this->tableGateway->select(function(Select $select){
			$select->order('order_num');

		});
		foreach($catResultSet as $singleCatObject){
			$catOrderArray[] = (int) $singleCatObject->order_num;
		}
		return $catOrderArray;
	}
// 取出制定cat_id的order_num
	public function fetchOrderByCatId($cat_id){
		$catOrder = (int) $this->getRatesCat($cat_id)->order_num;
		return $catOrder;
	}

// update 一个 cat的order_num
	public function updateOrderByCatId($cat_id, $order_num){
		$this->tableGateway->update(array("order_num"=>$order_num),array('id'=>$cat_id));
	}
// 取出所有cat的id到一个数组
	public function fetchAllIdsToArray(){
		$catIdArray = array();
		$catResultSet = $this->tableGateway->select(function(Select $select){
			$select->order('order_num');

		});
		foreach($catResultSet as $singleCatObject){
			$catIdArray[] = (int) $singleCatObject->id;
		}
		return $catIdArray;
	}


    public function getRatesCat($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveRatesCat(RatesCat $ratesCat)
    {
        $data = array(
            'shop_id' => $ratesCat->shop_id,
            'name' => $ratesCat->name,
			'order_num' =>$ratesCat->order_num,
			'is_active' =>$ratesCat->is_active,
        );

        $id = (int)$ratesCat->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
			return $this->tableGateway->getLastInsertValue();
        } else {
            if ($this->getRatesCat($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteRatesCat($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }

}