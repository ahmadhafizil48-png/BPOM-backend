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
            'rating' => 'required|integer|min(1)|max(5)',
            'pendapat' => 'required|string',
            'saran' => 'nullable|string',
        ]);

        // Cegah user kirim feedback lebih dari sekali
        if (Feedback::where('user_id', $request->user_id)->exists()) {
            return response()->json(['message' => 'Feedback sudah pernah dikirim'], 409);
        }

        $feedback = Feedback::create($validated);

        return response()->json(['message' => 'Feedback berhasil dikirim', 'data' => $feedback], 201);
    }

    public function checkFeedback($user_id)
    {
        $hasFeedback = Feedback::where('user_id', $user_id)->exists();
        return response()->json(['submitted' => $hasFeedback]);
    }
}
