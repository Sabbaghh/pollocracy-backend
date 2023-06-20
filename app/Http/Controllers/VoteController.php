<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VoteController extends Controller
{
    /**
     * @PRIVATE (permission admin/candidate/user)
     * vote for candidate
     */
    public function store(Request $request)
    {
    }
    /**
     * @PRIVATE (permission admin/candidate/user)
     * unvote candidate
     */
    public function destroy(string $id)
    {
        //
    }
}
