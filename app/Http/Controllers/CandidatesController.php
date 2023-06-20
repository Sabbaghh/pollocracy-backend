<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class CandidatesController extends Controller
{
    /**
     * get all candidates with votes
     */
    public function index()
    {
        $users = User::where('is_candidate', true)
            ->select('id', 'name')
            ->withCount('receivedVotes')
            ->with([
                'receivedFeedback' => function ($query) {
                    $query
                        ->where('public', true)
                        ->select('id', 'user_id', 'candidate_id', 'feedback', 'anonymous')
                        ->orderBy('updated_at', 'desc');
                }
            ])
            ->get();
        return response()->json($users);
    }
    /**
     * show candidate with votes and feedback
     */
    public function show(User $user)
    {
        //check if user is candidate
        if (!$user->is_candidate) return response()->json(['message' => 'User is not a candidate'], 400);
        //get all necessary data
        $user
            ->loadCount('receivedVotes')
            ->load([
                'receivedFeedback' => function ($query) {
                    $query
                        ->where('public', true)
                        ->select('id', 'user_id', 'candidate_id', 'feedback', 'anonymous')
                        ->orderBy('updated_at', 'desc');
                }
            ]);
        // show user if the user is not anonymous in the feedback
        $formattedFeedback = $user->receivedFeedback->map(function ($feed) {
            return !$feed->anonymous ? $feed : $feed->load('user:id,name,email');
        });
        return [
            'id' => $user->id,
            'name' => $user->name,
            'received_votes_count' => $user->received_votes_count,
            'received_feedback' => $formattedFeedback
        ];
    }

    /**
     * remove candidate from the candidates list
     */
    public function remove_candidate(Request $request, string $id)
    {
    }
}
