<?php

namespace DaVinci\Controllers;

use DaVinci\Core\App;
use DaVinci\Core\View;
use DaVinci\Models\Post;

class HomeController extends Controller
{
    public function index()
    {
        $posts = (new Post())->traerTodosPublicos();

        View::render('home',compact('posts'));
    }
}