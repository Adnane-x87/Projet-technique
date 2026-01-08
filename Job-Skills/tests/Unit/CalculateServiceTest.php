<?php

namespace Tests\Unit;

use App\Services\CalculateService;
use PHPUnit\Framework\TestCase;

class CalculateServiceTest extends TestCase
{
    public function test_sum_of_two_numbers()
    {
        
        $service = new CalculateService();

        
        $result = $service->sum(2, 3);

        $this->assertEquals(5, $result);
    }
}
