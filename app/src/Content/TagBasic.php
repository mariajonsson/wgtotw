<?php

namespace Meax\Content;
 
/**
 * Model for Content.
 *
 */
class TagBasic extends \Anax\MVC\CDatabaseModel
{

/**
 * Find and return all.
 *
 * @return array
 */
public function findAll($order='tagname ASC')
{
    $this->db->select()
             ->from($this->getSource())
             ->orderBy($order);
 
    $this->db->execute();
    $this->db->setFetchModeClass(__CLASS__);
    return $this->db->fetchAll();
}

	
	public function getIdForName($tagname) {
	
	$id = $this->query('id')
	->where('tagname = ?')
        ->execute([$tagname]);
    
      return $id[0]->id;
	}

/**
 * Find and return all tags for a particular post.
 *
 * @return array
 */
public function findTag($tagname)
{

    $this->db->select()
             ->from($this->getSource())
             ->where('tagname = ?');
 
    $this->db->execute([$tagname]);
    $this->db->setFetchModeClass(__CLASS__);
    return $this->db->fetchAll();
}


}