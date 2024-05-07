<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Plant;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){
        $categories = Category::all();
        if(!$categories){
            return response()->json(['status'=>'Error','msg'=>'No data found'],404);
        }
        return response()->json(['status'=> 'Success','msg'=>'Categories is here','data'=>$categories]);
    }
    public function show(request $request){
        $id = $request->query('category_id');
        $category=Category::find($id);
        if(! $category){
            return response()->json(['status'=>'Error','msg'=>'No data found'],404);

        }
        return response()->json(['status'=> 'Success','msg'=>'Category is here','data'=>$category]);

    }


    public function getAllCategoriesWithPlants()
    {
        $allCategory = new Category;
        $allCategory->name = 'all';

        $allCategory->plants = Plant::all();

        return response()->json(['status'=>'Success','msg'=>'all categories with plants','data'=>$allCategory],200);
    }
}
