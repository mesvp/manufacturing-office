<?php

namespace App\Http\Controllers\ProductionLineup\Pallete;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\ProductionLineUp\Pallete_Model;
use Illuminate\Support\Facades\Log;

class Pallete_Controller extends Controller
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
	public function index(Request $request)
	{
		$data['menu'] = 'pallete';
    $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
		$data['empId'] = request()->session()->get('empId');
    $data['pallets'] = DB::table('tble_pallete')
    ->leftJoin('mstr_emp','mstr_emp.id', '=', 'tble_pallete.uploader')
    ->select('tble_pallete.*','mstr_emp.fullname' )
    ->get();
		
		return view('ProductionLineUp.Pallete.index', $data);
	}
  public function getUserIP()
	{
		// Get real visitor IP behind CloudFlare network
		if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
			$_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
			$_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
		}
		$client  = @$_SERVER['HTTP_CLIENT_IP'];
		$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
		$remote  = $_SERVER['REMOTE_ADDR'];

		if (filter_var($client, FILTER_VALIDATE_IP)) {
			$ip = $client;
		} elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
			$ip = $forward;
		} else {
			$ip = $remote;
		}

		return $ip;
	}

  public function insert(Request $request) {
    $request->validate(['file' => 'required']);

    // 1. CRITICAL: Handle the \r line endings found in your file
    ini_set("auto_detect_line_endings", true);

    $file = $request->file('file');
    $path = $file->getRealPath();
    $handle = fopen($path, 'r');

    // 2. Skip the header
    $header = fgetcsv($handle, 10000, ",");
    
    $count = 0;
    $skipped = 0;
    //$skippedSl = '';

    while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
        // Skip empty rows
        if (empty($data) || count($data) < 2) continue;

        $palleteName = trim($data[0]);
        $itemCode = trim($data[1]);

        // 3. Debugging: Log the data being read to see if it's empty
        Log::info("Processing: Pallete: $palleteName, Serial: $itemCode");

        // 4. Check for duplicates
        $exists = Pallete_Model::where('pallete', $palleteName)
                                ->where('serial', $itemCode)
                                ->exists();

        if ($exists) {
            $skipped++;
            //$skippedSl = $skippedSl.', '.$itemCode;
            continue; 
        }

        // 5. Manual Insert (Try/Catch to see database errors)
        try {
            Pallete_Model::create([
                'pallete'   => $palleteName,
                'serial'    => $itemCode,
                'uploader'  => request()->session()->get('empId'),
            ]);
            $count++;
        } catch (\Exception $e) {
            Log::error("Database Error on row $itemCode: " . $e->getMessage());
        }
    }

    fclose($handle);
    
    return back()->with('success', "Imported $count records. Un-Imported $skipped duplicates.");
  }
}
