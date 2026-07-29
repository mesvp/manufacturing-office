<?php

namespace App\Http\Controllers\ProductionLineUp\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\ProductionLineUp\materialMaster_Model;
// use App\Models\FactoryCreater\Factory_Uom;
use Illuminate\Support\Facades\DB;

class Material_Controller extends Controller
{
  public  static function PermittedMenuList($sessionId){
      //Menu Permission
      $res = DB::table('prod_menu_laravel')
      ->leftJoin('prod_menu_acc_laravel', 'prod_menu_laravel.id', '=', 'prod_menu_acc_laravel.menu_id')
      ->where('prod_menu_acc_laravel.emp_id', '=', $sessionId)
      ->where('prod_menu_acc_laravel.accessType', '=', 'yes')
      ->select('prod_menu_laravel.*', 'prod_menu_acc_laravel.accessType')
      ->get();
      
      return $res;
  }  
	public function index()
	{
		$data['menu'] = 'material';
		$data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
		$data['materials'] = materialMaster_Model::all();
		$data['admindata'] = Admin::all_admin();
		return view('ProductionLineUp.Master.Material.index', $data);
	}

	public function insert(Request $request)
	{
		$data = [
			'title' => $request->material_name,
			'uom' => $request->uom,
			'created_by' => auth()->id(),
			'created_at' => now(),
			'updated_at' => now(),
		];


		// Assuming materialMaster_Model is already imported
		materialMaster_Model::create($data);

		return redirect('production-lineup/material')->with('success', 'Material added successfully.');
	}
}
