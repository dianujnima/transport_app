<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Administrator\AdminController;
use App\Models\Category;
use App\Services\Slug;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends AdminController {

    public function index() {
        $data = array(
            'title' => 'All Categories',
            'categories' => Category::latest()->get()
        );
        return view('admin.categories.all_categories')->with($data);
    }

    public function edit(Request $request) {
        $data = array(
            'title' => 'Edit Staff Member',
            'categories' => Category::withCount('childs')->latest()->get(),
            'category' => Category::hashidFind($request->id)
        );
        return view('admin.categories.all_categories')->with($data);
    }

    public function save(Request $request, Slug $slug) {
        $category_id = @hashids_decode($request->category_id);

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:100', 'unique:categories,name,'.$category_id],
        ]);

        if ($validator->fails()) {
            return ['errors' => $validator->errors()];
        }


        if($request->category_id){
            $category = Category::hashidFind($request->category_id);
            if($category->title != $request->title){
                $category->slug = $slug->createSlug('categories', $request->name, $category->id);
            }
            $msg = [
                'success' => 'Category has been updated',
                'redirect' => route('admin.categories'),
            ];
        }else{
            $category = new Category();
            $category->slug = $slug->createSlug('categories', $request->name);
            $msg = [
                'success' => 'Category has been added',
                'reload' => true,
            ];
        }

        if ($request->hasFile('category_image')) {
            $category_image = \CommonHelpers::uploadSingleFile($request->file('category_image'), 'upload/category_images/');
            if (is_array($category_image)) {
                return response()->json($category_image);
            }
            if (file_exists($category->image)) {
                @unlink($category->image);
            }
            $category->image = $category_image;
        }
        $category->name = ucwords($request->name);
        $category->save();

        return response()->json($msg);
    }

    public function delete(Request $request)
    {        
        $category = Category::hashidFind($request->id);
        // $category->delete();
        return response()->json([
            'success' => 'Category deleted successfully',
            'remove_tr' => true
        ]);
    }

    public function updateStatus(Request $request) {
        $category = Category::hashidFind($request->id);
        $category->is_active = !$category->is_active;
        $category->save();
        return response()->json([
            'success' => 'Category\'s Status Updated Successfully',
            'reload' => true
        ]);
    }
}
