<?php
namespace Roster\Model;

use Zend\Db\TableGateway\TableGateway;

class RosterTable
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

    public function getRoster($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveRoster(Roster $roster)
    {
        $data = array(
            'shop_id' => $roster->shop_id,
            'name'  => $roster->name,
            'description' => $roster->description,
            'email' => $roster->email,
            'phone' => $roster->phone,
        );

        $id = (int)$roster->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getRoster($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteRoster($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}