<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    /**
     * Tampilkan daftar Agenda.
     */
    public function index(Request $request)
    {
        Agenda::autoArchivePastAgendas();

        $query = Agenda::latest('agenda_date')->latest('agenda_time');

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($request->input('status') === 'arsip') {
            $query->where('is_active', false);
        } else {
            $query->where('is_active', true);
        }

        $agendas = $query->paginate(10)->withQueryString();

        return view('admin.agenda.index', compact('agendas'));
    }

    /**
     * Simpan agenda baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'agenda_date' => 'required|date',
            'agenda_end_date' => 'nullable|date|after_or_equal:agenda_date',
            'agenda_time' => 'nullable|date_format:H:i',
            'agenda_end_time' => [
                'nullable',
                'date_format:H:i',
                function ($attribute, $value, $fail) use ($request) {
                    $startDate = $request->input('agenda_date');
                    $endDate = $request->input('agenda_end_date') ?: $startDate;
                    $startTime = $request->input('agenda_time');
                    if ($startTime && $value && $startDate === $endDate) {
                        if (strtotime($value) <= strtotime($startTime)) {
                            $fail('Jam selesai harus setelah jam mulai jika di hari yang sama.');
                        }
                    }
                }
            ],
            'location' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ], [
            'title.required' => 'Judul kegiatan wajib diisi.',
            'agenda_date.required' => 'Tanggal kegiatan wajib diisi.',
            'agenda_end_date.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',
            'agenda_time.date_format' => 'Format waktu tidak valid (Gunakan format HH:MM).',
            'agenda_end_time.date_format' => 'Format waktu selesai tidak valid (Gunakan format HH:MM).',
        ]);

        Agenda::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'agenda_date' => $request->input('agenda_date'),
            'agenda_end_date' => $request->input('agenda_end_date'),
            'agenda_time' => $request->input('agenda_time'),
            'agenda_end_time' => $request->input('agenda_end_time'),
            'location' => $request->input('location'),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return back()->with('status', 'Jadwal kegiatan baru berhasil ditambahkan.');
    }

    /**
     * Perbarui data agenda.
     */
    public function update(Request $request, $id)
    {
        $agenda = Agenda::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'agenda_date' => 'required|date',
            'agenda_end_date' => 'nullable|date|after_or_equal:agenda_date',
            'agenda_time' => 'nullable|date_format:H:i',
            'agenda_end_time' => [
                'nullable',
                'date_format:H:i',
                function ($attribute, $value, $fail) use ($request) {
                    $startDate = $request->input('agenda_date');
                    $endDate = $request->input('agenda_end_date') ?: $startDate;
                    $startTime = $request->input('agenda_time');
                    if ($startTime && $value && $startDate === $endDate) {
                        if (strtotime($value) <= strtotime($startTime)) {
                            $fail('Jam selesai harus setelah jam mulai jika di hari yang sama.');
                        }
                    }
                }
            ],
            'location' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ], [
            'title.required' => 'Judul kegiatan wajib diisi.',
            'agenda_date.required' => 'Tanggal kegiatan wajib diisi.',
            'agenda_end_date.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',
            'agenda_time.date_format' => 'Format waktu tidak valid (Gunakan format HH:MM).',
            'agenda_end_time.date_format' => 'Format waktu selesai tidak valid (Gunakan format HH:MM).',
        ]);

        $agenda->update([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'agenda_date' => $request->input('agenda_date'),
            'agenda_end_date' => $request->input('agenda_end_date'),
            'agenda_time' => $request->input('agenda_time'),
            'agenda_end_time' => $request->input('agenda_end_time'),
            'location' => $request->input('location'),
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('status', "Agenda {$agenda->title} berhasil diperbarui.");
    }

    /**
     * Toggle status aktif agenda.
     */
    public function toggle($id)
    {
        $agenda = Agenda::findOrFail($id);
        $agenda->update([
            'is_active' => !$agenda->is_active,
        ]);

        $statusText = $agenda->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('status', "Agenda {$agenda->title} berhasil {$statusText}.");
    }

    /**
     * Hapus agenda.
     */
    public function destroy($id)
    {
        if (!auth()->user()->isAdmin()) {
            return back()->with('error', 'Akses Ditolak: Anda tidak memiliki wewenang untuk menghapus data.');
        }

        $agenda = Agenda::findOrFail($id);
        $agenda->delete();

        return back()->with('status', 'Agenda kegiatan berhasil dihapus.');
    }
}
