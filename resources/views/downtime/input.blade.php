@extends('layouts.app')

@section('title', 'Input Downtime')

@section('content')
<x-card title="Input Downtime Produksi">

    {{-- FEEDBACK --}}
    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            {{ $errors->first() }}
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('downtime.store') }}">
        @csrf

        <div class="form-grid">

            {{-- TANGGAL DOWNTIME --}}
            <div class="form-group">
                <label for="downtime_date">Tanggal Downtime</label>
                <input type="date"
                       id="downtime_date"
                       name="downtime_date"
                       value="{{ old('downtime_date', now()->toDateString()) }}"
                       required>
            </div>

            {{-- OPERATOR --}}
            <div class="form-group">
                <label for="operator_code">Operator</label>
                <select id="operator_code"
                        name="operator_code"
                        required>
                    <option value="">-- Pilih Operator --</option>
                    @foreach ($operators as $operator)
                        <option value="{{ $operator->code }}"
                            {{ old('operator_code') === $operator->code ? 'selected' : '' }}>
                            {{ $operator->code }} — {{ $operator->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- MESIN --}}
            <div class="form-group">
                <label for="machine_code">Mesin</label>
                <select id="machine_code"
                        name="machine_code"
                        required>
                    <option value="">-- Pilih Mesin --</option>
                    @foreach ($machines as $machine)
                        <option value="{{ $machine->code }}"
                            {{ old('machine_code') === $machine->code ? 'selected' : '' }}>
                            {{ $machine->code }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- DURASI DOWNTIME (MENIT) --}}
            <div class="form-group">
                <label for="duration_minutes">Durasi Downtime (Menit)</label>
                <input type="number"
                       id="duration_minutes"
                       name="duration_minutes"
                       min="1"
                       value="{{ old('duration_minutes') }}"
                       required>
            </div>

            {{-- CATATAN --}}
            <div class="form-group form-span-2">
                <label for="note">Keterangan</label>
                <textarea id="note"
                          name="note"
                          rows="3">{{ old('note') }}</textarea>
            </div>

        </div>

        <div class="form-actions">
            <x-button type="submit">
                Simpan Downtime
            </x-button>
        </div>
    </form>

</x-card>
@endsection
