<?php

namespace App\Repositories;

use App\Events\TodoCreated;
use App\Events\TodoUpdated;
use App\Exceptions\GeneralException;
use App\Exceptions\ModelNotFound;
use App\Http\Requests\Todo\CreateTodoRequest;
use App\Http\Requests\Todo\UpdateTodoRequest;
use App\Http\Requests\Todo\UpdateTodoStatusRequest;
use App\Models\Todo;
use Exception;
use Illuminate\Http\Request;

class TodoRepository
{
    /**
     * Todos model instance.
     *
     * @var Todo $todo
     */
    protected $todo;

    /**
     * Todos repository constructor.
     */
    public function __construct()
    {
        $this->todo = new Todo();
    }

    /**
     * Retrieve all todos.
     *
     * @param Request $request
     * @return mixed
     */
    public function getAllTodos(Request $request): mixed
    {
        // Retrieve all todos with search and pagination functionalities.
        return $this->todo->search($request->search ?? null)->paginate($request->offset ?? 10);
    }

    /**
     * Retrieve a todo based on id.
     *
     * @param string $id
     * @return mixed
     * @throws ModelNotFound|GeneralException|Exception
     */
    public function showTodo(string $id): mixed
    {
        try {
            // Get the todo based on the id.
            $todo = $this->todo->find($id);
            // Check if the todo does not exist.
            if (! $todo) {
                throw new ModelNotFound(__('todos.not_found'));
            }
            return $todo;
        } catch (ModelNotFound $ex) {
            throw $ex;
        } catch (Exception $ex) {
            throw new GeneralException();
        }
    }

    /**
     * Create a new todo.
     *
     * @param CreateTodoRequest $request
     * @return mixed
     * @throws GeneralException|Exception
     */
    public function storeTodo(CreateTodoRequest $request): mixed
    {
        try {
            // Create a new todo.
            $this->todo->title = $request->title;
            $this->todo->description = $request->description;
            // Check if the todo has an image and store it.
            if (! is_null(request()->file('image'))) {
                $this->todo->image = $request->image->store('todos', 'public');
            }
            $this->todo->save();
            // Send mail notification to the user with todo details.
            event(new TodoCreated($this->todo));
            return $this->todo;
        } catch (GeneralException $ex) {
            throw $ex;
        } catch (Exception $ex) {
            throw new GeneralException();
        }
    }

    /**
     * Update a todo.
     *
     * @param UpdateTodoRequest $request
     * @param string $id
     * @return mixed
     * @throws ModelNotFound|GeneralException|Exception
     */
    public function updateTodo(UpdateTodoRequest $request, string $id): mixed
    {
        try {
            // Get the todo based on the id.
            $todo = $this->todo->find($id);
            // Check if the todo does not exist.
            if (! $todo) {
                throw new ModelNotFound(__('todos.not_found'));
            }
            // Update the todo details.
            $todo->title = $request->title;
            $todo->description = $request->description;
            $todo->status = $request->status;
            // Check if the todo has an image and store it then delete the old one.
            if (! is_null(request()->file('image'))) {
                // Delete the old image.
                if ($todo->image && file_exists(public_path('storage/'.$todo->image))) {
                    unlink(public_path('storage/'.$todo->image));
                }
                // Store the new image.
                $todo->image = $request->image->store('todos', 'public');
            }
            $todo->save();
            // Send mail notification to the user with updated todo details.
            event(new TodoUpdated($todo));
            return $todo;
        } catch (ModelNotFound|GeneralException $ex) {
            throw $ex;
        } catch (Exception $ex) {
            throw new GeneralException();
        }
    }

    /**
     * Update a todo status.
     *
     * @param UpdateTodoStatusRequest $request
     * @param string $id
     * @return mixed
     * @throws ModelNotFound|GeneralException|Exception
     */
    public function updateTodoStatus(UpdateTodoStatusRequest $request, string $id): mixed
    {
        try {
            // Get the todo based on the id.
            $todo = $this->todo->find($id);
            // Check if the todo does not exist.
            if (! $todo) {
                throw new ModelNotFound(__('todos.not_found'));
            }
            // Update the todo status.
            $todo->status = $request->status;
            $todo->save();
            return $todo;
        } catch (ModelNotFound|GeneralException $ex) {
            throw $ex;
        } catch (Exception $ex) {
            throw new GeneralException();
        }
    }

    /**
     * Delete a todo.
     *
     * @param string $id
     * @return mixed
     * @throws ModelNotFound|GeneralException|Exception
     */
    public function deleteTodo(string $id): void
    {
        try {
            // Get the todo based on the id.
            $todo = $this->todo->find($id);
            // Check if the todo does not exist.
            if (! $todo) {
                throw new ModelNotFound(__('todos.not_found'));
            }
            // Check if the todo has an image and delete it.
            if (isset($todo->image) && file_exists(public_path('storage/'.$todo->image))) {
                unlink(public_path('storage/'.$todo->image));
            }
            // Delete the todo.
            $todo->delete();
        } catch (ModelNotFound|GeneralException $ex) {
            throw $ex;
        } catch (Exception $ex) {
            throw new GeneralException();
        }
    }
}
