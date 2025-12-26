<?php

use Illuminate\Support\Facades\Auth;

if (!function_exists('operator')) {
    function operator()
    {
        return Auth::guard('operator')->user();
    }
}
