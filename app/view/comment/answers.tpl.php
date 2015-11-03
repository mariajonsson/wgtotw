<?php $controller = isset($controller) ? $controller : 'answer'; ?>
<div class='answers'>
<!--<pre><?php echo var_dump($answers); ?></pre>-->
<h3>Svar</h3>
<?php if (is_array($answers)) : ?>
<?php /*$answers = array_reverse($answers)*/ ?>
<?php foreach ($answers as $id => $answer) : ?>
<?php $id = (is_object($answer)) ? $answer->id : $id; ?>
<?php $answer = (is_object($answer)) ? get_object_vars($answer) : $answer; ?> 
 
<div class='answer'>
<div class='answer-id'>
<a href='<?=$this->url->create($controller .'/edit/'.$pagekey.'/'.$id.'/'.$redirect)?>'>#<?=$id?></a>

<?php $gravatar = $user->getGravatarForAcronym($answer['name'])?>
<img src='<?=$gravatar?>?s=40' alt='gravatar'>
</div>
<div class='answer-content'>
<?php $userid = $user->getIdForAcronym($answer['name'])?>
<p class='answer-header'><a href='<?=$this->url->create('users/id/'.$userid)?>' class='comment-name'><?=$answer['name']?></a> skrev för 
<?php $elapsedsec = (time()-strtotime($answer['timestamp'])); ?>
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
<p><?=$answer['content']?></p>
<p class='answer-footer'>
<?php if (!empty($answer['web'])) : ?>
<?php $prefix = preg_match('/^[www]/', $answer['web']) ? 'http://' : '';?>
<a href='<?=$prefix.$answer['web']?>' target='_blank'>hemsida</a>
<?php endif; ?>
<?php if (!empty($answer['updated'])) : ?>
Redigerades <?=$answer['updated']?>
<?php endif; ?>
</p>
</div>
</div>
<div class='answer-divider'></div>

<?php endforeach; ?>

<?php endif; ?>
<?php if (is_string($answers)) : ?>

<p class='answer'><?=$answers?></p>

<?php endif; ?>
</div>

