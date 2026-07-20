<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LearningData;

class StudentDashboardController extends Controller
{
    private function getStudent(Request $request)
    {
        $user = auth()->user() ?? $request->user();
        if ($user) {
            
            if (!isset($user->nama)) {
                $user->nama = $user->name;
            }
            
            if (!isset($user->username)) {
                $user->username = explode('@', $user->email ?? 'siswa@-')[0];
            }
        }
        return $user;
    }

    public function index(Request $request)
    {
        $student = $this->getStudent($request);

        
        $learningCount  = LearningData::where('user_id', $student->id)->count();
        $latestLearning = LearningData::where('user_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->first();

        return view('siswa.dashboard', compact('student', 'learningCount', 'latestLearning'));
    }

    public function riwayat(Request $request)
    {
        $student = $this->getStudent($request);

        $learningData = LearningData::where('user_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('siswa.riwayat', compact('student', 'learningData'));
    }
}
