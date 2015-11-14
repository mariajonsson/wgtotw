<?php $userid = $user->getIdForAcronym($post->getProperties()['acronym'])?>

<article class='article1'>
<div>

</div>
<h4><?=$post->getProperties()['title']?></h4>

<p class='smaller dark-grey subtitle'>fråga ställd av 
<?php if (isset($userid)): ?>
<a href='<?=$this->url->create('users/id/'.$userid)?>' class='comment-name'>
<?php endif;?>
<?=$post->getProperties()['acronym']?> 
<?php if (isset($userid)): ?></a><?php endif;?> 
<?=date('j/m H:i', strtotime($post->getProperties()['created']))?>
<?=isset($post->getProperties()['updated'])?", redigerades 
".$post->getProperties ( ) [ 'updated' ]:'';?> 
<?php if ($post->getProperties()['deleted'] == null) : ?>
<?php if($user->getLoggedInUser() == $post->getProperties()['acronym']): ?>    <a 
href="<?=$this->url->create($controller.'/update').'/'.$post->getProperties()['id']?>" 
title='redigera'><i class="fa fa-pencil"></i>
</a>
<?php endif; ?>
<?php endif; ?>
</p>


<p><?=$post->getProperties()['data']?></p>


<p class='smaller'>taggar:

<?php foreach ($tags as $id => $tag): ?>
 <a href='<?=$this->url->create($controller.'/list-by-tag').'/'.$tag->getProperties()['tagid']?>' class='tags'><?=$tag->getProperties()['tagname']?></a>
<?php endforeach; ?>
</p>
</article>
