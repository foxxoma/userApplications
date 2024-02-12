<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Application;
use App\Models\User;
use App\Models\Role;

class ApplicationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_applications_store(): void
    {
        $response = $this->postJson('/api/v1/applications', [
            'name' => 'name',
            'email' => 'email',
            'message' => 'message'
        ]);
        $response->assertStatus(422);

        $response = $this->postJson('/api/v1/applications', [
            'name' => 'name',
            'email' => 'email@email.com',
            'message' => 'message'
        ]);
        $response->assertStatus(201);
        $response->assertJsonPath('data.email', 'email@email.com');
    }

    public function test_applications_index(): void
    {
        $role = Role::factory()->create(['name' => 'admin']);
        $user = User::factory()->create(['role_id' => $role->id]);

        Application::factory(10)->create();
        Application::factory(1)->create(['status' => 'Resolved']);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get('/api/v1/applications?count=10');
        $response->assertStatus(401);

        $response = $this->actingAs($user, 'sanctum')->get('/api/v1/applications?count=10');
        $response->assertStatus(200);
        $response->assertJsonCount(10, 'data');
        $response->assertJsonPath('meta.last_page', 2);
        $response->assertJsonPath('meta.current_page', 1);

        $response = $this->actingAs($user, 'sanctum')->get('/api/v1/applications?count=10&sortBy=status&operator=desc');
        $response->assertStatus(200);
        $response->assertJsonCount(10, 'data');
        $response->assertJsonPath('meta.last_page', 2);
        $response->assertJsonPath('meta.current_page', 1);
        $response->assertJsonPath('data.0.status', 'Resolved');
    }

    public function test_applications_show(): void
    {
        $role = Role::factory()->create(['name' => 'admin']);
        $user = User::factory()->create(['role_id' => $role->id]);

        $application = Application::factory()->create();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get('/api/v1/applications/'. $application->id);
        $response->assertStatus(401);

        $response = $this->actingAs($user, 'sanctum')->get('/api/v1/applications/'. $application->id);
        $response->assertStatus(200);
        $response->assertJsonPath('data.id', $application->id);
    }


    public function test_applications_comment(): void
    {
        $role = Role::factory()->create(['name' => 'admin']);
        $role2 = Role::factory()->create(['name' => 'noAdmin']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $user2 = User::factory()->create(['role_id' => $role2->id]);

        $application = Application::factory()->create();

        $response = $this->putJson('/api/v1/applications/'.$application->id, [
            'comment' => 'test comment'
        ]);
        $response->assertStatus(401);

        $response = $this->actingAs($user2, 'sanctum')->putJson('/api/v1/applications/'.$application->id, [
            'comment' => 'test comment'
        ]);
        $response->assertStatus(403);

        $response = $this->actingAs($user, 'sanctum')->putJson('/api/v1/applications/'.$application->id, [
            'comment' => 'test comment'
        ]);
        $response->assertStatus(200);
        $response->assertJsonPath('data.status', 'Resolved');
    }

}
