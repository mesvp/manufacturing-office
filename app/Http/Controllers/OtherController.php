<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CheckBox;
use Session;

class OtherController extends Controller
{
    public function CheckBoxStore(Request $request)
    {
        $userID = auth()->user()->id;
        $id = $request->input('id');
        $columns = $request->input('columns');

        $data = CheckBox::where('userID', $userID)->where('tableID', $id)->get();

        if ($data->count() > 0) {
            $data->each(function ($item) {
                $item->delete();
            });
        }

        if (isset($columns) && $columns != '') {
            foreach (explode(',', $columns) as $key => $value) {
                $insert = new CheckBox;
                $insert->userID = $userID;
                $insert->tableID = $id;
                $insert->CheckBox = $value;
                $insert->save();
            }
        }

        return response()->json(['success' => true, 'message' => 'Data Inserted']);
    }

    public function getCheckBoxData(Request $request)
    {
        $userID = auth()->user()->id;
        $id = $request->input('ID');

        $data = CheckBox::where('userID', $userID)->where('tableID', $id)->get();

        return response()->json(['success' => true, 'columns' => $data->pluck('CheckBox')]);
    }
    static function pass($message)
    {
        return response()->json(['success' => true,'message'=>$message]);
    }
    static function fail($message)
    {
        return response()->json(['success' => false,'message'=>$message]);
    }



 
}
