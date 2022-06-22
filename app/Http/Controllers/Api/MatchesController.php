<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreScoreRequest;
use App\Http\Resources\ScheduleResource;
use App\Http\Resources\SchedulesCollection;
use App\Models\Predict;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MatchesController extends Controller
{
    public function index()
    {

        return new SchedulesCollection(Schedule::paginate(2));

    }

    public function store(StoreScoreRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->except('_token');

        $result = Predict::create([
            'match_id' => $data['match_id'],
            'user_id' => Auth::user()?->id ?? null,
            'home_team_goals' => $data['home_team_goals'],
            'away_team_goals' => $data['away_team_goals'],
        ]);

        return response()->json([
            'status' => true,
            'message' => "Mail succesfully added",
            'emails' => $result
        ], 200);
    }

    public function show($id)
    {
        $schedule = Schedule::find($id);

        if ($schedule) {
            return new ScheduleResource($schedule);
        }

        return [
            'data' => [
                'status' => 'failed',
                'error' => 404,
            ]
        ];
    }
}