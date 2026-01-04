<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function getPackages()
    {
        $packages = Package::get();
        return response()->json($packages);
    }
}
