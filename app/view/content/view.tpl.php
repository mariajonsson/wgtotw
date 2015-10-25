<article>

<h4><?=$post->getProperties()['title']?></h4>
<?php $userid = $user->getIdForAcronym($post['acronym'])?>
<p>Av <a href='<?=$this->url->create('users/id/'.$userid)?>'><?=$post->getProperties()['acronym']?></a></p>
<p><?=$post->getProperties()['data']?></p>

<p>Skapad <?=$post->getProperties()['created']?>
<?=isset($post->getProperties()['updated'])?"<br>Redigerad 
".$post->getProperties ( ) [ 'updated' ]:'';?>
<?=isset($post->getProperties()['published'])?"<br>Publicerad  
".$post->getProperties ( ) [ 'published' ]:'';?></p>
<p>
<?php if ($post->getProperties()['deleted'] == null) : ?>
    <a 
href="<?=$this->url->create($controller.'/update').'/'.$post->getProperties()['id']?>" 
title='Edit'>Redigera innehåll
</a>
<?php endif; ?>
</p>
</article>
