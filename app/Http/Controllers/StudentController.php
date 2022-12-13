<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function index()
    {
        return [
            'data' => Student::with('major')->get(),
        ];
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $validated = Validator::make($request->all(), [
                'student_number' => ['required', 'min:4', 'unique:students'],
                'email' => ['required', 'email'],
                'name' => ['required'],
                'major_id' => ['required', Rule::exists('majors', 'id')],
            ]);

            if ($validated->fails()) {
                return response()->json($validated->errors(), 422);
            }

            $student = Student::create([
                'student_number' => $request->student_number,
                'email' => $request->email,
                'name' => $request->name,
                'major_id' => $request->major_id,
            ]);

            DB::commit();
            return [
                'data' => $student,
                'message' => 'Mahasiswa berhasil didaftarkan.'
            ];
        } catch (\Exception $error) {
            DB::rollBack();

            return response()->json([
                'message' => 'Oops.. gagal menambahkan Mahasiswa',
            ], 500);
        }
    }

    public function show($id)
    {
        $student = Student::with('major')->where('id', $id)->first();

        return [
            'data' => $student
        ];
    }

    public function update(Request $request, $studentNumber)
    {
        $validated = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'name' => ['required'],
            'major_id' => ['required', Rule::exists('majors', 'id')],
        ]);

        if ($validated->fails()) {
            return response()->json($validated->errors(), 422);
        }

        $student = Student::where('student_number', $studentNumber)->first();

        if (!$student) {
            return response()->json([
                'message' => 'Mahasiswa tidak ditemukan'
            ], 404);
        }

        // Menggunakan ->only agar spesifik field apa saja yang diinput
        $student = $student->update($request->only([
            'name',
            'email',
            'major_id'
        ]));

        return [
            'data' => $student,
            'message' => 'Data Mahasiswa berhasil diperbaharui.'
        ];
    }

    public function destroy($id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json([
                'message' => 'Mahasiswa tidak ditemukan'
            ], 404);
        }

        $student->delete();

        return [
            'message' => 'Data Mahasiswa berhasil dihapus.'
        ];
    }
}
