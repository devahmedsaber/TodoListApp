<?php

namespace Tests\Feature\Todo;

use App\Models\Todo;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\ExpectedResponse\DataResponse;
use Tests\ExpectedResponse\ValidationResponse;
use Tests\TestCase;
use Closure;

use function PHPUnit\Framework\assertEquals;

class StoreTodoTest extends TestCase
{
    use RefreshDatabase;

    /** @dataProvider storeTodoDataProvider */
    public function testStoreTodo(array $data, Closure $responseFactory, ?Closure $extraAsserts = null)
    {
        $this->withoutMiddleware();

        $url = route('todos.store');

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

    public static function storeTodoDataProvider()
    {
        $commonStructure = [
            'id',
            'title',
            'description',
            'image',
            'status',
            'created_at',
        ];

        $todoStoredSuccessfullyResponse = function () use ($commonStructure): DataResponse {
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

        $todoCantBeStoredWithMissingDataResponse = function (): ValidationResponse {
            return new ValidationResponse(422, [
                'errors' => [
                    'title' => [
                        'The title field is required.',
                    ],
                ],
            ]);
        };

        return [
            'Todo Can be Stored Successfully' => [
                [
                    'title' => 'Todo 1',
                    'description' => 'Description 1',
                    'image' => UploadedFile::fake()->image('Todo1.jpeg', 1024, 1080),
                    'status' => 'Uncompleted',
                    'created_at' => Carbon::now()->toDateTimeString(),
                ],
                $todoStoredSuccessfullyResponse,
                function () {
                    assertEquals(1, Todo::count());
                },
            ],
            'Todo Cant be Stored With Missing Data [title]' => [
                [
                    'description' => 'Description 1',
                    'image' => UploadedFile::fake()->image('Todo1.jpeg', 1024, 1080),
                    'status' => 'Uncompleted',
                    'created_at' => Carbon::now()->toDateTimeString(),
                ],
                $todoCantBeStoredWithMissingDataResponse,
                function () {
                    assertEquals(0, Todo::count());
                },
            ],
        ];
    }
}
