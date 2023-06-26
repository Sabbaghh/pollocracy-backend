<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vote;
use App\Models\Feedback;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Traits\CheckUserExist;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ValidateUserIdRequest;

class UserController extends Controller
{
    use HttpResponses, CheckUserExist;
    /**
     * @PUBLIC
     * show all users who are not candidates
     */
    public function index()
    {
        $users = User::select('id', 'first_name', 'avatar', 'has_voted')->paginate(5);
        return response()->json($users);
    }

    /**
     * @PUBLIC
     * show user who are not candidates
     */
    public function show(string $id)
    {
        //
    }

    /**
     * @PRIVATE (permission only for admin and the user/candidate)
     * edit user data
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * @PRIVATE ( permission only for admin )
     * make the user candidate by id
     */
    public function make_candidate(ValidateUserIdRequest $request)
    {
        return $this->make_role($request, 'is_candidate');
    }

    public function make_admin(ValidateUserIdRequest $request)
    {
        return $this->make_role($request, 'is_admin');
    }

    /**
     * @PRIVATE (permission only for admin)
     * Remove any user by id
     */
    public function destroy(ValidateUserIdRequest $request)
    {
        $user = $this->check_user_exist($request);
        $user->tokens()->delete();
        //delete all feedback and votes related to the user or by the user
        $this->remove_related_data($user->id);
        $user->delete();
        return $this->success(
            ['user' => $user],
            'User is deleted',
            200
        );
    }
    /**
     * @PRIVATE (permission for any user)
     * user remove themselves
     */

    public function destroy_self()
    {
        /** @var \App\Models\User */

        $user = Auth::user();
        $user->tokens()->delete();
        //delete all feedback and votes related to the user or by the user
        $this
            ->remove_related_data($user->id);
        $user->delete();
        return $this
            ->success(['user' => $user], 'User is deleted', 200);
    }

    /**
     * @public
     * search for users/candidates
     */
    public function search_users(Request $request)
    {
        //
    }


    private function make_role(ValidateUserIdRequest $request, string $role)
    {
        $user = $this
            ->check_user_exist($request);
        if (!$user->{$role}) {

            $user->tokens()->delete();

            $user->update([$role => true]);
            return $this
                ->success(['user' => $user], "User has been successfully made a $role", 201);
        }
        return $this
            ->fail(null, "User is already a $role", 400);
    }
    private function remove_related_data(String $id)
    {
        Feedback::where('user_id', $id)->orWhere('candidate_id', $id)->delete();
        Vote::where('user_id', $id)->orWhere('candidate_id', $id)->delete();
    }
}
