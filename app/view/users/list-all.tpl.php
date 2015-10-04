<h1><?=$title?></h1>

<?php if (!empty($users)) : ?>
<table>
  <tbody>
    
<tr><th class='th-id'>id</th><th class='th-status'></th><th class='th-user'>användare</th><th class='th-name'>namn</th><th class='th-symbol'></th><th class='th-symbol'></th></tr>
    <?php foreach ($users as $user) : ?>
    <?php 
    $class = "";
    if ($user->getProperties()['deleted'] != null) {
      $faclass = "fa fa-user-times fa-fw";
      $class = "user-deleted";
    }
    elseif ($user->getProperties()['active'] == null) {
      $faclass = "fa fa-user fa-fw";
      $class = "user-inactive";
    }
    else {
      $faclass = "fa fa-user fa-fw";
      $class = "user-active";
    } ?>
    <tr>
    <td><?=$user->getProperties()['id']?></td>
    <td><a href="<?=$this->url->create('users/activate').'/'.$user->getProperties()['id'].'/'.$this->request->getRoute()?>" class="<?=$class?>"><i class="<?=$faclass." ".$class?>"></i></a></td>
    <td><a href="<?=$this->url->create('users/id').'/'.$user->getProperties()['id']?>" class="<?=$class?>"><?=$user->getProperties()['acronym']?></a></td>
    <td><?=$user->getProperties()['name']?></td>
    <td>
    <?php if ($user->getProperties()['deleted'] == null) : ?>
    <a href="<?=$this->url->create('users/update').'/'.$user->getProperties()['id']?>" title='Ändra'><i class="fa fa-pencil"></i>
</a><?php endif; ?>
    </td>
    <td>
    <?php if ($user->getProperties()['deleted'] == null) : ?>
    <a href="<?=$this->url->create('users/soft-delete').'/'.$user->getProperties()['id']?>" title='Ta bort'><i class="fa fa-trash"></i>
</a>
    <?php endif; ?>
    </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<p></p>
<div class='byline'>Klicka på användarakronymen för att se profilen. Klicka på 
användarsymbolen för att aktivera eller inaktivera. Klicka på redigera-symbolen <i class="fa fa-pencil"></i> för att redigera. Klicka på papperskorgen <i class="fa fa-trash"></i> för att ta bort användaren. </div>

<?php elseif (empty($users)) : ?>
<p>Det finns inga användare att visa i den här kategorin.</p>
<?php endif; ?>