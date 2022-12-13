<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function index()
    {
        return [
            'data' => Student::get(),
        ];
    }

    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'student_number' => ['required', 'min:4', 'unique:students'],
            'email' => ['required', 'email'],
            'name' => ['required'],
        ]);

        if ($validated->fails()) {
            return response()->json($validated->errors(), 422);
        }

        $student = Student::create([
            'student_number' => $request->student_number,
            'email' => $request->email,
            'name' => $request->name,
        ]);

        $student = Student::create([
            'student_number' => $request->student_number,
            'email' => $request->email,
            'name' => $request->name,
        ]);

        return [
            'data' => $student,
            'message' => 'Mahasiswa berhasil didaftarkan.'
        ];
    }

    public function show($id)
    {
        // otomatis search by kolom id, dan hanya ambil data pertama
        $student = Student::find($id);

        $student = Student::where('id', $id)->first();

        return [
            'data' => $student
        ];
    }

    public function update(Request $request, $studentNumber)
    {
        $validated = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'name' => ['required'],
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
            'email'
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
