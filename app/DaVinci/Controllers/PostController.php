<?php

namespace DaVinci\Controllers;

use DaVinci\Core\App;
use DaVinci\Core\Route;
use DaVinci\Core\View;
use DaVinci\Models\Categoria;
use DaVinci\Models\Comentarios;
use DaVinci\Models\Post;
use Davinci\Storage\FileUpload;
use Davinci\Storage\Session;
use DaVinci\Validation\Validator;

class PostController extends Controller
{
    /**
     * Trae una lista de todos los POSTEOS
     *
     * return array[$posts]
     */
    public function listarTodos(){
        $this->requiresAuth();

        $posts = (new Post())->traerTodo();
        View::render('posts', [
            'posts' => $posts
        ]);
    }

    /**
     * Trae POST por ID
     * return $Post
     * @throws \Exception
     */
    public function listarPorID(){
        $params = Route::getUrlParameters();
        $post = (new Post())->traerPorID($params['id']);
        $post->traerCategoria();
        $post->traerUsuarioPost();
        $post->traerComentarios();

        // Lo cargo con compact
        View::render('post', compact('post'));
    }


    /**
     * Abre el formulario para nuevo post
     */
    public function nuevoForm(){
        $this->requiresAuth();
        $categorias = (new Categoria)->traerTodo();

        View::render('post-nuevo', compact(['categorias']));
    }

    /**
     * Funcion para hacer INSERT de un POST
     * @return void
     */
    public function agregarNuevoPostController(){
        $this->requiresAuth();

        $validator = new Validator($_POST, [
            'title'     => ['required', 'min:1'],
            'content'   => ['required', 'min:1']
        ]);

        if(!$validator->passes()) {
            Session::set('error', 'Ocurrieron errores de validación');
            $error = Session::get('error');
            Session::set('errores', $validator->getErrores());
            $errores = Session::get('errores');
            App::redirect('post/nuevo', compact(['error'],['errores']));
            exit;
        }

        // Captura de datos.
        $title          = $_POST['title'];
        $content        = $_POST['content'];
        $post_pic       = $_FILES['post_pic'];
        $owner_id       = $_SESSION['id'];
        $category_id    = $_POST['category_id'];

        if(!empty($post_pic['tmp_name'])) {
            $upload = new FileUpload($post_pic);
            $ruta = App::getPublicPath() . '/img';
            $extention = explode('.',$post_pic['name']);
            $nombreImagen = date('Ymd_His_') . $owner_id . '.' . $extention[1] ;
            $upload->save($ruta . '/' . $nombreImagen);

            $post = new Post();
            $exito = $post->crear([
                'title'         =>  $title,
                'content'       =>  $content,
                'post_pic'      =>  $nombreImagen,
                'owner_id'      =>  $owner_id,
                'category_id'   =>  $category_id
            ]);
        } else {
            $post = new Post();
            $exito = $post->crear([
                'title'         =>  $title,
                'content'       =>  $content,
                'owner_id'      =>  $owner_id,
                'category_id'   =>  $category_id
            ]);
        }



        if($exito) {
            Session::set('exito', 'Post creado con éxito!');
            App::redirect('posts');
            exit;
        } else {
            Session::set('error', 'Error al crear el Post ');
            App::redirect('post/nuevo');
        }
    }


    /**
     * Abre el formulario para editar post
     */
    public function editarForm(){
        $this->requiresAuth();
        $categorias = (new Categoria)->traerTodo();

        $params = Route::getUrlParameters();
        $post = (new Post())->traerPorID($params['id']);

        if(empty($post)){
            Session::set('error','Acción no permitida');
            App::redirect('posts');
            exit;
        }

        if (!($post->getOwnerId() == Session::get('id'))){
            Session::set('error','No puedes editar un Post que no te pertenece');
            App::redirect('posts');
            exit;
        }
        View::render('post-editar', compact(['categorias','post']));
    }


    /**
     *  Funcion para hacer el UPDATE del POST
     * @return void
     */
    public function hacerEditarForm(){

        $this->requiresAuth();
        $params = Route::getUrlParameters();
        $id = $params['id'];

        $validator = new Validator($_POST, [
            'title'     => ['required', 'min:1'],
            'content'   => ['required', 'min:1']
        ]);

        if(!$validator->passes()) {
            Session::set('error', 'Ocurrieron errores. Por favor verifique los campos');
            App::redirect('post/'. $params['id'] .'/editar');
            exit;
        }

        // Captura de datos.
        $title          = $_POST['title'];
        $content        = $_POST['content'];
        $post_pic       = $_FILES['post_pic'];
        $owner_id       = $_SESSION['id'];
        $category_id    = $_POST['category_id'];


        if(!empty($post_pic['tmp_name'])) {
            $upload = new FileUpload($post_pic);
            $ruta = App::getPublicPath() . '/img';
            $extention = explode('.',$post_pic['name']);
            $nombreImagen = date('Ymd_His_') . $owner_id . '.' . $extention[1] ;
            $upload->save($ruta . '/' . $nombreImagen);

            $post = new Post();
            $exito = $post->editar([
                'title'         =>  $title,
                'content'       =>  $content,
                'post_pic'      =>  $nombreImagen,
                'owner_id'      =>  $owner_id,
                'category_id'   =>  $category_id
            ], $id);
        } else {
            $post = new Post();
            $exito = $post->editar([
                'title'         =>  $title,
                'content'       =>  $content,
                'owner_id'      =>  $owner_id,
                'category_id'   =>  $category_id
            ], $id);
        }

        if($exito) {
            Session::set('exito', 'Post editado con éxito!');
            App::redirect('posts');
            exit;
        } else {
            Session::set('error', 'Error al editar el Post ');
            App::redirect('post/'. $params['id'] .'/editar');
            exit;
        }
    }

    /**
     * Elimina el POST con el ID indicado en la URL
     */
    public function eliminarPorID(){
        $this->requiresAuth();

        $comments = (new Comentarios())->eliminarComentarios(urlParam('id'));

        if(!$comments){
            Session::set('error','Ocurrió un error al eliminar el Post con comentarios' . urlParam('id'));
            App::redirect('posts');
            exit;
        }

        $post = (new Post())->eliminarXId(urlParam('id'));
        if(!$post){
            Session::set('error','Ocurrió un error al eliminar el Post' . urlParam('id'));
            App::redirect('posts');
            exit;
        }
        Session::set('exito','El Post #ID ' . urlParam('id') . ' fue eliminado con éxito');
        App::redirect('posts');
        exit;
    }


    public function insertComment(){
        $idPost = urlParam('id');
        $comentario = $_POST['comment'];

        $validator = new Validator($_POST, [
            'comment'     => ['required', 'min:1']
        ]);

        if(!$validator->passes()) {
            Session::set('error', 'Ingrese un valor');
            Session::set('errores', $validator->getErrores());
            App::redirect('post/'.$idPost);
            exit;
        }

        $comment = new Comentarios();
        $exito = $comment->crear([
            'post_id' => $idPost,
            'owner_id' => Session::get('id'),
            'content' => $comentario
        ]);

        if($exito) {
            App::redirect('post/'.$idPost);
            exit;
        } else {
            Session::set('error', 'Error al realizar el comentario');
            App::redirect('post/'.$idPost);
        }
    }

}