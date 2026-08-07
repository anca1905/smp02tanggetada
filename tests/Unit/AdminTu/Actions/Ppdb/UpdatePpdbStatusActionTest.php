<?php

namespace Tests\Unit\AdminTu\Actions\Ppdb;

use Tests\TestCase;
use App\Models\Ppdb;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Actions\Ppdb\UpdatePpdbStatusAction;

class UpdatePpdbStatusActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_updates_ppdb_status()
    {
        $ppdb = Ppdb::factory()->create(['status_pendaftaran' => 'pending']);
        
        $action = new UpdatePpdbStatusAction();
        $action->execute($ppdb, 'Accepted');

        $this->assertEquals('Accepted', $ppdb->fresh()->status_pendaftaran);
    }
}
