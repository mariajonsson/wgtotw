<h2>Användare</h2>
<?php $userisloggedin = ($user->getProperties()['acronym'] == $userinfo->getLoggedInUser()) ?>
<?php if ($user->getProperties()['deleted'] != null) : ?>
<p>Den här användaren är borttagen och kan inte redigeras. Gå till <a 
href="<?=$this->di->get('url')->create('users/discarded')?>">papperskorgen</a> 
för att återställa användaren eller för att radera användaren permanent.</p>
<?php endif; ?>

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
<table class='userinfo'>
<tbody>
<tr><td>
<img src='<?=$user->getProperties()['gravatar']?>?s=50' alt='gravatar'></td>
<td><?=$user->getProperties()['name']?> 
<?php if ($userinfo->getLoggedInUser()) :?><br><?=$user->getProperties()['email']?><?php endif;?>
<br>Status: <?=$status?> <?=$date?>
<br>Skapades <?=$user->getProperties()['created']?>
<br><?=isset($user->getProperties()['updated'])?"Uppdaterad 
".$user->getProperties ( ) [ 'updated' ]:'';?>
</td></tr></tbody></table>
<p>
<?php if ($userisloggedin || $userinfo->getLoggedInUser() == 'admin') : ?>
<?php if ($user->getProperties()['deleted'] == null) : ?>
    <a 
href="<?=$this->url->create('users/update').'/'.$user->getProperties()['id']?>" 
title='Ändra'><i class="fa fa-pencil"></i> Redigera användare
</a>
<?php endif; ?>
<?php endif; ?>
</p>

