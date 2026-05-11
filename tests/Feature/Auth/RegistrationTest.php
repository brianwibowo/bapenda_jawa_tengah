<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    if (DB::getDriverName() === 'sqlite') {
        $this->markTestSkipped('IndoRegion foreign key schema is not sqlite-friendly for this test.');
    }

    Schema::disableForeignKeyConstraints();
    DB::table('provinces')->insert([
        'id' => '33',
        'name' => 'JAWA TENGAH',
    ]);

    DB::table('regencies')->insert([
        'id' => '3374',
        'province_id' => '33',
        'name' => 'KOTA SEMARANG',
    ]);
    Schema::enableForeignKeyConstraints();

    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'domisili_regency_id' => '3374',
        'terms_taxpayer' => '1',
        'terms_law' => '1',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});
