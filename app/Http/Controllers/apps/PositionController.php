<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Position;

class PositionController extends Controller
{
    protected $path = "Admin";
    protected $module = "dataPosition";
    protected $title = "Daftar Jabatan";





    public function index()
    {
        $positions = Position::all();

        return view($this->path . '.' . $this->module, [
            'title' => $this->title,
            'path' => $this->path,
            'module' => $this->module,
            'positions' => $positions,
        ]);
    }


    public function insertData(Request $request)
    {

        $validated = $request->validate([
            'position' => 'required|string|min:3|max:255|unique:positions,position',
        ]);

        $position = Position::create([
            'position' => $validated['position'],
        ]);

        return response()->json([
            'message' => 'Data berhasil disimpan',
            'data' => $position,
        ]);

        // try{
        // } catch (\Exception $e) {
        //     return response()->json([
        //         'error' => $e->getMessage()
        //     ], 500);
        // }
    }

    public function getById($id)
    {
        $position = Position::findOrFail($id);
        return response()->json($position);
    }


    public function updateData(Request $request, $id)
    {
        $validated = $request->validate([
            'position' => 'required|string|min:3|max:255|unique:positions,position,' . $id,
        ]);

        $position = Position::findOrFail($id);
        $position->update([
            'position' => $validated['position']
        ]);

        return response()->json(['message' => 'Data berhasil diperbarui']);
    }

    public function deleteData($id)
    {
        $data = Position::findOrFail($id);
        $data->delete();

        return response()->json([
            'message' => 'Data pegawai berhasil dihapus.'
        ]);
    }
}
