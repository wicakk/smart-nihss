@extends('layouts.app')
@section('title','Tambah Pasien')
@section('page-title','Tambah Pasien Baru')
@section('breadcrumb','Daftarkan pasien baru ke sistem')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card p-6">
        <form method="POST" action="{{ route('patients.store') }}" class="space-y-5">
            @csrf
            @include('patients._form', ['patient' => null])
            <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                <a href="{{ route('patients.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Pasien
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
