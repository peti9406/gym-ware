<?php

namespace App\Http\Controllers;

use App\Facades\ExerciseDBSvc;
use App\Facades\FilteringSvc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ExerciseDBController extends Controller
{
    public function index(Request $request)
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

            return view("catalog", [
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

    public function show($id, Request $request)
    {

        $page = $request->query("page", 1);
        $bodypart = $request->query("bodypart");
        $equipment = $request->query("equipment");

        $cacheKey = "exercises_{$bodypart}_{$equipment}_page_{$page}";
        $cachedPage = Cache::get($cacheKey, ["data" => []]);

        $exercise = collect($cachedPage['data'] ?? [])->firstWhere("exerciseId", $id);

        return view("exercise-details", ["exercise" => $exercise]);

    }
}
