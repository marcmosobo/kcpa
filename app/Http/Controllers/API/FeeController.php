<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AmountSetting;

class FeeController extends Controller
{
    public function index()
    {
        return AmountSetting::latest()->paginate(10);
    }
}
