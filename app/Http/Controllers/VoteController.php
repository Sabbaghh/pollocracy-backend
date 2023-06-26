<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Traits\CheckUserExist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class VoteController extends Controller
{
    use HttpResponses, CheckUserExist;
    public $ALLOWED_DAYS_MARGIN = 30;
    /**
     * @PRIVATE (permission admin/candidate/user)
     * vote for candidate
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        // Check if user voted for themselves
        if ($user->id == $request->candidate_id) {
            return $this->fail(null, 'You cannot vote for yourself', 403);
        }

        // Check if the user is a candidate, and if the candidate exists
        try {
            $votedFor = User::findOrFail($request->candidate_id);
            if (!$votedFor->is_candidate) {
                return $this->fail(null, 'User is not a candidate', 400);
            }
        } catch (ModelNotFoundException $e) {
            return $this->fail($e->getMessage(), 'User does not exist', 400);
        }

        // Check if the user has voted for the candidate within the last month
        $nowDate = Carbon::now();
        $lastVoteDate = $user->has_voted;
        if ($lastVoteDate !== null && $nowDate->diffInDays($lastVoteDate) < $this->ALLOWED_DAYS_MARGIN) {
            return $this->fail(null, 'You can only vote once every ' . $this->ALLOWED_DAYS_MARGIN  . ' days', 403);
        }

        // Update the user's has_voted field to now
        $user->has_voted = $nowDate;
        $user->save();
        // Create the vote record
        $vote = Vote::create([
            'user_id' => $user->id,
            'candidate_id' => $votedFor->id,
        ]);

        return $this->success($vote, 'Vote successful', 201);
    }
}
