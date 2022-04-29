<?php
use DaVinci\Auth\Auth;
use Davinci\Storage\Session;

$auth = new Auth;

$error = Session::getAndForget('error', null);
$exito = Session::getAndForget('exito', null);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Listado de Posteos</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="<?= url('css/bootstrap.min.css');?>">
    <link rel="stylesheet" href="<?= url('css/estilos.css');?>">
</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= url('/');?>">GM Final P3</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Abrir/cerrar menú de navegación">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= url('/');?>">Home</a>
                    </li>
                    <?php
                    if($auth->isAuthenticated()): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= url('posts');?>">Mis Posts</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= url('friends');?>">Amigos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= url('chats');?>">Chats</a>
                    </li>
                    <?php
                    endif; ?>
                    <?php
                    if(!$auth->isAuthenticated()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= url('login');?>">Login</a>
                        </li>
                    <?php
                    else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= url('profile');?>">Perfil</a>
                        </li>
                        <li class="nav-item">
                            <form action="<?= url('logout');?>" method="post">
                                <button type="submit" class="nav-link btn"><?= $auth->getUser()->getEmail();?> (Logout)</button>
                            </form>
                        </li>
                    <?php
                    endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container main-content pt-3">
        @{{content}}
    </div>

    <footer class="footer">
        Copyright &copy; Gaston Marino 2021
    </footer>
</div>

<script src="<?= \DaVinci\Core\App::getUrlPath();?>js/jquery-3.2.1.js"></script>
<script src="<?= \DaVinci\Core\App::getUrlPath();?>js/bootstrap.js"></script>
<script src="<?= \DaVinci\Core\App::getUrlPath();?>js/password.js"></script>
</body>
</html>



