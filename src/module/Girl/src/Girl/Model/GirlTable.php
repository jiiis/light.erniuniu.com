<?php
namespace Girl\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class GirlTable
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
	
	public function fetchAllGirlsByOrder()
    {
        $resultSet = $this->tableGateway->select(function(Select $select){
			$select->order('order_num');
		});
        return $resultSet;
    }
	
	public function fetchAllActiveGirlsByOrder()
    {
        $resultSet = $this->tableGateway->select(function(Select $select){
			$select->where(array('is_active' => 1));
			$select->order('order_num');
		});
        return $resultSet;
    }
	
	public function fetchFirstNActiveGirlsByOrder($n = 4){
		$resultSet = $this->tableGateway->select(function(Select $select) use($n){
			$select->where(array('is_active' => 1));
			$select->limit($n);
			$select->order('order_num');
		});
        return $resultSet;
	}

    public function getGirl($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
	
	public function fetchFirstNGirls($n = 3){
		$GLOBALS['fetch_girl_number'] = $n;
		$resultSet = $this->tableGateway->select(function(Select $select){
			$select->limit($GLOBALS['fetch_girl_number']);
		});
        return $resultSet;
	}
	
	public function fetchFirstNGirlsByOrder($n = 4){
		$GLOBALS['fetch_girl_number_order'] = $n;
		$resultSet = $this->tableGateway->select(function(Select $select){
			$select->limit($GLOBALS['fetch_girl_number_order']);
			$select->order('order_num');
		});
        return $resultSet;
	}
	
	public function saveGirlSpecial(Girl $girl)
    {
        $data = array(
            'shop_id' => $girl->shop_id,
            'name'  => $girl->name,
            'description' => $girl->description,
            'email' => $girl->email,
            'phone' => $girl->phone,
            'thumb_url' => $girl->thumb_url,
            'roster' => $girl->roster,
            'from_nation' => $girl->from_nation,
			'order_num' => $girl->order_num,
			'age' => $girl->age,
			'is_active' => $girl->is_active,
			'can_be_active' => $girl->can_be_active,
			'is_new' => $girl->is_new,
			'star_text' => $girl->star_text,
            /*
            'mon' => $girl->mon,
            'tue' => $girl->tue,
            'wed' => $girl->wed,
            'thu' => $girl->thu,
            'fri' => $girl->fri,
            'sat' => $girl->sat,
            'sun' => $girl->sun,
            */
        );
		$id = (int)$girl->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
			return $this->tableGateway->getLastInsertValue();
        } else {
            if ($this->getGirl($id)) {
                $this->tableGateway->update($data, array('id' => $id));
				return "updated";
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
	}	
	
    public function saveGirl(Girl $girl)
    {
        $data = array(
            'shop_id' => $girl->shop_id,
            'name'  => $girl->name,
            'description' => $girl->description,
            'email' => $girl->email,
            'phone' => $girl->phone,
            'thumb_url' => $girl->thumb_url,
            'roster' => \Zend\Json\Json::encode($girl->roster),
            'from_nation' => $girl->from_nation,
			'order_num' => $girl->order_num,
			'age' => $girl->age,
			'is_active' => $girl->is_active,
			'can_be_active' => $girl->can_be_active,
			'is_new' => $girl->is_new,
			'star_text' => $girl->star_text,
            /*
            'mon' => $girl->mon,
            'tue' => $girl->tue,
            'wed' => $girl->wed,
            'thu' => $girl->thu,
            'fri' => $girl->fri,
            'sat' => $girl->sat,
            'sun' => $girl->sun,
            */
        );

        $id = (int)$girl->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
			return $this->tableGateway->getLastInsertValue();
        } else {
            if ($this->getGirl($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function saveInterGirl(Girl $girl)
    {
        $data = array(
            'shop_id' => $girl->shop_id,
            'name'  => $girl->name,
            'description' => $girl->description,
            'email' => $girl->email,
            'phone' => $girl->phone,
            'thumb_url' => $girl->thumb_url,
            'roster' => $girl->roster,// \Zend\Json\Json::encode($girl->roster), IMPORTANT!!!
            'from_nation' => $girl->from_nation,
			'age' => $girl->age,
			'is_active' => $girl->is_active,
			'can_be_active' => $girl->can_be_active,
			'is_new' => $girl->is_new,
			'star_text' => $girl->star_text,
        );

        $id = (int)$girl->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
			return $this->tableGateway->getLastInsertValue();
        } else {
            if ($this->getGirl($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteGirl($id)
    {
        return $this->tableGateway->delete(array('id' => $id));
    }
}