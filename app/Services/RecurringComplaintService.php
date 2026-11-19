<?php

namespace App\Services;

use App\Models\Complaint;

class RecurringComplaintService
{
    /**
     * Cari kendala serupa berdasarkan PC, kategori, dan keyword.
     * Kalau $aiKeywords diberikan, pakai keyword dari AI.
     */
    public function findSimilar(
        int $pcId,
        int $categoryId,
        string $keywordText,
        int $limit = 5,
        ?array $aiKeywords = null
    ) {
        // Pakai AI keywords kalau ada, fallback ke extractKeywords manual
        $keywords = $aiKeywords ?? $this->extractKeywords($keywordText);

        $candidates = Complaint::with(['pc', 'category', 'handling.teknisi', 'user'])
            ->solved()
            ->where('pc_id', $pcId)
            ->where('category_id', $categoryId)
            ->orderByDesc('tanggal_selesai')
            ->get();

        $scored = $candidates->map(function ($complaint) use ($keywords) {
            $text = strtolower(
                ($complaint->what ?? '') . ' ' .
                ($complaint->how ?? '') . ' ' .
                ($complaint->deskripsi ?? '')
            );

            $matchCount = 0;
            foreach ($keywords as $kw) {
                if (str_contains($text, strtolower($kw))) {
                    $matchCount++;
                }
            }

            // Skor: base 3 (PC) + 2 (kategori) + jumlah keyword match
            $score = 3 + 2 + $matchCount;

            return [
                'complaint' => $complaint,
                'score' => $score,
                'matched_keywords' => $matchCount,
            ];
        });

        $filtered = $scored->filter(fn ($item) => $item['matched_keywords'] > 0);
        $sorted = $filtered->sortByDesc('score')->values();

        return $sorted->take($limit);
    }

    /**
     * Ekstrak kata kunci dari teks (rule-based, fallback kalau AI gagal).
     */
    private function extractKeywords(string $text): array
    {
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9\s]/', ' ', $text);

        $stopwords = [
            'yang', 'dan', 'di', 'ke', 'dari', 'untuk', 'pada', 'dengan',
            'adalah', 'itu', 'ini', 'saya', 'tidak', 'bisa', 'ada', 'sudah',
            'akan', 'atau', 'juga', 'karena', 'saat', 'ketika', 'agar',
            'the', 'and', 'or', 'a', 'an', 'is', 'are', 'was', 'were',
        ];

        $words = preg_split('/\s+/', $text);
        $words = array_filter($words, function ($w) use ($stopwords) {
            return strlen($w) >= 4 && !in_array($w, $stopwords);
        });

        return array_values(array_unique($words));
    }

    /**
     * Tandai bahwa solusi dari riwayat berhasil dipakai ulang.
     */
    public function markReused(Complaint $complaint, bool $berhasil): void
    {
        if (!$complaint->handling) {
            return;
        }

        $handling = $complaint->handling;
        $handling->increment('digunakan_kembali');

        if ($berhasil) {
            $handling->increment('berhasil_digunakan_kembali');
        }
    }
}