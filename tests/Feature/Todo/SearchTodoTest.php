<?php

namespace Tests\Feature\Todo;

use App\Models\Todo;
use Carbon\Carbon;
use Closure;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ExpectedResponse\DataResponse;
use Tests\ExpectedResponse\ExpectedResponse;
use Tests\TestCase;

class SearchTodoTest extends TestCase
{
    use RefreshDatabase;

    /** @dataProvider searchTodoDataProvider */
    public function testSearchTodo(array $data, Closure $responseFactory, Closure $preDefinedFactory)
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

    public static function searchTodoDataProvider()
    {
        $commonStructure = [
            'id',
            'title',
            'description',
            'image',
            'status',
            'created_at',
        ];

        $searchedTodosSuccessfullyResponse = function () use ($commonStructure): DataResponse {
            return new DataResponse([
                'data' => [
                    [
                        'id' => 2,
                        'title' => 'Todo 2',
                        'description' => 'Description 2',
                        'image' => 'image2.jpg',
                        'status' => 'Uncompleted',
                        'created_at' => Carbon::now()->toDateTimeString(),
                    ],
                ],
                'pagination' => static::getPaginationAttributes(1, 1, 1, 10, 1, 1),
            ], [
                'data' => [
                    $commonStructure,
                ],
            ], 'item');
        };

        return [
            'Searched Todos Can be Listed Successfully [With Searched Key]' => [
                [
                    'search' => 'Todo 2',
                ],
                $searchedTodosSuccessfullyResponse,
                function () {
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
