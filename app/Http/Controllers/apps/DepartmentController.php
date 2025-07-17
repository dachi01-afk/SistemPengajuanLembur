<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Department;

class DepartmentController extends Controller
{
    protected $path = "Admin";
    protected $module = "dataDepartment";
    protected $title = "Daftar Department";





    public function index()
    {
        $departments = Department::all();

        return view($this->path . '.' . $this->module, [
            'title' => $this->title,
            'path' => $this->path,
            'module' => $this->module,
            'departments' => $departments,
        ]);
    }


    public function insertData(Request $request)
    {


        $validated = $request->validate([
            'department' => 'required|string|min:3|max:255|unique:departments,department',
        ]);

        $department = Department::create([
            'department' => $validated['department'],
        ]);

        return response()->json([
            'message' => 'Data berhasil disimpan',
            'data' => $department,
        ]);
        // try {
        // } catch (\Exception $e) {
        //     return response()->json([
        //         'error' => $e->getMessage()
        //     ], 500);
        // }
    }

    public function getById($id)
    {
        $department = Department::findOrFail($id);
        return response()->json($department);
    }


    public function updateData(Request $request, $id)
    {
        $validated = $request->validate([
            'department' => 'required|string|min:3|max:255|unique:departments,department,' . $id,
        ]);

        $department = Department::findOrFail($id);
        $department->update([
            'department' => $validated['department']
        ]);

        return response()->json(['message' => 'Data berhasil diperbarui']);
    }

    public function deleteData($id)
    {
        $data = department::findOrFail($id);
        $data->delete();

        return response()->json([
            'message' => 'Data pegawai berhasil dihapus.'
        ]);
    }
}
