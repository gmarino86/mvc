<?php
/**
 * @var $friends \DaVinci\Models\AmigosUsuarios
 */
?>
<div class="container">
    <h1>Amigos</h1>


    <?php
    if(!$friends):
    ?>
        <div class="alert alert-secondary" role="alert">
            <h2 class="alert-heading">Aún no tienes amigos</h2>
        </div>
    <?php
    else:
    ?>
        <?php
        foreach ($friends as $friend):
        ?>
            <div class="alert alert-secondary" role="alert">
                <div class="d-flex justify-content-between">
                    <h3><?= $friend->getName().' '.$friend->getLastName() ?></h3>
                    <div>
                        <a href="<?= url('chats/'.$friend->getIdFriend()) ?>" role="button" class="btn btn-outline-secondary">Chat</a>
                        <a href="<?= url('friend/'.$friend->getIdFriend()) ?>" role="button" class="btn btn-outline-secondary">Ver</a>
                    </div>
                </div>
            </div>
        <?php
        endforeach;
        ?>
    <?php
    endif;
    ?>
</div>
