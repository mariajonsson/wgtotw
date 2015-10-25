<?php $controller = isset($controller) ? $controller : 'comment'; ?>
<div class='comments'>
<!--<pre><?php echo var_dump($comments); ?></pre>-->
<h3>Kommentarer</h3>
<?php if (is_array($comments)) : ?>
<?php /*$comments = array_reverse($comments)*/ ?>
<?php foreach ($comments as $id => $comment) : ?>
<?php $id = (is_object($comment)) ? $comment->id : $id; ?>
<?php $comment = (is_object($comment)) ? get_object_vars($comment) : $comment; ?> 
 
<div class='comment'>
<div class='comment-id'>
<a href='<?=$this->url->create($controller .'/edit/'.$pagekey.'/'.$id.'/'.$redirect)?>'>#<?=$id?></a>

<?php $gravatar = $user->getGravatarForAcronym($comment['name'])?>
<img src='<?=$gravatar?>?s=40' alt='gravatar'>
</div>
<div class='comment-content'>
<?php $userid = $user->getIdForAcronym($comment['name'])?>
<p class='comment-header'><a href='<?=$this->url->create('users/id/'.$userid)?>' class='comment-name'><?=$comment['name']?></a> skrev för 
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
</p>
<p><?=$comment['content']?></p>
<p class='comment-footer'>
<?php if (!empty($comment['web'])) : ?>
<?php $prefix = preg_match('/^[www]/', $comment['web']) ? 'http://' : '';?>
<a href='<?=$prefix.$comment['web']?>' target='_blank'>hemsida</a>
<?php endif; ?> <?=$comment['ip']?> 
<?php if (!empty($comment['updated'])) : ?>
Redigerades <?=$comment['updated']?>
<?php endif; ?>
</p>
</div>
</div>
<div class='comment-divider'></div>
<?php endforeach; ?>

<?php endif; ?>
<?php if (is_string($comments)) : ?>

<p class='comment'><?=$comments?></p>

<?php endif; ?>
</div>