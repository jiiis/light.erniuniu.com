<?php
namespace BasicInfo\Model;

use Zend\Db\TableGateway\TableGateway;

class SectionTable
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

    public function getSection($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveSection(Section $section)
    {
        $data = array(
            'shop_id' => $section->shop_id,
            'name' => $section->name,
            'type' => $section->type,
            'flag' => $section->flag,
            'content' => $section->content,
        );

        $id = (int)$section->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getSection($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteSection($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }

    public function fetchHomeData(){
        $rowset = $this->tableGateway->select(array('name' => 'home', ));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row home");
        }
        return $row;
    }

    public function fetchRatesData(){
        $rowset = $this->tableGateway->select(array('name' => 'rates', ));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row home");
        }
        return $row;
    }

    public function fetchRosterData(){
        $rowset = $this->tableGateway->select(array('name' => 'roster', ));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row home");
        }
        return $row;
    }

    public function fetchEmployData(){
        $rowset = $this->tableGateway->select(array('name' => 'employment', ));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row home");
        }
        return $row;
    }

    public function getSectionByName($name){

        $rowset = $this->tableGateway->select(array('name' => $name, ));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row home");
        }
        return $row;
    }
    
}
