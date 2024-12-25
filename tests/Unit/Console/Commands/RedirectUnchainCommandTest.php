<?php

namespace Tests\Unit\Console\Commands;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\RedirectsCleanup;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RedirectUnchainCommandTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        // Seed the database or set up your test environment here
        DB::table('redirects')->insert([
            ['from' => 'a', 'to' => 'b', 'language' => 'en'], // Chain 1
            ['from' => 'a', 'to' => 'c', 'language' => 'en'], // Duplicate
            ['from' => 'c', 'to' => 'a', 'language' => 'en'], // Chain 1
            ['from' => 'd', 'to' => 'e', 'language' => 'en'], // Three level chain 2
            ['from' => 'e', 'to' => 'f', 'language' => 'en'], // Three level chain 2
            ['from' => 'f', 'to' => 'd', 'language' => 'en'], // Three level chain 2
            ['from' => 'g', 'to' => 'h', 'language' => 'en'], // Chain 3
            ['from' => 'h', 'to' => 'g', 'language' => 'en'], // Chain 3
        ]);
    }

    public function testCommandRemovesOldRedirectChains()
    {
        Artisan::call('redirects:unchain');
        $redirect = DB::table('redirects')->where('from', 'a')->where('to', 'c')->first();
        $this->assertNull($redirect);
        $redirect = DB::table('redirects')->where('from', 'g')->where('to', 'h')->first();
        $this->assertNull($redirect);
    }

    public function testCommandRemovesKeepNewRedirectChains()
    {
        Artisan::call('redirects:unchain');
        $redirect = DB::table('redirects')->where('from', 'c')->where('to', 'a')->first();
        $this->assertNotNull($redirect);
        $redirect = DB::table('redirects')->where('from', 'h')->where('to', 'g')->first();
        $this->assertNotNull($redirect);
    }
}