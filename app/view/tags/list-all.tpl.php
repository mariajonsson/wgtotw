<?php if (isset($title)):?><h1><?=$title?></h1><?php endif?>
<?php if (isset($subtitle)):?><h3><?=$subtitle?></h3><?php endif?>
<div class='content'>
<?php if (is_array($content)) : ?>
<?php foreach ($content as $id => $post) : ?>
<?php $id = (is_object($post)) ? $post->id : $id; ?>
<?php $post = (is_object($post)) ? get_object_vars($post) : $post; ?> 
 
<div class='tags'>
<a href='issues/list-by-tag/<?=$id?>'><?=$post['tagname']?></a>
<?php if ('admin' == $loggedinuser):?>
<a href="<?=$this->url->create('tag-basic/update').'/'.$id?>" title='Ändra' class='no-tag'><i class="fa fa-pencil"></i></a>
<?php endif;?>
</div>

<?php endforeach; ?>

<?php endif; ?>
</div>

