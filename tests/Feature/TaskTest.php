<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Task;
use App\Models\User;
use App\Jobs\UpdateStatisticsJob;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class TaskTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;


    public function test_logged_in_admin_can_see_tasks_list_page(): void
    {
        // Arrange
        $admin = User::factory()->create([
            'type' => User::ADMIN,
        ]);

        // Act
        $response = $this->actingAs($admin)->get('/tasks');

        // Assert
        $response->assertStatus(200);
        $response->assertSee('Tasks');
    }

    public function test_admin_can_add_task(): void
    {
        // Arrange
        $admin = User::factory()->create([
            'type' => User::ADMIN,
        ]);
        $user = User::factory()->create(['type' => User::USER]);

        $taskData = Task::factory()->make()->toArray();

        // Act
        $response = $this->actingAs($admin)->post('/tasks', $taskData);

        $this->assertDatabaseCount('tasks', 1);

        $response->assertStatus(302);
    }

    public function test_tasks_list_page_show_tasks_correctly()
    {
        // Arrange
        $admin = User::factory()->create([
            'type' => User::ADMIN,
        ]);
        $tasks = Task::factory()->count(3)->create();

        // Act
        $response = $this->actingAs($admin)->get('/tasks');

        // Assert
        $response->assertStatus(200);
        foreach ($tasks as $task) {
            $response->assertSee($task->title);
            $response->assertSee($task->description);
        }
    }

    public function test_update_statistics_job_when_create_new_task(): void
    {
        // Arrange
        $user = User::factory()->create(['type' => User::USER]);
        $admin = User::factory()->create(['type' => User::ADMIN]);
        Queue::fake();

        // Act
        $response = $this->actingAs($admin)->post('/tasks', Task::factory()->make()->toArray());

        // Assert
        $response->assertStatus(302);
        Queue::assertPushed(UpdateStatisticsJob::class);
    }

}
