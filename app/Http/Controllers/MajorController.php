<?php

namespace App\Http\Controllers;

use App\Models\Major;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MajorController extends Controller
{
    public function index()
    {
        return [
            'data' => Major::with('students')->get(),
        ];
    }

    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name' => ['required'],
            'code' => ['required'],
            'description' => ['nullable'],
        ]);

        if ($validated->fails()) {
            return response()->json($validated->errors(), 422);
        }

        $major = Major::create($request->all());

        return [
            'data' => $major,
            'message' => 'Data Jurusan baru berhasil didaftarkan.'
        ];
    }

    public function show($id)
    {
        // otomatis search by kolom id, dan hanya ambil data pertama
        $major = Major::find($id);

        $major = Major::where('id', $id)->first();

        return [
            'data' => $major
        ];
    }

    public function update(Request $request, $id)
    {
        $validated = Validator::make($request->all(), [
            'name' => ['required'],
            'description' => ['nullable'],
        ]);

        if ($validated->fails()) {
            return response()->json($validated->errors(), 422);
        }

        $major = Major::find($id);

        if (!$major) {
            return response()->json([
                'message' => 'Jurusan tidak ditemukan'
            ], 404);
        }

        // Menggunakan ->only agar spesifik field apa saja yang diinput
        $major = $major->update($request->only([
            'name',
            'description'
        ]));

        return [
            'data' => $major,
            'message' => 'Data Jurusan berhasil diperbaharui.'
        ];
    }

    public function destroy($id)
    {
        $major = Major::find($id);

        if (!$major) {
            return response()->json([
                'message' => 'Jurusan tidak ditemukan'
            ], 404);
        }

        $major->delete();

        return [
            'message' => 'Data Jurusan berhasil dihapus.'
        ];
    }
}
