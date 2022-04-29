<?php

use Davinci\Storage\Session;

/** @var array $old_data */
/** @var array $errores */
/** @var array $error */

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

<h1>Registrarse</h1>
<form action="<?= url('register') ;?>" method="post">
    <div class="form-group">
        <label for="name">Nombre</label>
        <input type="text" name="name" id="name" class="form-control" value="<?= $old_data['name'] ?? null; ?>" <?= isset($errores['name']) ? 'aria-describedby="error-name"' : null; ?>>
        <?php
        if(isset($errores['name'])): ?>
            <div id="error-name" class="text-danger"><?= $errores['name'][0] ?></div>
        <?php
        endif; ?>
    </div>

    <div class="form-group">
        <label for="last_name">Apellido</label>
        <input type="text" name="last_name" id="last_name" class="form-control" value="<?= $old_data['last_name'] ?? null; ?>" <?= isset($errores['last_name']) ? 'aria-describedby="error-last_name"' : null; ?>>
        <?php
        if(isset($errores['last_name'])): ?>
            <div id="error-last_name" class="text-danger"><?= $errores['last_name'][0] ?></div>
        <?php
        endif; ?>
    </div>

    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" class="form-control" value="<?= $old_data['email'] ?? null; ?>" <?= isset($errores['email']) ? 'aria-describedby="error-email"' : null; ?>>
        <?php
        if(isset($errores['email'])): ?>
        <div id="error-email" class="text-danger"><?= $errores['email'][0] ?></div>
        <?php
        endif; ?>
    </div>

    <div class="form-group">
        <label for="password">Password</label>
        <div class="input-group mb-3">
            <input type="password" id="password" name="password" class="form-control">
            <button class="btn btn-outline-secondary toggle-password" type="button" data-id="password" aria-controls="password" aria-label="Mostrar password">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                    <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
                    <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
                </svg>
            </button>
        </div>
        <?php
        if(isset($errores['password'])): ?>
            <div id="error-password" class="text-danger"><?= $errores['password'][0] ?></div>
        <?php
        endif; ?>
    </div>

    <div class="form-group">
        <label for="gender_id">Género</label>
        <select class="form-control" id="id_gender" name="gender_id">
            <?php foreach ($generos as $g) :?>
                <option value="<?=$g->getId(); ?>"><?=$g->getType(); ?></option>
            <?php
            endforeach;
            ?>
        </select>
    </div>
    <div class="d-grid gap-2 my-4">
        <button type="submit" class="btn btn-primary btn-block">Registrarse</button>
    </div>
</form>
