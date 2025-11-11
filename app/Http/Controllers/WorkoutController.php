<?php

namespace App\Http\Controllers;

use App\Facades\ExerciseDBSvc;
use App\Facades\WorkoutPlanSvc;
use App\Facades\WorkoutProgressionSvc;
use App\Facades\WorkoutSvc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkoutController extends Controller
{
    public function create(int $id)
    {
        $plan = WorkoutPlanSvc::getWorkoutPlanById($id);
        $plan = ExerciseDBSvc::getExercisesForPlan($plan);
        return view('workout.create', compact('plan'));
    }

    public function store(Request $request)
    {
        WorkoutSvc::createWorkoutFromRequest($request);
    }

    public function show(int $planId)
    {
        $workouts = WorkoutSvc::getWorkoutWithDetailsByPlanId($planId);

        if (empty($workouts)) {
            $plan = WorkoutPlanSvc::getWorkoutPlanById($planId);
            $workouts = ['name' => $plan['name']];
        }

        return view('workout.template-history', ['data' => $workouts]);
    }

    public function index()
    {
        $userId = Auth::id();
        $workouts = WorkoutSvc::getWorkoutsWithDetailsByUserId($userId);
        return view('workout.history', ['data' => $workouts]);
    }

    public function progression(Request $request, string $id)
    {
        $workouts = WorkoutSvc::getWorkoutWithDetailsByPlanId($id);

        if (!$workouts || count($workouts['workouts']) < 2) {
            $plan = WorkoutPlanSvc::getWorkoutPlanById($id);
            return view('workout.progression', [
                'plan' => $plan['name'],
                'id' => $id,
                'error' => 'You have to complete at least 2 of this workout to check progression!'
            ]);
        }

        $chartType = $request->query("chart") ?? 'max-lifts';
        $chart = WorkoutProgressionSvc::getProgressionChart($id, $chartType, $workouts);

        return view('workout.progression', [
            'plan' => $workouts['name'],
            'id' => $id,
            'chart' => $chart,
            'chartType' => $chartType
        ]);
    }
}
