<?php

declare(strict_types=1);

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Http\Requests\Question\StoreRequest;
use App\Http\Resources\QuestionResource;
use App\Models\Question;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(StoreRequest $request)
    {

        $question = Question::query()->create([
            'user_id'  => auth()->user()->id,
            'status'   => 'draft',
            'question' => $request->question,
        ]);

        return QuestionResource::make($question);
    }
}
