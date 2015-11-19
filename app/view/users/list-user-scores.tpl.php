<article>

<?php if (!empty($user)) : ?>
<?php $total = ($issues+$answer+$comments+$irank+$arank+$crank)?>
<h5>Poäng: <?=$total?></h5>
 <table class='userinfo'>
  <tbody> 
    <tr><td>Poänggivande <br>aktivitet</td><td>antal</td><td>ranking</td><td>antal+ranking</td></tr>
    <tr><td>Frågor:</td><td><?=$issues?></td><td><?=$irank?></td><td>= <?=($issues+$irank)?></td></tr>
    <tr><td>Svar: </td><td><?=$answer?></td><td><?=$arank?></td><td>= <?=($answer+$arank)?></td></tr>
    <tr><td>Kommentarer: </td><td><?=$comments?></td><td><?=$crank?></td><td>= <?=($comments+$crank)?></td></tr>
    <tr><td>SUMMA </td><td><?=($issues+$answer+$comments)?></td><td> <?=($irank+$arank+$crank)?></td><td>= <strong class='red'><?=$total?></strong></td></tr>
   </tbody>
</table>
 <table class='userinfo'>
  <tbody>
    <tr><td colspan='2' >Givna röster <br>(ej poänggivande aktivitet)</td></tr>
    <tr><td>Frågor:</td><td><?=$ivote?></td></tr>
    <tr><td>Svar: </td><td><?=$avote?></td></tr>
    <tr><td>Kommentarer: </td><td><?=$cvote?></td></tr>
    
  </tbody>
</table>

<?php elseif (empty($user)) : ?>
<p>Det finns inget att visa.</p>
<?php endif; ?>
</article>