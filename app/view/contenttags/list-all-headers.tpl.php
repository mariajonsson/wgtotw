
<?php if (isset($title)):?><h1><?=$title?></h1>
<?php endif?>
<h3><?=$subtitle?></h3>
<div class='content'>
<?php if (is_array($content)) : ?>
<?php foreach ($content as $id => $post) : ?>
<?php $id = (is_object($post)) ? $post->id : $id; ?>
<?php $post = (is_object($post)) ? get_object_vars($post) : $post; ?> 
 
<article class='content'>
<?php $userid = $user->getIdForAcronym($post['acronym'])?>
<p><span class='answercount smaller'><?=$post['total']?> svar</span>
 <a href='<?=$this->url->create('issues/id/'.$id)?>'><?=$post['title']?></a> - <em><?php if($userid):?><a href='<?=$this->url->create('users/id/'.$userid)?>'><?php endif;?><?=$post['acronym']?><?php if($userid):?></a><?php endif;?></em>, <?=date('j/m H:i', strtotime($post['created']));?></p>
</article>

<?php endforeach; ?>

<?php endif; ?>
<?php if (isset($link)):?><?=$link?><?php endif?>
</div>
<div class='content-divider'></div>


