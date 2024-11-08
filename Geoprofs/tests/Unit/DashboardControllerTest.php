<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use App\Models\User;
use App\Models\Verlofaanvragen;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndexAssignsCorrectStatusLabel()
    {
        $user = User::factory()->make(['id' => 1, 'verlof_dagen' => 20]);
        Auth::shouldReceive('user')->andReturn($user);

        $verlofaanvragenData = collect([
            (object) ['status' => null, 'status_label' => null, 'user_id' => 1],
            (object) ['status' => 1, 'status_label' => null, 'user_id' => 1],
            (object) ['status' => 0, 'status_label' => null, 'user_id' => 1],
        ]);

        $verlofaanvragenMock = Mockery::mock('alias:' . Verlofaanvragen::class);
        $verlofaanvragenMock->shouldReceive('with')
            ->with('user')
            ->andReturnSelf();
        $verlofaanvragenMock->shouldReceive('get')
            ->andReturn($verlofaanvragenData);

        $this->app->instance(Verlofaanvragen::class, $verlofaanvragenMock);

        $controller = new DashboardController();
        $response = $controller->index();

        $this->assertEquals('Afwachting', $verlofaanvragenData[0]->status_label);
        $this->assertEquals('Goedgekeurd', $verlofaanvragenData[1]->status_label);
        $this->assertEquals('Geweigerd', $verlofaanvragenData[2]->status_label);

        $viewData = $response->getData();
        $this->assertEquals(20, $viewData['vakantiedagen']);
    }
}
