<?php

/**
 * @var $friendship \DaVinci\Models\Friendship;
 * @var $friendUser \DaVinci\Models\User;
 * @var $posts \DaVinci\Models\Post;
 */

?>

<?php
if(!$friendship):?>
    <div class="container">

        <div class="alert alert-secondary" role="alert">
            <h1 class="alert-heading">Aún no sigues a:</h1>
            <p class="h5"><?= $friendUser->getName() . ' ' . $friendUser->getLastName() ?></p>
            <hr>
            <p class="mb-0">Seguilo para ver sus Posteos desde la sección de "Amigos"</p>
            <div class="text-center">
                <form action="<?= url('friend/'. $friendUser->getId() . '/follow');?>" method="post">
                    <button type="submit" class="btn btn-outline-success">Seguir</button>
                </form>
            </div>
        </div>

    </div>
<?php
else :?>
    <div class="container">
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

        <h1><?= $friendship->getName().' '. $friendship->getLastName() ?></h1>

        <?php
        if ($posts):
        ?>
            <div class="row row-cols-1 row-cols-md-2">
                <?php
                foreach ($posts as $p) :
                ?>
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
        else:
        ?>
            <div class="alert alert-secondary" role="alert">
                <h2 class="alert-heading">Aún no creó Posteos</h2>
            </div>
        <?php
        endif;
        ?>

    </div>
<?php
endif;
?>