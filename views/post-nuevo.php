<?php

use Auth\Auth;
use \Davinci\Storage\Session;
/**
 * @var $exito
 * @var $error
 * @var $errores
 * @var $categorias \DaVinci\Models\Categoria
 */
$errores = Session::get('errores');

?>

    <div class="container">
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

        <h1>Crear nuevo Post</h1>

        <form action="<?= url('post/nuevo')?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Título</label>
                <input type="text" id="title" name="title" class="form-control" <?= isset($errores['title']) ? 'aria-describedby="error-title"' : null; ?>>
                <?php
                if(isset($errores['title'])): ?>
                    <div id="error-title" class="text-danger"><?= $errores['title'][0] ?></div>
                <?php
                endif; ?>
            </div>
            <div class="form-group">
                <label for="content">Contenido</label>
                <input type="text" id="content" name="content" class="form-control" <?= isset($errores['content']) ? 'aria-describedby="error-content"' : null; ?>>
                <?php
                if(isset($errores['content'])): ?>
                    <div id="error-content" class="text-danger"><?= $errores['content'][0] ?></div>
                <?php
                endif; ?>
            </div>
            <div class="form-group">
                <label for="post_pic">Imágen</label>
                <input type="file" id="post_pic" name="post_pic" class="form-control">
                <?php
                if(isset($errores['post_pic'])): ?>
                    <div id="error-post_pic" class="text-danger"><?= $errores['post_pic'][0] ?></div>
                <?php
                endif; ?>
            </div>
            <div class="form-group">
                <label for="category_id">Categoría</label>
                <select class="form-control" id="category_id" name="category_id">
                    <?php foreach ($categorias as $c) :?>
                      <option value="<?=$c->getId(); ?>"><?=$c->getName(); ?></option>
                    <?php
                    endforeach;
                    ?>
                </select>
            </div>
            <div class="d-grid gap-2 mt-4">
                <button class="btn btn-primary" type="submit">Crear post</button>
            </div>
        </form>
    </div>