<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FormSection;
use App\Models\FormQuestion;
use App\Models\FormOption;
use Illuminate\Http\Request;

class FormBuilderController extends Controller
{
    public function index()
    {
        $sections = FormSection::with(['allQuestions.allOptions'])->orderBy('order_number')->get();
        return view('admin.form-builder.index', compact('sections'));
    }

    // ── Sections ─────────────────────────────────────────────────
    public function storeSection(Request $request)
    {
        $data = $request->validate([
            'code'         => 'required|string|max:20|unique:form_sections,code',
            'name'         => 'required|string|max:255',
            'description'  => 'nullable|string',
            'order_number' => 'required|integer|min:0',
        ]);
        FormSection::create($data + ['is_active' => true]);
        return back()->with('success', 'Seksi berhasil ditambahkan.');
    }

    public function updateSection(Request $request, FormSection $section)
    {
        $data = $request->validate([
            'code'         => 'required|string|max:20|unique:form_sections,code,' . $section->id,
            'name'         => 'required|string|max:255',
            'description'  => 'nullable|string',
            'order_number' => 'required|integer|min:0',
            'is_active'    => 'boolean',
        ]);
        $section->update($data);
        return back()->with('success', 'Seksi berhasil diperbarui.');
    }

    public function destroySection(FormSection $section)
    {
        $section->delete();
        return back()->with('success', 'Seksi berhasil dihapus.');
    }

    // ── Questions ─────────────────────────────────────────────────
    public function storeQuestion(Request $request)
    {
        $data = $request->validate([
            'section_id'    => 'required|exists:form_sections,id',
            'question_text' => 'required|string|max:500',
            'instruction'   => 'nullable|string',
            'order_number'  => 'required|integer|min:0',
            'is_required'   => 'boolean',
        ]);
        FormQuestion::create($data + ['is_active' => true]);
        return back()->with('success', 'Pertanyaan berhasil ditambahkan.');
    }

    public function updateQuestion(Request $request, FormQuestion $question)
    {
        $data = $request->validate([
            'question_text' => 'required|string|max:500',
            'instruction'   => 'nullable|string',
            'order_number'  => 'required|integer|min:0',
            'is_required'   => 'boolean',
            'is_active'     => 'boolean',
        ]);
        $question->update($data);
        return back()->with('success', 'Pertanyaan berhasil diperbarui.');
    }

    public function destroyQuestion(FormQuestion $question)
    {
        $question->delete();
        return back()->with('success', 'Pertanyaan berhasil dihapus.');
    }

    // ── Options ───────────────────────────────────────────────────
    public function storeOption(Request $request)
    {
        $data = $request->validate([
            'question_id'  => 'required|exists:form_questions,id',
            'option_text'  => 'required|string|max:500',
            'score'        => 'required|integer|min:0|max:99',
            'description'  => 'nullable|string',
            'order_number' => 'required|integer|min:0',
        ]);
        FormOption::create($data + ['is_active' => true]);
        return back()->with('success', 'Opsi berhasil ditambahkan.');
    }

    public function updateOption(Request $request, FormOption $option)
    {
        $data = $request->validate([
            'option_text'  => 'required|string|max:500',
            'score'        => 'required|integer|min:0|max:99',
            'description'  => 'nullable|string',
            'order_number' => 'required|integer|min:0',
            'is_active'    => 'boolean',
        ]);
        $option->update($data);
        return back()->with('success', 'Opsi berhasil diperbarui.');
    }

    public function destroyOption(FormOption $option)
    {
        $option->delete();
        return back()->with('success', 'Opsi berhasil dihapus.');
    }
}
