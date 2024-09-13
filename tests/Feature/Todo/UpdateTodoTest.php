<?php

namespace Tests\Feature\Todo;

use App\Models\Todo;
use Carbon\Carbon;
use Closure;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\ExpectedResponse\DataResponse;
use Tests\ExpectedResponse\ResourceNotFoundResponse;
use Tests\ExpectedResponse\ValidationResponse;
use Tests\TestCase;

use function PHPUnit\Framework\assertEquals;

class UpdateTodoTest extends TestCase
{
    use RefreshDatabase;

    /** @dataProvider updateTodoDataProvider */
    public function testUpdateTodo(int $id, array $data, Closure $responseFactory, Closure $preDefinedFactory, ?Closure $extraAsserts = null)
    {
        $this->withoutMiddleware();

        $url = route('todos.update', ['id' => $id]);

        $preDefinedFactory ? $preDefinedFactory() : null;

        // Call Endpoints
        $response = $this->json(
            'POST',
            $url,
            $data,
        );

        $extraAsserts ? $extraAsserts() : null;

        /** @var ExpectedResponse $expected */
        $expected = $responseFactory($this);
        $expected->assert($response);
    }

    public static function updateTodoDataProvider()
    {
        $commonStructure = [
            'id',
            'title',
            'description',
            'image',
            'status',
            'created_at',
        ];

        $todoUpdatedSuccessfullyResponse = function () use ($commonStructure): DataResponse {
            return new DataResponse(
                [
                    'item' => [
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

        $todoCantBeUpdatedWithInvalidIDGivenResponse = function (): ResourceNotFoundResponse {
            return new ResourceNotFoundResponse();
        };

        $todoCantBeUpdatedWithMissingDataResponse = function (): ValidationResponse {
            return new ValidationResponse(422, [
                'errors' => [
                    'status' => [
                        'The status field is required.',
                    ]
                ],
            ]);
        };

        $todoCantBeUpdatedWithInvalidStatusDataResponse = function (): ValidationResponse {
            return new ValidationResponse(422, [
                'errors' => [
                    'status' => [
                        'The selected status is invalid.',
                    ]
                ],
            ]);
        };

        return [
            'Todo Can be Updated Successfully' => [
                1,
                [
                    'title' => 'Todo 1',
                    'description' => 'Description 1',
                    'image' => UploadedFile::fake()->image('Todo1.jpeg', 1024, 1080),
                    'status' => 'completed',
                    'created_at' => Carbon::now()->toDateTimeString(),
                ],
                $todoUpdatedSuccessfullyResponse,
                function () {
                    Todo::factory()->create([
                        'id' => 1,
                        'title' => 'Todo 1',
                        'description' => 'Description 1',
                        'image' => UploadedFile::fake()->image('Todo1.jpeg', 1024, 1080),
                        'created_at' => Carbon::now()->toDateTimeString(),
                    ]);
                },
                function () {
                    assertEquals(1, Todo::count());
                },
            ],
            'Todo Cant be Updated With Invalid ID Given' => [
                2,
                [
                    'title' => 'Todo 1',
                    'description' => 'Description 1',
                    'image' => UploadedFile::fake()->image('Todo1.jpeg', 1024, 1080),
                    'status' => 'completed',
                    'created_at' => Carbon::now()->toDateTimeString(),
                ],
                $todoCantBeUpdatedWithInvalidIDGivenResponse,
                function () {
                    Todo::factory()->create([
                        'id' => 1,
                        'title' => 'Todo 1',
                        'description' => 'Description 1',
                        'image' => UploadedFile::fake()->image('Todo1.jpeg', 1024, 1080),
                        'created_at' => Carbon::now()->toDateTimeString(),
                    ]);
                },
                function () {
                    assertEquals(1, Todo::count());
                },
            ],
            'Todo Cant be Updated With Missing Data [status]' => [
                1,
                [
                    'title' => 'Todo 1',
                    'description' => 'Description 1',
                    'image' => UploadedFile::fake()->image('Todo1.jpeg', 1024, 1080),
                    'created_at' => Carbon::now()->toDateTimeString(),
                ],
                $todoCantBeUpdatedWithMissingDataResponse,
                function () {
                    Todo::factory()->create([
                        'id' => 1,
                        'title' => 'Todo 1',
                        'description' => 'Description 1',
                        'image' => UploadedFile::fake()->image('Todo1.jpeg', 1024, 1080),
                        'created_at' => Carbon::now()->toDateTimeString(),
                    ]);
                },
                function () {
                    assertEquals(1, Todo::count());
                },
            ],
            'Todo Cant be Updated With Invalid Data [status]' => [
                1,
                [
                    'title' => 'Todo 1',
                    'description' => 'Description 1',
                    'image' => UploadedFile::fake()->image('Todo1.jpeg', 1024, 1080),
                    'status' => 'pending',
                    'created_at' => Carbon::now()->toDateTimeString(),
                ],
                $todoCantBeUpdatedWithInvalidStatusDataResponse,
                function () {
                    Todo::factory()->create([
                        'id' => 1,
                        'title' => 'Todo 1',
                        'description' => 'Description 1',
                        'image' => UploadedFile::fake()->image('Todo1.jpeg', 1024, 1080),
                        'created_at' => Carbon::now()->toDateTimeString(),
                    ]);
                },
                function () {
                    assertEquals(1, Todo::count());
                },
            ],
        ];
    }
}
