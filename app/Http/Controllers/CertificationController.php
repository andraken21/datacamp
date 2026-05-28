<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class CertificationController extends Controller
{
    private function getAll()
    {
        return DB::table('sertifikasi')->get();
    }

    private function base()
    {
        $all = $this->getAll();
        return [
            'career' => $all->where('jenis', 'Career'),
            'technology' => $all->where('jenis', 'Technology'),
        ];
    }

    public function index()
    {
        extract($this->base());
        return view('certification', compact('career', 'technology'))->with('section', 'index');
    }

    public function careerAnalyst() {
        extract($this->base());
        $cert = DB::table('sertifikasi')->where('slug', 'associate-data-analyst')->first();
        return view('certification', compact('cert', 'career', 'technology'))->with('section', 'career-analyst');
    }

    public function careerScientist() {
        extract($this->base());
        $cert = DB::table('sertifikasi')->where('slug', 'associate-data-scientist')->first();
        return view('certification', compact('cert', 'career', 'technology'))->with('section', 'career-scientist');
    }

    public function careerEngineer() {
        extract($this->base());
        $cert = DB::table('sertifikasi')->where('slug', 'data-engineer-associate')->first();
        return view('certification', compact('cert', 'career', 'technology'))->with('section', 'career-engineer');
    }

    public function careerAIEngineerDev() {
        extract($this->base());
        $cert = DB::table('sertifikasi')->where('slug', 'ai-engineer-for-developers-associate')->first();
        return view('certification', compact('cert', 'career', 'technology'))->with('section', 'career-ai-dev');
    }

    public function careerAIEngineerDS() {
        extract($this->base());
        $cert = DB::table('sertifikasi')->where('slug', 'ai-engineer-for-data-scientists-associate')->first();
        return view('certification', compact('cert', 'career', 'technology'))->with('section', 'career-ai-ds');
    }

    // Helper untuk tech cert — dengan try/catch agar tidak error
    // jika tabel sertifikasi_faq / sertifikasi_section belum ada
    private function getTechView($slug, $section) {
        extract($this->base());
        $cert = DB::table('sertifikasi')->where('slug', $slug)->first();

        // Coba ambil FAQ & sections dari tabel tambahan jika ada
        $faqs = collect();
        $sections = collect();
        try {
            if ($cert) {
                $faqs = DB::table('sertifikasi_faq')
                    ->where('sertifikasi_id', $cert->id)
                    ->orderBy('urutan')->get();
                $sections = DB::table('sertifikasi_section')
                    ->where('sertifikasi_id', $cert->id)
                    ->orderBy('urutan')->get();
            }
        } catch (\Exception $e) {
            // tabel belum ada, biarkan kosong
        }

        return view('certification', compact('cert', 'career', 'technology', 'faqs', 'sections'))
            ->with('section', $section);
    }

    public function techPowerBI()  { return $this->getTechView('power-bi-pl-300',                     'tech-powerbi');   }
    public function techTableau()  { return $this->getTechView('tableau-certified-data-analyst',       'tech-tableau');   }
    public function techSQL()      { return $this->getTechView('sql-associate',                        'tech-sql');       }
    public function techPython()   { return $this->getTechView('python-data-associate',                'tech-python');    }
    public function techAzure()    { return $this->getTechView('azure-fundamentals',                   'tech-azure');     }
    public function techAzureDev() { return $this->getTechView('azure-developer',                      'tech-azure-dev'); }
    public function techGithub()   { return $this->getTechView('github-foundations',                   'tech-github');    }
    public function techAWS()      { return $this->getTechView('aws-cloud-practitioner',               'tech-aws');       }
    public function techAlteryx()  { return $this->getTechView('alteryx-designer-core',                'tech-alteryx');   }
    public function techKNIME()    { return $this->getTechView('knime-fundamentals',                   'tech-knime');     }

    public function cpe() {
        extract($this->base());

        $filter = request('filter', 'all');

        $query = DB::table('cpe_course')
            ->join('courses', 'cpe_course.course_id', '=', 'courses.course_id')
            ->join('level', 'cpe_course.level_id', '=', 'level.level_id')
            ->select(
                'courses.course_id',
                'courses.nama_course',
                'courses.slug',
                'courses.difficulty',
                'courses.duration_hours',
                'cpe_course.cpe_credits',
                'cpe_course.bagian_dari_track',
                'level.nama_level'
            );

        if ($filter !== 'all') {
            $query->where('cpe_course.bagian_dari_track', $filter);
        }

        $cpeData = $query->orderByDesc('cpe_course.cpe_credits')->get();

        return view('certification', compact('cpeData', 'filter', 'career', 'technology'))
            ->with('section', 'cpe');
    }

    public function theory() {
        extract($this->base());

        // Coba ambil dari jenis Theory; fallback ke Technology jika kosong
        $theoryCerts = DB::table('sertifikasi')->where('jenis', 'Theory')->get();
        if ($theoryCerts->isEmpty()) {
            $theoryCerts = DB::table('sertifikasi')->where('jenis', 'Technology')->take(2)->get();
        }

        return view('certification', compact('theoryCerts', 'career', 'technology'))->with('section', 'theory');
    }

    public function history() {
        extract($this->base());
        return view('certification', compact('career', 'technology'))->with('section', 'history');
    }

    public function faq() {
        extract($this->base());

        // try/catch kalau tabel faqs belum ada
        try {
            $faqs = DB::table('faqs')->get();
        } catch (\Exception $e) {
            $faqs = collect();
        }

        return view('certification', compact('faqs', 'career', 'technology'))->with('section', 'faq');
    }

    public function feedback() {
        extract($this->base());
        return view('certification', compact('career', 'technology'))->with('section', 'feedback');
    }
}