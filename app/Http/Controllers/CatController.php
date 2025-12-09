<?php

namespace App\Http\Controllers;

use App\Models\Cat;
use App\Models\Subcat;
use Illuminate\Http\Request;

class CatController extends Controller
{
    public function allCats()
    {
        $cats = Cat::all();
        return response()->json([
            'success' => true,
            'cats' => $cats
        ]);
    }

    public function allCatsWithSubcats()
    {
        $cats = Cat::with('subcats')->get();
        return response()->json([
            'success' => true,
            'cats' => $cats
        ]);
    }

    public function getCatBySlug($cat_slug)
    {
        $cat = Cat::where('cat_slug', $cat_slug)->first();
        if (!$cat) {
            return response()->json([
                'success' => false,
                'error' => 'Category not found'
            ]);
        }
        return response()->json([
            'success' => true,
            'cat' => $cat
        ]);
    }

    public function getCatWithSubcats($cat_slug)
    {
        $cat = Cat::with('subcats')->where('cat_slug', $cat_slug)->first();
        if (!$cat) {
            return response()->json([
                'success' => false,
                'error' => 'Category not found'
            ]);
        }
        return response()->json([
            'success' => true,
            'cat' => $cat
        ]);
    }

    public function allSubcats()
    {
        $subcats = Subcat::all();
        return response()->json([
            'success' => true,
            'subcats' => $subcats
        ]);
    }

    public function getSubcatBySlug($subcat_slug)
    {
        $subcat = Subcat::where('subcat_slug', $subcat_slug)->first();
        if (!$subcat) {
            return response()->json([
                'success' => false,
                'error' => 'Subcategory not found'
            ]);
        }
        return response()->json([
            'success' => true,
            'subcat' => $subcat
        ]);
    }

}
