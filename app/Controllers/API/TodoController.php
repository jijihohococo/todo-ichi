<?php

namespace App\Controllers\API;

use JiJiHoHoCoCo\IchiRoute\Controller\BaseController;
use App\Models\Todo;
use App\Repositories\TodoRepositoryInterface;
use JiJiHoHoCoCo\IchiValidation\Validator;

class TodoController extends BaseController
{
    private $todo;

    public function __construct(TodoRepositoryInterface $todo)
    {
        $this->todo = $todo;
    }

    /**
     * GET /api/todos
     */
    public function index()
    {
        return jsonResponse([
            'todos' => Todo::orderBy('id', 'desc')->get()
        ]);
    }

    /**
     * GET /api/todos/{id}
     */
    public function show($id)
    {
        return jsonResponse([
            'todo' => Todo::find($id)
        ]);
    }

    /**
     * POST /api/todos
     */
    public function create()
    {
        $requestData = request();

        $errorMessages = $this->validateInput($requestData);
        if (!empty($errorMessages)) {
            return jsonResponse([
                'message' => 'Validation failed',
                'errors' => $errorMessages,
            ], 422);
        }

        return $this->todo->create([
            'title' => $requestData['title'],
            'description' => $requestData['description'] ?? null,
            'is_completed' => false
        ]);
    }

    /**
     * PUT /api/todos/{id}
     */
    public function update($id)
    {
        $requestData = request();

        $errorMessages = $this->validateInput($requestData);
        if (!empty($errorMessages)) {
            return jsonResponse([
                'message' => 'Validation failed',
                'errors' => $errorMessages,
            ], 422);
        }
        return $this->todo->update($id, $requestData);
    }

    /**
     * DELETE /api/todos/{id}
     */
    public function delete($id)
    {
        return $this->todo->delete($id);
    }

    private function validateInput($request)
    {
        $validator = new Validator();
        $rules = [
            'title' => ['required', 'string'],
            'description' => ['string'],
        ];

        $validator->validate($request, $rules);
        return $validator->getErrors();
    }
}
