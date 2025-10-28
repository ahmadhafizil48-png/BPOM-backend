<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'rating' => 'required|numeric|between:1,5',
            'pendapat' => 'required|string',
            'saran' => 'nullable|string',
            'file_laporan' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        // Cegah user kirim feedback lebih dari sekali
        if (Feedback::where('user_id', $request->user_id)->exists()) {
            return response()->json(['message' => 'Feedback sudah pernah dikirim'], 409);
        }

        // Upload file laporan
        if ($request->hasFile('file_laporan')) {
            $file = $request->file('file_laporan');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('laporan', $fileName, 'public');

            $validated['file_laporan'] = $fileName;
        }

        $feedback = Feedback::create($validated);

        return response()->json([
            'message' => 'Feedback & laporan berhasil dikirim',
            'data' => $feedback
        ], 201);
    }

    public function checkFeedback($user_id)
    {
        $hasFeedback = Feedback::where('user_id', $user_id)->exists();
        return response()->json(['submitted' => $hasFeedback]);
    }
}
