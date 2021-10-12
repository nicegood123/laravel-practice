<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\College as CollegeResource;
use App\Models\College;
use Validator;


class CollegeController extends BaseController
{

    public function index()
    {
        $colleges = College::all();
        return $this->handleResponse(CollegeResource::collection($colleges), 'Colleges have been retrieved!');
    }

    
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'description' => 'required'
        ]);
        if($validator->fails()){
            return $this->handleError($validator->errors());       
        }
        $college = College::create($input);
        return $this->handleResponse(new CollegeResource($college), 'College created!');
    }

   
    public function show($id)
    {
        $college = College::find($id);
        if (is_null($college)) {
            return $this->handleError('College not found!');
        }
        return $this->handleResponse(new CollegeResource($college), 'College retrieved.');
    }
    

    public function update(Request $request, College $college)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'description' => 'required'
        ]);

        if($validator->fails()){
            return $this->handleError($validator->errors());       
        }

        $college->name = $input['name'];
        $college->description = $input['description'];
        $college->save();
        
        return $this->handleResponse(new CollegeResource($college), 'College successfully updated!');
    }
   
    public function destroy(College $college)
    {
        $college->delete();
        return $this->handleResponse([], 'College deleted!');
    }
}