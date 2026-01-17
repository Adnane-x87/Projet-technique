<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Emploi;
use App\Models\Skills;
use App\Models\User;
use App\Services\EmploiService;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EmploiServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected EmploiService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new EmploiService();
    }

    public function test_it_can_get_all_jobs()
    {
        $result = $this->service->getJobs();

        $this->assertGreaterThan(0, $result->count());
    }

    public function test_it_can_get_job_by_id()
    {
        $job = Emploi::first();

        $result = $this->service->getJobId($job->id);

        $this->assertEquals($job->id, $result->id);
        $this->assertEquals($job->title, $result->title);
    }

    public function test_it_can_search_jobs_by_title_or_company()
    {
        $job = Emploi::first();
        $searchTerm = substr($job->title, 0, 5);

        $result = $this->service->getJobs(['search' => $searchTerm]);

        $this->assertGreaterThan(0, $result->count());

        $firstJob = $result->first();
        $this->assertTrue(str_contains($firstJob->title, $searchTerm) || str_contains($firstJob->company, $searchTerm));
    }

    public function test_it_can_filter_jobs_by_skill()
    {
        $skill = Skills::whereHas('emplois')->first();

        $result = $this->service->getJobs(['skill' => $skill->id]);

        $this->assertGreaterThan(0, $result->count());

        foreach ($result as $job) {
            $jobSkills = $job->skills->pluck('id')->toArray();
            $this->assertContains($skill->id, $jobSkills);
        }
    }

    public function test_it_can_create_a_job_with_skills()
    {
        $skills = Skills::take(2)->get();
        $user = User::first();

        $data = [
            'title' => 'New Job Test',
            'description' => 'Some description',
            'company' => 'Test Company',
            'skills' => $skills->pluck('id')->toArray(),
        ];

        $job = $this->service->createJob($data);

        $this->assertDatabaseHas('emplois', [
            'id' => $job->id,
            'title' => 'New Job Test',
        ]);

        $this->assertCount(2, $job->skills);
    }

    public function test_it_can_update_a_job()
    {
        $job = Emploi::first();

        $updatedData = [
            'title' => 'Updated Job Test',
            'description' => 'Updated description',
            'company' => 'Updated Company',
        ];

        $this->service->updateJob($job->id, $updatedData);

        $this->assertDatabaseHas('emplois', [
            'id' => $job->id,
            'title' => 'Updated Job Test',
            'description' => 'Updated description',
            'company' => 'Updated Company',
        ]);
    }

    public function test_it_can_delete_a_job()
    {
        $job = Emploi::first();

        $this->service->deleteJob($job->id);

        $this->assertDatabaseMissing('emplois', [
            'id' => $job->id,
        ]);
    }
}
