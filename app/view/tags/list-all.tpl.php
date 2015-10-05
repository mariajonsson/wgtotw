<h1><?=$title?></h1>
<div class='content'>
<?php if (is_array($content)) : ?>
<?php foreach ($content as $id => $post) : ?>
<?php $id = (is_object($post)) ? $post->id : $id; ?>
<?php $post = (is_object($post)) ? get_object_vars($post) : $post; ?> 
 
<article class='content'>
<h3><a href='issues/list-by-tag/<?=$id?>'><?=$post['tagname']?></a></h3>


<p class='content-footer'>

</p>
</article>

<div class='content-divider'></div>
<?php endforeach; ?>

<?php endif; ?>
</div>

