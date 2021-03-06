<h1><?=$title?></h1>

<div class='content'>
<?php if (is_array($content)) : ?>
<?php foreach ($content as $id => $post) : ?>
<?php $id = (is_object($post)) ? $post->id : $id; ?>
<?php $post = (is_object($post)) ? get_object_vars($post) : $post; ?> 
 
<article class='content'>
<p><a href='id/<?=$id?>'><?=$post['title']?></a></p>
<?php $userid = $user->getIdForAcronym($post['acronym'])?>
<p>Av <em><a href='<?=$this->url->create('users/id/'.$userid)?>'><?=$post['acronym']?></a></em>, <?=$post['created']?></p>
<p><?=$post['data']?></p>
<p class='content-footer'>
<?php if (!empty($post['published'])) : ?>
Publicerad <?=$post['published']?>
<?php endif; ?>
<?php if (!empty($post['updated'])) : ?>
<br>Redigerad <?=$post['updated']?>
<?php endif; ?>
</p>
</article>

<div class='content-divider'></div>
<?php endforeach; ?>

<?php endif; ?>
</div>

