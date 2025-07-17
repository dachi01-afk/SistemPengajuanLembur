<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Role;
use App\Models\Department;
use App\Models\Position;

class UserController extends Controller
{
    protected $path = "Admin";
    protected $module = "dataPegawai";
    protected $title = "Daftar User Pegawai";



    public function index(Request $request)
    {
        $act = $request->query('view', 'default');

        $roles = Role::all();
        $departments = Department::all();
        $positions = Position::all();

        return view($this->path . '.' . $this->module, [
            'title' => $this->title,
            'path' => $this->path,
            'module' => $this->module,
            'act' => $act,


            'roles' => $roles,
            'departments' => $departments,
            'positions' => $positions,

        ]);
    }

    public function getShowData(Request $request)
    {
        if ($request->ajax()) {
            $data = User::query()
                ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
                ->leftJoin('departments', 'users.department_id', '=', 'departments.id')
                ->leftJoin('positions', 'users.position_id', '=', 'positions.id')
                ->whereNull('users.deleted_at')
                ->select([
                    'users.*',
                    'roles.role as role_name',
                    'departments.department as department_name',
                    'positions.position as position_name',
                ]);

            if ($request->has('search') && $request->search['value'] != '') {
                $search = $request->search['value'];
                $data = $data->where(function ($query) use ($search) {
                    $query->where('users.name', 'like', "%{$search}%")
                        ->orWhere('users.nip', 'like', "%{$search}%")
                        ->orWhere('users.no_tlp', 'like', "%{$search}%")
                        ->orWhere('users.email', 'like', "%{$search}%")
                        ->orWhere('roles.role', 'like', "%{$search}%")
                        ->orWhere('departments.department', 'like', "%{$search}%")
                        ->orWhere('positions.position', 'like', "%{$search}%");
                });
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('role', fn($user) => $user->role_name ?? '-')
                ->addColumn('department', fn($user) => $user->department_name ?? '-')
                ->addColumn('position', fn($user) => $user->position_name ?? '-')
                ->addColumn('action', function ($user) {
                    return '
                     <div class="flex items-center space-x-3">
                        <button type="button"
                            class="text-purple-600 hover:text-purple-800 focus:outline-none editData" data-id="' . $user->id . '">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <button type="button"
                            class="text-purple-600 hover:text-purple-800 focus:outline-none deleteData" data-id="' . $user->id . '">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }


    public function insertData(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'min:3',
                'max:255'
            ],
            'nip' => [
                'required',
                'string',
                'max:30',
                'regex:/^[0-9]+$/',
                'unique:users,nip'
            ],
            'no_tlp' => [
                'required',
                'string',
                'max:20',
                'regex:/^[0-9]+$/',
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email'
            ],
            'password' => [
                'required',
                'string',
                'min:6',
                'max:255',
                // Jika mau lebih ketat bisa pakai regex misalnya:
                // 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
            ],
            'role_id' => [
                'required',
                'integer',
                Rule::exists('roles', 'id')
            ],
            'department_id' => [
                'required',
                'integer',
                Rule::exists('departments', 'id')
            ],
            'position_id' => [
                'required',
                'integer',
                Rule::exists('positions', 'id')
            ],
        ]);


        User::create([
            'name' => $validated['name'],
            'nip' => $validated['nip'],
            'no_tlp' => $validated['no_tlp'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $validated['role_id'],
            'department_id' => $validated['department_id'],
            'position_id' => $validated['position_id'],
        ]);

        return response()->json(['message' => 'Data pegawai berhasil ditambahkan.']);
    }

    public function Edit(Request $request)
    {
        $act = 'edit';
        $editId = $request->query('id');

        if (!$editId) {
            return redirect()->route('pegawai.index')->with('error', 'ID tidak ditemukan!');
        }

        $editData = User::findOrFail($editId);

        $roles = Role::all();
        $departments = Department::all();
        $positions = Position::all();

        return view($this->path . '.' . $this->module, [
            'title' => $this->title,
            'path' => $this->path,
            'module' => $this->module,
            'act' => $act,
            'editId' => $editId,
            'editData' => $editData,
            'roles' => $roles,
            'departments' => $departments,
            'positions' => $positions,
        ]);
    }

    public function updateData(Request $request, $id)
    {

        $request->merge([
            'name' => $request->input('name_edit'),
            'nip' => $request->input('nip_edit'),
            'no_tlp' => $request->input('no_tlp_edit'),
            'email' => $request->input('email_edit'),
            'password' => $request->input('password_edit'),
            'role_id' => $request->input('role_id_edit'),
            'department_id' => $request->input('department_id_edit'),
            'position_id' => $request->input('position_id_edit'),
        ]);

        if (!$id || !is_numeric($id)) {
            return redirect()->back()->withErrors(['error' => 'ID user tidak valid untuk update!']);
        }

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'min:3',
                'max:255'
            ],

            'nip' => [
                'required',
                'string',
                'max:30',
                'regex:/^[0-9]+$/',
                Rule::unique('users', 'nip')->ignore($id, 'id'),
            ],

            'no_tlp' => [
                'required',
                'string',
                'max:20',
                'regex:/^[0-9]+$/',
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                // Rule::unique('users')->ignore($id),
                Rule::unique('users', 'email')->ignore($id, 'id'),
            ],
            'password' => [
                'nullable',
                'string',
                'min:6',
                'max:255'
                // Jika mau lebih ketat bisa pakai regex misalnya:
                // 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
            ],
            'role_id' => [
                'required',
                'integer',
                Rule::exists('roles', 'id')
            ],
            'department_id' => [
                'required',
                'integer',
                Rule::exists('departments', 'id')
            ],
            'position_id' => [
                'required',
                'integer',
                Rule::exists('positions', 'id')
            ],
        ]);

        $user = User::findOrFail($id);

        $user->name = $validated['name'];
        $user->nip = $validated['nip'];
        $user->no_tlp = $validated['no_tlp'];
        $user->email = $validated['email'];
        $user->role_id = $validated['role_id'];
        $user->department_id = $validated['department_id'];
        $user->position_id = $validated['position_id'];
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        return response()->json(['message' => 'Data pegawai berhasil diperbarui.']);
    }

    public function deleteData($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json([
                'message' => 'Data pegawai berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menghapus data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
