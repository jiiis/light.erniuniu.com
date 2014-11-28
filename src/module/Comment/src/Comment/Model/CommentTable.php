<?php
namespace Comment\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;

class CommentTable
{
    protected $tableGateway;
    protected $id;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select(function(Select $select){
		$select->order('post_time DESC');
	});
        return $resultSet;
    }

    public function getComment($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveComment(Comment $comment)
    {
        $data = array(
            'shop_id' => $comment->shop_id,
            'poster'  => $comment->poster,
            'type' => $comment->type,
            'link_id' => $comment->link_id,
            'post_time' => $comment->post_time,
            'content' => $comment->content,
            'is_private' => $comment->is_private,
            'email' => $comment->email,
        );

        $id = (int)$comment->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
			return $this->tableGateway->getLastInsertValue();
        } else {
            if ($this->getComment($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteComment($id)
    {
        return $this->tableGateway->delete(array('id' => $id));
    }
                    
    public function getCommentsByGirlId($id)
    {	 
		$GLOBALS['girl_id_for_comment'] = $id;
		return $this->tableGateway->select(function(Select $select){
			$select->where(array('link_id'=>$GLOBALS['girl_id_for_comment']));
			$select->order('post_time DESC');
		});
    }
}