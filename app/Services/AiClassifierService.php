<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiClassifierService
{
    /**
     * ============================================================
     * CLASSIFY COMPLAINT
     * ============================================================
     */
    public function classify(string $complaintText): array
    {
        try {
            $apiKey = config('services.groq.api_key');

            if (!$apiKey) {
                Log::warning('Groq API key tidak ditemukan.');

                return [
                    'category' => 'Lainnya',
                    'keywords' => [],
                    'summary' => $complaintText,
                ];
            }

            $categories = [
                'Jaringan',
                'Hardware',
                'Software',
                'Printer',
                'Akun & Akses',
                'Sistem',
                'Keamanan',
                'Lainnya',
            ];

            $prompt = <<<PROMPT
Anda adalah sistem klasifikasi keluhan IT untuk Helpdesk TRIX.

Klasifikasikan keluhan berikut ke salah satu kategori:

- Jaringan
- Hardware
- Software
- Printer
- Akun & Akses
- Sistem
- Keamanan
- Lainnya

Keluhan:
{$complaintText}

Kembalikan HANYA JSON dengan format:

{
    "category": "nama kategori",
    "keywords": ["kata kunci 1", "kata kunci 2"],
    "summary": "ringkasan singkat keluhan"
}

Jangan menambahkan teks lain di luar JSON.
PROMPT;

            $response = Http::withToken($apiKey)
                ->timeout(30)
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => config(
                        'services.groq.model',
                        'openai/gpt-oss-20b'
                    ),

                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'Anda adalah classifier keluhan IT yang akurat.',
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt,
                        ],
                    ],

                    'temperature' => 0.1,
                    'max_tokens' => 300,
                ]);

            if (!$response->successful()) {
                Log::warning(
                    'Groq classify gagal: ' .
                    $response->status()
                );

                return [
                    'category' => 'Lainnya',
                    'keywords' => [],
                    'summary' => $complaintText,
                ];
            }

            $content = $response->json(
                'choices.0.message.content'
            );

            if (!$content) {
                return [
                    'category' => 'Lainnya',
                    'keywords' => [],
                    'summary' => $complaintText,
                ];
            }

            $content = trim($content);

            // Bersihkan kemungkinan markdown JSON
            $content = preg_replace(
                '/^```json\s*/i',
                '',
                $content
            );

            $content = preg_replace(
                '/\s*```$/',
                '',
                $content
            );

            $result = json_decode(
                trim($content),
                true
            );

            if (!is_array($result)) {
                return [
                    'category' => 'Lainnya',
                    'keywords' => [],
                    'summary' => $complaintText,
                ];
            }

            return [
                'category' => $result['category'] ?? 'Lainnya',
                'keywords' => $result['keywords'] ?? [],
                'summary' => $result['summary'] ?? $complaintText,
            ];

        } catch (\Throwable $e) {

            Log::warning(
                'AI classify error: ' .
                $e->getMessage()
            );

            return [
                'category' => 'Lainnya',
                'keywords' => [],
                'summary' => $complaintText,
            ];
        }
    }


    /**
     * ============================================================
     * SUGGEST SOLUTION
     * ============================================================
     */
    public function suggestSolution(
        string $complaintText,
        string $category
    ): array {

        try {

            $apiKey = config('services.groq.api_key');

            if (!$apiKey) {
                return [
                    'steps' => [],
                    'tips' => [],
                ];
            }

            $prompt = <<<PROMPT
Anda adalah TRIX Assistant, asisten IT Helpdesk.

Kategori kendala:
{$category}

Keluhan:
{$complaintText}

Berikan solusi troubleshooting yang aman dan mudah dipahami pengguna.

Prioritaskan:
1. Pemeriksaan sederhana terlebih dahulu.
2. Langkah yang aman untuk pengguna.
3. Jangan memberikan instruksi yang berisiko merusak sistem.
4. Jika masalah membutuhkan teknisi, sarankan menghubungi tim IT.

Kembalikan HANYA JSON:

{
    "steps": [
        "langkah 1",
        "langkah 2",
        "langkah 3"
    ],
    "tips": [
        "tips 1",
        "tips 2"
    ]
}

Jangan menambahkan teks lain di luar JSON.
PROMPT;

            $response = Http::withToken($apiKey)
                ->timeout(30)
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => config(
                        'services.groq.model',
                        'openai/gpt-oss-20b'
                    ),

                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'Anda adalah asisten troubleshooting IT yang aman.',
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt,
                        ],
                    ],

                    'temperature' => 0.2,
                    'max_tokens' => 500,
                ]);

            if (!$response->successful()) {
                Log::warning(
                    'Groq suggestSolution gagal: ' .
                    $response->status()
                );

                return [
                    'steps' => [],
                    'tips' => [],
                ];
            }

            $content = $response->json(
                'choices.0.message.content'
            );

            if (!$content) {
                return [
                    'steps' => [],
                    'tips' => [],
                ];
            }

            $content = trim($content);

            $content = preg_replace(
                '/^```json\s*/i',
                '',
                $content
            );

            $content = preg_replace(
                '/\s*```$/',
                '',
                $content
            );

            $result = json_decode(
                trim($content),
                true
            );

            if (!is_array($result)) {
                return [
                    'steps' => [],
                    'tips' => [],
                ];
            }

            return [
                'steps' => $result['steps'] ?? [],
                'tips' => $result['tips'] ?? [],
            ];

        } catch (\Throwable $e) {

            Log::warning(
                'AI suggestSolution error: ' .
                $e->getMessage()
            );

            return [
                'steps' => [],
                'tips' => [],
            ];
        }
    }


    /**
     * ============================================================
     * CHAT WITH CONTEXT
     * ============================================================
     *
     * Fungsi utama percakapan TRIX Assistant.
     */
    public function chatWithContext(
        string $userMessage,
        string $category,
        array $history = [],
        array $complaintContext = []
    ): ?string {

        try {

            $apiKey = config('services.groq.api_key');

            if (!$apiKey) {
                Log::warning(
                    'Groq API key tidak ditemukan.'
                );

                return null;
            }


            /*
            |--------------------------------------------------------------------------
            | IDENTITAS TRIX ASSISTANT
            |--------------------------------------------------------------------------
            */

            $systemPrompt = <<<PROMPT
Kamu adalah **TRIX Assistant**, asisten IT Helpdesk untuk perusahaan TRIX.

Tugas utama kamu adalah membantu pelapor menangani kendala IT melalui percakapan yang natural, singkat, ramah, dan relevan.

Kamu bukan sekadar mesin pemberi solusi.

Kamu harus memahami:
- apa masalah pengguna,
- apa yang sudah dicoba,
- bagaimana respon pengguna terhadap solusi sebelumnya,
- apakah masalah masih terjadi,
- apakah pengguna hanya mengonfirmasi,
- apakah pengguna sedang menutup percakapan,
- dan kapan masalah harus diteruskan kepada tim IT.


==================================================
ATURAN PALING PENTING: PAHAMI KONTEKS
==================================================

Jangan memperlakukan setiap pesan pengguna sebagai keluhan baru.

Selalu baca:
1. pesan pengguna saat ini,
2. percakapan sebelumnya,
3. informasi pengaduan,
4. solusi yang sudah diberikan sebelumnya.

Jawaban harus melanjutkan percakapan sebelumnya.


==================================================
JANGAN MENGULANG SOLUSI
==================================================

Jika kamu sudah memberikan langkah troubleshooting sebelumnya dan pengguna hanya memberikan respon seperti:

- "baik saya coba"
- "oke saya coba"
- "siap"
- "sudah saya coba"
- "sudah"
- "belum bisa"
- "masih sama"
- "masih belum bisa"
- "tetap tidak bisa"

Jangan kembali memulai jawaban dengan:

"Halo! Berikut langkah..."

atau mengulang seluruh solusi sebelumnya.

Berikan respon yang sesuai dengan keadaan percakapan.


==================================================
JIKA PENGGUNA MENGATAKAN "MASIH SAMA"
==================================================

Anggap masalah belum selesai.

Jangan mengulang langkah yang sama.

Berikan langkah berikutnya yang belum dicoba atau sarankan eskalasi ke tim IT jika pemeriksaan pengguna sudah cukup.


==================================================
JIKA PENGGUNA MENGATAKAN "SUDAH SAYA COBA"
==================================================

Jangan menyuruh pengguna mengulangi langkah yang sama.

Contoh respon yang baik:

"Baik, berarti langkah tersebut sudah dicoba. Kalau kendalanya masih terjadi, kita lanjut ke pengecekan berikutnya."

Kemudian berikan langkah berikutnya.


==================================================
JIKA PENGGUNA MENGATAKAN "BELUM BISA"
==================================================

Pahami bahwa solusi sebelumnya belum menyelesaikan masalah.

Jangan mengulang jawaban sebelumnya.

Lanjutkan dengan troubleshooting berikutnya atau arahkan ke tim IT jika diperlukan.


==================================================
JIKA PENGGUNA MENGATAKAN "OKE", "BAIK", "SIAP"
==================================================

Pahami konteksnya.

Jika pengguna sedang menerima instruksi, cukup berikan respon singkat.

Contoh:

"Baik, silakan dicoba dulu ya. Kalau masih terkendala, beri tahu saya hasilnya."

Jangan memberikan daftar solusi baru yang tidak diminta.


==================================================
JIKA PENGGUNA MENGUCAPKAN TERIMA KASIH
==================================================

Jika pengguna mengatakan:

- "terima kasih"
- "makasih"
- "thanks"
- "oke makasih"
- "terima kasih sudah membantu"

Berikan respon penutup singkat.

Contoh:

"Sama-sama. Semoga kendalanya segera teratasi. Jika masih ada kendala, silakan chat kembali."


==================================================
JIKA PENGGUNA MENGATAKAN AKAN MENGHUBUNGI TIM IT
==================================================

Ini sangat penting.

Jika pengguna mengatakan:

- "saya akan hubungi tim IT"
- "baik saya akan hubungi tim it"
- "saya hubungi IT saja"
- "nanti saya tanyakan ke IT"
- "saya lapor ke IT"
- "akan saya sampaikan ke tim IT"

Pahami bahwa pengguna sedang melakukan eskalasi dan bukan meminta troubleshooting baru.

Jangan mengulang solusi sebelumnya.

Jangan memberikan daftar langkah baru.

Berikan respon singkat dan mendukung.

Contoh:

"Baik, silakan hubungi tim IT untuk pengecekan lebih lanjut ya. Semoga kendalanya segera teratasi."

atau:

"Baik, silakan diteruskan ke tim IT agar bisa dilakukan pengecekan langsung. Semoga segera teratasi."


==================================================
JIKA PENGGUNA MENUTUP PERCAKAPAN
==================================================

Jika pengguna memberikan tanda percakapan selesai seperti:

- "oke"
- "baik"
- "siap"
- "makasih"
- "terima kasih"
- "sudah cukup"
- "nanti saya coba"
- "nanti saya hubungi IT"

Jangan membuka kembali masalah dari awal.

Jangan memberikan solusi panjang.

Jawab secara natural dan singkat.


==================================================
JIKA PENGGUNA MENGAJUKAN PERTANYAAN LANJUTAN
==================================================

Jika pengguna bertanya:

- "kalau masih tidak bisa bagaimana?"
- "setelah itu gimana?"
- "terus bagaimana?"
- "kenapa bisa begitu?"
- "yang dimaksud restart bagaimana?"

Jawab berdasarkan konteks percakapan sebelumnya.

Jangan meminta informasi yang sebenarnya sudah tersedia.


==================================================
INFORMASI YANG SUDAH DIKETAHUI
==================================================

Jika informasi sudah tersedia dalam konteks pengaduan, jangan menanyakan ulang.

Misalnya sudah diketahui:
- nomor pengaduan,
- kode PC,
- kategori,
- jenis kendala,
- lokasi,
- deskripsi masalah,

jangan bertanya lagi hanya untuk mengisi informasi tersebut.


==================================================
KAPAN BOLEH BERTANYA?
==================================================

Boleh bertanya jika informasi tersebut memang belum diketahui dan diperlukan untuk menentukan langkah berikutnya.

Contoh:

"Apakah koneksi internet di PC lain juga mengalami kendala?"

Pertanyaan harus relevan dengan masalah.


==================================================
JANGAN MENGARANG
==================================================

Jangan membuat fakta yang tidak tersedia.

Jangan menganggap:
- pengguna sudah melakukan sesuatu jika belum disebutkan,
- masalah sudah selesai jika belum dikonfirmasi,
- kondisi jaringan tertentu jika belum diketahui,
- perangkat tertentu jika tidak ada dalam konteks.


==================================================
GAYA BAHASA
==================================================

Gunakan Bahasa Indonesia yang:
- natural,
- ramah,
- singkat,
- profesional tetapi tidak kaku,
- mudah dipahami pengguna umum.

Jangan terlalu sering menggunakan:

"Halo!"

Jika percakapan sudah berjalan, langsung lanjutkan konteks.

Jangan terdengar seperti membaca template.


==================================================
FORMAT JAWABAN
==================================================

Untuk troubleshooting:
- gunakan langkah bernomor jika memang diperlukan,
- gunakan bullet jika membantu,
- jangan terlalu panjang.

Untuk percakapan biasa:
- jawab seperti petugas helpdesk manusia,
- cukup 1-3 paragraf pendek.

Untuk konfirmasi atau penutup:
- cukup 1-2 kalimat.


==================================================
KESELAMATAN
==================================================

Jangan memberikan instruksi yang berisiko merusak komputer atau data.

Hindari menyuruh pengguna:
- menghapus file sistem,
- mengubah registry,
- menjalankan perintah berbahaya,
- mengubah konfigurasi penting tanpa alasan,
- melakukan tindakan yang membutuhkan hak administrator jika tidak diperlukan.

Jika masalah membutuhkan pemeriksaan langsung, sarankan menghubungi tim IT.


==================================================
KATEGORI PENGADUAN
==================================================

Kategori saat ini:
{$category}


==================================================
INFORMASI PENGADUAN
==================================================
PROMPT;


            /*
            |--------------------------------------------------------------------------
            | TAMBAHKAN CONTEXT PENGADUAN
            |--------------------------------------------------------------------------
            */

            if (!empty($complaintContext)) {

                $systemPrompt .= "\n";

                $systemPrompt .=
                    "Berikut informasi pengaduan yang sudah diketahui:\n";

                $systemPrompt .=
                    $this->formatComplaintContext(
                        $complaintContext
                    );

            }


            /*
            |--------------------------------------------------------------------------
            | MESSAGE HISTORY
            |--------------------------------------------------------------------------
            */

            $messages = [];

            $messages[] = [
                'role' => 'system',
                'content' => $systemPrompt,
            ];


            /*
            |--------------------------------------------------------------------------
            | MASUKKAN HISTORY
            |--------------------------------------------------------------------------
            */

            foreach ($history as $item) {

                if (
                    !isset($item['role']) ||
                    !isset($item['content'])
                ) {
                    continue;
                }

                $role = $item['role'];

                if (!in_array(
                    $role,
                    ['user', 'assistant'],
                    true
                )) {
                    continue;
                }

                $content = trim(
                    (string) $item['content']
                );

                if ($content === '') {
                    continue;
                }

                $messages[] = [
                    'role' => $role,
                    'content' => $content,
                ];
            }


            /*
            |--------------------------------------------------------------------------
            | PESAN TERBARU
            |--------------------------------------------------------------------------
            |
            | ChatService saat ini mengambil history setelah pesan
            | pengguna disimpan. Jadi pesan terbaru kemungkinan
            | sudah ada di history.
            |
            | Hindari mengirim pesan yang sama dua kali.
            |
            */

            $lastHistoryMessage = null;

            if (!empty($history)) {

                $lastItem =
                    $history[count($history) - 1];

                if (
                    isset($lastItem['role']) &&
                    isset($lastItem['content'])
                ) {
                    $lastHistoryMessage =
                        trim(
                            (string) $lastItem['content']
                        );
                }
            }


            if (
                $lastHistoryMessage !==
                trim($userMessage)
            ) {

                $messages[] = [
                    'role' => 'user',
                    'content' => $userMessage,
                ];

            }


            /*
            |--------------------------------------------------------------------------
            | PANGGIL GROQ
            |--------------------------------------------------------------------------
            */

            $response = Http::withToken($apiKey)
                ->timeout(45)
                ->post(
                    'https://api.groq.com/openai/v1/chat/completions',
                    [
                        'model' => config(
                            'services.groq.model',
                            'openai/gpt-oss-20b'
                        ),

                        'messages' => $messages,

                        /*
                         * Temperature rendah supaya AI lebih
                         * konsisten mengikuti konteks.
                         */
                        'temperature' => 0.3,

                        'max_tokens' => 500,
                    ]
                );


            if (!$response->successful()) {

                Log::warning(
                    'Groq chatWithContext gagal: ' .
                    $response->status() .
                    ' - ' .
                    $response->body()
                );

                return null;
            }


            /*
            |--------------------------------------------------------------------------
            | AMBIL JAWABAN
            |--------------------------------------------------------------------------
            */

            $reply = $response->json(
                'choices.0.message.content'
            );


            if (!$reply) {
                return null;
            }


            $reply = trim($reply);


            /*
            |--------------------------------------------------------------------------
            | BERSIHKAN JIKA AI MENGIRIM MARKDOWN CODE BLOCK
            |--------------------------------------------------------------------------
            */

            $reply = preg_replace(
                '/^```(?:text|markdown)?\s*/i',
                '',
                $reply
            );

            $reply = preg_replace(
                '/\s*```$/',
                '',
                $reply
            );


            return trim($reply);


        } catch (\Throwable $e) {

            Log::warning(
                'AI chatWithContext error: ' .
                $e->getMessage()
            );

            return null;
        }
    }


    /**
     * ============================================================
     * FORMAT COMPLAINT CONTEXT
     * ============================================================
     */
    private function formatComplaintContext(
        array $context
    ): string {

        $lines = [];


        $fields = [
            'nomor_pengaduan' => 'Nomor Pengaduan',
            'pc' => 'PC / Perangkat',
            'jenis_kendala' => 'Jenis Kendala',
            'kategori' => 'Kategori',
            'what' => 'What',
            'who' => 'Who',
            'when' => 'When',
            'where' => 'Where',
            'why' => 'Why',
            'how' => 'How',
            'deskripsi' => 'Deskripsi',
            'status' => 'Status',
            'is_recurring' => 'Kendala Berulang',
            'related_complaint_id' => 'Pengaduan Terkait',
        ];


        foreach ($fields as $key => $label) {

            if (!array_key_exists(
                $key,
                $context
            )) {
                continue;
            }


            $value = $context[$key];


            if ($value === null) {
                continue;
            }


            if ($value === '') {
                continue;
            }


            if ($key === 'is_recurring') {

                $value = $value
                    ? 'Ya'
                    : 'Tidak';
            }


            $lines[] =
                "- {$label}: {$value}";
        }


        /*
        |--------------------------------------------------------------------------
        | PENGADUAN SEBELUMNYA
        |--------------------------------------------------------------------------
        */

        if (
            isset($context['related_complaint']) &&
            is_array($context['related_complaint'])
        ) {

            $previous =
                $context['related_complaint'];


            $lines[] = '';

            $lines[] =
                'Informasi pengaduan sebelumnya yang berkaitan:';


            if (
                !empty(
                    $previous['nomor_pengaduan']
                )
            ) {

                $lines[] =
                    '- Nomor: ' .
                    $previous['nomor_pengaduan'];
            }


            if (
                !empty(
                    $previous['jenis_kendala']
                )
            ) {

                $lines[] =
                    '- Jenis kendala: ' .
                    $previous['jenis_kendala'];
            }


            if (
                !empty(
                    $previous['deskripsi']
                )
            ) {

                $lines[] =
                    '- Deskripsi: ' .
                    $previous['deskripsi'];
            }


            if (
                !empty(
                    $previous['status']
                )
            ) {

                $lines[] =
                    '- Status: ' .
                    $previous['status'];
            }


            if (
                !empty(
                    $previous['hasil_penanganan']
                )
            ) {

                $lines[] =
                    '- Hasil penanganan: ' .
                    $previous['hasil_penanganan'];
            }
        }


        if (empty($lines)) {

            return "- Tidak ada informasi tambahan.\n";
        }


        return implode(
            "\n",
            $lines
        ) . "\n";
    }
}