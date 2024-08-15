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

describe('validation rules', function () {
    test('question:required', function () {

        $user = User::factory()->create();

        $question = Question::factory()->create(['user_id' => $user->id]);

        //utilizando para logar
        Sanctum::actingAs($user);

        putJson(route('questions.update', $question), [])->assertJsonValidationErrors([
            'question' => 'required',
        ]);
    });

    test('question::ending with question mark', function () {
        $user = User::factory()->create();

        $question = Question::factory()->create(['user_id' => $user->id]);

        //utilizando para logar
        Sanctum::actingAs($user);

        putJson(route('questions.update', $question), [
            'question' => 'Question without a question mark',
        ])->assertJsonValidationErrors([
            'question' => '?',
        ]);
    });


    test('question::min caracters should be 10 ', function () {
        $user = User::factory()->create();

        //utilizando para logar
        Sanctum::actingAs($user);

        $question = Question::factory()->create(['user_id' => $user->id]);


        putJson(route('questions.update', $question), [
            'question' => 'question?',
        ])->assertJsonValidationErrors([
            'question' => 'The question field must be at least 10 characters',
        ]);
    });

    test('question::be unique ', function () {
        $user = User::factory()->create();

        //utilizando para logar
        Sanctum::actingAs($user);

        //criando fake para dar erro
        $question = Question::factory()->create([
            'question' => 'Lorem ipusn Jeremias?',
            'user_id'  => $user->id,
            'status'   => 'draft',
        ]);
        $user = User::factory()->create();

        //utilizando para logar
        Sanctum::actingAs($user);

        putJson(route('questions.update', $question), [
            'question' => 'Lorem ipusn Jeremias?',
        ])->assertJsonValidationErrors([
            'question' => 'already been taken',
        ]);
    });
});
