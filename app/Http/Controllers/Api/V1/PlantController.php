<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Plant;
use Illuminate\Http\Request;

class PlantController extends Controller
{

    public function showAll(request $request)
    {
        $categoryId= $request->query('category_id');
        $plants= Plant::whereHas('categories',function ($query)use($categoryId){
            if($categoryId){
                $query->where('category_id',$categoryId);
            }
        })->get();
        if(count($plants) == 0){
            return response()->json(['status'=>'Error','msg'=>'No data found'],404);
        }
        return response()->json(['Status'=>'Success','msg'=>'plants','data'=>$plants],200);

    }


    public function search($name){
        $plant_search= Plant::where('name','LIKE','%'.$name.'%')->get();
        if(count($plant_search) == 0){
            return response()->json(['status'=>'Error','msg'=>'No data found'],404);
        }
        return response()->json(['status'=>'Success','msg'=>'plants','data'=>$plant_search],200);

    }

    public function popularPlant()
    {
        $popularPlantIds = [13, 14, 2];
        $popularPlants = Plant::whereIn('id', $popularPlantIds)->get();
        if(count($popularPlants) == 0){
            return response()->json(['status'=>'Error','msg'=>'No data found'],404);
        }
        return response()->json(['status'=>'Success','msg'=>'plants','data'=>$popularPlants],200);

    }
}
