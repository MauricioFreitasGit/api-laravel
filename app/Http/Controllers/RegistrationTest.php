<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;
use function PHPUnit\Framework\assertTrue;

it('should be able to register in the application', function () {
    postJson(route('register'), [
        'name' => 'Mauricio Freitas',
        'email' => 'mauricio.freitas@gmail.com',
        'password' => '123123',
    ])->assertSessionHasNoErrors();

    assertDatabaseHas('users', [
        'name' => 'Mauricio Freitas',
        'email' => 'mauricio.freitas@gmail.com',
    ]);
    $user = User::whereEmail('mauricio.freitas@gmail.com')->first();


    assertTrue(Hash::check('123123', $user->password));
});
