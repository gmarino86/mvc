<?php
use DaVinci\Core\Route;

/**
 * Login
 */
// Formulario para login
Route::add('GET','/login','AuthController@login');
// Envia el formulario para login
Route::add('POST','/login','AuthController@loginDo');
// Formulario para Registrar un usuario
Route::add('GET','/register','AuthController@register');
// Graba los datos del registro en la Base
Route::add('POST','/register','AuthController@registerDo');
// Quita de la sesion el registro de login
Route::add('POST','/logout','AuthController@logout');


/**
 * Perfil
 */
// Muestra la pantalla con los datos del usuario registrado
Route::add('GET', '/profile', 'UserController@verPerfil');
// Trae el formulario para edita un Perfil por el ID
Route::add('GET','/profile/{id}/editar','UserController@editarPerfilForm');
// Edita un Perfil por el ID
 Route::add('POST','/profile/{id}/editar','UserController@editarPerfilFormDo');

/**
 * CHATS
 */
Route::add('GET','/chats','ChatController@index');
Route::add('GET','/chats/{id}','ChatController@verChat');
Route::add('POST','/chats/{id}','ChatController@enviarMsg');


/**
 * Friend
 */
// Pantalla Lista de todos los Posteos de Amigos
// Vista de Post por Amigo
Route::add('GET','/friends','FriendshipController@listarAmigos');
Route::add('GET','/friend/{id}','FriendshipController@listarPorID');
Route::add('POST','/friend/{id}/follow','FriendshipController@followFriend');


/**
 * Posts
 */
// Pantalla Lista de todos los POSTS
Route::add('GET','/posts','PostController@listarTodos');
// Formulario para Nuevo POST
Route::add('GET','/post/nuevo','PostController@nuevoForm');
// Acción que realiza el alta del nuevo post
Route::add('POST','/post/nuevo','PostController@agregarNuevoPostController');
// Vista de Post por ID específico
Route::add('GET','/post/{id}','PostController@listarPorID');
// Elimina un post por el ID
Route::add('POST','/post/{id}/eliminar','PostController@eliminarPorID');
// Trae el formulario para edita un post por el ID
Route::add('GET','/post/{id}/editar','PostController@editarForm');
// Edita un post por el ID
Route::add('POST','/post/{id}/editar','PostController@hacerEditarForm');

// Insertar comentarios a POST ID
Route::add('POST','/post/{id}/comentar','PostController@insertComment');



/**
 * HOME
 */
// Abre la Home
Route::add('GET', '/', 'HomeController@index');



