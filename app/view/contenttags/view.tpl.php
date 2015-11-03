<?php $userid = $user->getIdForAcronym($post->getProperties()['acronym'])?>

<article class='article1'>
<div>

</div>
<h4><?=$post->getProperties()['title']?></h4>
<p><?=$post->getProperties()['data']?></p>

<p class='smaller dark-grey'>fråga ställd av <?php if (isset($userid)): ?><a href='<?=$this->url->create('users/id/'.$userid)?>' class='comment-name'><?php endif;?><?=$post->getProperties()['acronym']?> <?php if (isset($userid)): ?></a><?php endif;?> <?=$post->getProperties()['created']?>
<?=isset($post->getProperties()['updated'])?", redigerades 
".$post->getProperties ( ) [ 'updated' ]:'';?> <?php if ($post->getProperties()['deleted'] == null) : ?>
    <a 
href="<?=$this->url->create($controller.'/update').'/'.$post->getProperties()['id']?>" 
title='Edit'>redigera</a>
<?php endif; ?>
</p>
<p>

<?php foreach ($tags as $id => $tag): ?>
<a href='<?=$this->url->create($controller.'/list-by-tag').'/'.$tag->getProperties()['tagid']?>'><?=$tag->getProperties()['tagname']?></a>
<?php endforeach; ?>
</p>
</article>
