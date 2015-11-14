<article class='active'>
<h5><?=$title?></h5>

<?php if (!empty($users)) : ?>
<table>
  <tbody>
    
  <tr>
    <?php foreach ($users as $user) : ?>
    <td  class="td-active smaller"><a href="<?=$this->url->create('users/id').'/'.$user->id?>"><img src='<?=$user->gravatar?>?s=30' alt='gravatar'><br><?=$user->acronym?></a><br><?=$user->total?></td>
    <?php endforeach; ?>
    </tr>
    
  </tbody>
</table>

<?php elseif (empty($users)) : ?>
<p>Det finns inga användare att visa i den här kategorin.</p>
<?php endif; ?>
</article>