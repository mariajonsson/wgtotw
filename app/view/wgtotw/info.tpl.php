<article class=<?=isset($class)?$class:''?>>
 
<?=$content?>
 
 
<?php if (isset($links)) : ?>
<ul>
<?php foreach ($links as $link) : ?>
<li><a href="<?=$link['href']?>"><?=$link['text']?></li>
<?php endforeach; ?>
</ul>
<?php endif; ?> 
</article>