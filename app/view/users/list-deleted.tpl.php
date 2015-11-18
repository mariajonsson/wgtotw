<h1><?=$title?></h1>

<?php if (!empty($users)) : ?>
<table>
  <tbody>
    
<tr><th class='th-id'>id</th><th class='th-status'></th><th class='th-user'>användare</th><th  class='th-name'>namn</th><th></th> 
<th></th></tr>
    <?php foreach ($users as $user) : ?>
    <tr>
    <td><?=$user->getProperties()['id']?></td>
    <td><a href="<?=$this->url->create('users/activate').'/'.$user->getProperties()['id'].'/'.$this->request->getRoute()?>" class="user-deleted"><i class="fa fa-user-times fa-fw user-deleted"></i></a></td>
    <td><a href="<?=$this->url->create('users/id').'/'.$user->getProperties()['id']?>" class="user-deleted"><?=$user->getProperties()['acronym']?></a></td>
    <td><?=$user->getProperties()['name']?></td>
    <td><a href="<?=$this->url->create('users/activate').'/'.$user->getProperties()['id'].'/'.$this->request->getRoute()?>" title='Aktivera'><i class="fa fa-undo"></i></a>
    </td>
    <td>
    <!-- <a href="<?=$this->url->create('users/delete').'/'.$user->getProperties()['id']?>" title='Ta bort permanent'><i class="fa fa-ban dark-red"></i></a> -->
    </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<p></p>
<div class='byline'>Klicka på ångra-symbolen <i class="fa fa-undo"></i> eller på 
användarsymbolen för att flytta bort användaren från papperskorgen.</div>

<?php elseif (empty($users)) : ?>
<p>Det finns inga borttagna användare att visa.</p>
<?php endif; ?>

