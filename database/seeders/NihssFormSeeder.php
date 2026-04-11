<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FormSection;
use App\Models\FormQuestion;
use App\Models\FormOption;

class NihssFormSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            [
                'code' => '1a',
                'name' => '1a. Tingkat Kesadaran',
                'description' => 'Pemeriksa harus memilih respons meskipun ada hambatan seperti selang endotrakeal, hambatan bahasa, atau trauma orotrakeal.',
                'order_number' => 1,
                'questions' => [
                    [
                        'text' => 'Tingkat Kesadaran',
                        'instruction' => 'Nilai respons kesadaran umum pasien. Nilai 3 hanya diberikan jika pasien tidak merespons stimuli nyeri atau stereotip postur.',
                        'options' => [
                            ['text' => '0 – Sadar penuh, responsif', 'score' => 0, 'order' => 0],
                            ['text' => '1 – Somnolen: tidak sadar penuh, dapat dibangunkan dengan stimulasi minor', 'score' => 1, 'order' => 1],
                            ['text' => '2 – Stupor: memerlukan stimulasi berulang untuk perhatian, atau stupor kuat', 'score' => 2, 'order' => 2],
                            ['text' => '3 – Koma: tidak merespons, hanya refleks stereotip motorik atau arefleks', 'score' => 3, 'order' => 3],
                        ],
                    ],
                ],
            ],
            [
                'code' => '1b',
                'name' => '1b. Pertanyaan Tingkat Kesadaran (LOC)',
                'description' => 'Pasien ditanya bulan apa sekarang dan usianya. Jawaban harus benar – tidak ada kredit parsial.',
                'order_number' => 2,
                'questions' => [
                    [
                        'text' => 'Pertanyaan Tingkat Kesadaran',
                        'instruction' => 'Tanyakan: "Bulan berapa sekarang?" dan "Berapa usia Anda?". Nilai jawaban pertama saja.',
                        'options' => [
                            ['text' => '0 – Menjawab kedua pertanyaan dengan benar', 'score' => 0, 'order' => 0],
                            ['text' => '1 – Menjawab satu pertanyaan dengan benar', 'score' => 1, 'order' => 1],
                            ['text' => '2 – Tidak menjawab pertanyaan dengan benar', 'score' => 2, 'order' => 2],
                        ],
                    ],
                ],
            ],
            [
                'code' => '1c',
                'name' => '1c. Perintah Tingkat Kesadaran (LOC)',
                'description' => 'Pasien diminta membuka/menutup mata, kemudian mengepalkan tangan dan melepaskan.',
                'order_number' => 3,
                'questions' => [
                    [
                        'text' => 'Perintah Tingkat Kesadaran',
                        'instruction' => 'Minta pasien membuka dan menutup mata, lalu mengepal dan membuka tangan yang tidak paresis.',
                        'options' => [
                            ['text' => '0 – Melakukan kedua tugas dengan benar', 'score' => 0, 'order' => 0],
                            ['text' => '1 – Melakukan satu tugas dengan benar', 'score' => 1, 'order' => 1],
                            ['text' => '2 – Tidak melakukan tugas dengan benar', 'score' => 2, 'order' => 2],
                        ],
                    ],
                ],
            ],
            [
                'code' => '2',
                'name' => '2. Gerakan Mata Terbaik',
                'description' => 'Hanya gerakan mata horizontal yang dinilai. Refleks okulosefalik dinilai jika tidak ada riwayat gerakan mata volunter.',
                'order_number' => 4,
                'questions' => [
                    [
                        'text' => 'Gerakan Mata Horizontal',
                        'instruction' => 'Nilai gerakan mata horizontal volunter atau refleks. Lakukan tes dengan menggerakkan jari pemeriksa atau melakukan doll\'s eye jika diperlukan.',
                        'options' => [
                            ['text' => '0 – Normal', 'score' => 0, 'order' => 0],
                            ['text' => '1 – Palsi parsial: gerakan abnormal satu atau kedua mata, tanpa deviasi gaze forceful', 'score' => 1, 'order' => 1],
                            ['text' => '2 – Deviasi gaze forceful atau palsi total, tidak dapat diatasi dengan manuver okulosefalik', 'score' => 2, 'order' => 2],
                        ],
                    ],
                ],
            ],
            [
                'code' => '3',
                'name' => '3. Visual',
                'description' => 'Nilai lapang pandang di semua kuadran menggunakan konfrontasi jari atau ancaman jika perlu.',
                'order_number' => 5,
                'questions' => [
                    [
                        'text' => 'Lapang Pandang',
                        'instruction' => 'Nilai lapang pandang menggunakan tes konfrontasi. Nilai seperti hemianopia jika terdapat asimetri jelas, termasuk kuadrantanopia.',
                        'options' => [
                            ['text' => '0 – Tidak ada gangguan visual', 'score' => 0, 'order' => 0],
                            ['text' => '1 – Hemianopia parsial', 'score' => 1, 'order' => 1],
                            ['text' => '2 – Hemianopia komplit', 'score' => 2, 'order' => 2],
                            ['text' => '3 – Hemianopia bilateral (buta termasuk buta kortikal)', 'score' => 3, 'order' => 3],
                        ],
                    ],
                ],
            ],
            [
                'code' => '4',
                'name' => '4. Fasial Palsi',
                'description' => 'Minta pasien menunjukkan gigi, mengangkat alis, dan menutup mata.',
                'order_number' => 6,
                'questions' => [
                    [
                        'text' => 'Kelumpuhan Wajah',
                        'instruction' => 'Minta pasien menunjukkan gigi atau senyum, mengangkat kedua alis, dan menutup mata. Nilai simetri gerakan wajah.',
                        'options' => [
                            ['text' => '0 – Gerakan normal dan simetris', 'score' => 0, 'order' => 0],
                            ['text' => '1 – Paresis minor (lipatan nasolabial mendatar, asimetri saat senyum)', 'score' => 1, 'order' => 1],
                            ['text' => '2 – Palsi parsial (paresis total atau hampir total wajah bawah)', 'score' => 2, 'order' => 2],
                            ['text' => '3 – Palsi total satu atau dua sisi (tidak ada gerakan wajah atas dan bawah)', 'score' => 3, 'order' => 3],
                        ],
                    ],
                ],
            ],
            [
                'code' => '5a',
                'name' => '5a. Motorik Lengan Kiri',
                'description' => 'Nilai lengan kiri. Posisi lengan 90° (duduk) atau 45° (berbaring). Nilai drift selama 10 detik.',
                'order_number' => 7,
                'questions' => [
                    [
                        'text' => 'Motorik Lengan Kiri',
                        'instruction' => 'Posisikan lengan pada 90° jika duduk atau 45° jika berbaring. Minta pasien menahan posisi selama 10 detik. Nilai adalah untuk lengan KIRI.',
                        'options' => [
                            ['text' => '0 – Tidak ada drift, lengan bertahan 10 detik penuh', 'score' => 0, 'order' => 0],
                            ['text' => '1 – Drift, lengan turun sebelum 10 detik, tidak menyentuh tempat tidur', 'score' => 1, 'order' => 1],
                            ['text' => '2 – Upaya melawan gravitasi, tetapi lengan tidak dapat mencapai atau mempertahankan posisi', 'score' => 2, 'order' => 2],
                            ['text' => '3 – Tidak ada upaya melawan gravitasi, lengan jatuh', 'score' => 3, 'order' => 3],
                            ['text' => '4 – Tidak ada gerakan', 'score' => 4, 'order' => 4],
                        ],
                    ],
                ],
            ],
            [
                'code' => '5b',
                'name' => '5b. Motorik Lengan Kanan',
                'description' => 'Nilai lengan kanan. Posisi lengan 90° (duduk) atau 45° (berbaring). Nilai drift selama 10 detik.',
                'order_number' => 8,
                'questions' => [
                    [
                        'text' => 'Motorik Lengan Kanan',
                        'instruction' => 'Posisikan lengan pada 90° jika duduk atau 45° jika berbaring. Minta pasien menahan posisi selama 10 detik. Nilai adalah untuk lengan KANAN.',
                        'options' => [
                            ['text' => '0 – Tidak ada drift, lengan bertahan 10 detik penuh', 'score' => 0, 'order' => 0],
                            ['text' => '1 – Drift, lengan turun sebelum 10 detik, tidak menyentuh tempat tidur', 'score' => 1, 'order' => 1],
                            ['text' => '2 – Upaya melawan gravitasi, tetapi lengan tidak dapat mencapai atau mempertahankan posisi', 'score' => 2, 'order' => 2],
                            ['text' => '3 – Tidak ada upaya melawan gravitasi, lengan jatuh', 'score' => 3, 'order' => 3],
                            ['text' => '4 – Tidak ada gerakan', 'score' => 4, 'order' => 4],
                        ],
                    ],
                ],
            ],
            [
                'code' => '6a',
                'name' => '6a. Motorik Kaki Kiri',
                'description' => 'Nilai kaki kiri. Posisi kaki 30° dari horizontal. Nilai drift selama 5 detik.',
                'order_number' => 9,
                'questions' => [
                    [
                        'text' => 'Motorik Kaki Kiri',
                        'instruction' => 'Posisikan kaki pada 30° dari horizontal. Minta pasien menahan posisi selama 5 detik. Nilai adalah untuk kaki KIRI.',
                        'options' => [
                            ['text' => '0 – Tidak ada drift, kaki bertahan 5 detik penuh', 'score' => 0, 'order' => 0],
                            ['text' => '1 – Drift, kaki turun sebelum 5 detik, tidak menyentuh tempat tidur', 'score' => 1, 'order' => 1],
                            ['text' => '2 – Upaya melawan gravitasi, tetapi kaki tidak dapat mencapai atau mempertahankan posisi', 'score' => 2, 'order' => 2],
                            ['text' => '3 – Tidak ada upaya melawan gravitasi, kaki jatuh ke tempat tidur segera', 'score' => 3, 'order' => 3],
                            ['text' => '4 – Tidak ada gerakan', 'score' => 4, 'order' => 4],
                        ],
                    ],
                ],
            ],
            [
                'code' => '6b',
                'name' => '6b. Motorik Kaki Kanan',
                'description' => 'Nilai kaki kanan. Posisi kaki 30° dari horizontal. Nilai drift selama 5 detik.',
                'order_number' => 10,
                'questions' => [
                    [
                        'text' => 'Motorik Kaki Kanan',
                        'instruction' => 'Posisikan kaki pada 30° dari horizontal. Minta pasien menahan posisi selama 5 detik. Nilai adalah untuk kaki KANAN.',
                        'options' => [
                            ['text' => '0 – Tidak ada drift, kaki bertahan 5 detik penuh', 'score' => 0, 'order' => 0],
                            ['text' => '1 – Drift, kaki turun sebelum 5 detik, tidak menyentuh tempat tidur', 'score' => 1, 'order' => 1],
                            ['text' => '2 – Upaya melawan gravitasi, tetapi kaki tidak dapat mencapai atau mempertahankan posisi', 'score' => 2, 'order' => 2],
                            ['text' => '3 – Tidak ada upaya melawan gravitasi, kaki jatuh ke tempat tidur segera', 'score' => 3, 'order' => 3],
                            ['text' => '4 – Tidak ada gerakan', 'score' => 4, 'order' => 4],
                        ],
                    ],
                ],
            ],
            [
                'code' => '7',
                'name' => '7. Ataksia Anggota Gerak',
                'description' => 'Tes jari-hidung dan tumit-lutut dilakukan di kedua sisi untuk mencari bukti ataksia ipsilateral.',
                'order_number' => 11,
                'questions' => [
                    [
                        'text' => 'Ataksia Anggota Gerak',
                        'instruction' => 'Lakukan tes jari-hidung dan tumit-lutut di kedua sisi. Nilai ataksia hanya jika tidak proporsional dengan kelemahan.',
                        'options' => [
                            ['text' => '0 – Tidak ada ataksia', 'score' => 0, 'order' => 0],
                            ['text' => '1 – Ataksia satu anggota gerak', 'score' => 1, 'order' => 1],
                            ['text' => '2 – Ataksia dua anggota gerak', 'score' => 2, 'order' => 2],
                        ],
                    ],
                ],
            ],
            [
                'code' => '8',
                'name' => '8. Sensori',
                'description' => 'Nilai sensasi dengan jarum atau grimase pada pasien yang tidak responsif.',
                'order_number' => 12,
                'questions' => [
                    [
                        'text' => 'Sensori',
                        'instruction' => 'Uji dengan jarum di lengan, kaki, dan wajah. Nilai penurunan simetri sensasi hanya yang berkaitan dengan stroke.',
                        'options' => [
                            ['text' => '0 – Normal, tidak ada kehilangan sensori', 'score' => 0, 'order' => 0],
                            ['text' => '1 – Hilang sensori ringan sampai sedang; sisi yang terkena terasa lebih tumpul tetapi sadar terhadap sentuhan', 'score' => 1, 'order' => 1],
                            ['text' => '2 – Hilang sensori berat sampai total: pasien tidak sadar terhadap sentuhan di wajah, lengan, dan kaki', 'score' => 2, 'order' => 2],
                        ],
                    ],
                ],
            ],
            [
                'code' => '9',
                'name' => '9. Bahasa Terbaik',
                'description' => 'Nilai kemampuan berbicara dan memahami bahasa.',
                'order_number' => 13,
                'questions' => [
                    [
                        'text' => 'Bahasa Terbaik',
                        'instruction' => 'Minta pasien mendeskripsikan gambar yang diberikan (gambar stroke NIH), membaca kalimat, dan menamai benda. Nilai dari semua observasi.',
                        'options' => [
                            ['text' => '0 – Tidak ada afasia, normal', 'score' => 0, 'order' => 0],
                            ['text' => '1 – Afasia ringan sampai sedang: kehilangan kelancaran atau kemampuan memahami yang nyata', 'score' => 1, 'order' => 1],
                            ['text' => '2 – Afasia berat: ekspresi atau komprehensi sangat terfragmentasi', 'score' => 2, 'order' => 2],
                            ['text' => '3 – Mute, afasia global; tidak ada bicara yang dapat digunakan atau tidak ada pemahaman pendengaran', 'score' => 3, 'order' => 3],
                        ],
                    ],
                ],
            ],
            [
                'code' => '10',
                'name' => '10. Disartria',
                'description' => 'Nilai kejelasan artikulasi bicara pasien dengan memintanya membaca atau mengulang kata.',
                'order_number' => 14,
                'questions' => [
                    [
                        'text' => 'Disartria',
                        'instruction' => 'Minta pasien membaca atau mengulang kata-kata dari daftar yang disediakan. Nilai hanya disartria, bukan afasia.',
                        'options' => [
                            ['text' => '0 – Normal', 'score' => 0, 'order' => 0],
                            ['text' => '1 – Disartria ringan sampai sedang: cadel tapi dapat dipahami', 'score' => 1, 'order' => 1],
                            ['text' => '2 – Disartria berat: bicara cadel sampai tidak dapat dipahami atau anartria/mute', 'score' => 2, 'order' => 2],
                        ],
                    ],
                ],
            ],
            [
                'code' => '11',
                'name' => '11. Extinction dan Inatensi',
                'description' => 'Nilai neglect dan extintion dengan stimulasi simultan bilateral.',
                'order_number' => 15,
                'questions' => [
                    [
                        'text' => 'Extinction dan Inatensi (Neglect)',
                        'instruction' => 'Lakukan stimulasi visual dan taktil bilateral simultan. Gunakan informasi dari tes sebelumnya jika relevan. Nilai neglect sensori atau visual unilateral.',
                        'options' => [
                            ['text' => '0 – Tidak ada abnormalitas', 'score' => 0, 'order' => 0],
                            ['text' => '1 – Inatensi visual, taktil, auditori, spasial, atau personal; atau extinction satu modalitas', 'score' => 1, 'order' => 1],
                            ['text' => '2 – Hemi-inatensi atau hemi-neglect profunda; tidak mengenal tangan sendiri; mengorientasikan diri ke satu sisi', 'score' => 2, 'order' => 2],
                        ],
                    ],
                ],
            ],
        ];

        foreach ($sections as $sectionData) {
            $section = FormSection::create([
                'code'         => $sectionData['code'],
                'name'         => $sectionData['name'],
                'description'  => $sectionData['description'] ?? null,
                'order_number' => $sectionData['order_number'],
                'is_active'    => true,
            ]);

            foreach ($sectionData['questions'] as $qIdx => $questionData) {
                $question = FormQuestion::create([
                    'section_id'    => $section->id,
                    'question_text' => $questionData['text'],
                    'instruction'   => $questionData['instruction'] ?? null,
                    'order_number'  => $qIdx,
                    'is_required'   => true,
                    'is_active'     => true,
                ]);

                foreach ($questionData['options'] as $option) {
                    FormOption::create([
                        'question_id' => $question->id,
                        'option_text' => $option['text'],
                        'score'       => $option['score'],
                        'order_number'=> $option['order'],
                        'is_active'   => true,
                    ]);
                }
            }
        }
    }
}
