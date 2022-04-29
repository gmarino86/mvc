<?php

namespace DaVinci\Controllers;

use DaVinci\Core\App;
use DaVinci\Core\View;
use DaVinci\Models\Chat;
use DaVinci\Models\User;
use Davinci\Storage\Session;
use DaVinci\Validation\Validator;

class ChatController extends Controller
{
    /**
     * Para traer la pantalla con la lista de los chats activos
     * @return void
     */
    public function index(){
        $id_user = Session::get('id');
        $chats = (new Chat())->traerTodosLosChats($id_user);

        View::render('chat/chats',compact('chats'));
    }

    public function verChat()
    {

        if(Session::has('errores')){
            $errores = Session::get('errores');
            Session::delete('errores');
        } else {
            $errores = [];
        }

        $id_user = Session::get('id');
        $id_friend = urlParam('id');

        $friend = (new User())->traerPorID($id_friend);
        $chats = (new Chat())->traerChatsConAmigo($id_user, $id_friend);

        if (empty($chats)){
            $chat
            Session::set('error','No tienes un Chat con ese usuario');
            App::redirect('chats/');
            exit;
        }

        $id_friendship = $chats[0]->getIdFriendship();

        $this->validarAmistad($id_user, $id_friendship);

        View::render('chat/chat',compact('chats','friend','id_friendship','errores'));
    }

    public function enviarMsg(){
        $id_user = Session::get('id');
        $id_friend = urlParam('id');
        $id_friendship = $_POST['id_friendship'];

        $validator = new Validator($_POST, [
            'message'     => ['required', 'min:1']
        ]);

        if(!$validator->passes()) {
            Session::set('errores', $validator->getErrores());
            App::redirect('chats/'.$id_friend);
            exit;
        }

        $chat = (new Chat())->enviarMsg($_POST['message'], $id_user, $id_friend, $id_friendship);
        App::redirect('chats/'.$id_friend);
        exit;
    }

    private function validarAmistad($id_user, $id_friendship)
    {

    }

}