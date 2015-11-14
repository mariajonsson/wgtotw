<?php

namespace Anax\Comments;
 
/**
 * Model for Answers.
 *
 */
class Answer extends \Anax\MVC\CDatabaseModel
{



  public function findAll($pagekey=null)
	{
	
	if (isset($pagekey)) {
		$all = $this->query()
        ->where('pagekey = ?')
        ->execute([$pagekey]);
        
        return $all;
	}
	
	else {
		parent::findAll();
    }
    }


    public function findAnswer($pagekey=null, $id)
    {
    if (isset($pagekey) && isset($id)) {
		$all = $this->query()
        ->where('pagekey = ?')
        ->andWhere('id = ?')
        ->execute([$pagekey, $id]);
        
        return $all;
	}
    else {	
    	parent::find($id);
    }
    }
    

    


}