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
        //criando fake para dar erro
        Question::factory()->create([
            'question' => 'Lorem ipusn Jeremias?',
            'user_id'  => $user->id,
            'status'   => 'draft',
        ]);

        $question = Question::factory()->create([
            'user_id' => $user->id,
        ]);

        //utilizando para logar
        Sanctum::actingAs($user);

        putJson(route('questions.update', $question), [
            'question' => 'Lorem ipusn Jeremias?',
        ])->assertJsonValidationErrors([
            'question' => 'already been taken',
        ]);
    });

    test('question::be unique only if id is different', function () {
        $user = User::factory()->create();

        //criando fake para dar erro
        $question = Question::factory()->create([
            'question' => 'Lorem ipusn Jeremias?',
            'user_id'  => $user->id,
            'status'   => 'draft',
        ]);

        //utilizando para logar
        Sanctum::actingAs($user);

        putJson(route('questions.update', $question), [
            'question' => 'Lorem ipusn Jeremias?',
        ])->assertOk();
    });
});

describe('security', function () {
    test('only the person who create the question can update the same question', function () {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $question = Question::factory()->create(['user_id' => $user1]);

        Sanctum::actingAs($user2);

        putJson(route('questions.update',$question),[
            'question' => 'Lorem ipusn Jeremias?',
        ])->assertForbidden();

        assertDatabaseHas('questions',[
            'id'=>$question->id,
            'question'=>$question->question
        ]);
    });
});
