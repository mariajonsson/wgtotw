<h1>Användarinfo</h1>
<p>
<?php if ($user->getProperties()['deleted'] != null) : ?>
Den här användaren är borttagen och kan inte redigeras. Gå till <a 
href="<?=$this->di->get('url')->create('users/discarded')?>">papperskorgen</a> 
för att återställa användaren eller för att radera användaren permanent.
<?php endif; ?>
</p>
<?php 
    $class = "";
    if ($user->getProperties()['deleted'] != null) {
      $faclass = "fa fa-user-times";
      $status = "Borttagen";
      $date = $user->getProperties()['deleted'];
    }
    elseif ($user->getProperties()['active'] == null) {
      $faclass = "fa fa-user";
      $status = "Inaktiv";
      $date = "";
    }
    else {
      $faclass = "fa fa-user";
      $status = "Aktiverad";
      $date = $user->getProperties()['active'];
    } 
    ?>

<h4><i class="<?=$faclass?>"></i> <?=$user->getProperties()['acronym']?> 
(id <?=$user->getProperties()['id']?>)</h4>
<p><img src='<?=$user->getProperties()['gravatar']?>?s=50'></p>
<p><em>Namn: <?=$user->getProperties()['name']?></em>
<br><?=$user->getProperties()['email']?></p>
<p><?=$status?> <?=$date?>
<br>Skapades <?=$user->getProperties()['created']?>
<br><?=isset($user->getProperties()['updated'])?"Uppdaterad 
".$user->getProperties ( ) [ 'updated' ]:'';?></p>
<p>
<?php if ($user->getProperties()['deleted'] == null) : ?>
    <a 
href="<?=$this->url->create('users/update').'/'.$user->getProperties()['id']?>" 
title='Ändra'><i class="fa fa-pencil"></i> Redigera användare
</a>
<?php endif; ?>
</p>

