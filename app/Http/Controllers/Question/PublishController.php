<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PublishController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Question $question)
    {
        //aborta e volta 404 caso o status não seja draft
        abort_unless($question->status === 'draft',Response::HTTP_NOT_FOUND);

        //Variação
        //$question = Question::query()->whereStatus('draft')->findOrFail($question->id);
        
        $this->authorize('publish', $question);
        $question->status = 'published';
        $question->save();

        return response()->noContent();

    }
}
