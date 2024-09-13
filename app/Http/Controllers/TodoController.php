<?php

namespace App\Http\Controllers;

use App\Http\Requests\Todo\CreateTodoRequest;
use App\Http\Requests\Todo\UpdateTodoRequest;
use App\Http\Requests\Todo\UpdateTodoStatusRequest;
use App\Http\Resources\TodoCollectionResource;
use App\Http\Resources\TodoResource;
use App\Services\TodoService;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    use ApiResponse;

    /**
     * Todos service instance.
     *
     * @var TodoService $todoService
     */
    protected TodoService $todoService;

    /**
     * Todos controller constructor.
     *
     * @return void
     */
    public function __construct()
    {
        // Protect all routes.
        $this->middleware('auth:api');
        $this->todoService = new TodoService();
    }

    /**
     * Retrieve a list of todos based on the request.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function index(Request $request): JsonResponse
    {
        $todos = $this->todoService->index($request);

        return $this->success(__('todos.fetched'), new TodoCollectionResource($todos));
    }

    /**
     * Retrieve a todo based on id.
     *
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    public function show(string $id): JsonResponse
    {
        $todo = $this->todoService->show($id);

        return $this->success(__('todos.fetched'), new TodoResource($todo));
    }

    /**
     * Create a new todo.
     *
     * @param CreateTodoRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function store(CreateTodoRequest $request): JsonResponse
    {
        $todo = $this->todoService->store($request);

        return $this->success(__('todos.created'), new TodoResource($todo));
    }

    /**
     * Update a todo.
     *
     * @param UpdateTodoRequest $request
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    public function update(UpdateTodoRequest $request, string $id): JsonResponse
    {
        $todo = $this->todoService->update($request, $id);

        return $this->success(__('todos.updated'), new TodoResource($todo));
    }

    /**
     * Update a todo status.
     *
     * @param UpdateTodoStatusRequest $request
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    public function updateStatus(UpdateTodoStatusRequest $request, string $id): JsonResponse
    {
        $todo = $this->todoService->updateStatus($request, $id);

        return $this->success(__('todos.status_updated'), new TodoResource($todo));
    }

    /**
     * Delete a todo.
     *
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(string $id): JsonResponse
    {
        $this->todoService->destroy($id);

        return $this->success(__('todos.deleted'));
    }
}
