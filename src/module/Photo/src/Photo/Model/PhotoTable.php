<?php
namespace Photo\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class PhotoTable
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

    public function getPhoto($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
	
	public function getFirstPhotoByGirlId($id){
		$id = (int) $id;
		$rowset = $this->tableGateway->select(array('link_id' => $id, 'isfirstshow' => 1));
		$row = $rowset->current();
		if(!$row){
			//throw new \Exception("Could not find photo link_id = $id and isfirstshow = 1");
			return null;
		}
		return $row;
	}
	
	public function fetchAllByOrderByGirlId($id){
		$id = (int) $id;
		$resultSet = $this->tableGateway->select(function(Select $select) use($id){
			$select->where(array("link_id" => $id));
			$select->order("order_num");
		});
		return $resultSet;
	}
	
	public function getPhotosExceptFirstByOrderByGirlId($id){
		$id = (int) $id;
		$resultset = $this->tableGateway->select(function(Select $select) use($id){
			$select
				->where(array("link_id" => $id))
				->where('isfirstshow <> 1')
				->order("order_num");
		});
		$photo_array = array();
		foreach($resultset as $photo){
			$photo_array[] = $photo;
		}
		return $photo_array;
	}
	
	public function getPhotosByGirlId($id){
		$id = (int) $id;
		$resultset = $this->tableGateway->select(array('link_id' => $id));
		$photo_array = array();
		foreach($resultset as $photo){
			$photo_array[] = $photo;
		}
		return $photo_array;
	}
	
	public function getPhotosExceptFirstByGirlId($id){
		$id = (int) $id;
		$GLOBALS['girl_id_for_getting_except_photos'] = $id;
		$resultset = $this->tableGateway->select(function(Select $select){
			$select->where(array('link_id = '.$GLOBALS['girl_id_for_getting_except_photos']))->where('isfirstshow <> 1');
		});
		$photo_array = array();
		foreach($resultset as $photo){
			$photo_array[] = $photo;
		}
		return $photo_array;
	}

    public function savePhoto(Photo $photo)
    {
        $data = array(
            'shop_id' => $photo->shop_id,
            'type'  => $photo->type,
            'link_id' => $photo->link_id,
            'pict_url' => $photo->pict_url,
            'isfirstshow' => $photo->isfirstshow,
            'thumb_url' => $photo->thumb_url,
            'is_cropped' => $photo->is_cropped,
            'order_num' => $photo->order_num,
        );

        $id = (int)$photo->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getPhoto($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deletePhoto($id)
    {
        return $this->tableGateway->delete(array('id' => $id));
    }

    public function fetchByGirlId($gId){
        $resultSet = $this->tableGateway->select(array('type'=>0,'link_id'=>$gId));
        return $resultSet;
    }

}