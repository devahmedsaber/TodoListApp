<?php

namespace Tests\Feature\Todo;

use App\Models\Todo;
use Carbon\Carbon;
use Closure;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ExpectedResponse\DataResponse;
use Tests\ExpectedResponse\ExpectedResponse;
use Tests\TestCase;

class PaginationTodoTest extends TestCase
{
    use RefreshDatabase;

    /** @dataProvider paginationTodoDataProvider */
    public function testPaginationTodo(array $data, Closure $responseFactory, Closure $preDefinedFactory)
    {
        $this->withoutMiddleware();

        $preDefinedFactory();

        $url = route('todos.index');

        // Call Endpoints
        $response = $this->json(
            'GET',
            $url,
            $data,
        );

        /** @var ExpectedResponse $expected */
        $expected = $responseFactory($this);
        $expected->assert($response);
    }

    public static function paginationTodoDataProvider()
    {
        $commonStructure = [
            'id',
            'title',
            'description',
            'image',
            'status',
            'created_at',
        ];

        $paginatedTodosSuccessfullyResponse = function () use ($commonStructure): DataResponse {
            return new DataResponse([
                'data' => [
                    [
                        'id' => 1,
                        'title' => 'Todo 1',
                        'description' => 'Description 1',
                        'image' => 'image1.jpg',
                        'status' => 'Uncompleted',
                        'created_at' => Carbon::now()->toDateTimeString(),
                    ],
                ],
                'pagination' => static::getPaginationAttributes(1, 1, 2, 1, 1, 2),
            ], [
                'data' => [
                    $commonStructure,
                ],
            ], 'item');
        };

        return [
            'Paginated Todos Can be Listed Successfully [With Pagination Key]' => [
                [
                    'offset' => 1,
                    'page' => 1,
                ],
                $paginatedTodosSuccessfullyResponse,
                function () {
                    Todo::factory()->create([
                        'id' => 1,
                        'title' => 'Todo 1',
                        'description' => 'Description 1',
                        'image' => 'image1.jpg',
                        'status' => 'uncompleted',
                        'created_at' => Carbon::now()->toDateTimeString(),
                    ]);
                    Todo::factory()->create([
                        'id' => 2,
                        'title' => 'Todo 2',
                        'description' => 'Description 2',
                        'image' => 'image2.jpg',
                        'status' => 'uncompleted',
                        'created_at' => Carbon::now()->toDateTimeString(),
                    ]);
                },
            ],
        ];
    }
}
