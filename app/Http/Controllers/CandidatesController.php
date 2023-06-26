<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ValidateUserIdRequest;
use App\Traits\CheckUserExist;

class CandidatesController extends Controller
{
    use HttpResponses, CheckUserExist;
    /**
     * get all candidates with votes
     */
    public function index()
    {
        $users = User::where('is_candidate', true)
            ->select('id', 'first_name', 'last_name', 'username')
            ->withCount(['receivedVotes' => function ($query) {
                //where votes this month and year
                $query->whereMonth('created_at', date('m'))
                    ->whereYear('created_at', date('Y'));
            }])
            ->orderBy('received_votes_count', 'desc')
            ->paginate(6);
        return $this->success($users, "Candidates list", 200);
    }
    /**
     * show candidate with votes and feedback
     */
    public function show(User $user)
    {
        //check if user is candidate
        if (!$user->is_candidate) {
            return $this
                ->fail(null, "User isn't a candidate", 400);
        }
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
            'firs_name' => $user->first_name,
            'received_votes_count' => $user->received_votes_count,
            'received_feedback' => $formattedFeedback
        ];
    }

    /**
     * remove candidate from the candidates list
     */
    public function remove_candidate(ValidateUserIdRequest $request)
    {
        $user = $this->check_user_exist($request);
        if (!$user->is_candidate) return $this->fail(null, "User isn't a candidate", 400);
        $user->is_candidate = false;
        $user->save();
        return $this->success($user, "User is no longer a candidate", 200);
    }

    public function remove_candidate_self()
    {
        /** @var \App\Models\User */
        $user = Auth::user();
        if (!$user->is_candidate) return $this->fail(null, "User isn't a candidate", 400);
        $user->tokens()->delete();
        $user->update(['is_candidate' => false]);
        return $this->success($user, "User is no longer a candidate", 200);
    }
}
