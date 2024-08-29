<?php

use App\Models\{Question, User};
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\{assertDatabaseHas, assertDatabaseMissing, deleteJson};

it('should be able to delete a question', function () {
    $user = User::factory()->create();

    $question = Question::factory()->create(['user_id' => $user->id]);

    Sanctum::actingAs($user);

    deleteJson(route('questions.delete', $question))
    ->assertNoContent();

    //a pergunta não pode existir nessa tabela
    assertDatabaseMissing('questions', ['id' => $question->id]);

});

it('should allow that only the creator can delete', function () {
    $user  = User::factory()->create();
    $user2 = User::factory()->create();

    $question = Question::factory()->create(['user_id' => $user->id]);

    Sanctum::actingAs($user2);

    deleteJson(route('questions.delete', $question))
    ->assertForbidden();

    //a pergunta Deve existir nessa tabela, pois a deleção não foi permitida
    assertDatabaseHas('questions', ['id' => $question->id]);

});
