<?php
use \Davinci\Storage\Session;


/** @var array $old_data */
/** @var array $errores */
/** @var array $exito */
/** @var array $error */

$errores = Session::get('errores');
$old_data = Session::get('old_data');

?>

    <?php
    if($exito):
        ?>
        <div class="alert alert-success"><?= $exito;?></div>
    <?php
    endif; ?>
    <?php
    if($error):
        ?>
        <div class="alert alert-danger"><?= $error;?></div>
    <?php
    endif; ?>


    <h1>Iniciar Sesión</h1>

    <form action="<?= url('login') ;?>" method="post">
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
            <input type="password" name="password" id="password" class="form-control">
            <?php
            if(isset($errores['password'])): ?>
                <div id="error-password" class="text-danger"><?= $errores['password'][0] ?></div>
            <?php
            endif; ?>

        </div>
        <div class="d-grid gap-2 my-4">
            <button type="submit" class="btn btn-primary btn-block">Ingresar</button>
        </div>
    </form>
