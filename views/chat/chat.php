<?php
/**
 * @var $chats \DaVinci\Models\Chat
 * @var $c \DaVinci\Models\Chat
 * @var $friend \DaVinci\Models\User
 */

?>

<h1>Chat con <?= $friend->getName().' '.$friend->getLastName() ?></h1>
<div class="d-flex flex-column justify-content-between" style="height: 80vh">
    <div class="container" style="height: 70vh;overflow-y: auto;">
        <?php
        foreach ($chats as $c):
        ?>
        <div class="row">
            <div class="container">
                <div class="card text-white mb-3 w-50 <?= ($c->getIdUser() == $friend->getId()) ? "float-start bg-secondary" : "float-end bg-primary"?>">
                    <div class="card-header"><?= $c->getName().' '.$c->getLastName() ?></div>
                    <div class="card-body">
                        <p class="card-title m-0"><?= $c->getMessage() ?></p>
                        <p class="card-text float-end"><small class="text-white-50"><?= date_format(date_create($c->getDate()),'d-m-Y H:i:s') ; ?></small></p>
                    </div>
                </div>
            </div>
        </div>
        <?php
        endforeach;
        ?>
    </div>
    <form action="<?= url('chats/').$friend->getId() ?>" method="post">
        <div class="input-group">
            <input type="text" name="message" class="form-control" placeholder="Ingrese un mensaje" <?= isset($errores['message']) ? 'aria-describedby="error-message"' : null; ?>>
            <input type="hidden" name="id_friendship" value="<?= $id_friendship ?>">
            <button class="btn btn-outline-secondary" type="submit">Enviar</button>
        </div>
        <?php
        if(isset($errores['message'])): ?>
            <div id="error-message" class="text-danger"><?= $errores['message'][0] ?></div>
        <?php
        endif; ?>
    </form>
</div>