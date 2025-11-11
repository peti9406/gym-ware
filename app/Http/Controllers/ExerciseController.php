<?php

namespace App\Http\Controllers;

use App\Facades\ExerciseDBSvc;
use App\Facades\ExerciseSvc;
use App\Facades\FilteringSvc;
use App\Facades\WorkoutPlanSvc;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class ExerciseController extends Controller
{
    public function create(Request $request, int $planId): View
    {
        try {
            $page = $request->query("page", 1);
            $bodypart = $request->query("bodypart");
            $equipment = $request->query("equipment");

            $bodyparts = FilteringSvc::getAllBodyParts()['data'];
            $equipments = FilteringSvc::getAllEquipments()['data'];

            usort($bodyparts, fn($a, $b) => strcmp($a["name"], $b["name"]));
            usort($equipments, fn($a, $b) => strcmp($a["name"], $b["name"]));

            $filters = [
                "bodypart" => $bodypart,
                "equipment" => $equipment,
            ];

            $exercises = ExerciseDBSvc::getByFilters($filters, $page);
            $plan = WorkoutPlanSvc::getWorkoutPlanById($planId);

            return view("exercise.create", [
                'plan' => $plan,
                "exercises" => $exercises,
                "bodyparts" => $bodyparts,
                "equipments" => $equipments,
                "selectedBodypart" => $bodypart,
                "selectedEquipment" => $equipment,
                "page" => $page
            ]);
        } catch (\Exception $e) {
            return view("catalog", [
                "error" => $e->getMessage(),
                "exercises" => ['data' => []],
                "page" => $page
            ]);
        }
    }

    public function store(Request $request): RedirectResponse|Redirector
    {
        $planId = $request->input('plan-id');

        if (empty($planId)) {
            return redirect('/workout-planner');
        }

        $sets = $request->input('sets');
        $apiId = $request->input('api-exercise-id');

        if ($sets < 1 || empty($sets) || empty($apiId)) {
            return redirect('/workout-planner/exercise/create/'.$planId)->with('error', 'Something went wrong');
        }

        ExerciseSvc::createExercise([
            'workout_plan_id' => $planId,
            'api_exercise_id' => $apiId,
            'sets' => $sets,
        ]);

        return redirect("/workout-planner/edit/{$planId}");
    }

    public function destroy(Request $request, $exerciseId): RedirectResponse
    {
        ExerciseSvc::deleteExercise($exerciseId);
        $planId = $request->input('plan-id');
        return redirect("/workout-planner/edit/{$planId}");
    }

}
