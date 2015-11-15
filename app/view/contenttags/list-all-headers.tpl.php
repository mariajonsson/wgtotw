
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
<p><span class='answercount smaller'><?=$post['total']?> svar</span> <span class='answercount smaller'><?=$vote->getRank($id, 'issues') ?> rank</span>
 <a href='<?=$this->url->create('issues/id/'.$id)?>'><?=$post['title']?></a><span class='smaller'> — <em><?php if($userid):?><a href='<?=$this->url->create('users/id/'.$userid)?>'><?php endif;?><?=$post['acronym']?><?php if($userid):?></a><?php endif;?></em>, <?=date('j/m H:i', strtotime($post['created']));?></span></p>
</article>

<?php endforeach; ?>

<?php endif; ?>
<?php if (empty($content)) : ?>
<p>Inga frågor i den här kategorin</p>
<?php endif; ?>

<?php if (isset($link)):?><?=$link?><?php endif?>
</div>
<div class='content-divider'></div>


