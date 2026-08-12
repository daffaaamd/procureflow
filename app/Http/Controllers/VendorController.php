<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendor;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        $query = Vendor::latest();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
        }

        $vendors = $query->paginate(15);
        return view('vendors.index', compact('vendors'));
    }

    // Stub for create/store/show/edit/update/destroy
    public function show(Vendor $vendor) {
        return view('vendors.show', compact('vendor')); // Note: view doesn't exist yet, this is just a stub
    }
}
