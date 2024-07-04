<?php

declare(strict_types = 1);

namespace Tests\Feature\Question;

use App\Models\User;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;

it('should be able to store a new question', function () {
    $user = User::factory()->create();

    //utilizando para logar
    Sanctum::actingAs($user);

    postJson(route('questions.store', [
        'question' => 'Lorem ipusn Jeremias?',
    ]))->assertSuccessful();

    assertDatabaseHas('questions', [
        'question' => 'Lorem ipusn Jeremias?s',
    ]);
});
