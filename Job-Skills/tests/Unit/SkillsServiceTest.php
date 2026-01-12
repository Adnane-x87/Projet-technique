<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\SkillsService;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SkillsServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected SkillsService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new SkillsService();
    }

    public function test_it_can_get_all_skills()
    {
        $skills = $this->service->getAllSkills();

        $this->assertGreaterThan(0, $skills->count());
    }
}
