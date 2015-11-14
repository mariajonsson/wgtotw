<?php

namespace Meax\Content;
 
/**
 * Model for Content.
 *
 */
class ContentTag extends \Anax\MVC\CDatabaseModel
{

	
/**
 * Find and return all tags for a particular post.
 *
 * @return array
 */
public function findTagsByPost($contentid, $tagstable='tagbasic', $contenttable='issues')
{

    $this->db->select('tagname, tagid')
             ->from($this->getSource())
             ->where('contentid = ' . $contentid)
             ->join($tagstable, 'tagid = wgtotw_' . $tagstable . '.id' )
             ->join($contenttable, 'contentid = wgtotw_' . $contenttable . '.id');
 
    $this->db->execute();
    $this->db->setFetchModeClass(__CLASS__);
    return $this->db->fetchAll();
}

/**
 * Find and return all tags for a particular post.
 *
 * @return array
 */
public function findPostsByTag($tagid, $tagstable, $contenttable)
{

    $this->db->select()
             ->from($this->getSource())
             ->where('tagid = ' . $tagid)
             ->join($tagstable, 'tagid = wgtotw_' . $tagstable . '.id' )
             ->join($contenttable, 'contentid = wgtotw_' . $contenttable . '.id');
 
    $this->db->execute();
    $this->db->setFetchModeClass(__CLASS__);
    return $this->db->fetchAll();
}



public function findMostUsedTags() {
    
    $matches = $this->db->select('*, tagname, COUNT(tagid) AS total')
             ->from($this->getSource())
             ->join('tagbasic', 'id = tagid')
             ->groupBy('tagid')
             ->orderBy('total DESC')
             ->executeFetchAll();
             
    return $matches;

    }
    
/**
 * Delete row.
 *
 * @param integer $id to delete.
 *
 * @return boolean true or false if deleting went okey.
 */
public function deleteByPost($postid)
{
    $this->db->delete(
        $this->getSource(),
        'contentid = ?'
    );
 
    return $this->db->execute([$postid]);
}
}