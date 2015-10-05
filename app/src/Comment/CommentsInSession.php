<?php

namespace Phpmvc\Comment;

/**
 * To attach comments-flow to a page or some content.
 *
 */
class CommentsInSession implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;



    /**
     * Add a new comment.
     *
     * @param array $comment with all details.
     *
     * @param $pagekey selects the comment array for current page-id.
     * 
     * @return void
     */
    public function add($comment, $pagekey)
    {
        $comments = $this->session->get('comments', []);
        $comments[$pagekey][] = $comment;
        $this->session->set('comments', $comments);
    }
    
    /**
     * Save edited comment.
     *
     * @param string $pagekey selects the array with the page-id. 
     * @param array $comment with all details.
     *
     * @return void
     */
    public function editComment($comment, $pagekey, $id)
    {
        $comments = $this->session->get('comments', []);
        $comments[$pagekey][$id] = $comment;
        $this->session->set('comments', $comments);
    }



    /**
     * Find and return all comments.
     *
     * @param $pagekey is the page-id for the current page.
     *
     * @return array with all comments.
     */
    public function findAll($pagekey = null)
    {
        $comments = $this->session->get('comments', []);
        if(!array_key_exists($pagekey, $comments))
        {
        	return 'Inga kommentarer än';
            
        }
            return $comments[$pagekey];
        
    }
    
     /**
     * Find and return one comment.
     *
     * @param string $pagekey is a key that selects the array for the page-id.
     * @param $id selects the comment.
     *
     * @return array with comment.
     */
    public function findComment($pagekey, $id)
    {
        $comments = $this->session->get('comments', []);
        return $comments[$pagekey][$id];
    }

    /**
     * Delete a comment.
     *
     * @param string $pagekey is a key that selects the array for the page-id.
     * @param $id selects the comment
     *
     * @return void
     */
    public function deleteComment($pagekey, $id)
    {
        $comments = $this->session->get('comments', []);
        unset($comments[$pagekey][$id]);
        $this->session->set('comments', $comments);
    }

    /**
     * Delete all comments.
     *
     * @return void
     */
    public function deleteAll()
    {
        $this->session->set('comments', []);
    }
}
