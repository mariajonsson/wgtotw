<?php $controller = isset($controller) ? $controller : 'comment'; ?>


<div class='comments'>

<?php if (is_array($comments)) : ?>
<?php /*$comments = array_reverse($comments)*/ ?>
<?php foreach ($comments as $id => $comment) : ?>
<?php $id = (is_object($comment)) ? $comment->id : $id; ?>
<?php $comment = (is_object($comment)) ? get_object_vars($comment) : $comment; ?> 
<?php $isloggedin = ($user->getLoggedInUser()) ?>
<?php $isloggedinposter = ($user->getLoggedInUser() == $comment['name']) ?>
<?php $voteupicon = '<i class="fa fa-caret-up grey"></i>'; ?>
<?php $votedownicon = '<i class="fa fa-caret-down grey"></i>'; ?>
<?php if ($isloggedin) { 
  if($vote->notAlreadyVotedUp($user->getIdForAcronym($isloggedin), $id, 'comments')) {
  $voteupicon = '<a href="'.$this->url->create("vote/vote").'/'.$user->getIdForAcronym($user->getLoggedInUser()).'/'.$id.'/comments/up/'.$pagekey.'" class="vote"><i class="fa fa-caret-up fa-lg"></i></a>';
  }
  if($vote->notAlreadyVotedDown($user->getIdForAcronym($isloggedin), $id, 'comments')) {
  $votedownicon = '<a href="'.$this->url->create("vote/vote").'/'.$user->getIdForAcronym($user->getLoggedInUser()).'/'.$id.'/comments/down/'.$pagekey.'" class="vote"><i class="fa fa-caret-down fa-lg"></i></a>';
  }
}
?>
 
<div class='comment'>
<div class='comment-id'>
<?php $gravatar = $user->getGravatarForAcronym($comment['name'])?>
<div class='comment-rank'><?=$voteupicon?> <?=$votedownicon?> </div><div class='comment-ranknum'> <p><?=$vote->getRank($id, 'comments') ?></p>  </div> <div class='comment-img'> <img src='<?=$gravatar?>?s=12'></div>
</div>
<div class='comment-content'>
<?php $userid = $user->getIdForAcronym($comment['name'])?>
<?php $content = $this->di->textFilter->doFilter($comment['content'], 'shortcode, markdown');?>
<p><?=$content?></p>
 — <a href='<?=$this->url->create('users/id/'.$userid)?>'><?=$comment['name']?></a> för 
<?php $elapsedsec = (time()-strtotime($comment['timestamp'])); ?>
<?php if (($elapsedsec) < 60): ?>
<?=round($elapsedsec)?> s sedan
<?php elseif (($elapsedsec/60) < 60): ?>
<?=round($elapsedsec/60)?> minuter sedan
<?php elseif (($elapsedsec/(60*60)) < 24): ?>
<?=round($elapsedsec/(60*60))?> h sedan
<?php elseif (($elapsedsec/(60*60*24)) < 7): ?>
<?=round($elapsedsec/(60*60*24))?> dygn sedan
<?php elseif (($elapsedsec/(60*60*24)) < 30) : ?>
<?=round($elapsedsec/(60*60*24*7))?> veckor sedan
<?php else : ?>
<?=round($elapsedsec/(60*60*24*30))?> månader sedan 
<?php endif; ?>
<?php if($isloggedinposter): ?>
<a href='<?=$this->url->create($controller .'/edit/'.$pagekey.'/'.$id.'/'.$redirect)?>' title='redigera'><i class="fa fa-pencil"></i></a> 
<?php endif; ?>

</div>
</div>
<div class='comment-divider'></div>
<?php endforeach; ?>

<?php endif; ?>
<?php if (is_string($comments)) : ?>

<p class='comment'><?=$comments?></p>

<?php endif; ?>
</div>
<div class='comment-divider'></div>