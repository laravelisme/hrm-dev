<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\MKaryawan;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private User $user;
    private MKaryawan $mkaryawan;

    public function __construct(User $user, MKaryawan $mkaryawan)
    {
        $this->user = $user;
        $this->mkaryawan = $mkaryawan;
    }


}
