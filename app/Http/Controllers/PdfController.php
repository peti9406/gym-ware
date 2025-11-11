<?php

namespace App\Http\Controllers;

use App\Facades\ExerciseDBSvc;
use App\Facades\WorkoutPlanSvc;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class PdfController extends Controller
{
    public function downloadChart(Request $request)
    {
        $subscribed = Auth::user()->subscription;

        if (!$subscribed) {
            $id = $request->input('id');
            return redirect('/workout/progression/' . $id)
                ->with('error', 'You have to be subscribed to download the progression chart!');
        }

        $base64 = $request->input('image');
        $plan = $request->input('plan', 'Workout');

        $imageData = str_replace('data:image/png;base64,', '', $base64);
        $imageData = str_replace(' ', '+', $imageData);
        $image = base64_decode($imageData);

        $path = storage_path('app/public/chart.png');
        file_put_contents($path, $image);

        $pdf = Pdf::loadView('workout.chart-pdf', [
            'imagePath' => $path,
            'plan' => $plan,
        ])->setPaper('a4', 'landscape');

        return Response::make($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="chart.pdf"',
        ]);
    }

    public function download(string $id)
    {
        $subscribed = Auth::user()->subscription;

        if (!$subscribed) {
            return redirect('/workout-planner')
                ->with('error', 'You have to be subscribed to download the workout template!');
        }

        $plan = WorkoutPlanSvc::getWorkoutPlanById($id);

        if (empty($plan['exercises'])) {
            return redirect('/workout-planner/edit/' . $id);
        }

        $plan = ExerciseDBSvc::getExercisesForPlan($plan);
        $pdf = Pdf::loadView('workout.create-pdf', [
            'plan' => $plan,
        ])->setPaper('a4');

        $filename = Str::slug($plan['name'], '_') . '.pdf';
        return $pdf->download($filename);
    }
}
