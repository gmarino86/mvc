<?php
use \DaVinci\Auth\Auth;
use \Davinci\Models\Post;

/**
 * @var $post Post
 */

$auth = new Auth();

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

<h1>Listado de Posteos</h1>

<?php
if($auth->isAuthenticated()) :
?>
<a href="<?= url('post/nuevo')?>">Crear nuevo post</a>
<?php
endif;
?>

<?php
if(!$posts) : ?>
    <div class="alert alert-warning my-2">
        <p class="text-center m-0 p-2">Aún no has creado posteos</p>
    </div>
<?php
else : ?>
<table class="table  table-hover tablaPosts">
    <thead>
    <tr>
        <th>ID</th>
        <th>Título</th>
        <th>Contenido</th>
        <th>Post_pic</th>
        <th>Propietario</th>
        <th>Likes</th>
        <th>Categoría</th>
        <th>Fecha de Creación</th>
        <th>Acciones</th>
    </tr>
    </thead>
    <tbody>
    <?php
    /** @var Post $posts */
    foreach($posts as $p): ?>
        <tr>
            <td class="align-middle"><?= $p->getId();?></td>
            <td class="align-middle"><?= $p->getTitle();?></td>
            <td class="align-middle"><?= $p->getContent();?></td>
            <td class="align-middle"><?= $p->getPostPic();?></td>
            <td class="align-middle"><?= $p->getUsuarioPost()->getName().' '.$p->getUsuarioPost()->getLastName();?></td>
            <td class="align-middle"><?= $p->getLikes();?></td>
            <td class="align-middle"><?= $p->getCategoria()->getName();?></td>
            <td class="align-middle"><?= date_format(date_create($p->getCreatedAt()),'d-m-Y H:i:s');?></td>
            <td>
                <div class="d-grid gap-2">
                <a href="<?= url('post/'. $p->getId());?>" class="btn btn-primary btn-sm">Ver</a>
                <?php
                if($auth->isAuthenticated()) :
                ?>
                <a href="<?= url('post/'. $p->getId()) . '/editar';?>" class="btn btn-warning btn-sm">Editar</a>
                <?php
                endif;
                ?>
                <?php
                if($auth->isAuthenticated()) :
                ?>
                <form action="<?= url('post/'. $p->getId() . '/eliminar');?>" method="post">
                    <div class="d-grid gap-2">
                        <button class="btn btn-sm btn-danger btnEliminarPost">Eliminar</button>
                    </div>
                </form>
                <?php
                endif;
                ?>
                </div>
            </td>
        </tr>
    <?php
    endforeach; ?>
    </tbody>
</table>
<?php
endif; ?>

