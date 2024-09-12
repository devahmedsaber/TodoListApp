<?php

namespace App\Services;

use App\Http\Requests\Todo\CreateTodoRequest;
use App\Http\Requests\Todo\UpdateTodoRequest;
use App\Http\Requests\Todo\UpdateTodoStatusRequest;
use App\Repositories\TodoRepository;
use Exception;
use Illuminate\Http\Request;

class TodoService
{
    /**
     * Todos repository instance.
     *
     * @var TodoRepository $todoRepository
     */
    protected TodoRepository $todoRepository;

    /**
     * Todos service constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->todoRepository = new TodoRepository();
    }

    /**
     * Retrieve a list of todos based on the request.
     *
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function index(Request $request): mixed
    {
        try {
            return $this->todoRepository->getAllTodos($request);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Retrieve a todo based on id.
     *
     * @param string $id
     * @return mixed
     * @throws Exception
     */
    public function show(string $id): mixed
    {
        try {
            return $this->todoRepository->showTodo($id);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Create a new todo.
     *
     * @param CreateTodoRequest $request
     * @return mixed
     * @throws Exception
     */
    public function store(CreateTodoRequest $request): mixed
    {
        try {
            return $this->todoRepository->storeTodo($request);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Update a todo.
     *
     * @param UpdateTodoRequest $request
     * @param string $id
     * @return mixed
     * @throws Exception
     */
    public function update(UpdateTodoRequest $request, string $id): mixed
    {
        try {
            return $this->todoRepository->updateTodo($request, $id);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Update a todo status.
     *
     * @param UpdateTodoStatusRequest $request
     * @param string $id
     * @return mixed
     * @throws Exception
     */
    public function updateStatus(UpdateTodoStatusRequest $request, string $id): mixed
    {
        try {
            return $this->todoRepository->updateTodoStatus($request, $id);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Delete a todo.
     *
     * @param string $id
     * @return mixed
     * @throws Exception
     */
    public function destroy(string $id): mixed
    {
        try {
            return $this->todoRepository->deleteTodo($id);
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
