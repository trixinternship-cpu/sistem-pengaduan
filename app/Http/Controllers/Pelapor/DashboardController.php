<?php

namespace App\Http\Controllers\Pelapor;

use App\Http\Controllers\Controller;
use App\Models\Complaint;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $stats = [
            'total' => Complaint::where('user_id', $userId)->count(),
            'baru' => Complaint::where('user_id', $userId)->where('status', 'baru')->count(),
            'diproses' => Complaint::where('user_id', $userId)->where('status', 'diproses')->count(),
            'selesai' => Complaint::where('user_id', $userId)->where('status', 'selesai')->count(),
        ];

        $recent = Complaint::with(['pc', 'category'])
            ->where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        return view('pelapor.dashboard', compact('stats', 'recent'));
    }
}