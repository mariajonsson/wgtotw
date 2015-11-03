<?php $controller = isset($controller) ? $controller : 'comment'; ?>
<div class='comments'>
<!--<pre><?php echo var_dump($comments); ?></pre>-->
<?php if (is_array($comments)) : ?>
<?php /*$comments = array_reverse($comments)*/ ?>
<?php foreach ($comments as $id => $comment) : ?>
<?php $id = (is_object($comment)) ? $comment->id : $id; ?>
<?php $comment = (is_object($comment)) ? get_object_vars($comment) : $comment; ?> 
 
<div class='comment'>
<div class='comment-id'>
<a href='<?=$this->url->create($controller .'/edit/'.$pagekey.'/'.$id.'/'.$redirect)?>'>#<?=$id?></a>
</div>
<div class='comment-content'>
<?php $userid = $user->getIdForAcronym($comment['name'])?>
<p><?=$comment['content']?> — <a href='<?=$this->url->create('users/id/'.$userid)?>' class='comment-name'><?=$comment['name']?></a> för 
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

</div>
</div>
<div class='comment-divider'></div>
<?php endforeach; ?>

<?php endif; ?>
<?php if (is_string($comments)) : ?>

<p class='comment'><?=$comments?></p>

<?php endif; ?>
</div>