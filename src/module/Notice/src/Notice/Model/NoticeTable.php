<?php
namespace Notice\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class NoticeTable
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
	
	public function fetchAllByTime(){
		$resultSet = $this->tableGateway->select(function(Select $select){
			$select->order('oper_time DESC');
		});
		return $resultSet;
	}
	
	public function fetchNoticeArrayByTime(){
		$notices = $this->fetchAllByTime();
		$notice_array = array();
		foreach($notices as $notice){
			$notice_array["notice_".$notice->id] = array(
				"type" => $notice->type,
				"oper_time" => $notice->oper_time,
				"description" => $notice->description,
			);
		}
		return $notice_array;
	}
	
	public function fetchNByTime($n = 6){
		$resultSet = $this->tableGateway->select(function(Select $select) use ($n){
			$select->limit($n);
			$select->order('oper_time DESC');
		});
		return $resultSet;
	}
	
	public function fetchNArrayByTime($n = 6){
		$notices = $this->fetchNByTime($n);
		$notice_array = array();
		foreach($notices as $notice){
			$notice_array["notice_".$notice->id] = array(
				"type" => $notice->type,
				"oper_time" => $notice->oper_time,
				"description" => $notice->description,
			);
		}
		return $notice_array;
	}
	
	public function fetchAllNoticesByOrder()
    {
        $resultSet = $this->tableGateway->select(function(Select $select){
			$select->order('order_num');
		});
        return $resultSet;
    }
	
	public function fetchAllActiveNoticesByOrder()
    {
        $resultSet = $this->tableGateway->select(function(Select $select){
			$select->where(array('is_active' => 1));
			$select->order('order_num');
		});
        return $resultSet;
    }

    public function getNotice($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
	
	public function fetchFirstNNotices($n = 3){
		$GLOBALS['fetch_notice_number'] = $n;
		$resultSet = $this->tableGateway->select(function(Select $select){
			$select->limit($GLOBALS['fetch_notice_number']);
		});
        return $resultSet;
	}
	
	public function fetchFirstNNoticesByOrder($n = 4){
		$GLOBALS['fetch_notice_number_order'] = $n;
		$resultSet = $this->tableGateway->select(function(Select $select){
			$select->limit($GLOBALS['fetch_notice_number_order']);
			$select->order('order_num');
		});
        return $resultSet;
	}
	
	public function createNotice($notice_array){
		$notice = new Notice();
		$notice->id = 0;
		$notice->name = $notice_array['name'];
		$notice->type = $notice_array['type'];
		$notice->oper_time = $notice_array['oper_time'];
		$notice->link_id = $notice_array['link_id'];
		$notice->order_num = $notice_array['order_num'];
		$notice->is_active = $notice_array['is_active'];
		$notice->operator = $notice_array['operator'];
		$notice->description = $notice_array['description'];
		$notice->comment = $notice_array['comment'];
		
		if(!$new_notice_id = $this->saveNotice($notice)){
			return 0;
		}else{
			return $new_notice_id;
		}
	}
	
	public function saveNotice(Notice $notice)
    {
        $data = array(
            'name'  => $notice->name,
            'type' => $notice->type,
            'oper_time' => $notice->oper_time,
            'link_id' => $notice->link_id,
            'order_num' => $notice->order_num,
            'is_active' => $notice->is_active,
            'operator' => $notice->operator,
			'description' => $notice->description,
			'comment' => $notice->comment,
        );
		$id = (int)$notice->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
			return $this->tableGateway->getLastInsertValue();
        } else {
            if ($this->getNotice($id)) {
                $this->tableGateway->update($data, array('id' => $id));
				return "updated";
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
	}

    public function deleteNotice($id)
    {
        return $this->tableGateway->delete(array('id' => $id));
    }
	
	public function deleteNoticeByParams($params){
		return $this->tableGateway->delete(array(
			'type' => $params['type'],
			'link_id' => $params['link_id'],
		));
	}
}