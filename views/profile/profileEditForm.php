<?php
use \Davinci\Storage\Session;
/**
 * @var $user \DaVinci\Models\User
 */
$errores = Session::get('errores');
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

<h1>Editar perfil</h1>

<form action="<?= url('profile/'. $user->getId().'/editar') ?>" method="post">
    <div class="row">
        <div class="col">
            <label for="name">Nombre</label>
            <input type="text" class="form-control" id="name" name="name" value="<?= $user->getName() ;?>" <?= isset($errores['name']) ? 'aria-describedby="error-name"' : null; ?>>
            <?php
            if(isset($errores['name'])): ?>
                <div id="error-name" class="text-danger"><?= $errores['name'][0] ?></div>
            <?php
            endif; ?>
        </div>
        <div class="col">
            <label for="last_name">Apellido</label>
            <input type="text" class="form-control" id="last_name" name="last_name" value="<?= $user->getLastName() ;?>" <?= isset($errores['last_name']) ? 'aria-describedby="error-last_name"' : null; ?>>
            <?php
            if(isset($errores['last_name'])): ?>
                <div id="error-last_name" class="text-danger"><?= $errores['last_name'][0] ?></div>
            <?php
            endif; ?>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= $user->getEmail() ;?>" <?= isset($errores['email']) ? 'aria-describedby="error-email"' : null; ?>>
            <?php
            if(isset($errores['email'])): ?>
                <div id="error-email" class="text-danger"><?= $errores['email'][0] ?></div>
            <?php
            endif; ?>
        </div>

        <div class="col">
            <label for="gender_id">Género</label>
            <select class="form-control" id="id_gender" name="gender_id" <?= isset($errores['gender_id']) ? 'aria-describedby="error-gender_id"' : null; ?>>
                    <option value="1" <?= ($user->getGender()->getId() == 1) ? 'selected' : '' ; ?>>Masculino</option>
                    <option value="2" <?= ($user->getGender()->getId() == 2) ? 'selected' : '' ; ?>>Femenino</option>
            </select>
            <?php
            if(isset($errores['gender_id'])): ?>
                <div id="error-gender_id" class="text-danger"><?= $errores['gender_id'][0] ?></div>
            <?php
            endif; ?>
        </div>
    </div>

    <div class="mt-4 text-center">
        <div class="btn-group" role="group" aria-label="Basic example">
            <button type="submit" class="btn btn-success">Editar</button>
            <a href="<?= url('profile');?>" class="btn btn-danger">Cancelar</a>
        </div>
    </div>
</form>