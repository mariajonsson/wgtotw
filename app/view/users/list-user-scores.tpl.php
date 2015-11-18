<article class='active'>
<h5><?=$title?></h5>

<?php if (!empty($user)) : ?>

<table>
  <tbody class='smaller'>
    
 
    
  	 <tr></td><td></td><td>Antal</td><td>Rank</td><td>Total</td></tr>
    <tr><td>Frågor:</td><td><?=$issues?></td><td><?=$irank?></td><td><?=($issues+$irank)?></td></tr>
    <tr><td>Svar: </td><td><?=$answer?></td><td><?=$arank?></td><td><?=($answer+$arank)?></td></tr>
    <tr><td>Kommentarer: </td><td><?=$comments?></td><td><?=$crank?></td><td><?=($comments+$crank)?></td></tr>
    <tr><td>SUMMA </td><td><?=($issues+$answer+$comments)?></td><td><?=($irank+$arank+$crank)?></td><td><strong><?=($issues+$answer+$comments+$irank+$arank+$crank)?></strong></td></tr>
    
  </tbody>
</table>

<?php elseif (empty($user)) : ?>
<p>Det finns inget att visa.</p>
<?php endif; ?>
</article>