<a href="<?=$this->di->get('url')->create('users/add')?>"><i class="fa 
fa-user-plus fa-fw"></i> Lägg till användare</a>
<br><a href="<?=$this->di->get('url')->create('users/discarded')?>"><i 
class="fa fa-user-times fa-fw user-deleted"></i> Administrera borttagna 
användare</a>
<br><a href="<?=$this->di->get('url')->create('users/active')?>"><i class="fa 
fa-user fa-fw"></i> Se aktiva användare</a>
<br><a href="<?=$this->di->get('url')->create('users/inactive')?>"><i class="fa 
fa-user fa-fw user-inactive"></i> Se inaktiva användare</a>
<br><a href="<?=$this->di->get('url')->create('users')?>"><i class="fa 
fa-users fa-fw"></i> Se samtliga användare</a>

<br><a href="<?=$this->di->get('url')->create('setup')?>"><i 
class="fa 
fa-database fa-fw"></i> Återställ databasen</a>