<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Plant;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;

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
        $popularPlantIds = [8, 9, 2];
        $popularPlants = Plant::whereIn('id', $popularPlantIds)->get();
        if(count($popularPlants) == 0){
            return response()->json(['status'=>'Error','msg'=>'No data found'],404);
        }
        return response()->json(['status'=>'Success','msg'=>'plants','data'=>$popularPlants],200);

    }

    public function togglePlant(Request $request)
    {
        try{
            $plantId = $request->query('plant_id');
             JWTAuth::parseToken()->toUser();
             auth()->user()->favouritePlants()->toggle($plantId);
             return response()->json(['status'=>'Success','msg'=>'plant toggled successfully'],200);


        }catch (TokenExpiredException $e){
            return response()->json(['status'=>'Error','msg'=>$e->getMessage()], 401);

        }catch (Exception $e){
            return response()->json(['status'=>'Error','msg'=>$e->getMessage()], 422);

        }



    }

    public function favouritePlants()
    {
        try{
            $user = JWTAuth::parseToken()->toUser();
            $favoritePlants = $user->favouritePlants()->with('favouriteByUsers')->get();
            return response()->json(['status'=>'Success','msg'=>'plants','data'=>$favoritePlants],200);
        }catch (TokenExpiredException $e){
            return response()->json(['status'=>'Error','msg'=>$e->getMessage()], 401);

        }catch (Exception $e){
            return response()->json(['status'=>'Error','msg'=>$e->getMessage()], 422);

        }



    }
}
