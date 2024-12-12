<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\VerlofaanvragenExport;
use App\Models\User;

class VerlofDataControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the index method.
     */
    public function testIndexMethod()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('verlofdata.index'));

        $response->assertStatus(200);
        $response->assertViewIs('verlofdata');
    }

    /**
     * Test the export method.
     */
    public function testExportMethod()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Excel::fake();

        $response = $this->get(route('verlofdata.export'));

        $response->assertStatus(200);

        Excel::assertDownloaded('verlofaanvragen.xlsx', function (VerlofaanvragenExport $export) {
            return true;
        });
    }
}
