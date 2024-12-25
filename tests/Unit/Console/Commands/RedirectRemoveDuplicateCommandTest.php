<?php

namespace Tests\Unit\Console\Commands;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\RedirectsCleanup;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RedirectRemoveDuplicateCommandTest extends TestCase
{
    use RefreshDatabase;

    private $dbData = [
        ['from' => 'a', 'to' => 'b', 'language' => 'en'], // 
        ['from' => 'a', 'to' => 'c', 'language' => 'en'], // Duplicate and Chain 1.a
        ['from' => 'c', 'to' => 'a', 'language' => 'en'], // Chain 1.b
        ['from' => 'd', 'to' => 'e', 'language' => 'en'], // Three level chain 2
        ['from' => 'e', 'to' => 'f', 'language' => 'en'], // Three level chain 2
        ['from' => 'f', 'to' => 'd', 'language' => 'en'], // Three level chain 2
        ['from' => 'g', 'to' => 'h', 'language' => 'en'], // Chain 3.a
        ['from' => 'h', 'to' => 'g', 'language' => 'en'], // Chain 3.b
        ['from' => 'h', 'to' => 'i', 'language' => 'en'], // Chain 3.b and Duplicate
        ['from' => 'h', 'to' => 'j', 'language' => 'en'], // Chain 3.b and Duplicate
        ['from' => 'h', 'to' => 'k', 'language' => 'en'], // Chain 3.b and Duplicate
        ['from' => 'h', 'to' => 'l', 'language' => 'en'], // Chain 3.b and Duplicate
        ['from' => 'h', 'to' => 'a', 'language' => 'en'], // Chain 3.b and Duplicate
    ];

    public function setUp(): void
    {
        parent::setUp();

        // Seed the database or set up your test environment
        DB::table('redirects')->insert($this->dbData);
    }

    public function testCommandRemovesDuplicates()
    {
        Artisan::call('redirects:remove-duplicate');
        $redirect = DB::table('redirects')->where('from', 'a')->where('to', 'b')->first();
        $this->assertNull($redirect);
        $redirect = DB::table('redirects')->where('from', 'h')->where('to', 'g')->first();
        $this->assertNull($redirect);
        $redirect = DB::table('redirects')->where('from', 'h')->where('to', 'i')->first();
        $this->assertNull($redirect);
        $redirect = DB::table('redirects')->where('from', 'h')->where('to', 'j')->first();
        $this->assertNull($redirect);
        $redirect = DB::table('redirects')->where('from', 'h')->where('to', 'k')->first();
        $this->assertNull($redirect);
        $redirect = DB::table('redirects')->where('from', 'h')->where('to', 'l')->first();
        $this->assertNull($redirect);
    }
    
    public function testCommandDoNotRemovesUniq()
    {
        $redirect = DB::table('redirects')->where('from', 'a')->where('to', 'c')->first();
        $this->assertNotNull($redirect);
        $redirect = DB::table('redirects')->where('from', 'g')->where('to', 'h')->first();
        $this->assertNotNull($redirect);
        $redirect = DB::table('redirects')->where('from', 'h')->where('to', 'a')->first();
        $this->assertNotNull($redirect);
        $redirect = DB::table('redirects')->where('from', 'd')->where('to', 'e')->first();
        $this->assertNotNull($redirect);
        $redirect = DB::table('redirects')->where('from', 'e')->where('to', 'f')->first();
        $this->assertNotNull($redirect);
        $redirect = DB::table('redirects')->where('from', 'f')->where('to', 'd')->first();
        $this->assertNotNull($redirect);
    }
}