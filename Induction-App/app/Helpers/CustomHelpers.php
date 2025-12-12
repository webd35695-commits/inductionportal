<?php

namespace App\Helpers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
class CustomHelpers
{
    public static function greetUser($name)
    {
        return "Hello, " . ucfirst($name) . "!";
    }

 public static function fetchUserDetails()
{
    $userDetails = User::where('id', Auth::id())
    ->with([
        'candidateProfile.city.district.province', // deep nested loading
        'userContact', // direct relation to user
        'userSpouses.city.district.province' // deep nested loading,
    ])
    ->first();
    return $userDetails;
}

    public static function isActiveRoute($routeName)
    {
        return request()->routeIs($routeName) ? 'active' : '';
    }
}



   