<?php $controller = isset($controller) ? $controller : 'answer'; ?>
<?php $userid = $user->getIdForAcronym($answer->getProperties()['name'])?>
<div class='answers'>

<div class='answer'>
<?php $isloggedin = ($user->getLoggedInUser() == $answer->getProperties()['name']) ?>
<?php $isloggedinposter = ($user->getLoggedInUser() == $issueposter) ?>
<?php $accepted = $answer->getProperties()['accepted'] ?>
<?php $accepticon = null; ?>
<?php $voteupicon = '<i class="fa fa-caret-up fa-lg light-grey"></i>'; ?>
<?php $votedownicon = '<i class="fa fa-caret-down fa-lg light-grey"></i>'; ?>
<?php if ($isloggedinposter) { 
  
  if ($accepted) {
  $accepticon = '<a href="'.$this->url->create("answer/un-accept").'/'.$id.'/'.$pagekey.'" class="accepted-selected"><i class="fa fa-star fa-lg accept-selected" title="accepterat svar"></i></a>';
  }
  else {
  $accepticon = '<a href="'.$this->url->create("answer/accept").'/'.$id.'/'.$pagekey.'" class="accepted-unselected"><i class="fa fa-star-o fa-lg accept-unselected" title="märk som accepterat svar"></i></a>';
  }
}

elseif ($accepted) {
$accepticon = '<i class="fa fa-star fa-lg accept-selected" title="accepterat svar"></i>';
}

if($user->getLoggedInUser()) {

$voteupicon = '<a href="'.$this->url->create("vote/vote").'/'.$user->getIdForAcronym($user->getLoggedInUser()).'/'.$id.'/answer/up/'.$pagekey.'" class="vote"><i class="fa fa-caret-up fa-lg"></i></a>';
$votedownicon = '<a href="'.$this->url->create("vote/vote").'/'.$user->getIdForAcronym($user->getLoggedInUser()).'/'.$id.'/answer/down/'.$pagekey.'" class="vote"><i class="fa fa-caret-down fa-lg"></i></a>';
}

?>

<div class='answer-id'>
<div class='answer-accept'><?=$accepticon?></div>
<div class='answer-rank'><?=$voteupicon?> <br><?=$vote->getRank($id, 'answer') ?> <br><?=$votedownicon?>  </div>
</div>
<div class='answer-content'>
<?php $gravatar = $user->getGravatarForAcronym($answer->getProperties()['name'])?>

<p><?=$answer->getProperties()['content']?></p>



<p class='smaller dark-grey'>— <a href='<?=$this->url->create('users/id/'.$userid)?>'><?=$answer->getProperties()['name']?></a> för 
<?php $elapsedsec = (time()-strtotime($answer->getProperties()['timestamp'])); ?>
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
<?php endif; ?> <?php if($isloggedin) :?><a href='<?=$this->url->create($controller .'/edit/'.$pagekey.'/'.$id.'/'.$redirect)?>' title='redigera'><i class="fa fa-pencil"></i></a><?php endif;?>

<?php if (!empty($answer->getProperties()['web'])) : ?>
<?php $prefix = preg_match('/^[www]/', $answer->getProperties()['web']) ? 'http://' : '';?>
<a href='<?=$prefix.$answer->getProperties()['web']?>' target='_blank'>hemsida</a>
<?php endif; ?>
<?php if (!empty($answer->getProperties()['updated'])) : ?>
Redigerades <?=$answer->getProperties()['updated']?>
</p>
<?php endif; ?>

</div>
</div>
</div>
<div class='answer-divider'></div>
