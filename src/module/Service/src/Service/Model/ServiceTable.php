<?php
namespace Service\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class ServiceTable
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
	
	public function fetchAllServicesByOrder()
    {
        $resultSet = $this->tableGateway->select(function(Select $select){
			$select->order('order_num');
		});
        return $resultSet;
    }

    public function getService($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveService(Service $service)
    {
        $data = array(
            'name' => $service->name,
            'description'  => $service->description,
			'order_num' => $service->order_num,
        );

        $id = (int)$service->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
			return $this->tableGateway->getLastInsertValue();
        } else {
            if ($this->getService($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteService($id)
    {
        return $this->tableGateway->delete(array('id' => $id));
    }
}