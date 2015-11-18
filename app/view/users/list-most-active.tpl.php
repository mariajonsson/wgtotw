<article class='active'>
<h5><?=$title?></h5>

<?php if (!empty($users)) : ?>
<table>
  <tbody>
    
  
    <?php foreach ($users as $user) : ?>
    <tr>
    <td  ><a href="<?=$this->url->create('users/id').'/'.$user->id?>"><img src='<?=$user->gravatar?>?s=15' alt='gravatar'></a></td><td class="td-active smaller"><a href="<?=$this->url->create('users/id').'/'.$user->id?>"><?=$user->acronym?></a></td><td class="td-active smaller"><?=$user->total?></td>
    </tr>
    <?php endforeach; ?>
    
    
  </tbody>
</table>

<?php elseif (empty($users)) : ?>
<p>Det finns inga användare att visa i den här kategorin.</p>
<?php endif; ?>
</article>