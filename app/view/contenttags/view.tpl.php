<?php $userid = $user->getIdForAcronym($post->getProperties()['acronym'])?>

<?php $isloggedin = ($user->getLoggedInUser()) ?>
<?php $isloggedinposter = ($user->getLoggedInUser() == $post->getProperties()['acronym']) ?>
<?php $voteupicon = '<i class="fa fa-caret-up fa-lg grey"></i>'; ?>
<?php $votedownicon = '<i class="fa fa-caret-down fa-lg grey"></i>'; ?>
<?php if ($isloggedin) { 

  if($vote->notAlreadyVotedUp($user->getIdForAcronym($isloggedin), $post->getProperties()['id'], 'issues')) {
  $voteupicon = '<a href="'.$this->url->create("vote/vote").'/'.$user->getIdForAcronym($user->getLoggedInUser()).'/'.$post->getProperties()['id'].'/issues/up/'.$post->getProperties()['id'].'" class="vote"><i class="fa fa-caret-up fa-lg"></i></a>';
  }
  if($vote->notAlreadyVotedDown($user->getIdForAcronym($isloggedin), $post->getProperties()['id'], 'issues')) {
  $votedownicon = '<a href="'.$this->url->create("vote/vote").'/'.$user->getIdForAcronym($user->getLoggedInUser()).'/'.$post->getProperties()['id'].'/issues/down/'.$post->getProperties()['id'].'" class="vote"><i class="fa fa-caret-down fa-lg"></i></a>';
  }
}
?>

<div class='issue'>
<div class='issue-rank'><?=$voteupicon?> <br><?=$vote->getRank($post->getProperties()['id'], 'issues') ?> <br><?=$votedownicon?></div>
<article class='article1'>

<h4><?=$post->getProperties()['title']?></h4>

<div class='article-content'>
<p class='smaller dark-grey subtitle'>—  
<?php if (isset($userid)): ?>
<a href='<?=$this->url->create('users/id/'.$userid)?>' class='comment-name'>
<?php endif;?>
<?=$post->getProperties()['acronym']?> 
<?php if (isset($userid)): ?></a><?php endif;?> 
<?=date('j/m H:i', strtotime($post->getProperties()['created']))?>
<?=isset($post->getProperties()['updated'])?", redigerades 
".$post->getProperties ( ) [ 'updated' ]:'';?> 
<?php if ($post->getProperties()['deleted'] == null) : ?>
<?php if($isloggedinposter): ?>    <a 
href="<?=$this->url->create($controller.'/update').'/'.$post->getProperties()['id']?>" 
title='redigera'><i class="fa fa-pencil"></i>
</a>
<?php endif; ?>
<?php endif; ?>
</p>

<?php $content = $this->di->textFilter->doFilter($post->getProperties()['data'], 'shortcode, markdown');?>
<p><?=$content?></p>


<p class='smaller'>taggar:

<?php foreach ($tags as $id => $tag): ?>
 <a href='<?=$this->url->create($controller.'/list-by-tag').'/'.$tag->getProperties()['tagid']?>' class='tags'><?=$tag->getProperties()['tagname']?></a>
<?php endforeach; ?>
</p>
</div>
</article>
</div>
<div class='issue-divider'></div>
