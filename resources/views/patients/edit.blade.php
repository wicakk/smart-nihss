@extends('layouts.app')
@section('title','Edit Pasien')
@section('page-title','Edit Data Pasien')
@section('breadcrumb', $patient->name . ' · ' . $patient->medical_record_number)

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card p-6">
        <form method="POST" action="{{ route('patients.update', $patient) }}" class="space-y-5">
            @csrf @method('PUT')
            @include('patients._form', ['patient' => $patient])
            <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                <a href="{{ route('patients.show', $patient) }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Perbarui Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
