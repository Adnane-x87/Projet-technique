<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Emploi;
use App\Models\Skills;
use App\Models\User;
use App\Services\EmploiService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmploiServiceTest extends TestCase
{
    use RefreshDatabase;

    protected EmploiService $emploiService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->emploiService = new EmploiService();
    }

    public function test_get_all_jobs()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        Emploi::create([
            'title' => 'Job 1',
            'description' => 'Desc 1',
            'company' => 'Company 1',
            'user_id' => $user->id,
        ]);

        $jobs = $this->emploiService->getAllJobs();

        $this->assertCount(1, $jobs);
        $this->assertEquals('Job 1', $jobs->first()->title);
    }

    public function test_get_job_by_id()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $job = Emploi::create([
            'title' => 'Job 1',
            'description' => 'Desc 1',
            'company' => 'Company 1',
            'user_id' => $user->id,
        ]);

        $foundJob = $this->emploiService->getJobId($job->id);

        $this->assertEquals($job->id, $foundJob->id);
    }

    public function test_create_job()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $this->actingAs($user);

        $skill = Skills::create(['name' => 'PHP']);

        $data = [
            'title' => 'New Job',
            'description' => 'New Desc',
            'company' => 'New Company',
            'skills' => [$skill->id]
        ];

        $job = $this->emploiService->createJob($data);

        $this->assertDatabaseHas('emplois', [
            'title' => 'New Job',
            'company' => 'New Company',
        ]);

        $this->assertCount(1, $job->skills);
        $this->assertEquals('PHP', $job->skills->first()->name);
    }

    public function test_update_job()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $job = Emploi::create([
            'title' => 'Old Title',
            'description' => 'Old Desc',
            'company' => 'Old Company',
            'user_id' => $user->id,
        ]);

        $skill = Skills::create(['name' => 'Laravel']);

        $data = [
            'title' => 'Updated Title',
            'description' => 'Updated Desc',
            'company' => 'Updated Company',
            'skills' => [$skill->id]
        ];

        $updatedJob = $this->emploiService->updateJob($job->id, $data);

        $this->assertEquals('Updated Title', $updatedJob->title);
        $this->assertCount(1, $updatedJob->skills);
        $this->assertEquals('Laravel', $updatedJob->skills->first()->name);
    }

    public function test_delete_job()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $job = Emploi::create([
            'title' => 'To Delete',
            'description' => 'Desc',
            'company' => 'Company',
            'user_id' => $user->id,
        ]);

        $this->emploiService->deleteJob($job->id);

        $this->assertDatabaseMissing('emplois', ['id' => $job->id]);
    }

    public function test_search_jobs()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        Emploi::create([
            'title' => 'Developer',
            'description' => 'Desc',
            'company' => 'Company',
            'user_id' => $user->id,
        ]);

        Emploi::create([
            'title' => 'Designer',
            'description' => 'Desc',
            'company' => 'Company',
            'user_id' => $user->id,
        ]);

        $results = $this->emploiService->searchJobs('Dev');

        $this->assertCount(1, $results);
        $this->assertEquals('Developer', $results->first()->title);
    }

    public function test_filter_by_skill()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $skill1 = Skills::create(['name' => 'PHP']);
        $skill2 = Skills::create(['name' => 'JS']);

        $job1 = Emploi::create([
            'title' => 'PHP Job',
            'description' => 'Desc',
            'company' => 'Company',
            'user_id' => $user->id,
        ]);
        $job1->skills()->attach($skill1->id);

        $job2 = Emploi::create([
            'title' => 'JS Job',
            'description' => 'Desc',
            'company' => 'Company',
            'user_id' => $user->id,
        ]);
        $job2->skills()->attach($skill2->id);

        $results = $this->emploiService->filterBySkill($skill1->id);

        $this->assertCount(1, $results);
        $this->assertEquals('PHP Job', $results->first()->title);
    }
}
