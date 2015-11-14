<?php

namespace Meax\Content;
 
/**
 * Model for Content.
 *
 */
class Vote extends \Anax\MVC\CDatabaseModel
{



public function getRank($contentid, $type) {
    
    $matches = $this->db->select('*, coalesce(SUM(vote), 0) AS rank')
             ->from($this->getSource())
             ->where('contentid = ?')
             ->andWhere('contenttype = ?')
             ->executeFetchAll([$contentid, $type]);
             
    return $matches[0]->rank;

    }
    

}