<?php

namespace App\Http\Controllers;

use App\Facades\ExerciseDBSvc;
use App\Facades\WorkoutPlanSvc;
use App\Models\WorkoutPlan;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;

class WorkoutPlanController extends Controller
{
    public function index(): Factory|View
    {
        $userId = Auth::id();
        $plans = WorkoutPlanSvc::getWorkoutPlans($userId);
        return view('planner.index', ['plans' => $plans]);
    }

    public function store(Request $request): RedirectResponse|Redirector
    {
        $name = $request->validate([
            'name' => ['required', 'min:3', 'max:255', 'unique:workout_plans,name'],
        ])['name'];

        $userId = Auth::id();

        WorkoutPlanSvc::createWorkoutPlan([
            'name' => $name,
            'user_id' => $userId,
        ]);

        return redirect('/workout-planner');
    }

    public function edit(int $planId): Factory|View
    {
        $plan = WorkoutPlanSvc::getWorkoutPlanById($planId);
        $plan = ExerciseDBSvc::getExercisesForPlan($plan);

        return view('planner.edit', ['plan' => $plan]);
    }

    public function update(Request $request, int $planId): RedirectResponse {
        $newName = $request->validate([
            'name' => ['required', 'min:3', 'max:255', 'unique:workout_plans,name'],
        ]);

        WorkoutPlanSvc::updateWorkoutPlan($planId, $newName);

        return redirect("/workout-planner/edit/{$planId}");
    }

    public function destroy(WorkoutPlan $plan): RedirectResponse {
        WorkoutPlanSvc::deleteWorkoutPlan($plan);
        return redirect('/workout-planner');
    }

}
