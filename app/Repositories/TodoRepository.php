<?php

namespace App\Repositories;

use App\Models\Todo;
use Throwable;

class TodoRepository implements TodoRepositoryInterface
{
    public function create(array $data)
    {
        try {
            Todo::create($data);
            return jsonResponse([
                'message' => 'Todo item created successfully',
            ]);
        } catch (Throwable $e) {
            return jsonResponse([
                'message' => 'Failed to create todo item',
            ], 500);
        }
    }

    public function update(int $id, array $data)
    {
        try {
            $todo = Todo::find($id);
            if ($todo) {
                $todo->update($data);
                return jsonResponse([
                    'message' => 'Todo item updated successfully',
                ]);
            }
            return jsonResponse([
                'message' => 'Todo item not found',
            ], 404);
        } catch (Throwable $e) {
            return jsonResponse([
                'message' => 'Failed to update todo item',
            ], 500);
        }
    }

    public function delete(int $id)
    {
        try {
            $todo = Todo::find($id);
            if ($todo) {
                $todo->delete();
                return jsonResponse([
                    'message' => 'Todo item deleted successfully',
                ]);
            }
            return jsonResponse([
                'message' => 'Todo item not found',
            ], 404);
        } catch (Throwable $e) {
            return jsonResponse([
                'message' => 'Failed to delete todo item',
            ], 500);
        }
    }
}
