<?php

namespace App\Controllers\API;

use JiJiHoHoCoCo\IchiRoute\Controller\BaseController;
use App\Models\Todo;

class TodoController extends BaseController
{
    public function index()
    {
        return jsonResponse([
            'todos' => Todo::paginate()
        ]);
    }

    public function show($id)
    {
        return jsonResponse([
            'todo' => Todo::find($id)
        ]);
    }
}
