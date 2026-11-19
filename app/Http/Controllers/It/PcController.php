<?php

namespace App\Http\Controllers\It;

use App\Http\Controllers\Controller;
use App\Http\Requests\PcRequest;
use App\Models\Pc;
use App\Models\User;

class PcController extends Controller
{
    public function index()
    {
        $pcs = Pc::with('user')
            ->withCount('complaints')
            ->orderBy('kode_pc')
            ->paginate(15);

        return view('it.pcs.index', compact('pcs'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        return view('it.pcs.form', ['pc' => new Pc(), 'users' => $users]);
    }

    public function store(PcRequest $request)
    {
        Pc::create($request->validated());
        return redirect()->route('it.pcs.index')->with('success', 'PC berhasil ditambahkan.');
    }

    public function edit(Pc $pc)
    {
        $users = User::orderBy('name')->get();
        return view('it.pcs.form', compact('pc', 'users'));
    }

    public function update(PcRequest $request, Pc $pc)
    {
        $pc->update($request->validated());
        return redirect()->route('it.pcs.index')->with('success', 'PC berhasil diperbarui.');
    }

    public function destroy(Pc $pc)
    {
        $pc->delete();
        return redirect()->route('it.pcs.index')->with('success', 'PC berhasil dihapus.');
    }

    public function history(Pc $pc)
    {
        $complaints = $pc->complaints()
            ->with(['category', 'handling.teknisi', 'user'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('it.pcs.history', compact('pc', 'complaints'));
    }
}