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

        return $this->todo->create($requestData);
    }

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
