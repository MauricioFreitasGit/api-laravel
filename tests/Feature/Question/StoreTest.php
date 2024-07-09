<?php

declare(strict_types=1);

namespace Tests\Feature\Question;

use App\Models\User;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\{assertDatabaseHas, postJson};

it('should be able to store a new question', function () {
    $user = User::factory()->create();

    //utilizando para logar
    Sanctum::actingAs($user);

    postJson(route('questions.store', [
        'question' => 'Lorem ipusn Jeremias?',
    ]))->assertSuccessful();

    assertDatabaseHas('questions', [
        'user_id'  => $user->id,
        'question' => 'Lorem ipusn Jeremias?',
    ]);
});

test('after creating a new question, I need to make sure that it creates on _draft_ status', function () {
    $user = User::factory()->create();

    //utilizando para logar
    Sanctum::actingAs($user);

    postJson(route('questions.store', [
        'question' => 'Lorem ipusn Jeremias?',
    ]))->assertSuccessful();

    assertDatabaseHas('questions', [
        'user_id'  => $user->id,
        'status'   => 'draft',
        'question' => 'Lorem ipusn Jeremias?',
    ]);
});

describe('validation rules', function () {
    test('question required', function () {
        $user = User::factory()->create();

        //utilizando para logar
        Sanctum::actingAs($user);
    
        postJson(route('questions.store',[]))->assertJsonValidationErrors([
            'question'=>'required'
        ]);
    });
});
