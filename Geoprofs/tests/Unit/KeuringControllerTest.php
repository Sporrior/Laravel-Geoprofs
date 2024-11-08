<?php

namespace Tests\Unit;

use Tests\TestCase;
use Mockery as m;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use App\Http\Controllers\DashboardController;

class KeuringControllerTest extends TestCase
{
    use m\Adapter\Phpunit\MockeryPHPUnitIntegration;

    protected function tearDown(): void
    {
        m::close();
        parent::tearDown();
    }

    public function testIndex()
    {
        // Arrange

        // Mock the Auth facade to return a user with 'verlof_dagen'
        $userMock = new \stdClass();
        $userMock->verlof_dagen = 10;

        Auth::shouldReceive('user')->once()->andReturn($userMock);

        // Create mocked Verlofaanvragen instances with different statuses
        $verlofaanvraag1 = new \stdClass();
        $verlofaanvraag1->status = null;

        $verlofaanvraag2 = new \stdClass();
        $verlofaanvraag2->status = 1;

        $verlofaanvraag3 = new \stdClass();
        $verlofaanvraag3->status = 0;

        // Collect the mocked Verlofaanvragen
        $verlofaanvragenCollection = new Collection([
            $verlofaanvraag1,
            $verlofaanvraag2,
            $verlofaanvraag3,
        ]);

        // Mock the static method Verlofaanvragen::with('user')->get()
        m::mock('alias:App\Models\Verlofaanvragen')
            ->shouldReceive('with')
            ->with('user')
            ->andReturnSelf()
            ->shouldReceive('get')
            ->andReturn($verlofaanvragenCollection);

        // Act
        $controller = new DashboardController();
        $response = $controller->index();

        // Assert
        $this->assertInstanceOf(View::class, $response);
        $viewData = $response->getData();

        // Assert that the view data contains 'verlofaanvragen' and 'vakantiedagen'
        $this->assertArrayHasKey('verlofaanvragen', $viewData);
        $this->assertArrayHasKey('vakantiedagen', $viewData);

        // Assert that the 'vakantiedagen' matches the user's 'verlof_dagen'
        $this->assertEquals($userMock->verlof_dagen, $viewData['vakantiedagen']);

        // Assert that 'verlofaanvragen' matches our mocked collection
        $this->assertEquals($verlofaanvragenCollection, $viewData['verlofaanvragen']);

        // Check that the 'status_label' is correctly set for each aanvraag
        $this->assertEquals('Afwachting', $verlofaanvraag1->status_label);
        $this->assertEquals('Goedgekeurd', $verlofaanvraag2->status_label);
        $this->assertEquals('Geweigerd', $verlofaanvraag3->status_label);
    }
}
