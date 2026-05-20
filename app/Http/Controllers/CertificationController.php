<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CertificationController extends Controller
{
    public function index()          { return view('certification', ['section' => 'index']); }
    public function careerAnalyst()  { return view('certification', ['section' => 'career-analyst']); }
    public function careerScientist(){ return view('certification', ['section' => 'career-scientist']); }
    public function careerEngineer() { return view('certification', ['section' => 'career-engineer']); }
    public function techPowerBI()    { return view('certification', ['section' => 'tech-powerbi']); }
    public function techTableau()    { return view('certification', ['section' => 'tech-tableau']); }
    public function techSQL()        { return view('certification', ['section' => 'tech-sql']); }
    public function cpe()            { return view('certification', ['section' => 'cpe']); }
    public function theory()         { return view('certification', ['section' => 'theory']); }
    public function history()        { return view('certification', ['section' => 'history']); }
}