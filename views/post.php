<?php
use DaVinci\Auth\Auth;
use \Davinci\Storage\Session;
/**
 * Recibe un objeto $post del tipo Post
 * @var $post \DaVinci\Models\Post
 */

?>
<div class="row paddingPostBottom">
    <div class="col-12 col-md-8 offset-md-2">
        <div class="card">
            <img src="<?= url()."img/".$post->getPostPic();?>" class="card-img-top postImage" alt="<?= $post->getPostPic();?>">
            <div class="card-body">
                <h1 class="card-title m-0"><?= $post->getTitle();?></h1>
                <p class="card- mb-2"><?= $post->getContent();?></p>
                <p class="fontSize5 text-muted m-0"><ins>Categoria</ins>: <?= $post->getCategoria()->getName();?></p>
                <?php
                if ($post->getOwnerId() != Session::get('id')):
                ?>
                <p class="fontSize5 text-muted m-0"><ins>Usuario</ins>: <a href="<?= url('friend/'.$post->getOwnerId()) ?>" class="text-decoration-none"><?= $post->getUsuarioPost()->getName().' '.$post->getUsuarioPost()->getLastName() ;?></a></p>
                <?php
                else :
                ?>
                <p class="fontSize5 text-muted m-0"><ins>Usuario</ins>: <?= $post->getUsuarioPost()->getName().' '.$post->getUsuarioPost()->getLastName() ;?></p>
                <?php
                endif;
                ?>
                <small class="fontSize5 text-muted float-end">Creado: <?= $post->getCreatedAt();?></small>
            </div>
            <div class="card-footer">
                <?php
                if($post->getComentarios()):?>

                    <?php
                    foreach ($post->getComentarios() as $c ): ?>

                    <div class="card">
                        <div class="card-body">
                            <?php
                            if($c->getOwnerId() == Session::get('id')):
                            ?>
                            <p class="postCommentName"><?= $c->getName().' '.$c->getLastName().':'; ?></p>
                            <?php
                            else :
                            ?>
                            <a href="<?= url('friend/'.$c->getOwnerId())?>" class="postCommentName"><?= $c->getName().' '.$c->getLastName().':'; ?></a>
                            <?php
                            endif;
                            ?>
                            <p class="postComment"><?= $c->getContent(); ?></p>

                        </div>
                    </div>

                    <?php
                    endforeach;
                    ?>
                <?php
                else :?>
                <p class="text-center">No hay Comentarios</p>
                <?php
                endif;
                ?>
                <?php
                if(Auth::isAuthenticated()): ?>
                <form action="<?= url('post/'.$post->getId().'/comentar') ?>" method="post">
                    <div class="input-group my-3">
                        <input type="text" name="comment" class="form-control" placeholder="Ingrese su comentario" aria-label="Recipient's username" <?= isset($errores['comment']) ? 'aria-describedby="error-comment"' : null; ?>>
                        <button class="btn btn-outline-secondary" type="submit" id="comment">Enviar</button>
                    </div>
                    <?php
                    if(isset($errores['comment'])): ?>
                        <div id="error-comment" class="text-danger"><?= $errores['comment'][0] ?></div>
                    <?php
                    endif; ?>
                </form>
                <?php
                endif;
                ?>
            </div>
        </div>
    </div>
</div>
