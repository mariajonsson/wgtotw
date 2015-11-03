<?php $controller = isset($controller) ? $controller : 'answer'; ?>
<div class='answers'>

<div class='answer'>
<div class='answer-id'>
<a href='<?=$this->url->create($controller .'/edit/'.$pagekey.'/'.$id.'/'.$redirect)?>'>#<?=$id?></a>

<?php $gravatar = $user->getGravatarForAcronym($answer->getProperties()['name'])?>
<img src='<?=$gravatar?>?s=40' alt='gravatar'>
</div>
<div class='answer-content'>
<?php $userid = $user->getIdForAcronym($answer->getProperties()['name'])?>
<p class='answer-header'><a href='<?=$this->url->create('users/id/'.$userid)?>' class='comment-name'><?=$answer->getProperties()['name']?></a> skrev för 
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
<?php endif; ?>
</p>
<p><?=$answer->getProperties()['content']?></p>
<p class='answer-footer'>
<?php if (!empty($answer->getProperties()['web'])) : ?>
<?php $prefix = preg_match('/^[www]/', $answer->getProperties()['web']) ? 'http://' : '';?>
<a href='<?=$prefix.$answer->getProperties()['web']?>' target='_blank'>hemsida</a>
<?php endif; ?>
<?php if (!empty($answer->getProperties()['updated'])) : ?>
Redigerades <?=$answer->getProperties()['updated']?>
<?php endif; ?>
</p>
</div>
</div>
<div class='answer-divider'></div>


</div>

