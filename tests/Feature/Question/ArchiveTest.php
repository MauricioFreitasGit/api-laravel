<?php

use App\Models\{Question, User};
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\{assertDatabaseHas, assertDatabaseMissing, assertNotSoftDeleted, assertSoftDeleted, deleteJson};

it('should be able to archive a question', function () {
    $user = User::factory()->create();

    $question = Question::factory()->create(['user_id' => $user->id]);

    Sanctum::actingAs($user);

    deleteJson(route('questions.archive', $question))
    ->assertNoContent();

    //Verifica se o softdeleted tem dados
    assertSoftDeleted('questions', ['id' => $question->id]);

});

it('should allow that only the creator can archive', function () {
    $user  = User::factory()->create();
    $user2 = User::factory()->create();

    $question = Question::factory()->create(['user_id' => $user->id]);

    Sanctum::actingAs($user2);

    deleteJson(route('questions.archive', $question))
    ->assertForbidden();

     //Verifica se o softdeleted não tem dados
    assertNotSoftDeleted('questions', ['id' => $question->id]);

});
