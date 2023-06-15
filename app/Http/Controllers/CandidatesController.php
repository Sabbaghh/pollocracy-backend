<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CandidatesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where('is_candidate', true)->with('receivedVotes')->get();

        $formattedData = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'avatar' => $user->avatar,
                'is_candidate' => $user->is_candidate,
                'votes' => $user->receivedVotes->count(),
            ];
        });

        return response()->json($formattedData);
    }




    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }
    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load([
            'receivedVotes',
            'receivedFeedback' => function ($query) {
                $query->where('public', true)->with('user:id,name')->latest('updated_at');
            }
        ]);

        $formattedFeedbacks = $user->receivedFeedback->map(function ($feedback) {
            return [
                'id' => $feedback->id,
                'feedback' => $feedback->feedback,
                'anonymous' => $feedback->anonymous,
                'created_at' => $feedback->created_at,
                'user' => $feedback->user,
            ];
        });
        $formattedVotes = $user->receivedVotes->count();

        $formattedUser = [
            'id' => $user->id,
            'name' => $user->name,
            'avatar' => $user->avatar,
            'is_candidate' => $user->is_candidate,
            'votes' => $formattedVotes,
            'feedback' => $formattedFeedbacks,
        ];

        return response()->json($formattedUser);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
