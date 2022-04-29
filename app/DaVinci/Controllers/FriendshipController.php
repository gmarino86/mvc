<?php

namespace DaVinci\Controllers;

use DaVinci\Core\App;
use DaVinci\Core\View;
use DaVinci\Models\AmigosUsuarios;
use DaVinci\Models\Friendship;
use DaVinci\Models\Post;
use DaVinci\Models\User;
use Davinci\Storage\createModelException;
use Davinci\Storage\Session;

class FriendshipController extends Controller
{
    public function listarPorID(){
        $idUser = Session::get('id');
        $friend = urlParam('id');

        $friendUser = (new User())->traerPorID($friend);
        $friendship = (new Friendship())->verAmistad($idUser, $friend);

        $posts = (new Post())->traerPublicosXFriend($friend);
        View::render('friend/post',compact('friendUser','friendship','posts'));
    }

    public function followFriend(){
        $idFriend = urlParam('id');
        $idUser = Session::get('id');

        try {
            $follow = (new Friendship())->crear([
                'id_friend' => $idFriend,
                'id_user'   => $idUser,
                'state'     => 1
            ]);
        } catch(createModelException $e) {
            View::render('error/error',compact('e'));
        }

        if($follow) {
            Session::set('exito','Follow exitoso!');
            App::redirect('friend/'.$idFriend);
            exit;
        }
    }

    public function listarAmigos(){
        $friends = (new AmigosUsuarios())->traerAmigos();

        View::render('friend/friends',compact(['friends']));
    }
}