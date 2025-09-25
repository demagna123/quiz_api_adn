<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Question;
use App\Models\UserAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $answers = Answer::all();
            return response()->json(["message" => "succès", "answers" => $answers], 200);
        } catch (\Throwable $th) {
            return $th;
            return response()->json(["message" => "Error",], 400);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            //code...
            $data = $request->validate([
                'question_id' => 'required|exists:questions,id',
                'description' => 'required|string',
            ]);

            $response = Answer::create($request->all());
            return response()->json(["message" => "succès", "reponse" => $response], 200);
        } catch (\Throwable $th) {
            return $th;
            return response()->json(["message" => "Error",], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            //code...
            $data = $request->validate([
                'question_id' => 'required|exists:questions,id',
                'description' => 'required|string',
            ]);

            $response = Answer::find($id)->update($request->all());
            return response()->json(["message" => "succès", "reponse" => $response], 200);
        } catch (\Throwable $th) {
            return $th;
            return response()->json(["message" => "Error",], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $result = Answer::find($id)->delete();
            return response()->json(["message" => "succès", "reponse" => $result], 200);
        } catch (\Throwable $th) {
            return $th;
            return response()->json(["message" => "Error",], 400);
        }
    }
    public function submitAnswer(Request $request)
    {
        try {
            // Validation du tableau principal
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'answers' => 'required|array|min:1',
                'answers.*.question_id' => 'required|exists:questions,id',
                'answers.*.answer_id' => 'required|exists:answers,id',
            ]);

            $userId = $request->user_id;
            $answersData = $request->answers;

            $totalPoints = 0;

            foreach ($answersData as $data) {
                $question = Question::findOrFail($data['question_id']);
                $answer = Answer::where('id', $data['answer_id'])
                    ->where('question_id', $data['question_id'])
                    ->firstOrFail();

                $isCorrect = $answer->is_correct;

                // Sauvegarde ou mise à jour
                UserAnswer::updateOrCreate(
                    [
                        'user_id' => $userId,
                        'question_id' => $data['question_id'],
                    ],
                    [
                        'answer_id' => $data['answer_id'],
                        'is_correct' => $isCorrect,
                    ]
                );

                if ($isCorrect) {
                    $totalPoints += $question->marke;
                }
            }

            // Recalcule des scores (mêmes requêtes que toi)
            $pointsByTheme = UserAnswer::select(
                'level_themes.name as level_theme',
                DB::raw('SUM(questions.marke) as total_points')
            )
                ->where('user_answers.user_id', $userId)
                ->where('user_answers.is_correct', true)
                ->join('questions', 'user_answers.question_id', '=', 'questions.id')
                ->join('level_themes', 'questions.level_theme_id', '=', 'level_themes.id')
                ->groupBy('level_themes.name')
                ->get();

            $pointsByLevel = UserAnswer::select(
                'levels.name as level',
                DB::raw('SUM(questions.marke) as total_points')
            )
                ->where('user_answers.user_id', $userId)
                ->where('user_answers.is_correct', true)
                ->join('questions', 'user_answers.question_id', '=', 'questions.id')
                ->join('level_themes', 'questions.level_theme_id', '=', 'level_themes.id')
                ->join('levels', 'level_themes.level_id', '=', 'levels.id')
                ->groupBy('levels.name')
                ->get();

            return response()->json([
                'success' => true,
                'total_points_gained' => $totalPoints,
                'points_by_theme' => $pointsByTheme,
                'points_by_level' => $pointsByLevel,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur côté serveur',
                'error' => $th->getMessage(),
                'line' => $th->getLine(),
                'file' => $th->getFile()
            ], 400);
        }
    }

    public function submitAnswers(Request $request)
{
    try {
        $data = $request->validate([
            'answers' => 'required|array|min:1',
            'answers.*.user_id' => 'required|exists:users,id',
            'answers.*.question_id' => 'required|exists:questions,id',
            'answers.*.answer_id' => 'required|exists:answers,id',
        ]);

        foreach ($data['answers'] as $answerData) {
            $answer = Answer::where('id', $answerData['answer_id'])
                ->where('question_id', $answerData['question_id'])
                ->firstOrFail();

            $isCorrect = $answer->is_correct;

            UserAnswer::updateOrCreate(
                [
                    'user_id' => $answerData['user_id'],
                    'question_id' => $answerData['question_id'],
                ],
                [
                    'answer_id' => $answerData['answer_id'],
                    'is_correct' => $isCorrect,
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Réponses enregistrées avec succès.',
        ]);
    } catch (\Throwable $th) {
        return response()->json([
            'success' => false,
            'message' => 'Erreur : ' . $th->getMessage(),
        ], 400);
    }
}

}
