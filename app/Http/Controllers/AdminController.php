<?php

namespace App\Http\Controllers;

use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\User;

class AdminController extends Controller
{
    use HttpResponses;
    /**
     * Display all admins
     */
    public function index()
    {
        $admins = User::where('is_admin', true)
            ->select('id', 'name')
            ->paginate(5);
        return response()->json($admins);
    }

    /**
     * display one admin
     */
    public function show(string $id)
    {
        //
    }

    /**
     * remove admin permission
     */
    public function remove_admin(string $id)
    {
        //
    }
}
