<?php
use \Davinci\Storage\Session;

/**
 * @var $chats \DaVinci\Models\Chat
 * @var $c \DaVinci\Models\Chat
 */
$idUser = Session::get('id');
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
<h1>Chats</h1>

<div class="row">
    <?php
    foreach ($chats as $c):
    ?>
        <?php
        if (!($c->getIdUser() == $idUser)):
        ?>
        <div class="col-12 col-sm-6 mb-2">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><?= $c->getName().' '.$c->getLastName() ?></h5>
                    <a href="<?= url('chats/').$c->getIdUser() ?>" class="btn btn-primary float-end">Ver Chat</a>
                </div>
            </div>
        </div>
        <?php
        endif;
        ?>

    <?php
    endforeach;
    ?>
</div>