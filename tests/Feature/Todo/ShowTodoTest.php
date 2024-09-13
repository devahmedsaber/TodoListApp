<?php

namespace Tests\Feature\Todo;

use App\Models\Todo;
use Carbon\Carbon;
use Closure;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\ExpectedResponse\DataResponse;
use Tests\ExpectedResponse\ResourceNotFoundResponse;
use Tests\TestCase;

class ShowTodoTest extends TestCase
{
    use RefreshDatabase;

    /** @dataProvider showTodoDataProvider */
    public function testShowOption(int $id, Closure $responseFactory, Closure $preDefinedFactory)
    {
        $this->withoutMiddleware();

        $preDefinedFactory();

        $url = route('todos.show', ['id' => $id]);

        // Call Endpoints
        $response = $this->json(
            'GET',
            $url,
        );

        /** @var ExpectedResponse $expected */
        $expected = $responseFactory($this);
        $expected->assert($response);
    }

    public static function showTodoDataProvider()
    {
        $commonStructure = [
            'id',
            'title',
            'description',
            'image',
            'status',
            'created_at',
        ];

        $todosSuccessfullyResponse = function () use ($commonStructure): DataResponse {
            return new DataResponse(
                [
                    [
                        'id' => 1,
                        'title' => 'Todo 1',
                        'description' => 'Description 1',
                        'image' => UploadedFile::fake()->image('Todo1.jpeg', 1024, 1080),
                        'status' => 'Uncompleted',
                        'created_at' => Carbon::now()->toDateTimeString(),
                    ],
                ],
                $commonStructure,
                'item'
            );
        };

        $notFoundTodoSuccessfullyResponse = function (): ResourceNotFoundResponse {
            return new ResourceNotFoundResponse();
        };

        return [
            'Todo Can be Showed Successfully' => [
                1,
                $todosSuccessfullyResponse,
                function () {
                    Todo::factory()->create([
                        'id' => 1,
                        'title' => 'Todo 1',
                        'description' => 'Description 1',
                        'image' => UploadedFile::fake()->image('Todo1.jpeg', 1024, 1080),
                        'created_at' => Carbon::now()->toDateTimeString(),
                    ]);
                },
            ],
            'Todo Not Found When Invalid ID is Sent' => [
                2,
                $notFoundTodoSuccessfullyResponse,
                function () {
                    Todo::factory()->create([
                        'id' => 1,
                        'title' => 'Todo 1',
                        'description' => 'Description 1',
                        'image' => UploadedFile::fake()->image('Todo1.jpeg', 1024, 1080),
                        'created_at' => Carbon::now()->toDateTimeString(),
                    ]);
                },
            ],
        ];
    }
}
