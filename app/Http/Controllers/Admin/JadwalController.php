<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\MataKuliah;
use App\Models\Ruangan;
use App\Services\RoomConflictService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Jadwal::with(['ruangan', 'matkul'])->select('jadwals.*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('ruangan_nama', function ($row) {
                    return $row->ruangan->nama_ruangan ?? '-';
                })
                ->addColumn('nama_perkuliahan', function ($row) {
                    return $row->matkul->mata_kuliah ?? '-';
                })
                ->addColumn('action', function ($row) {
                    $editBtn = '<button onclick="loadEditModal(\'jadwal\', ' . $row->id . ')" type="button" id="edit-jadwal-' . $row->id . '" class="bg-yellow-500 hover:bg-yellow-600 text-white p-2 rounded transition duration-150">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                        </svg>
                    </button>';

                    $deleteBtn = '<form method="POST" action="' . route('jadwal.destroy', $row->id) . '" style="display: inline;">
                        ' . csrf_field() . '
                        ' . method_field('DELETE') . '
                        <button type="submit" class="btn-delete bg-red-500 hover:bg-red-600 text-white p-2 rounded transition duration-150" data-item-name="jadwal '.$row->nama_perkuliahan.'">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </form>';

                    return '<div class="flex items-center gap-2">' . $editBtn . ' ' . $deleteBtn . '</div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $jadwals = Jadwal::with('ruangan')->get();
        $ruangan = Ruangan::all();
        $matkul = MataKuliah::all();
        return view('components.admin.jadwal', compact('jadwals', 'ruangan', 'matkul'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validatedData = $request->validate([
            'id_ruang' => 'required|numeric|exists:ruangan_kelas,id_ruang',
            'id_matkul' => 'required|numeric|exists:mata_kuliah,id',
            'hari' => 'required|string|in:minggu,senin,selasa,rabu,kamis,jumat,sabtu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ],[],[
            'id_ruang' => 'Nama Ruangan',
            'id_matkul' => 'Nama Mata Kuliah',
            'hari' => 'Hari',
            'jam_mulai' => 'Jam Mulai',
            'jam_selesai' => 'Jam Selesai',
        ]);

        // Use the conflict service to validate the booking
        $conflictService = new RoomConflictService();
        $validation = $conflictService->validateBooking($validatedData, 'jadwal');

        if (!$validation['valid']) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode(' ', $validation['messages']))
                ->with('conflict_details', $conflictService->formatConflictDetails($validation['conflicts']));
        }

        Jadwal::create($validatedData);

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $ruangan = \App\Models\Ruangan::all(); // Need to load ruangan for the modal
        $matkul = \App\Models\MataKuliah::all(); // Need to load matkul for the modal

        // If this is an AJAX request, return the modal HTML
        if (request()->ajax()) {
            return view('components.modals.jadwal.edit', compact('jadwal', 'ruangan', 'matkul'))->render();
        }

        return view('components.modals.jadwal.edit', compact('jadwal', 'ruangan', 'matkul'));
    }


    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::findOrFail($id);
        // dd($request->all());
        $validatedData = $request->validate([
            'id_ruang' => 'required|numeric|exists:ruangan_kelas,id_ruang',
            'id_matkul' => 'required|numeric|exists:mata_kuliah,id',
            'hari' => 'required|string|in:minggu,senin,selasa,rabu,kamis,jumat,sabtu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        // Use the conflict service to validate the booking update
        $conflictService = new RoomConflictService();
        $validation = $conflictService->validateBooking($validatedData, 'jadwal', $id);

        if (!$validation['valid']) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode(' ', $validation['messages']))
                ->with('conflict_details', $conflictService->formatConflictDetails($validation['conflicts']));
        }

        // Convert time format for database storage
        $validatedData['jam_mulai'] = $validatedData['jam_mulai'] . ':00';
        $validatedData['jam_selesai'] = $validatedData['jam_selesai'] . ':00';

        $jadwal->update($validatedData);
        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil diupdate.');
    }

    public function destroy($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $jadwal->delete();
        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil dihapus.');
    }
}
