@extends('layouts.app')

@section('title', 'Input Produksi Bubut')

@section('content')
<x-card title="Input Hasil Operator Bubut">

    {{-- FEEDBACK USER --}}
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

    <form method="POST" action="{{ route('produksi.store') }}">
        @csrf

        <div class="form-grid">

            {{-- TANGGAL PRODUKSI --}}
            <div class="form-group">
                <label for="production_date">Tanggal Produksi</label>
                <input type="date"
                       id="production_date"
                       name="production_date"
                       value="{{ old('production_date', now()->toDateString()) }}"
                       required>
            </div>

            {{-- SHIFT --}}
            <div class="form-group">
                <label for="shift">Shift</label>
                <select id="shift" name="shift" required>
                    @foreach (['A','B','C'] as $shift)
                        <option value="{{ $shift }}"
                            {{ old('shift') === $shift ? 'selected' : '' }}>
                            Shift {{ $shift }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- OPERATOR --}}
            <div class="form-group">
                <label for="operator_code">Operator</label>
                <select id="operator_code" name="operator_code" required>
                    <option value="">-- Pilih Operator --</option>
                    @foreach ($operators as $operator)
                        <option value="{{ $operator->code }}"
                            {{ old('operator_code') === $operator->code ? 'selected' : '' }}>
                            {{ $operator->name }} ({{ $operator->code }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- MESIN --}}
            <div class="form-group">
                <label for="machine_code">Mesin</label>
                <select id="machine_code" name="machine_code" required>
                    <option value="">-- Pilih Mesin --</option>
                    @foreach ($machines as $machine)
                        <option value="{{ $machine->code }}"
                            {{ old('machine_code') === $machine->code ? 'selected' : '' }}>
                            {{ $machine->name }} ({{ $machine->code }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- ITEM --}}
            <div class="form-group">
                <label for="item_code">Item</label>
                <select id="item_code" name="item_code" required>
                    <option value="">-- Pilih Item --</option>
                    @foreach ($items as $item)
                        <option value="{{ $item->code }}"
                                data-cycle="{{ $item->cycle_time_sec }}"
                                {{ old('item_code') === $item->code ? 'selected' : '' }}>
                            {{ $item->code }} — {{ $item->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- PREVIEW CYCLE TIME --}}
            <div class="form-group">
                <label>Cycle Time (detik)</label>
                <input type="number"
                       id="cycle_time_preview"
                       readonly>
            </div>

            {{-- JAM MULAI --}}
            <div class="form-group">
                <label for="time_start">Jam Mulai</label>
                <input type="time"
                       id="time_start"
                       name="time_start"
                       value="{{ old('time_start') }}"
                       required>
            </div>

            {{-- JAM SELESAI --}}
            <div class="form-group">
                <label for="time_end">Jam Selesai</label>
                <input type="time"
                       id="time_end"
                       name="time_end"
                       value="{{ old('time_end') }}"
                       required>
            </div>

            {{-- QTY AKTUAL --}}
            <div class="form-group">
                <label for="actual_qty">Qty Aktual</label>
                <input type="number"
                       id="actual_qty"
                       name="actual_qty"
                       min="0"
                       value="{{ old('actual_qty') }}"
                       required>
            </div>

            {{-- PREVIEW TARGET --}}
            <div class="form-group">
                <label>Target (Preview)</label>
                <input type="number"
                       id="target_preview"
                       readonly>
            </div>

        </div>

        <div class="form-actions">
            <x-button type="submit">
                Simpan Data Produksi
            </x-button>
        </div>
    </form>

</x-card>

{{-- ===============================
     JS PREVIEW (NON KRITIS)
     =============================== --}}
<script>
document.addEventListener('DOMContentLoaded', () => {

    const itemSelect   = document.getElementById('item_code');
    const timeStart    = document.getElementById('time_start');
    const timeEnd      = document.getElementById('time_end');

    const cyclePreview = document.getElementById('cycle_time_preview');
    const targetPrev   = document.getElementById('target_preview');

    function updatePreview() {
        const option = itemSelect.options[itemSelect.selectedIndex];
        const cycle  = Number(option?.dataset?.cycle || 0);

        if (!cycle) {
            cyclePreview.value = '';
            targetPrev.value   = '';
            return;
        }

        cyclePreview.value = cycle;

        if (!timeStart.value || !timeEnd.value) {
            targetPrev.value = '';
            return;
        }

        const start = new Date(`1970-01-01T${timeStart.value}:00`);
        const end   = new Date(`1970-01-01T${timeEnd.value}:00`);
        const diff  = (end - start) / 1000;

        targetPrev.value = diff > 0
            ? Math.floor(diff / cycle)
            : '';
    }

    itemSelect.addEventListener('change', updatePreview);
    timeStart.addEventListener('change', updatePreview);
    timeEnd.addEventListener('change', updatePreview);

    updatePreview();
});
</script>
@endsection
