<?php
use \DaVinci\Models\Genero;
use Davinci\Storage\Session;

/**
 * Recibo un \DaVinci\Models\User
 * @var $user \DaVinci\Models\User
 */

?>

<?php
if(isset($exito)):
    ?>
    <div class="alert alert-success"><?= $exito;?></div>
<?php
endif; ?>
<?php
if(isset($error)):
    ?>
    <div class="alert alert-danger"><?= $error;?></div>
<?php
endif; ?>

<h1>Perfil del usuario</h1>

<div class="row">
    <div class="col">
        <label for="name">Nombre</label>
        <input type="text" class="form-control" id="name" placeholder="<?= $user->getName() ;?>" disabled>
    </div>
    <div class="col">
        <label for="last_name">Apellido</label>
        <input type="text" class="form-control" id="last_name" placeholder="<?= $user->getLastName() ;?>" disabled>
    </div>
</div>
<div class="row">
    <div class="col">
        <label for="name">Email</label>
        <input type="email" class="form-control" id="email" placeholder="<?= $user->getEmail() ;?>" disabled>
    </div>
    <div class="col">
        <label for="gender">Género</label>
        <input type="text" class="form-control" id="gender" placeholder="<?= $user->getGender()->getType() ;?>" disabled>
    </div>
</div>

<div class="mt-4 text-center">
    <div class="btn-group" role="group" aria-label="Basic example">
        <a href="<?= url('profile/'.$user->getId().'/editar') ;?>" class="btn btn-warning">Editar</a>
        <a class="btn btn-info">Cambiar Password</a>
    </div>
</div>
