<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Pengguna;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Peminjaman::with(['pengguna', 'ruangan'])->select('peminjaman.*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nama_peminjam', function ($row) {
                    return $row->pengguna->nama ?? '-';
                })
                ->addColumn('nama_ruangan', function ($row) {
                    return $row->ruangan->nama_ruangan ?? '-';
                })
                ->addColumn('status_badge', function ($row) {
                    $color = '';
                    switch ($row->status_persetujuan) {
                        case 'disetujui':
                            $color = 'bg-green-100 text-green-800';
                            break;
                        case 'ditolak':
                            $color = 'bg-red-100 text-red-800';
                            break;
                        default:
                            $color = 'bg-yellow-100 text-yellow-800';
                    }
                    return '<span class="px-2 py-1 text-xs font-medium rounded-full ' . $color . '">' . $row->status_persetujuan . '</span>';
                })
                ->addColumn('action', function ($row) {
                    $editBtn = '<button onclick="loadEditModal(\'peminjam\', ' . $row->id . ')" type="button" class="bg-yellow-500 hover:bg-yellow-600 text-white p-2 rounded transition duration-150">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                        </svg>
                    </button>';

                    $approveBtn = '';
                    if ($row->status_persetujuan == 'pending') {
                        $approveBtn = ' <form method="POST" action="' . route('peminjam.persetujuan', $row->id) . '" style="display: inline;">
                            ' . csrf_field() . '
                            ' . method_field('PUT') . '
                            <input type="hidden" name="status" value="disetujui">
                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white p-2 rounded transition duration-150" title="Setujui">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </form>';
                    }
                    return '<div class="action-buttons">' . $editBtn . $approveBtn . '</div>';
                })
                ->rawColumns(['status_badge', 'action'])
                ->make(true);
        }

        $peminjam = Peminjaman::all();
        $ruangan = Ruangan::all();
        $pengguna = Pengguna::all();
        return view('components.admin.peminjam', compact('peminjam', 'ruangan', 'pengguna'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validatedData = $request->validate([
            'id_pengguna' => 'required|exists:penggunas,id',
            'id_ruang' => 'required|exists:ruangan_kelas,id_ruang',
            // 'status_peminjaman' => 'required|in:terencana,insidental',
            'keperluan' => 'required|string|max:255',
            // 'status_persetujuan' => 'required|in:pending,disetujui,ditolak',
            'tanggal_pinjam' => 'required|date',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
        ]);

        Peminjaman::create($validatedData);

        return redirect()->route('peminjam.index')->with('success', 'Peminjaman berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $peminjam = Peminjaman::findOrFail($id);
        $pengguna = Pengguna::all();
        $ruangan = Ruangan::all();

        // If this is an AJAX request, return the modal HTML
        if (request()->ajax()) {
            return view('components.modals.peminjam.edit', compact('peminjam', 'pengguna', 'ruangan'))->render();
        }

        return view('components.modals.peminjam.edit', compact('peminjam', 'pengguna', 'ruangan'));
    }


    public function update(Request $request, $id)
    {
        $peminjam = Peminjaman::findOrFail($id);

        // dd($request->all()); // Pastikan key "debug" muncul
        $validated = $request->validate([
            'id_pengguna' => 'required|exists:penggunas,id',
            'id_ruang' => 'required|exists:ruangan_kelas,id_ruang',
            'status_peminjaman' => 'required|in:terencana,insidental',
            'keperluan' => 'required|string|max:255',
            'status_persetujuan' => 'required|in:pending,disetujui,ditolak',
            'tanggal_pinjam' => 'required|date',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
        ]);

        $peminjam->update($validated);

        return redirect()->route('peminjam.index')->with('success', 'Peminjaman berhasil diupdate.');
    }

    public function destroy($id)
    {
        $peminjam = Peminjaman::findOrFail($id);
        $peminjam->delete();
        return redirect()->route('peminjam.index')->with('success', 'Peminjaman berhasil dihapus.');
    }

    public function persetujuan(Request $request, $id)
    {
        $request->validate([
            'status_persetujuan' => 'required|in:disetujui,ditolak',
        ]);

        $peminjam = Peminjaman::findOrFail($id);
        $peminjam->status_persetujuan = $request->status_persetujuan;
        $peminjam->save();

        return redirect()->route('peminjam.index')->with('success', 'Status persetujuan berhasil diubah.');
    }
}
