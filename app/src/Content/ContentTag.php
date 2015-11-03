<?php

namespace Meax\Content;
 
/**
 * Model for Content.
 *
 */
class ContentTag extends \Meax\MVC\CDatabaseModel
{

	
/**
 * Find and return all tags for a particular post.
 *
 * @return array
 */
public function findTagsByPost($contentid, $tagstable, $contenttable)
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


}