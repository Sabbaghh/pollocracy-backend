<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;

class UserController extends Controller
{
    use HttpResponses;
    /**
     * @PUBLIC
     * show all users who are not candidates
     */
    public function index()
    {
        $users = User::where('is_candidate', false)
            ->select('id', 'name')
            ->paginate(5);
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
     * make the user candidate
     */
    public function make_candidate(Request $request, string $id)
    {
        //
    }

    /**
     * @PRIVATE ( permission only for admin )
     * make the user admin
     */
    public function make_admin(Request $request, string $id)
    {
        //
    }

    /**
     * @PRIVATE (permission only for admin)
     * Remove any user
     */
    public function destroy(string $id)
    {
        //
    }
    /**
     * @PRIVATE (permission only for admin and the user/candidate)
     * user remove themselves
     */
    public function destroy_self(Request $request, string $id)
    {
        //
    }

    /**
     * @public
     * search for users/candidates
     */
    public function search_users(Request $request)
    {
        //
    }
}
