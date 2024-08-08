<?php

use App\Models\{Question, User};
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\{assertDatabaseHas, putJson};

it('should be able to update a new question', function () {
    $user = User::factory()->create();

    $question = Question::factory()->create(['user_id' => $user->id]);

    //utilizando para logar
    Sanctum::actingAs($user);

    putJson(route('questions.update', $question), [
        'question' => 'Updating question?',
    ])->assertOk();

    assertDatabaseHas('questions', [
        'user_id'  => $user->id,
        'question' => 'Updating question?',
    ]);
});
