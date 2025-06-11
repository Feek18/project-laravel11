<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

class PesananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Peminjaman::with(['ruangan'])
                ->where('id_pengguna', Auth::user()->pengguna->id)
                ->select('peminjaman.*');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nama_ruangan', function ($row) {
                    return $row->ruangan->nama_ruangan ?? '-';
                })
                ->addColumn('status_badge', function ($row) {
                    $color = '';

                    switch ($row->status_persetujuan) {
                        case 'disetujui':
                            $color = 'bg-green-100 text-green-700';
                            break;
                        case 'ditolak':
                            $color = 'bg-red-100 text-red-700';
                            break;
                        case 'pending':
                        default:
                            $color = 'bg-yellow-100 text-yellow-700';
                            break;
                    }

                    return '<span class="px-3 py-1 text-xs font-semibold capitalize rounded-full ' . $color . ' shadow-sm">'
                        . $row->status_persetujuan .
                        '</span>';
                })
                ->rawColumns(['status_badge'])
                ->make(true);
        }

        $peminjaman = Peminjaman::where('id_pengguna', Auth::user()->pengguna->id)->get();
        return view('components.user.pages.dashboard.pesanan', compact('peminjaman'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
