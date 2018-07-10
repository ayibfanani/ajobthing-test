<?php

namespace Tests\Feature;

use App\Jobs\Job;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\BaseTest;

class JobsTest extends BaseTest
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->withServerVariables([
            'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest',
        ]);

        $this->_seedRoles();
        $this->_seedUsers();
    }

    public function test_client_can_fetch_all_posted_job()
    {
        $client = $this->client;
        $jobs = factory(Job::class, 10)->create([
            'user_id' => $client->id
        ]);

        $this->get("/api/jobs", [
            'Authorization' => "Bearer {$client->api_token}"
        ])
            ->assertJson([])
            ->assertStatus(200);
    }

    public function test_client_can_add_a_new_job()
    {
        $client = $this->client;

        $data = [
            'title' => 'Job #1',
            'body' => 'Hi, we need some PHP Developer to build our website application!',
            'budget' => 30000000,
            'status' => 1
        ];

        $this->post("/api/jobs", $data, [
            'Authorization' => "Bearer {$client->api_token}"
        ])
            ->assertStatus(200);

        $data['user_id'] = $this->client->id;
        $this->assertDatabaseHas('jobs', $data);
    }

    public function test_freelancer_can_apply_to_a_job()
    {
        $freelancer = $this->freelancer;
        $points = $freelancer->points;
        $jobs = factory(Job::class, 10)->create([
            'user_id' => $freelancer->id
        ]);
        $job = $jobs->shuffle()->first();

        $data = [
            'job_id' => $job->id,
            'fr_budget' => 1000,
            'completed_at' => now()->addDays(3)->toDateTimeString(),
            'messages' => 'Test'
        ];

        $this->post("/api/jobs/{$job->id}/apply", $data, [
            'Authorization' => "Bearer {$freelancer->api_token}"
        ])
            ->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'id' => $freelancer->id,
            'points' => $freelancer->points - 2
        ]);

        $this->assertDatabaseHas('job_user', [
            'job_id' => $job->id,
            'user_id' => $freelancer->id,
            'fr_budget' => $data['fr_budget'],
            'completed_at' => $data['completed_at'],
            'messages' => $data['messages']
        ]);
    }

    public function test_client_can_accept_submitted_job()
    {
        $client = $this->client;
        $freelancer = $this->freelancer;
        $points = $freelancer->points;
        $jobs = factory(Job::class, 10)->create([
            'user_id' => $client->id,
            'status' => 1
        ]);

        $jobs->each(function ($job) use ($freelancer) {
            $job->applicants()->attach($freelancer->id, [
                'status' => 'pending',
                'fr_budget' => 1000,
                'completed_at' => now()->addDays(3)->toDateTimeString(),
                'messages' => 'test'
            ]);
        });

        $job = $freelancer->jobs->shuffle()->first();
        $data = [
            'user_id' => $freelancer->id,
        ];

        $this->post("/api/jobs/{$job->id}/accept", $data, [
            'Authorization' => "Bearer {$client->api_token}"
        ])
            ->assertStatus(200);

        $this->assertDatabaseHas('job_user', [
                'job_id' => $job->id,
                'user_id' => $freelancer->id,
                'status' => 'completed'
            ]);
    }

    public function test_client_can_mark_as_complete_submitted_job()
    {
        $client = $this->client;
        $freelancer = $this->freelancer;
        $points = $freelancer->points;
        $jobs = factory(Job::class, 10)->create([
            'user_id' => $client->id,
            'status' => 1
        ]);

        $jobs->each(function ($job) use ($freelancer) {
            $job->applicants()->attach($freelancer->id, [
                'status' => 'accepted',
                'fr_budget' => 1000,
                'completed_at' => now()->addDays(3)->toDateTimeString(),
                'messages' => 'test'
            ]);
        });

        $job = $freelancer->jobs->shuffle()->first();
        $data = [
            'user_id' => $freelancer->id,
        ];

        $this->post("/api/jobs/{$job->id}/markcomplete", $data, [
            'Authorization' => "Bearer {$client->api_token}"
        ])
            ->assertStatus(200);
    }
}
