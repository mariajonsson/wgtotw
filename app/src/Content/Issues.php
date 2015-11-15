<?php

namespace Meax\Content;
 
/**
 * Model for Content.
 *
 */
class Issues extends \Anax\MVC\CDatabaseModel
{

    public function findAllMatches($table, $match1, $match2) {
    
    $matches = $this->db->select('t0.*, t1.'.$match2.' as match2, COUNT(t1.'.$match2.') AS total')
             ->from($this->getSource(). ' AS t0')
             ->leftJoin($table.' AS t1', 't0.'.$match1.' = t1.'.$match2)
             ->groupBy('t0.'.$match1)
             ->orderBy('created DESC')
             ->executeFetchAll();
             
    return $matches;

    }
    
     public function findAllMatchesLim($table, $match1, $match2, $limit) {
    
    $matches = $this->db->select('t0.*, t1.'.$match2.' as match2, COUNT(t1.'.$match2.') AS total')
             ->from($this->getSource(). ' AS t0')
             ->leftJoin($table.' AS t1', 't0.'.$match1.' = t1.'.$match2)
             ->groupBy('t0.'.$match1)
             ->orderBy('created DESC')
             ->limit($limit)
             ->executeFetchAll();
             
    return $matches;

    }
    
    public function findAllMatchesUser($table, $match1, $match2, $user) {
    
    $matches = $this->db->select('t0.*, t1.'.$match2.' as match2, COUNT(t1.'.$match2.') AS total')
             ->from($this->getSource(). ' AS t0')
             ->where('t0.acronym = ?')
             ->leftJoin($table.' AS t1', 't0.'.$match1.' = t1.'.$match2)
             ->groupBy('t0.'.$match1)
             ->executeFetchAll([$user]);
             
    return $matches;

    }
    
    public function findAllMatchesByTag($tagid) {
    
    $matches = $this->db->select('t0.*, t1.pagekey as match2, COUNT(t1.pagekey) AS total, contentid, tagid')
             ->from($this->getSource(). ' AS t0')
             ->leftJoin('answer AS t1', 't0.id = t1.pagekey')
             ->join('contenttag AS t2', 'tagid = ? AND contentid = t0.id')
             ->groupBy('t0.id')
             ->executeFetchAll([$tagid]);
    /*      
     $sql =  "SELECT `i`.*,  coalesce(`count1`, 0) as `total`
FROM `wgtotw_issues` `i`
left join 
	(SELECT `wgtotw_answer`.`pagekey`, count(`wgtotw_answer`.`pagekey`) as `count1`
 	from `wgtotw_answer`)
	`answercount` on `i`.`id` = `answercount`.`count1` 
left join 
	(select `contentid`, `tagid` 
     from `wgtotw_contenttag`)
	`contenttag` on `i`.`id` =`contentid`
where `tagid` = ?";

$matches = $this->db->executeFetchAll($sql, [$tagid]);*/
             
    return $matches;

    }

}