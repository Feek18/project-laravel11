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
            $data = Peminjaman::with(['pengguna', 'ruangan'])->orderBy('created_at', 'desc')->select('peminjaman.*');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nama_peminjam', function ($row) {
                    return $row->pengguna->nama ?? '-';
                })
                ->addColumn('nama_ruangan', function ($row) {
                    return $row->ruangan->nama_ruangan ?? '-';
                })
                ->addColumn('status_badge', function ($row) {
                    if ($row->status_persetujuan === 'pending') {
                        $setujuiForm = '<form method="POST" action="' . route('peminjam.persetujuan', $row->id) . '" style="display:inline-block;">
            ' . csrf_field() . method_field('PUT') . '
            <input type="hidden" name="status_persetujuan" value="disetujui">
            <button type="submit" class="flex items-center gap-1 bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-full text-xs transition duration-150 shadow-sm" title="Setujui">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                Setujui
            </button>
        </form>';

                        $tolakForm = '
    <form method="POST" action="' . route('peminjam.persetujuan', $row->id) . '" style="display:inline-block;">
        ' . csrf_field() . '
        ' . method_field('PUT') . '
        <input type="hidden" name="status_persetujuan" value="ditolak">
        <button type="submit"
            class="flex items-center gap-1 bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-full text-xs transition duration-150 shadow-sm"
            title="Tolak">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                    clip-rule="evenodd" />
            </svg>
            Tolak
        </button>
    </form>';

                        return '<div class="flex gap-2 justify-center">' . $setujuiForm . $tolakForm . '</div>';
                    }

                    // Status badge jika bukan pending
                    $color = $row->status_persetujuan === 'disetujui'
                        ? 'bg-green-100 text-green-800'
                        : 'bg-red-100 text-red-800';

                    return '<span class="px-3 py-1 text-xs font-semibold rounded-full ' . $color . ' capitalize shadow-sm">'
                        . $row->status_persetujuan .
                        '</span>';
                })
                ->addColumn('action', function ($row) {
                    $editBtn = '<button onclick="loadEditModal(\'peminjam\', ' . $row->id . ')" type="button" class="bg-yellow-500 hover:bg-yellow-600 text-white p-2 rounded transition duration-150 mr-1" title="Edit">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
        </svg>
    </button>';

                    $deleteForm = '<form action="' . route('peminjam.destroy', $row->id) . '" method="POST" onsubmit="return confirm(\'Yakin ingin menghapus data ini?\')" style="display:inline-block;">
        ' . csrf_field() . method_field('DELETE') . '
        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white p-2 rounded transition duration-150" title="Hapus">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H3.5a.5.5 0 000 1h.4l.7 10.3A2 2 0 006.6 18h6.8a2 2 0 002-1.7L16.1 5h.4a.5.5 0 000-1H15V3a1 1 0 00-1-1H6zm1 4a.5.5 0 011 0v8a.5.5 0 01-1 0V6zm4 0a.5.5 0 011 0v8a.5.5 0 01-1 0V6z" clip-rule="evenodd" />
            </svg>
        </button>
    </form>';

                    return '<div class="flex gap-1">' . $editBtn . $deleteForm . '</div>';
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
