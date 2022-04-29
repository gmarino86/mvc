<?php
use \DaVinci\Auth\Auth;
/**
 * @var $posts \DaVinci\Models\Post
 */

?>
<div class="container pb-5">
    <h1>Bienvenidos al Final de Programación 3 de Gaston Marino!</h1>

    <?php
    if (!Auth::isAuthenticated()):;?>
        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-6">
                    <div class="rounded bg-secondary m-1 p-4">
                        <p class="text-white">Aquí podrás ver Posteos públicos o bien, hacer una cuenta desde el siguiente botón</p>
                        <a href="<?= url('register')?>" class="btn btn-primary btn-block">Registrarse</a>
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="rounded bg-secondary m-1 p-4">
                        <p class="text-white">O si ya tienes una, loguearte para ver los Post de tus amigos también.</p>
                        <a href="<?= url('login')?>" class="btn btn-primary btn-block">Ingresar</a>
                    </div>
                </div>
            </div>
        </div>
    <?php
    else :?>
        <?php
        if(!$posts) : ?>
            <div class="alert alert-primary d-flex justify-content-center my-4">
                <p class="m-0 p-2">No hay Posteos aún</p>
            </div>
        <?php
        else: ?>
            <h2 class="d-block text-center my-4">Posteos </h2>
            <div class="row row-cols-1 row-cols-md-2">
            <?php
            foreach ($posts as $p) :?>
                <div class="col-4 mb-2">
                    <div class="card">
                        <img src="<?= url()."img/".$p->getPostPic();?>" class="card-img-top" alt="<?= $p->getPostPic();?>">
                        <div class="card-body">
                            <h3 class="card-title"><?= $p->getTitle(); ?></h3>
                            <p class="card-text"><?= $p->getUsuarioPost()->getName().' '.$p->getUsuarioPost()->getLastName(); ?></p>
                        </div>
                        <div class="card-footer">
                            <div class="text-center">
                                <a href="<?= url('post/'. $p->getId());?>" class="btn btn-primary btn-sm">Ver</a>
                                <!--<button type="button" class="btn btn-secondary btn-sm">Favorito</button>-->
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            endforeach;
            ?>
            </div>
        <?php
        endif;
        ?>
    <?php
    endif;
    ?>
</div>