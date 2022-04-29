<?php

namespace DaVinci\Controllers;

use DaVinci\Auth\Auth;
use DaVinci\Core\App;
use DaVinci\Core\Route;
use DaVinci\Core\View;
use DaVinci\Models\Categoria;
use DaVinci\Models\Post;
use DaVinci\Models\User;
use Davinci\Storage\Session;
use DaVinci\Validation\Validator;

class UserController extends Controller
{

    /**
     * Trae una lista de todos los POSTEOS
     *
     * return array[$posts]
     */
    public function listarTodos(){
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
            App::redirect('post/nuevo');
            exit;
        }

        // Captura de datos.
        $title          = $_POST['title'];
        $content        = $_POST['content'];
        $post_pic       = $_POST['post_pic'];
        $owner_id       = $_SESSION['id'];
        $category_id    = $_POST['category_id'];

        $post = new Post();
        $exito = $post->crear([
            'title'         =>  $title,
            'content'       =>  $content,
            'post_pic'      =>  $post_pic,
            'owner_id'      =>  $owner_id,
            'category_id'   =>  $category_id
        ]);

        if($exito) {
            Session::set('exito', 'Post creado con éxito!');
            App::redirect('posts');
            exit;
        } else {
            Session::set('error', 'Error al crear el Post ');
            App::redirect('post/nuevo');
            exit;
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
        $post_pic       = $_POST['post_pic'];
        $owner_id       = $_SESSION['id'];
        $category_id    = $_POST['category_id'];

        $post = new Post();
        $exito = $post->editar([
            'title'         =>  $title,
            'content'       =>  $content,
            'post_pic'      =>  $post_pic,
            'owner_id'      =>  $owner_id,
            'category_id'   =>  $category_id
        ], $id);

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



    /**
     * Trae la vista para ver el perfil
     * @return void
     */
    public function verPerfil()
    {
        $this->requiresAuth();
        $user = (new User())->getUserById(Session::get('id'));
        $user->traerGenero();
        Session::delete('errores');

        View::render('profile/profile',compact('user'));
    }




    /**
     * Abre el formulario para editar Perfil
     */
    public function editarPerfilForm()
    {
        $this->requiresAuth();
        $params = Route::getUrlParameters();
        $user = (new User())->traerPorID($params['id']);
        $user->traerGenero();

        View::render('profile/profileEditForm',compact('user'));
    }

    public function editarPerfilFormDo()
    {
        $this->requiresAuth();
        $validator = new Validator($_POST, [
            'name'          => ['required', 'min:1'],
            'last_name'     => ['required', 'min:1'],
            'email'         => ['required', 'min:1'],
            'gender_id'     => ['required', 'min:1']
        ]);

        if(!$validator->passes()) {
            Session::set('error', 'Ocurrieron errores. Por favor verifique los campos');
            Session::set('errores', $validator->getErrores());
            App::redirect('profile/'. $_SESSION['id'] .'/editar');
            exit;
        }

        // Captura de datos.
        $id             = $_SESSION['id'];
        $name           = $_POST['name'];
        $last_name      = $_POST['last_name'];
        $email          = $_POST['email'];
        $gender_id      = $_POST['gender_id'];

        $user = new User();
        $exito = $user->editar([
            'name'          => $name,
            'last_name'     => $last_name,
            'email'         => $email,
            'gender_id'     => $gender_id
        ],$id);

        if($exito) {
            Session::set('exito', 'Usuario editado con éxito!');
            App::redirect('profile');
            exit;
        } else {
            Session::set('error', 'Error al editar el Perfil');
            App::redirect('profile/'. $id .'/editar');
            exit;
        }
    }


}