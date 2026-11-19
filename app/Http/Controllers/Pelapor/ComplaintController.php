<?php

namespace App\Http\Controllers\Pelapor;

use App\Http\Controllers\Controller;
use App\Http\Requests\ComplaintRequest;
use App\Models\Category;
use App\Models\Complaint;
use App\Models\ComplaintAttachment;
use App\Models\Pc;
use App\Models\User;
use App\Notifications\ComplaintStatusNotification;
use App\Services\AiClassifierService;
use App\Services\RecurringComplaintService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ComplaintController extends Controller
{
    public function __construct(
        private RecurringComplaintService $recurring,
        private AiClassifierService $aiClassifier
    ) {
    }

    public function index()
    {
        $complaints = Complaint::with(['pc', 'category'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('pelapor.complaints.index', compact('complaints'));
    }

    public function create()
    {
        $pcs = Pc::orderBy('kode_pc')->get();
        $categories = Category::orderBy('nama_kategori')->get();

        return view('pelapor.complaints.create', compact('pcs', 'categories'));
    }

    public function checkRecurring(Request $request)
    {
        $data = $request->validate([
            'pc_id' => ['required', 'exists:pcs,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'what' => ['nullable', 'string'],
            'how' => ['nullable', 'string'],
            'deskripsi' => ['nullable', 'string'],
        ]);

        $text = trim(($data['what'] ?? '') . ' ' . ($data['how'] ?? '') . ' ' . ($data['deskripsi'] ?? ''));

        if (strlen($text) < 5) {
            return response()->json(['found' => false, 'data' => []]);
        }

        $aiEnabled = config('services.gemini.enabled', false);
        $aiResult = null;
        $aiKeywords = null;

        if ($aiEnabled) {
            $aiResult = $this->aiClassifier->classify($text);
            $aiKeywords = $aiResult['keywords'] ?? null;
        }

        $results = $this->recurring->findSimilar(
            (int) $data['pc_id'],
            (int) $data['category_id'],
            $text,
            5,
            $aiKeywords
        );

        return response()->json([
            'found' => $results->isNotEmpty(),
            'ai_category' => $aiResult['category'] ?? null,
            'ai_summary' => $aiResult['summary'] ?? null,
            'data' => $results->map(function ($item) {
                $c = $item['complaint'];
                return [
                    'id' => $c->id,
                    'nomor_pengaduan' => $c->nomor_pengaduan,
                    'tanggal' => $c->tanggal_selesai?->format('d M Y') ?? '-',
                    'pc' => $c->pc->kode_pc . ' - ' . $c->pc->nama_pc,
                    'kategori' => $c->category->nama_kategori,
                    'what' => $c->what,
                    'penyebab' => $c->handling->analisis_penyebab ?? '-',
                    'tindakan' => $c->handling->tindakan ?? '-',
                    'solusi' => $c->handling->solusi ?? '-',
                    'hasil' => $c->handling->hasil_penanganan ?? '-',
                    'teknisi' => $c->handling->teknisi->name ?? '-',
                    'tanggal_penanganan' => $c->handling->tanggal_penanganan?->format('d M Y') ?? '-',
                    'score' => $item['score'],
                ];
            }),
        ]);
    }

    public function store(ComplaintRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();

            $complaint = Complaint::create([
                'nomor_pengaduan' => $this->generateNomorPengaduan(),
                'user_id' => auth()->id(),
                'pc_id' => $data['pc_id'],
                'category_id' => $data['category_id'],
                'jenis_kendala' => $data['jenis_kendala'],
                'what' => $data['what'],
                'who' => $data['who'],
                'when' => $data['when'],
                'where' => $data['where'],
                'why' => $data['why'] ?? null,
                'how' => $data['how'],
                'deskripsi' => $data['deskripsi'],
                'status' => 'baru',
            ]);

            $relatedId = $request->input('related_complaint_id');
            $recurringResult = $request->input('recurring_result');

            if ($relatedId && $recurringResult) {
                $related = Complaint::find($relatedId);

                if ($related) {
                    $complaint->update([
                        'is_recurring' => true,
                        'related_complaint_id' => $related->id,
                    ]);

                    if ($recurringResult === 'berhasil') {
                        $complaint->update([
                            'status' => 'selesai',
                            'tanggal_selesai' => now(),
                        ]);

                        $this->recurring->markReused($related, true);

                        DB::commit();

                        return redirect()
                            ->route('pelapor.complaints.show', $complaint->id)
                            ->with('success', 'Solusi berhasil! Pengaduan ditandai selesai tanpa perlu ke IT.');
                    }

                    $this->recurring->markReused($related, false);
                }
            }

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('complaints', 'public');
                    ComplaintAttachment::create([
                        'complaint_id' => $complaint->id,
                        'file_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                        'file_type' => $file->getClientMimeType(),
                    ]);
                }
            }

            // Kirim notifikasi ke semua user IT
            $itUsers = User::where('role', 'it')->get();
            foreach ($itUsers as $it) {
                $it->notify(new ComplaintStatusNotification(
                    $complaint,
                    'Pengaduan Baru',
                    'Pengaduan ' . $complaint->nomor_pengaduan . ' dari ' . auth()->user()->name,
                    route('it.complaints.show', $complaint->id)
                ));
            }

            DB::commit();

            return redirect()
                ->route('pelapor.complaints.show', $complaint->id)
                ->with('success', 'Pengaduan berhasil dibuat dengan nomor ' . $complaint->nomor_pengaduan);

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Gagal menyimpan: ' . $e->getMessage()]);
        }
    }

    public function show(Complaint $complaint)
    {
        abort_if($complaint->user_id !== auth()->id(), 403);

        $complaint->load(['pc', 'category', 'handling.teknisi', 'attachments', 'relatedComplaint']);

        return view('pelapor.complaints.show', compact('complaint'));
    }

    public function confirmRecurring(Request $request, Complaint $complaint)
    {
        abort_if($complaint->user_id !== auth()->id(), 403);

        $data = $request->validate([
            'berhasil' => ['required', 'boolean'],
            'related_complaint_id' => ['required', 'exists:complaints,id'],
        ]);

        $related = Complaint::findOrFail($data['related_complaint_id']);

        if ($data['berhasil']) {
            $complaint->update([
                'status' => 'selesai',
                'tanggal_selesai' => now(),
                'is_recurring' => true,
                'related_complaint_id' => $related->id,
            ]);

            $this->recurring->markReused($related, true);

            if ($complaint->handling && $complaint->handling->teknisi) {
                $complaint->handling->teknisi->notify(new ComplaintStatusNotification(
                    $complaint,
                    'Solusi Dikonfirmasi',
                    'Pelapor mengkonfirmasi solusi pengaduan ' . $complaint->nomor_pengaduan . ' berhasil',
                    route('it.complaints.show', $complaint->id)
                ));
            }

            return redirect()
                ->route('pelapor.complaints.show', $complaint->id)
                ->with('success', 'Bagus! Solusi berhasil. Pengaduan ditandai selesai.');
        }

        $complaint->update([
            'status' => 'baru',
            'is_recurring' => true,
            'related_complaint_id' => $related->id,
        ]);

        $this->recurring->markReused($related, false);

        return redirect()
            ->route('pelapor.complaints.show', $complaint->id)
            ->with('info', 'Solusi tidak berhasil. Pengaduan akan diteruskan ke tim IT.');
    }

    private function generateNomorPengaduan(): string
    {
        $prefix = 'PG-' . now()->format('Ymd') . '-';

        $lastToday = Complaint::where('nomor_pengaduan', 'like', $prefix . '%')
            ->orderByDesc('id')
            ->first();

        $sequence = 1;
        if ($lastToday) {
            $lastSeq = (int) substr($lastToday->nomor_pengaduan, -3);
            $sequence = $lastSeq + 1;
        }

        return $prefix . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }
}