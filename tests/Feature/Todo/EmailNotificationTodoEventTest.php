<?php

namespace Tests\Feature\Todo;

use App\Events\TodoCreated;
use App\Models\Todo;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class EmailNotificationTodoEventTest extends TestCase
{
    use RefreshDatabase;

    public function testEventEmailNotificationDispatched()
    {
        Event::fake();

        $todo = Todo::factory()->create([
            'title' => 'Todo 1',
            'description' => 'Description 1',
            'image' => UploadedFile::fake()->image('Todo1.jpeg', 1024, 1080),
            'created_at' => Carbon::now()->toDateTimeString(),
        ]);

        event(new TodoCreated($todo));

        Event::assertDispatched(TodoCreated::class);
    }
}
