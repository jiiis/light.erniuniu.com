<?php
namespace BasicInfo\Model;

use Zend\Db\TableGateway\TableGateway;

class BasicInfoTable
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

    public function getBasicInfo($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveBasicInfo(BasicInfo $basicInfo)
    {
        $data = array(
            'shop_id' => $basicInfo->shop_id,
            'name' => $basicInfo->name,
            'type' => $basicInfo->type,
            'email' => $basicInfo->email,
            'address' => $basicInfo->address,
            'phone' => $basicInfo->phone,
            'lattitude' => $basicInfo->lattitude,
            'longitude' => $basicInfo->longitude,
        );

        $id = (int)$basicInfo->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getBasicInfo($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteBasicInfo($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }

    public function fetchByShopId($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('shop_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

}