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

use function PHPUnit\Framework\assertEquals;

class DeleteTodoTest extends TestCase
{
    use RefreshDatabase;

    /** @dataProvider deleteTodoDataProvider */
    public function testDeleteTodo(int $id, Closure $preDefinedFactory, Closure $responseFactory, ?Closure $extraAsserts = null)
    {
        $this->withoutMiddleware();

        $url = route('todos.destroy', ['id' => $id]);

        $preDefinedFactory();

        // Call Endpoints
        $response = $this->json(
            'DELETE',
            $url,
        );

        $extraAsserts ? $extraAsserts() : null;

        /** @var ExpectedResponse $expected */
        $expected = $responseFactory($this);
        $expected->assert($response);
    }

    public static function deleteTodoDataProvider()
    {
        $todoDeletedSuccessfullyResponse = function (): DataResponse {
            return new DataResponse(
                [
                    'item' => [],
                ],
                [],
                'item'
            );
        };

        $todoCantBeDeletedWithInvalidIDGivenResponse = function (): ResourceNotFoundResponse {
            return new ResourceNotFoundResponse();
        };

        return [
            'Todo Can be Deleted Successfully' => [
                1,
                function () {
                    Todo::factory()->create([
                        'id' => 1,
                        'title' => 'Test Todo',
                        'description' => 'Test Description',
                        'image' => UploadedFile::fake()->image('Todo1.jpeg', 1024, 1080),
                        'created_at' => Carbon::now()->toDateTimeString(),
                    ]);
                },
                $todoDeletedSuccessfullyResponse,
                function () {
                    assertEquals(0, Todo::count());
                },
            ],
            'Todo Cant be Deleted With Invalid ID Given' => [
                2,
                function () {
                    Todo::factory()->create([
                        'id' => 1,
                        'title' => 'Test Todo',
                        'description' => 'Test Description',
                        'image' => UploadedFile::fake()->image('Todo1.jpeg', 1024, 1080),
                        'created_at' => Carbon::now()->toDateTimeString(),
                    ]);
                },
                $todoCantBeDeletedWithInvalidIDGivenResponse,
                function () {
                    assertEquals(1, Todo::count());
                },
            ],
        ];
    }
}
