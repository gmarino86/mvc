<?php
use Auth\Auth;
use \DaVinci\Core\App;

/**
 * @var $post \DaVinci\Models\Post
 */
?>

<div class="container">
    <?php
    if(isset($_SESSION['exito'])):
        $exito = $_SESSION['exito'];
        unset($_SESSION['exito']);
        ?>
        <div class="alert alert-success"><?= $exito;?></div>
    <?php
    endif; ?>
    <?php
    if(isset($_SESSION['error'])):
        $error = $_SESSION['error'];
        unset($_SESSION['error']);
        ?>
        <div class="alert alert-danger"><?= $error;?></div>
    <?php
    endif; ?>

    <h2>Editar el Post #<?= $post->getId(); ?></h2>

    <form action="<?= url('post/'. $post->getId() .'/editar')?>" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Título</label>
            <input type="text" id="title" name="title" class="form-control" value="<?= $post->getTitle(); ?>">
        </div>
        <div class="form-group">
            <label for="content">Contenido</label>
            <input type="text" id="content" name="content" class="form-control" value="<?= $post->getContent(); ?>">
        </div>
        <div class="form-group">
            <label for="post_pic">Imágen</label>
            <input type="file" id="post_pic" name="post_pic" class="form-control" value="<?= App::getImgPath().'/'. $post->getPostPic() ?>" >
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
                    <option <?= ($c->getId() === $post->getCategoryId()) ? "selected" : '';?> value="<?=$c->getId(); ?>"><?=$c->getName(); ?></option>
                <?php
                endforeach;
                ?>
            </select>
        </div>
        <div class="d-grid gap-2 mt-4">
            <button class="btn btn-primary" type="submit">Actualizar post</button>
        </div>
    </form>
</div>