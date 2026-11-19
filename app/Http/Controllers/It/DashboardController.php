<?php

namespace App\Http\Controllers\It;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Pc;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'baru' => Complaint::where('status', 'baru')->count(),
            'diproses' => Complaint::where('status', 'diproses')->count(),
            'menunggu' => Complaint::where('status', 'menunggu_konfirmasi')->count(),
            'selesai' => Complaint::where('status', 'selesai')->count(),
            'berulang' => Complaint::where('is_recurring', true)->count(),
            'total_pc' => Pc::count(),
            'total_user' => User::count(),
        ];

        $terbaru = Complaint::with(['pc', 'category', 'user'])
            ->whereIn('status', ['baru', 'diproses'])
            ->latest()
            ->take(5)
            ->get();

        $topPc = Pc::withCount('complaints')
            ->orderByDesc('complaints_count')
            ->take(5)
            ->get();

        return view('it.dashboard', compact('stats', 'terbaru', 'topPc'));
    }
}