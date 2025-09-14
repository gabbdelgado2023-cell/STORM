<?php

namespace App\Http\Controllers\Admin;

use App\Models\AuditLog;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:categories,name']);

        $category = Category::create(['name' => $request->name]);

        AuditLog::create([
            'user_id'   => auth()->id(),
            'action'    => "Created category: {$category->name}",
            'ip_address'=> request()->ip(),
        ]);

        // only flash success for store
        return redirect()->back()->with([
            'success' => 'Category added!',
            'edit_category_id' => null, // reset edit flag
        ]);
    }

    public function edit(Category $category)
    {
        return response()->json($category);
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ]);

        $category->update([
            'name' => $request->name,
        ]);

        AuditLog::create([
            'user_id'   => auth()->id(),
            'action'    => "Updated category: {$category->name}",
            'ip_address'=> request()->ip(),
        ]);

        // FIX: Use the correct route name
        return redirect()->route('admin.categories')->with('success', 'Category updated successfully!');
    }


    public function destroy(Category $category)
    {
        $category->delete();

        AuditLog::create([
            'user_id'   => auth()->id(),
            'action'    => "Deleted category: {$category->name}",
            'ip_address'=> request()->ip(),
        ]);

        return redirect()->route('admin.categories')->with('success', 'Category deleted successfully!');
    }
}
