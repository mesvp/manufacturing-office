<!DOCTYPE html>
<html lang="en" dir="ltr">
@php
$userid = Illuminate\Support\Facades\Auth::guard('admin')->id();
$userData = App\Models\Admin::where('id', $userid)->first();
$emp = [];
$ex_type = [];
$step = [];
if ($userData->role != 0) {
$empoye_depart = App\Models\Department_Assign::where('userID', $userData->id)->get();
foreach ($empoye_depart as $empda) {
$emp[] = $empda->departments;
if ($empda->ex_type == 1) {
$ex_type[$empda->departments]['inputer'] = $empda->ex_type;
}
if ($empda->ex_type == 2) {
$ste = [];
foreach ($empoye_depart as $empdass) {
if ($empdass->step != 0 && $empda->departments==$empdass->departments ) {
$ste[] = $empdass->step;
}
}
$ex_type[$empda->departments]['approver'] = $ste;
}
$step[] = $empda->step;
}
foreach (App\Models\Employee_Department::where('userID', $userData->id)->get() as $empdepartment) {
$emp[] = $empdepartment->Departments;
}
$Forwaded_data = App\Models\Forwarded_Data::where('Forward_To_id', $userData->id)->where('status', 0)->get();
$storeapprove=App\Models\Storeissue\StoreIssueApprovedMaterial::where(['recived_by'=>$userid,'status'=>0])->count();
foreach ($Forwaded_data as $f_data) {
$ex_type[$f_data->DepartmentID]['Forward'] = [1, 2, 3];
}
} else {
foreach (App\Models\Departments::get() as $dasss) {
$emp[] = $dasss->id;
}
$ex_type = [1, 2];
$step = [0, 1, 2, 3];
}

Session::put('Department', array_unique($emp));
Session::put('EXT', $ex_type);
Session::put('STEP', $step);

// for emp & visitor gatepass
function setUserSessionData()
{
  $userid = Illuminate\Support\Facades\Auth::guard('admin')->id();
  $userData = App\Models\Admin::where('id', $userid)->first();

    $emp = [];
    $ex_type = [];
    $step = [];

    if ($userData->role != 0) {
        $empoye_depart = App\Models\Department_Assign::where('userID', $userData->id)->get();
        foreach ($empoye_depart as $empda) {
            $emp[] = $empda->departments;
            
            if ($empda->ex_type == 1) {
                $ex_type[$empda->departments]['inputer'] = $empda->ex_type;
            }

            if ($empda->ex_type == 2) {
                $ste = [];
                foreach ($empoye_depart as $empdass) {
                    if ($empdass->step != 0 && $empda->departments == $empdass->departments) {
                        $ste[] = $empdass->step;
                    }
                }
                $ex_type[$empda->departments]['approver'] = $ste;
            }
            $step[] = $empda->step;
        }

        foreach (App\Models\Employee_Department::where('userID', $userData->id)->get() as $empdepartment) {
            $emp[] = $empdepartment->Departments;
        }

        $Forwaded_data = App\Models\GatePass\Forwarded_Data_Gatepass::where('Forward_To_id', $userData->id)
            ->where('status', 0)
            ->get();

        foreach ($Forwaded_data as $f_data) {
            $ex_type[$f_data->DepartmentID]['Forward'] = [1, 2, 3];
        }
    } else {
        foreach (App\Models\Departments::get() as $dasss) {
            $emp[] = $dasss->id;
        }
        $ex_type = [1, 2];
        $step = [0, 1, 2, 3];
    }

    Session::put('CustDepartment', array_unique($emp));
    Session::put('CUSTEXT', $ex_type);
    Session::put('CUSTSTEP', $step);
}
@endphp

<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="{{url('css/style.css')}}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">

  <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.1/mdb.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">

  <style>
    @import '~mdb-ui-kit/css/mdb.min.css';

    button#dropdownMenuButton1 {
      background: transparent;
      border: none;
      color: black !important;
      border: none;
      outline: none;
      box-shadow: none;
    }

    .sidebar .nav-links li i {
      /* height: 50px; */
      min-width: 45px !important;
      text-align: center;
      line-height: 50px;
      color: #0a0a0a;
      font-size: 20px;
      cursor: pointer;
      transition: all 0.5s ease;
    }


    .show {
      position: unset !important;
      inset: 0px auto auto 0px !important;
      margin: 0px;
      transform: translate(0px, 0px) !important;
      margin-left: 10px;
      background: transparent !important;
      border: none !important;
      margin-top: -15px;
    }


    button#bbbhggghgghh::after {

      display: none;
    }


    button#bbbhggghgghh {
      position: unset !important;
      inset: 0px auto auto 0px !important;
      margin: 0px;
      transform: translate(0px, 0px) !important;
      margin-left: 10px;
      background: transparent !important;
      border: none !important;
      margin-top: -15px;
      color: #000;



    }

    ul.dropdown-menu.show {
      margin-top: -14px !important;
    }


    a.dropdown-item {
      color: #000 !important;
    }

    ul#myDIV .dropdown {
      position: unset !important;
      width: 100%;
      /* height: 100%; */
    }

    .dropdown {
      background: #BCD8FF;
      border-bottom: 1px solid #ddd;
    }

    ul#myDIV ul.dropdown-menu.show {
      width: 100%;
    }


    .sidebar {
      /* position: fixed; */
      top: 0;
      margin-left: 0;
      height: auto;
      width: 300px;
      background: #e8eff7;
      /* z-index: 100; */
      transition: all 0.5s ease;
      box-shadow: rgba(0, 0, 0, 0.15) 2.4px 2.4px 3.2px;
    }

    .iocn-link {
      padding: 0px 10px;
    }

    .iocn-link img {
      margin-right: 10px;
    }


    ul.dropdown-menu.show li a.dropdown-item {
      padding: 10px 30px;
      display: block;
      width: 100%;
      height: 100%;
    }

    a.dropdown-item:hover {
      background: #6741D5 !important;
    }

    ul#myDIV ul.dropdown-menu.show {
      width: 100%;
      padding-bottom: 0px;
    }



    .sidebar {
      /* position: fixed; */
      top: 0;
      margin-left: 0;
      height: auto;
      width: 20%;
      background: #e8eff7 !important;
      /* z-index: 100; */
      transition: all 0.5s ease;
      box-shadow: rgba(0, 0, 0, 0.15) 2.4px 2.4px 3.2px;
    }


    section.sectin {
      min-height: 95vh;
      height: 100% !important;
    }

    section.sectin div#sidebar {
      height: initial !important;
      padding-bottom: 20px !important;
    }

    .close {
      float: unset !important;
      font-size: unset !important;
      font-weight: unset !important;
      line-height: unset !important;
      color: unset !important;
      text-shadow: unset !important;
      opacity: unset !important;
    }


    ul.dropdown-menu.show {
      BACKGROUND: #87B8FB !IMPORTANT;
      margin-top: 0px !important;
    }

    ul.dropdown-menu.show li {
      border-bottom: 1px solid #ddd;
    }

    a.nav-link {
      display: block;
    }

    button#bbbhggghgghh {
      /* display: block; */
      width: 100%;
      height: 100%;
      text-align: left;
    }

    button#bbbhggghgghh a.nav-link {
      display: block;
      z-index: 999 !important;
      width: 100%;
      height: 100%;
    }

    a#sumit {
      background: #BCD8FF;
      border: none !important;
      display: block;
      padding-bottom: 10px;
      padding-left: 15px;
    }

    .dropdown:hover ul#myDIV33 {
      display: block !important;
    }

    ul.main_light {
      position: relative;
    }

    ul.under_drop {
      display: none;
    }

    ul.under_drop {
      list-style: none;
      margin-left: -52px;
    }

    li.under_t {
      list-style: none;
    }

    ul.under_drop li.pura {
      background: #a2d6dd;
      padding: 0px 0px;
      border-bottom: 1px solid #ddd;
    }

    ul.under_drop li.pura i {
      margin-right: 10px;
    }


    /* .pura:hover {
      background: #6741D5 !important;
    } */

    li.under_t {
      background: #BCD8FF;
      width: 100%;

      width: 260px;
      margin-left: -29px;
      padding-bottom: 10;
      border-bottom: 1px solid #ddd;
      color: #0c0c0c;
    }

    li.under_t i {
      margin-right: 10px;
    }

    li.under_t a {
      text-decoration: none;
      color: black;
      width: 100% !important;
      display: block;

    }

    ul.under_drop {
      margin-top: 10px;
    }


    ul#myDIVwwep .active {
      background: #6741D5 !important;
    }
    .activet {
      background-color: #87B8FB !important;
    }
    .activejj {
      background: #4FC8D9 !important;
    }

    li.under_t.luck {
      padding: 10px;
    }

    li.under_t.luck {
      padding: 10px 0px;
      padding-bottom: 10px;
    }

    li.under_t.luck:hover {
      padding: 10px 0px;

      cursor: pointer;

    }

    div#sidebar {
      width: 50px;

    }

    li.under_t.luck.activejj {
      padding-bottom: 0px;
    }

    .luck {
      width: 50px !important;
      transition: all 0.5s ease;
    }
    
    div#sidebar:hover .luck {
      width: 250px !important;
      align-items: center;
      transition: all 0.5s ease;
    }

    span.mjhu {
      display: none;
      transition: all 0.5s ease !important;
      font-size: 14px;
    }

    li#javab::before {
      display: none !important;
    }

    div#sidebar:hover span.mjhu {
      display: -webkit-inline-box;
      transition: all 0.5s ease;

    }

    div#sidebar:hover {
      width: 280px !important;
      transition: all 0.5s ease;
    }


    div#sidebar:hover li.under_t.luck::before {
      display: block;
      transition: all 0.5s ease;
    }

    li.pura a.dropdown-item {
      padding: 10px;
    }

    .show li.under_t.luck::before {
      display: block !important;
    }

    .luck {
      padding-left: 10px !important;
    }

    li.under_t.luck::before {
      position: absolute;
      right: 10px;
      top: 15px;
      content: "";
      width: 7px;
      height: 7px;
      border: 1px solid black;
      border-left: 0;
      border-top: 0;
      transform: rotate(45deg);
      display: none;
    }



    .showli.under_t.luck::before {
      position: absolute;
      right: 10px;
      top: 15px;
      content: "";
      width: 10px;
      height: 10px;
      border: 3px solid black;
      border-left: 0;
      border-top: 0;
      transform: rotate(45deg);
      display: block;
    }

    li.under_t.luck {
      position: relative;
    }

    li#javab::before {
      display: none;
    }

    .show {
      width: 280px !important;
    }

    .show li.under_t.luck {
      width: 250px !important;
    }

    .show li.under_t.luck span.mjhu {
      display: inline-block;
    }


    svg.bi.bi-geo-alt {
      margin-bottom: 10px;
    }

    ul.under_drop li {
      padding-left: 10px !important;
    }

    ul.under_drop.extra {
      margin-left: -13px !important;
    }

    .loader-container {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(255, 255, 255, 0.8);
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 9999;
    }

    .loader {
      border: 6px solid #f3f3f3;
      border-top: 6px solid #3498db;
      border-radius: 50%;
      width: 60px;
      height: 60px;
      animation: spin 2s linear infinite;
    }

    @keyframes spin {
      0% {
        transform: rotate(0deg);
      }

      100% {
        transform: rotate(360deg);
      }
    }

    .count span {
      display: inline-block;
      width: auto !important;
      height: 20px;
      background-color: #007bff;
      color: #fff;
      border-radius: 40%;
      text-align: center;
      line-height: 20px;
      margin-left: 5px;
      padding: 0px 5px !important;
    }

    .count.active {
      background-color: #007bff;
      color: #fff;
      border-radius: 5px;
    }

    .nav-tabs .nav-item.show .nav-link,
    .nav-tabs .nav-link.active {
      color: #ffffff !important;
      border-color: #ffffff !important;
    }

    .nav-tabs .nav-link:hover {
      border-color: transparent;
      background: transparent !important;
      color: #858181 !important;
    }

    .nav-tabs .nav-link {
      --mdb-nav-tabs-link-border-bottom-width: 18px;
      padding: 0 15px;
    }

    td.PendingColor {
      color: red !important;
    }

   /* .show {
      width: 100% !important;
    }  */


    .left-bar p {
      margin: 8% !important;
      text-transform: capitalize;
    }

    textarea.select2-search__field {
      width: 22em !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
      color: #928a8a !important;
      line-height: 28px !important;
      font-size: 13px !important;
    }

    .activesle {
      background: #6741D5 !important;
      color: white !important;
    }

    span.select2.select2-container.select2-container--default {
      top: 5px;
      width: 100% !important;
    }
  </style>

</head>
@php
$Department=Session::get('Department');
$EXT=Session::get('EXT');
$STEP=Session::get('STEP');
    
      //Factory approval count start
      $factory_data = App\Models\FactoryCreater\Factory_Address_Detail::select('factory_address_details.*');

            if (isset($EXT[1]['Forward']) && isset($EXT[1]['approver'])) {
            $factory_data = $factory_data->where(function ($factory_data) use ($EXT) {
                $factory_data->whereNull('Approve_status')
                            ->where('Forward_Status', 0)
                            ->whereIn('Approve_Step', $EXT[1]['approver']);
            })
            ->orWhere(function ($factory_data) {
                $factory_data->whereIn('id', function($query) {
                    $query->select('DataID')
                          ->from('forwarded_data')
                          ->where('Forward_To_id', auth()->user()->id)
                          ->where('status', 0);
                })
            ->where(function($query) {
                $query->whereNull('Approve_status')
                      ->orWhere('Approve_status', 'FORWARD');
            })
            ->where('Forward_Status', 1);
            })
              ->orderBy('id', 'DESC');
            } elseif (isset($EXT[1]['Forward'])) {
                $factory_data = $factory_data->where('Forward_Status', 1)
                  ->whereIn('id', function($query) {
                      $query->select('DataID')
                            ->from('forwarded_data')
                            ->where('Forward_To_id', auth()->user()->id)
                            ->where('status', 0);
                  })
                  ->where(function($query) {
                      $query->whereNull('Approve_status')
                            ->orWhere('Approve_status', 'FORWARD');
                  })
                                            ->orderBy('id', 'DESC');
            } elseif (isset($EXT[1]['approver'])) {
                $factory_data = $factory_data->whereNull('Approve_status')
                                            ->where('Forward_Status', 0)
                                            ->whereIn('Approve_Step', $EXT[1]['approver'])
                                            ->orderBy('id', 'DESC');
            }
          $FACTORY = $factory_data->count();
      //Factory approval count end

      //Material Management approval count start
      $mat_mng_data = App\Models\MaterialManagement\MaterialManagement_Add_Material::select('materialmanagement_add_material.*');

             if (isset($EXT[4]['Forward']) && isset($EXT[4]['approver'])) {
                  $mat_mng_data = $mat_mng_data->where(function ($query) use ($EXT) {
                      $query->whereNull('Approve_status')
                          ->where('Forward_Status', 0)
                          ->whereIn('Approve_Step', $EXT[4]['approver']);
                  })
                  ->orWhere(function ($query) {
                      $query->whereIn('id', function ($subquery) {
                          $subquery->select('DataID')
                              ->from('forwarded_data')
                              ->where('Forward_To_id', auth()->user()->id)
                              ->where('status', 0);
                      })
                      ->where(function ($subquery) {
                          $subquery->whereNull('Approve_status')
                              ->orWhere('Approve_status', 'FORWARD')
                              ->where('Forward_Status', 1);
                      });
                  })
                  ->orderBy('id', 'DESC');
          } elseif (isset($EXT[4]['Forward'])) {
                $mat_mng_data = $mat_mng_data->where('Forward_Status', 1)
                    ->whereIn('id', function ($subquery) {
                        $subquery->select('DataID')
                            ->from('forwarded_data')
                            ->where('Forward_To_id', auth()->user()->id)
                            ->where('status', 0);
                    })
                    ->where(function ($query) {
                        $query->whereNull('Approve_status')
                            ->orWhere('Approve_status', 'FORWARD');
                    })
                    ->orderBy('id', 'DESC');
          } elseif (isset($EXT[4]['approver'])) {
                  $mat_mng_data = $mat_mng_data->whereNull('Approve_status')
                      ->where('Forward_Status', 0)
                      ->where('status', 0)
                      ->whereIn('Approve_Step', $EXT[4]['approver'])
                      ->orderBy('id', 'DESC');
          }
          $MATERIAL_MNG = $mat_mng_data->count();
      //Material Management approval count end

      //Raw Material approval count strat
      $rawmat_data = App\Models\RawMaterial\RawMaterial_stock::select('rawmaterial_stock.*');
              if (isset($EXT[6]['Forward']) && isset($EXT[6]['approver'])) {
                $rawmat_data = $rawmat_data->where(function ($query) use ($EXT) {
                    $query->where('Approve_status', null)
                        ->where('Forward_Status', 0)
                        ->whereIn('Approve_Step', $EXT[6]['approver']);
                })
                ->orWhere(function ($query) {
                    $query->whereIn('id', function ($subquery) {
                        $subquery->select('DataID')
                            ->from('forwarded_data')
                            ->where('Forward_To_id', auth()->user()->id)
                            ->where('status', 0);
                    })
                    ->where(function ($subquery) {
                        $subquery->whereNull('Approve_status')
                            ->orWhere('Approve_status', 'FORWARD');
                    })
                    ->where('Forward_Status', 1);
                })
                ->orderBy('id', 'DESC');
                } elseif (isset($EXT[6]['Forward'])) {
                    $rawmat_data = $rawmat_data->where('Forward_Status', 1)
                        ->whereIn('id', function ($query) {
                            $query->select('DataID')
                                ->from('forwarded_data')
                                ->where('Forward_To_id', auth()->user()->id)
                                ->where('status', 0);
                        })
                        ->where(function ($query) {
                            $query->whereNull('Approve_status')
                                ->orWhere('Approve_status', 'FORWARD');
                        })
                        ->orderBy('id', 'DESC');
                } elseif (isset($EXT[6]['approver'])) {
                    $rawmat_data = $rawmat_data->where('Approve_status', null)
                        ->where('Forward_Status', 0)
                        ->where('status', 0)
                        ->whereIn('Approve_Step', $EXT[6]['approver'])
                        ->orderBy('id', 'DESC');
                }
            $RAWMATERIAL = $rawmat_data->count();

      //Raw Material approval count end

      //Finished Good approval count start
      $finished_good_data = App\Models\ProductCategories\ProductCategories_Add_Product::select('productcategories_add_product.*');

              if (isset($EXT[5]['Forward']) && isset($EXT[5]['approver'])) {
                  $finished_good_data = $finished_good_data->where(function ($query) use ($EXT) {
                      $query->whereNull('Approve_status')
                          ->where('Forward_Status', 0)
                          ->whereIn('Approve_Step', $EXT[5]['approver']);
                  })
                  ->orWhere(function ($query) {
                      $query->whereIn('id', function ($subquery) {
                          $subquery->select('DataID')
                              ->from('forwarded_data')
                              ->where('Forward_To_id', auth()->user()->id)
                              ->where('status', 0);
                      })
                      ->where(function ($subquery) {
                          $subquery->whereNull('Approve_status')
                              ->orWhere('Approve_status', 'FORWARD');
                      })
                      ->where('Forward_Status', 1);
                  })
                  ->orderBy('id', 'DESC');
            } elseif (isset($EXT[5]['Forward'])) {
                $finished_good_data = $finished_good_data->where('Forward_Status', 1)
                    ->whereIn('id', function ($subquery) {
                        $subquery->select('DataID')
                            ->from('forwarded_data')
                            ->where('Forward_To_id', auth()->user()->id)
                            ->where('status', 0);
                    })
                    ->where(function ($subquery) {
                        $subquery->whereNull('Approve_status')
                            ->orWhere('Approve_status', 'FORWARD');
                    })
                    ->orderBy('id', 'DESC');
            } elseif (isset($EXT[5]['approver'])) {
                $finished_good_data = $finished_good_data->whereNull('Approve_status')
                    ->where('Forward_Status', 0)
                    ->where('status', 0)
                    ->whereIn('Approve_Step', $EXT[5]['approver'])
                    ->orderBy('id', 'DESC');
            }
          $FINISHED_GOOD = $finished_good_data->count();

      //Finished Good approval count end

      //bom approval count start
        $bom_data = App\Models\BOM\BOM::select('bom.*');

        if (isset($EXT[11]['Forward']) && isset($EXT[11]['approver'])) {
            $bom_data = $bom_data->where(function ($query) use ($EXT) {
                $query->where('Approve_status', null)
                      ->where('Forward_Status', 0)
                      ->whereIn('Approve_Step', $EXT[11]['approver']);
            })
            ->orWhere(function ($query) {
                $query->whereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD") AND `Forward_Status` = 1');
            })
            ->orWhereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD") AND `Forward_Status` = 1')
            ->orderBy('id', 'DESC');
            } elseif (isset($EXT[11]['Forward'])) {
                $bom_data = $bom_data->where('Forward_Status', 1)
                                    ->whereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD")')
                                    ->orderBy('id', 'DESC');
            } elseif (isset($EXT[11]['approver'])) {
                $bom_data = $bom_data->where('Approve_status', null)
                                    ->where(['Forward_Status' => 0, 'status' => 0])
                                    ->whereIn('Approve_Step', $EXT[11]['approver'])
                                    ->orderBy('id', 'DESC');
            }
          $BOM = $bom_data->count();
          //bom approval count End

          //PP Finished Good apprval count start
          $pp_finished_good = App\Models\PPFinishedGood\PPFinishedGood::select('ppfinishedgood.*');

                if (isset($EXT[7]['Forward']) && isset($EXT[7]['approver'])) {
                    $pp_finished_good = $pp_finished_good->where(function ($query) use ($EXT) {
                        $query->whereNull('Approve_status')
                              ->where('Forward_Status', 0)
                              ->whereIn('Approve_Step', $EXT[7]['approver']);
                    })
                    ->orWhere(function ($query) {
                        $query->whereIn('id', function ($subquery) {
                            $subquery->select('DataID')
                                    ->from('forwarded_data')
                                    ->where('Forward_To_id', auth()->user()->id)
                                    ->where('status', 0);
                        })
                        ->where(function ($subquery) {
                            $subquery->whereNull('Approve_status')
                                    ->orWhere('Approve_status', 'FORWARD');
                        })
                        ->where('Forward_Status', 1);
                    })
                    ->orderBy('id', 'DESC');
                } elseif (isset($EXT[7]['Forward'])) {
                    $pp_finished_good = $pp_finished_good->where('Forward_Status', 1)
                                                          ->whereIn('id', function ($subquery) {
                                                              $subquery->select('DataID')
                                                                        ->from('forwarded_data')
                                                                        ->where('Forward_To_id', auth()->user()->id)
                                                                        ->where('status', 0);
                                                          })
                                                          ->where(function ($subquery) {
                                                              $subquery->whereNull('Approve_status')
                                                                        ->orWhere('Approve_status', 'FORWARD');
                                                          })
                                                          ->orderBy('id', 'DESC');
                } elseif (isset($EXT[7]['approver'])) {
                    $pp_finished_good = $pp_finished_good->whereNull('Approve_status')
                                                          ->where('Forward_Status', 0)
                                                          ->where('status', 0)
                                                          ->whereIn('Approve_Step', $EXT[7]['approver'])
                                                          ->orderBy('id', 'DESC');
                }

            $PPFINISHEDGOOD = $pp_finished_good->count();

          //PP Finished Good apprval count end
          
          //Production Process approval count start
            $production_prs_data = App\Models\ProductionProcess\Production_Process::select('production_process.*');

                if (isset($EXT[19]['Forward']) && isset($EXT[19]['approver'])) {
                    $production_prs_data = $production_prs_data->where(function ($query) use ($EXT) {
                        $query->where('Approve_status', null)
                            ->where('Forward_Status', 0)
                            ->whereIn('Approve_Step', $EXT[19]['approver']);
                    })
                    ->orWhere(function ($query) {
                        $query->whereIn('id', function ($subQuery) {
                            $subQuery->select('DataID')
                                ->from('forwarded_data')
                                ->where('Forward_To_id', auth()->user()->id)
                                ->where('status', 0);
                        })
                        ->where(function ($subQuery) {
                            $subQuery->whereNull('Approve_status')
                                ->orWhere('Approve_status', 'FORWARD');
                        })
                        ->where('Forward_Status', 1);
                    })
                    ->orderBy('id', 'DESC');
                } elseif (isset($EXT[19]['Forward'])) {
                    $production_prs_data = $production_prs_data->where('Forward_Status', 1)
                        ->whereIn('id', function ($subQuery) {
                            $subQuery->select('DataID')
                                ->from('forwarded_data')
                                ->where('Forward_To_id', auth()->user()->id)
                                ->where('status', 0);
                        })
                        ->where(function ($subQuery) {
                            $subQuery->whereNull('Approve_status')
                                ->orWhere('Approve_status', 'FORWARD');
                        })
                        ->orderBy('id', 'DESC');
                } elseif (isset($EXT[19]['approver'])) {
                    $production_prs_data = $production_prs_data->where('Approve_status', null)
                        ->where('Forward_Status', 0)
                        ->where('status', 0)
                        ->whereIn('Approve_Step', $EXT[19]['approver'])
                        ->orderBy('id', 'DESC');
                }
            $PRODUCTIONPROCESS = $production_prs_data->count();  
          //Production Process approval count end

          //procurement Request approval count start
              $procurement_req_data = App\Models\orderRequirement\Order_Requirement_Stock::select('order_requirement_stock.*');

                if (isset($EXT[18]['Forward']) && isset($EXT[18]['approver'])) {
                    $procurement_req_data = $procurement_req_data->where(function ($procurement_req_data) use ($EXT) {
                        $procurement_req_data->where('Approve_status', null)
                            ->where('Forward_Status', 0)
                            ->whereIn('Approve_Step', $EXT[18]['approver']);
                    })
                    ->orWhere(function ($procurement_req_data) {
                        $procurement_req_data->whereIn('id', function ($query) {
                            $query->select('DataID')
                                ->from('forwarded_data')
                                ->where('Forward_To_id', auth()->user()->id)
                                ->where('status', 0);
                        })
                        ->where(function ($query) {
                            $query->whereNull('Approve_status')
                                ->orWhere('Approve_status', 'FORWARD');
                        })
                        ->where('Forward_Status', 1);
                    })
                    ->orderBy('id', 'DESC');
                } elseif (isset($EXT[18]['Forward'])) {
                    $procurement_req_data = $procurement_req_data->where('Forward_Status', 1)
                        ->whereIn('id', function ($query) {
                            $query->select('DataID')
                                ->from('forwarded_data')
                                ->where('Forward_To_id', auth()->user()->id)
                                ->where('status', 0);
                        })
                        ->where(function ($query) {
                            $query->whereNull('Approve_status')
                                ->orWhere('Approve_status', 'FORWARD');
                        })
                        ->orderBy('id', 'DESC');
                } elseif (isset($EXT[18]['approver'])) {
                    $procurement_req_data = $procurement_req_data->where('Approve_status', null)
                        ->where('Forward_Status', 0)
                        ->where('status', 0)
                        ->whereIn('Approve_Step', $EXT[18]['approver'])
                        ->orderBy('id', 'DESC');
                }

                $PROCUREMENTREQUEST = $procurement_req_data->count(); 
                
          //procurement Request approval count end

          //Store Requisition approval count start
            $store_req_data = App\Models\StoreRequistion\Store_Requistion::select('store_requistion.*');

                  if (isset($EXT[15]['Forward']) && isset($EXT[15]['approver'])) {       
                      $store_req_data = $store_req_data->where(function ($store_req_data) use ($EXT) {
                          $store_req_data->where('Approve_status', null)
                              ->where('Forward_Status', 0)
                              ->whereIn('Approve_Step', $EXT[15]['approver']);
                      })
                      ->orWhere(function ($store_req_data) {
                          $store_req_data->whereIn('id', function ($query) {
                              $query->select('DataID')
                                  ->from('forwarded_data')
                                  ->where('Forward_To_id', auth()->user()->id)
                                  ->where('status', 0);
                          })
                          ->where(function ($query) {
                              $query->whereNull('Approve_status')
                                  ->orWhere('Approve_status', 'FORWARD');
                          })
                          ->where('Forward_Status', 1);
                      })
                      ->orderBy('id', 'DESC');
                  } elseif (isset($EXT[15]['Forward'])) {       
                      $store_req_data = $store_req_data->where('Forward_Status', 1)
                          ->whereIn('id', function ($query) {
                              $query->select('DataID')
                                  ->from('forwarded_data')
                                  ->where('Forward_To_id', auth()->user()->id)
                                  ->where('status', 0);
                          })
                          ->where(function ($query) {
                              $query->whereNull('Approve_status')
                                  ->orWhere('Approve_status', 'FORWARD');
                          })
                          ->orderBy('id', 'DESC');
                  } elseif (isset($EXT[15]['approver'])) {
                      $store_req_data = $store_req_data->where('Approve_status', null)
                          ->where('Forward_Status', 0)
                          ->where('status', 0)
                          ->whereIn('Approve_Step', $EXT[15]['approver'])
                          ->orderBy('id', 'DESC');
                  }

                  $STOREREQUISITION = $store_req_data->count(); 

          //Store Requisition approval count end

          //Store Issue approval count start
              // $store_issue_data = App\Models\StoreRequistion\Store_Requistion::select('store_requistion.*')->get();
              // foreach($store_issue_data as $val){
              //   $cout=StoreIssueApprovedMaterial::where(['Store_Requistion_id'=>$val->id,'status'=>0,'recived_by'=>auth()->user()->id])->count();
              // }
              $store_issue_data = App\Models\StoreRequistion\Store_Requistion::select('store_requistion.*')->get();
              $store = []; // Make sure this is initialized
              foreach ($store_issue_data as $val) {
                  $cout = App\Models\Storeissue\StoreIssueApprovedMaterial::where([
                      'Store_Requistion_id' => $val->id,
                      'status' => 0,
                      'recived_by' => auth()->user()->id
                  ])->count();

                  if ($cout > 0) {
                      $store[] = $val;
                  }
              }
              $total_cout_issue = count($store);
              //echo "Total cout: " . $total_cout;
              //dd($total_cout);


          //Store Issue approval count end

          //production approval count start
          $production_data = App\Models\Production\Production::select('production.*');
                if (isset($EXT[17]['Forward']) && isset($EXT[17]['approver'])) {
                  $production_data = $production_data->where(function ($query) use ($EXT) {
                      $query->whereNull('Approve_status')
                          ->where('Forward_Status', 0)
                          ->whereIn('Approve_Step', $EXT[17]['approver']);
                  })
                  ->orWhere(function ($query) {
                      $query->whereIn('id', function ($subquery) {
                          $subquery->select('DataID')
                              ->from('forwarded_data')
                              ->where('Forward_To_id', auth()->user()->id)
                              ->where('status', 0);
                      })
                      ->where(function ($subquery) {
                          $subquery->whereNull('Approve_status')
                              ->orWhere('Approve_status', 'FORWARD');
                      })
                      ->where('Forward_Status', 1);
                  })
                  ->orderBy('id', 'DESC');
              } elseif (isset($EXT[17]['Forward'])) {
                  $production_data = $production_data->where('Forward_Status', 1)
                      ->whereIn('id', function ($query) {
                          $query->select('DataID')
                              ->from('forwarded_data')
                              ->where('Forward_To_id', auth()->user()->id)
                              ->where('status', 0);
                      })
                      ->where(function ($query) {
                          $query->whereNull('Approve_status')
                              ->orWhere('Approve_status', 'FORWARD');
                      })
                      ->orderBy('id', 'DESC');
              } elseif (isset($EXT[17]['approver'])) {
                  $production_data = $production_data->whereNull('Approve_status')
                      ->where('Forward_Status', 0)
                      ->where('status', 0)
                      ->whereIn('Approve_Step', $EXT[17]['approver'])
                      ->orderBy('id', 'DESC');
              }

              $PRODUCTIONCOUNT = $production_data->count(); 
          //production approval count end
          
          //QC sample Testing count Start
          $qc_data = App\Models\QCSampleTesting\QCFinishedGood::select('qcfinishedgood.*')->where('status',0)->orderBy('id', 'DESC');
              if (isset($EXT[9]['Forward']) && isset($EXT[9]['approver'])) {
                  $qc_data = $qc_data->where(function ($qc_data) use ($EXT) {
                      $qc_data->whereNull('Approve_status')
                          ->where('Forward_Status', 0)
                          ->whereIn('Approve_Step', $EXT[9]['approver']);
                  })
                  ->orWhere(function ($qc_data) {
                      $qc_data->whereIn('id', function ($query) {
                          $query->select('DataID')
                              ->from('forwarded_data')
                              ->where('Forward_To_id', auth()->user()->id)
                              ->where('status', 0);
                      })
                      ->where(function ($query) {
                          $query->whereNull('Approve_status')
                              ->orWhere('Approve_status', 'FORWARD');
                      })
                      ->where('Forward_Status', 1);
                  })
                  ->orderBy('id', 'DESC');
              } elseif (isset($EXT[9]['Forward'])) {
                  $qc_data = $qc_data->where('Forward_Status', 1)
                      ->whereIn('id', function ($query) {
                          $query->select('DataID')
                              ->from('forwarded_data')
                              ->where('Forward_To_id', auth()->user()->id)
                              ->where('status', 0);
                      })
                      ->where(function ($query) {
                          $query->whereNull('Approve_status')
                              ->orWhere('Approve_status', 'FORWARD');
                      })
                      ->orderBy('id', 'DESC');
              } elseif (isset($EXT[9]['approver'])) {
                  $qc_data = $qc_data->whereNull('Approve_status')
                      ->where('Forward_Status', 0)
                      ->where('status', 0)
                      ->whereIn('Approve_Step', $EXT[9]['approver'])
                      ->orderBy('id', 'DESC');
              }
          $QCCOUNT = $qc_data->count();

          //QC sample Testing count end

          //Inventory Approval count start
        $inventory_data = App\Models\InventoryManagement\Inventory_Management::select('inventory_management.*')->where('status',0)->orderBy('id', 'DESC');
          if (isset($EXT[14]['Forward']) && isset($EXT[14]['approver']))
        {
           $inventory_data = $inventory_data->where(function ($inventory_data) use ($EXT) {
               $inventory_data->where('Approve_status', null)->where('Forward_Status', 0)->whereRaw("Approve_Step IN (" . implode(",", $EXT[14]['approver']) . ")");
           })
               ->orWhere(function ($inventory_data) {
                   $inventory_data->whereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD") AND `Forward_Status` = 1');
               })
               ->orWhereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD") AND `Forward_Status` = 1')
               ->orderBy('id', 'DESC');
       } 
       elseif (isset($EXT[14]['Forward']))
        {       
           $inventory_data = $inventory_data->where('Forward_Status', 1)->whereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD")')->orderBy('id', 'DESC');
       } 
       elseif (isset($EXT[14]['approver'])) 
       {
           
           $inventory_data = $inventory_data->where('Approve_status', null)->where(['Forward_Status' => 0, 'status' => 0])->WhereRaw("Approve_Step IN (" . implode(",", $EXT[14]['approver']) . ")")->orderBy('id', 'DESC');
       }
       $INVENTORYCOUNT = $inventory_data->count();

          //Inventory Approval count end

          //samplefood approval count start
            $samplefood_data = App\Models\SampleFreeGood\SampleFreeGood::select('samplefreegood.*');
                  if (isset($EXT[10]['Forward']) && isset($EXT[10]['approver'])) {
                    $samplefood_data = $samplefood_data->where(function ($samplefood_data) use ($EXT) {
                        $samplefood_data->where('Approve_status', null)
                                        ->where('Forward_Status', 0)
                                        ->whereIn('Approve_Step', $EXT[10]['approver']);
                    })
                    ->orWhere(function ($samplefood_data) {
                        $samplefood_data->whereIn('id', function ($query) {
                            $query->select('DataID')
                                  ->from('forwarded_data')
                                  ->where('Forward_To_id', auth()->user()->id)
                                  ->where('status', 0);
                        })
                        ->where(function ($query) {
                            $query->whereNull('Approve_status')
                                  ->orWhere('Approve_status', 'FORWARD')
                                  ->where('Forward_Status', 1);
                        });
                    })
                    ->orderBy('id', 'DESC');
                } elseif (isset($EXT[10]['Forward'])) {
                    $samplefood_data = $samplefood_data->where('Forward_Status', 1)
                                                        ->whereIn('id', function ($query) {
                                                            $query->select('DataID')
                                                                  ->from('forwarded_data')
                                                                  ->where('Forward_To_id', auth()->user()->id)
                                                                  ->where('status', 0);
                                                        })
                                                        ->where(function ($query) {
                                                            $query->whereNull('Approve_status')
                                                                  ->orWhere('Approve_status', 'FORWARD');
                                                        })
                                                        ->orderBy('id', 'DESC');
                } elseif (isset($EXT[10]['approver'])) {
                    $samplefood_data = $samplefood_data->whereNull('Approve_status')
                                                        ->where('Forward_Status', 0)
                                                        ->where('status', 0)
                                                        ->whereIn('Approve_Step', $EXT[10]['approver'])
                                                        ->orderBy('id', 'DESC');
                }
                $SAMPLEFOODCOUNT = $samplefood_data->count();
          //samplefood approval count end
          //Serial Number approval count start
          $serial_data = App\Models\SerialNumber\FactorySerialNumber::select('factory_serial_numbers.*');

                  if (isset($EXT[8]['Forward']) && isset($EXT[8]['approver'])) {
                    $serial_data = $serial_data->where(function ($serial_data) use ($EXT) {
                        $serial_data->where('Approve_status', null)
                                        ->where('Forward_Status', 0)
                                        ->whereIn('Approve_Step', $EXT[8]['approver']);
                    })
                    ->orWhere(function ($serial_data) {
                        $serial_data->whereIn('id', function ($query) {
                            $query->select('DataID')
                                  ->from('forwarded_data')
                                  ->where('Forward_To_id', auth()->user()->id)
                                  ->where('status', 0);
                        })
                        ->where(function ($query) {
                            $query->whereNull('Approve_status')
                                  ->orWhere('Approve_status', 'FORWARD')
                                  ->where('Forward_Status', 1);
                        });
                    })
                    ->orderBy('id', 'DESC');
                } elseif (isset($EXT[8]['Forward'])) {
                    $serial_data = $serial_data->where('Forward_Status', 1)
                                                        ->whereIn('id', function ($query) {
                                                            $query->select('DataID')
                                                                  ->from('forwarded_data')
                                                                  ->where('Forward_To_id', auth()->user()->id)
                                                                  ->where('status', 0);
                                                        })
                                                        ->where(function ($query) {
                                                            $query->whereNull('Approve_status')
                                                                  ->orWhere('Approve_status', 'FORWARD');
                                                        })
                                                        ->orderBy('id', 'DESC');
                } elseif (isset($EXT[8]['approver'])) {
                    $serial_data = $serial_data->whereNull('Approve_status')
                                                        ->where('Forward_Status', 0)
                                                        ->where('status', 0)
                                                        ->whereIn('Approve_Step', $EXT[8]['approver'])
                                                        ->orderBy('id', 'DESC');
                }
                $SERIALCOUNT = $serial_data->count();
            //Serial Number approval count end
            
              setUserSessionData();
              $CustDepartment=Session::get('CustDepartment');
              $CUSTEXT=Session::get('CUSTEXT');
              $CUSTSTEP=Session::get('CUSTSTEP');
              //IN employee gatepass approval count start
                $IN_employee_gatepass_data = App\Models\GatePass\Gatepass_Employee::select('gatepass_employees.*');
                  if (isset($CUSTEXT[2]['Forward']) && isset($CUSTEXT[2]['approver'])) {
                      $IN_employee_gatepass_data = $IN_employee_gatepass_data->where(function ($IN_employee_gatepass_data) use ($CUSTEXT) {
                          $IN_employee_gatepass_data->where('Approve_status', null)
                                          ->where('Forward_Status', 0)
                                          ->whereIn('Approve_Step', $CUSTEXT[2]['approver']);
                      })
                      ->orWhere(function ($IN_employee_gatepass_data) {
                          $IN_employee_gatepass_data->whereIn('request_no', function ($query) {
                              $query->select('DataID')
                                    ->from('forwarded_data_gatepass')
                                    ->where('Forward_To_id', auth()->user()->id)
                                    ->where('status', 0);
                          })
                          ->where(function ($query) {
                              $query->whereNull('Approve_status')
                                    ->orWhere('Approve_status', 'FORWARD')
                                    ->where('Forward_Status', 1);
                          });
                      })
                      ->orderBy('id', 'DESC');
                  } elseif (isset($CUSTEXT[2]['Forward'])) {
                      $IN_employee_gatepass_data = $IN_employee_gatepass_data->where('Forward_Status', 1)
                                                          ->whereIn('request_no', function ($query) {
                                                              $query->select('DataID')
                                                                    ->from('forwarded_data_gatepass')
                                                                    ->where('Forward_To_id', auth()->user()->id)
                                                                    ->where('status', 0);
                                                          })
                                                          ->where(function ($query) {
                                                              $query->whereNull('Approve_status')
                                                                    ->orWhere('Approve_status', 'FORWARD');
                                                          })
                                                          ->orderBy('id', 'DESC');
                  } elseif (isset($CUSTEXT[2]['approver'])) {
                      $IN_employee_gatepass_data = $IN_employee_gatepass_data->whereNull('Approve_status')
                                                          ->where('Forward_Status', 0)
                                                          ->where('status', 0)
                                                          ->whereIn('Approve_Step', $CUSTEXT[2]['approver'])
                                                          ->orderBy('id', 'DESC');
                                                        
                  }
                $INEMPLOYEEGATEPASSCOUNT = $IN_employee_gatepass_data->count();
              //IN employee gatepass approval count end
    
              //OUT employee gatepass approval count start
                $OUT_employee_gatepass_data = App\Models\GatePass\Gatepass_Employee::select('gatepass_employees.*');
                  if (isset($CUSTEXT[2]['Forward']) && isset($CUSTEXT[2]['approver'])) {
                      $OUT_employee_gatepass_data = $OUT_employee_gatepass_data->where(function ($OUT_employee_gatepass_data) use ($CUSTEXT) {
                          $OUT_employee_gatepass_data->where('Out_Approve_status', null)
                                          ->where('Out_Forward_Status', 0)
                                          ->whereIn('Out_Approve_Step', $CUSTEXT[2]['approver']);
                      })
                      ->orWhere(function ($OUT_employee_gatepass_data) {
                          $OUT_employee_gatepass_data->whereIn('out_request_no', function ($query) {
                              $query->select('DataID')
                                    ->from('forwarded_data_gatepass')
                                    ->where('Forward_To_id', auth()->user()->id)
                                    ->where('status', 0);
                          })
                          ->where(function ($query) {
                              $query->whereNull('Out_Approve_status')
                                    ->orWhere('Out_Approve_status', 'FORWARD')
                                    ->where('Out_Forward_Status', 1);
                          });
                      })
                      ->orderBy('id', 'DESC');
                  } elseif (isset($CUSTEXT[2]['Forward'])) {
                      $OUT_employee_gatepass_data = $OUT_employee_gatepass_data->where('Out_Forward_Status', 1)
                                                          ->whereIn('out_request_no', function ($query) {
                                                              $query->select('DataID')
                                                                    ->from('forwarded_data_gatepass')
                                                                    ->where('Forward_To_id', auth()->user()->id)
                                                                    ->where('status', 0);
                                                          })
                                                          ->where(function ($query) {
                                                              $query->whereNull('Out_Approve_status')
                                                                    ->orWhere('Out_Approve_status', 'FORWARD');
                                                          })
                                                          ->orderBy('id', 'DESC');
                  } elseif (isset($CUSTEXT[2]['approver'])) {
                      $OUT_employee_gatepass_data = $OUT_employee_gatepass_data->whereNull('Out_Approve_status')
                                                          ->where('Out_Forward_Status', 0)
                                                          ->where('status', 0)
                                                          ->whereIn('Out_Approve_Step', $CUSTEXT[2]['approver'])
                                                          ->orderBy('id', 'DESC');
                                                        
                  }
                $OUTEMPLOYEEGATEPASSCOUNT = $OUT_employee_gatepass_data->count();
              //OUT employee gatepass approval count end
    
              //IN visitor gatepass approval count start
                $IN_visitor_gatepass_data = App\Models\GatePass\Gatepass_Visitor::select('gatepass_visitors.*');
                  if (isset($CUSTEXT[2]['Forward']) && isset($CUSTEXT[2]['approver'])) {
                      $IN_visitor_gatepass_data = $IN_visitor_gatepass_data->where(function ($IN_visitor_gatepass_data) use ($CUSTEXT) {
                          $IN_visitor_gatepass_data->where('Approve_status', null)
                                          ->where('Forward_Status', 0)
                                          ->whereIn('Approve_Step', $CUSTEXT[2]['approver']);
                      })
                      ->orWhere(function ($IN_visitor_gatepass_data) {
                          $IN_visitor_gatepass_data->whereIn('request_no', function ($query) {
                              $query->select('DataID')
                                    ->from('forwarded_data_gatepass')
                                    ->where('Forward_To_id', auth()->user()->id)
                                    ->where('status', 0);
                          })
                          ->where(function ($query) {
                              $query->whereNull('Approve_status')
                                    ->orWhere('Approve_status', 'FORWARD')
                                    ->where('Forward_Status', 1);
                          });
                      })
                      ->orderBy('id', 'DESC');
                  } elseif (isset($CUSTEXT[2]['Forward'])) {
                      $IN_visitor_gatepass_data = $IN_visitor_gatepass_data->where('Forward_Status', 1)
                                                          ->whereIn('request_no', function ($query) {
                                                              $query->select('DataID')
                                                                    ->from('forwarded_data_gatepass')
                                                                    ->where('Forward_To_id', auth()->user()->id)
                                                                    ->where('status', 0);
                                                          })
                                                          ->where(function ($query) {
                                                              $query->whereNull('Approve_status')
                                                                    ->orWhere('Approve_status', 'FORWARD');
                                                          })
                                                          ->orderBy('id', 'DESC');
                  } elseif (isset($CUSTEXT[2]['approver'])) {
                      $IN_visitor_gatepass_data = $IN_visitor_gatepass_data->whereNull('Approve_status')
                                                          ->where('Forward_Status', 0)
                                                          ->where('status', 0)
                                                          ->whereIn('Approve_Step', $CUSTEXT[2]['approver'])
                                                          ->orderBy('id', 'DESC');
                                                        
                  }
                $INVISITORGATEPASSCOUNT = $IN_visitor_gatepass_data->count();
              //IN visitor gatepass approval count end
    
              //OUT visitor gatepass approval count start
                $OUT_visitor_gatepass_data = App\Models\GatePass\Gatepass_Visitor::select('gatepass_visitors.*');
                  if (isset($CUSTEXT[2]['Forward']) && isset($CUSTEXT[2]['approver'])) {
                      $OUT_visitor_gatepass_data = $OUT_visitor_gatepass_data->where(function ($OUT_visitor_gatepass_data) use ($CUSTEXT) {
                          $OUT_visitor_gatepass_data->where('Out_Approve_status', null)
                                          ->where('Out_Forward_Status', 0)
                                          ->whereIn('Out_Approve_Step', $CUSTEXT[2]['approver']);
                      })
                      ->orWhere(function ($OUT_visitor_gatepass_data) {
                          $OUT_visitor_gatepass_data->whereIn('out_request_no', function ($query) {
                              $query->select('DataID')
                                    ->from('forwarded_data_gatepass')
                                    ->where('Forward_To_id', auth()->user()->id)
                                    ->where('status', 0);
                          })
                          ->where(function ($query) {
                              $query->whereNull('Out_Approve_status')
                                    ->orWhere('Out_Approve_status', 'FORWARD')
                                    ->where('Out_Forward_Status', 1);
                          });
                      })
                      ->orderBy('id', 'DESC');
                  } elseif (isset($CUSTEXT[2]['Forward'])) {
                      $OUT_visitor_gatepass_data = $OUT_visitor_gatepass_data->where('Out_Forward_Status', 1)
                                                          ->whereIn('out_request_no', function ($query) {
                                                              $query->select('DataID')
                                                                    ->from('forwarded_data_gatepass')
                                                                    ->where('Forward_To_id', auth()->user()->id)
                                                                    ->where('status', 0);
                                                          })
                                                          ->where(function ($query) {
                                                              $query->whereNull('Out_Approve_status')
                                                                    ->orWhere('Out_Approve_status', 'FORWARD');
                                                          })
                                                          ->orderBy('id', 'DESC');
                  } elseif (isset($CUSTEXT[2]['approver'])) {
                      $OUT_visitor_gatepass_data = $OUT_visitor_gatepass_data->whereNull('Out_Approve_status')
                                                          ->where('Out_Forward_Status', 0)
                                                          ->where('status', 0)
                                                          ->whereIn('Out_Approve_Step', $CUSTEXT[2]['approver'])
                                                          ->orderBy('id', 'DESC');
                                                        
                  }
                $OUTVISITORGATEPASSCOUNT = $OUT_visitor_gatepass_data->count();
              //OUT visitor gatepass approval count end
               
              //ManuaL Finished good approval count start
              $manual_finishedgood_data = App\Models\FinishedGood\FinishedGoodGatepass::select('finished_good_gatepasses.*');
              if (isset($EXT[22]['Forward']) && isset($EXT[22]['approver']))
              {
                  $manual_finishedgood_data = $manual_finishedgood_data->where(function ($manual_finishedgood_data) use ($EXT) {
                      $manual_finishedgood_data->where('Approve_status', null)->where('Forward_Status', 0)->whereRaw("Approve_Step IN (" . implode(",", $EXT[22]['approver']) . ")");
                  })
                  ->orWhere(function ($manual_finishedgood_data) {
                      $manual_finishedgood_data->whereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD") AND `Forward_Status` = 1');
                      })
                  ->orWhereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD") AND `Forward_Status` = 1')
                  ->orderBy('id', 'DESC');
                  } 
                  elseif (isset($EXT[22]['Forward']))
                  {       
                      $manual_finishedgood_data = $manual_finishedgood_data->where('Forward_Status', 1)->whereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD")')->orderBy('id', 'DESC');
                  } 
                  elseif (isset($EXT[22]['approver'])) 
                  {
                      
                      $manual_finishedgood_data = $manual_finishedgood_data->where('Approve_status', null)->where(['Forward_Status' => 0, 'status' => 0])->WhereRaw("Approve_Step IN (" . implode(",", $EXT[22]['approver']) . ")")->orderBy('id', 'DESC');
                  }
              $MANUALFINISHEDGOODCOUNT= $manual_finishedgood_data->count();

              //ManuaL Finished good approval count End

              //Mrn Stock Approval Count Start
                $mrn_stocktransfer_data = App\Models\StoreTransfer\Mrn_Stock_Transfer::select('mrn_stock_transfer.*');
    
                if (isset($EXT[23]['Forward']) && isset($EXT[23]['approver'])) 
                {
                    $mrn_stocktransfer_data = $mrn_stocktransfer_data->where(function ($query) use ($EXT) {
                            $query->where('Approve_status', null)
                                  ->where('Forward_Status', 0)
                                  ->whereRaw("Approve_Step IN (" . implode(",", $EXT[23]['approver']) . ")");
                        })
                        ->orWhere(function ($query) {
                            $query->whereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0)')
                                  ->where(function($q) {
                                      $q->whereNull('Approve_status')
                                        ->orWhere('Approve_status', 'FORWARD');
                                  })
                                  ->where('Forward_Status', 1);
                        })
                        ->orWhereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD") AND Forward_Status = 1')
                        ->orderBy('id', 'DESC');
                } 
                elseif (isset($EXT[23]['Forward'])) 
                {
                    $mrn_stocktransfer_data = $mrn_stocktransfer_data->where('Forward_Status', 1)
                        ->whereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0)')
                        ->where(function($q) {
                            $q->whereNull('Approve_status')
                              ->orWhere('Approve_status', 'FORWARD');
                        })
                        ->orderBy('id', 'DESC');
                } 
                elseif (isset($EXT[23]['approver'])) 
                {
                    $mrn_stocktransfer_data = $mrn_stocktransfer_data->where('Approve_status', null)
                        ->where('Forward_Status', 0)
                        ->where('status', 0)
                        ->whereRaw("Approve_Step IN (" . implode(",", $EXT[23]['approver']) . ")")
                        ->orderBy('id', 'DESC');
                }
    
                $MRNSTOCKTRANSFERCOUNT = $mrn_stocktransfer_data->count();
    
    
                  //Mrn Stock Approval Count End
                  
                  //OUT material gatepass approval count start
            $OUT_material_gatepass_data = App\Models\GatePass\OutGatepassMaterials::select('out_gatepass_materials.*');
              if (isset($CUSTEXT[2]['Forward']) && isset($CUSTEXT[2]['approver'])) {
                  $OUT_material_gatepass_data = $OUT_material_gatepass_data->where(function ($OUT_material_gatepass_data) use ($CUSTEXT) {
                      $OUT_material_gatepass_data->where('Out_Approve_status', null)
                                      ->where('Out_Forward_Status', 0)
                                      ->whereIn('Out_Approve_Step', $CUSTEXT[2]['approver']);
                  })
                  ->orWhere(function ($OUT_material_gatepass_data) {
                      $OUT_material_gatepass_data->whereIn('request_no', function ($query) {
                          $query->select('DataID')
                                ->from('forwarded_data_gatepass')
                                ->where('Forward_To_id', auth()->user()->id)  
                                ->where('status', 0);
                      })
                      ->where(function ($query) {
                          $query->whereNull('Out_Approve_status') 
                                ->orWhere('Out_Approve_status', 'FORWARD')
                                ->where('Out_Forward_Status', 1);
                      });
                  })
                  ->orderBy('id', 'DESC');
              } elseif (isset($CUSTEXT[2]['Forward'])) {
                  $OUT_material_gatepass_data = $OUT_material_gatepass_data->where  ('Out_Forward_Status', 1)
                                                      ->whereIn('request_no', function ($query) {
                                                          $query->select('DataID')
                                                                ->from('forwarded_data_gatepass')
                                                                ->where('Forward_To_id', auth()->user()->id)
                                                                ->where('status', 0);
                                                      })
                                                      ->where(function ($query) {
                                                          $query->whereNull('Out_Approve_status')
                                                                ->orWhere('Out_Approve_status', 'FORWARD');
                                                      })
                                                      ->orderBy('id', 'DESC');
              } elseif (isset($CUSTEXT[2]['approver'])) {
                  $OUT_material_gatepass_data = $OUT_material_gatepass_data->whereNull('Out_Approve_status')
                                                      ->where('Out_Forward_Status', 0)
                                                      ->where('status', 0)
                                                      ->whereIn('Out_Approve_Step', $CUSTEXT[2]['approver'])
                                                      ->orderBy('id', 'DESC');
              }
            $OUTMATERIALGATEPASSCOUNT = $OUT_material_gatepass_data->count();
          //OUT material gatepass approval count end   
          
          // IN material gatepass approval count start
            $IN_material_gatepass_data = App\Models\GatePass\InGatepassMaterials::select('in_gatepass_materials.*');
              if (isset($CUSTEXT[2]['Forward']) && isset($CUSTEXT[2]['approver'])) {
                  $IN_material_gatepass_data = $IN_material_gatepass_data->where(function ($IN_material_gatepass_data) use ($CUSTEXT) {
                      $IN_material_gatepass_data->where('Approve_status', null)
                                      ->where('Forward_Status', 0)
                                      ->whereIn('Approve_Step', $CUSTEXT[2]['approver']);
                  })
                  ->orWhere(function ($IN_material_gatepass_data) {
                      $IN_material_gatepass_data->whereIn('request_no', function ($query) {
                          $query->select('DataID')
                                ->from('forwarded_data_gatepass')
                                ->where('Forward_To_id', auth()->user()->id)
                                ->where('status', 0);
                      })
                      ->where(function ($query) {
                          $query->whereNull('Approve_status')
                                ->orWhere('Approve_status', 'FORWARD')
                                ->where('Forward_Status', 1);
                      });
                  })
                  ->orderBy('id', 'DESC');
              } elseif (isset($CUSTEXT[2]['Forward'])) {
                  $IN_material_gatepass_data = $IN_material_gatepass_data->where  ('Forward_Status', 1)
                                                      ->whereIn('request_no', function ($query) {
                                                          $query->select('DataID')
                                                                ->from('forwarded_data_gatepass')
                                                                ->where('Forward_To_id', auth()->user()->id)
                                                                ->where('status', 0);
                                                      })
                                                      ->where(function ($query) {
                                                          $query->whereNull('Approve_status')
                                                                ->orWhere('Approve_status', 'FORWARD');
                                                      })
                                                      ->orderBy('id', 'DESC');
              } elseif (isset($CUSTEXT[2]['approver'])) {
                  $IN_material_gatepass_data = $IN_material_gatepass_data->whereNull('Approve_status')
                                                      ->where('Forward_Status', 0)
                                                      ->where('status', 0)
                                                      ->whereIn('Approve_Step', $CUSTEXT[2]['approver'])
                                                      ->orderBy('id', 'DESC');
              }
            $INMATERIALGATEPASSCOUNT = $IN_material_gatepass_data->count();
          // IN material gatepass approval count end
          
          

@endphp

<body>
  <div id="loader" class="loader-container">
    <div class="loader"></div>
  </div>
  <section class="home-section" style="height:100%;background:#fff;">
    <div class="home-content" style="position: fixed;top: 0px;background-color:#fff; width: 100%;left: 0px;z-index: 1; border-bottom: 1px solid #8eafac;">
      <div class="text" style="padding-left: 10px;">
        <img src="{{url('image/new-loogo-1.png')}}" width="100%" height="80">
        <i class='bx bx-menu' onclick="sidebar()"></i>
      </div>
      <div class="left-bar">
        <img src="{{url('image/P_IMG1.png')}}" width="50" height="50" style="border-radius: 20px;">
        <p>{{auth()->user()->fullname}}</p>
      </div>
    </div>
  </section>
  <section class="sectin">
    <div id="sidebar" class="sidebar close" style="margin-top:60px;">
      <ul class="main_light" id="myDIV">
        
          <li class="under_t luck activejj outside" id="javab">
            <a class="dropdown-item" href="{{url('Dashboard/dashboard')}}">
            <i>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-speedometer2" viewBox="0 0 16 16">
                    <path d="M11.121 2.121a.5.5 0 0 1 .354-.146H13.5a.5.5 0 0 1 .5.5V6a.5.5 0 0 1-.146.354l-1.729 1.729a.5.5 0 0 0-.125.646l.679 1.118a.5.5 0 0 1 .063.266V12.5a.5.5 0 0 1-.5.5h-.5a.5.5 0 0 1-.5-.5v-2.293a.5.5 0 0 0-.146-.354l-3-3a.5.5 0 0 0-.708.708l2.646 2.646v1.793a.5.5 0 0 1-.146.354L6.354 12.5H3.5a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .146-.354L4.646 9.354a.5.5 0 0 0 .146-.354V7.207a.5.5 0 0 1 .063-.266l.679-1.118a.5.5 0 0 0-.125-.646L2.025 2.475a.5.5 0 0 1-.146-.354v-.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .354.146L6 3.793l1.121-1.121a.5.5 0 0 1 .708 0l3 3zM8 5.707L6.293 4H4v1h2.293L8 5.707zM4.5 7a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5V7zm3.354 3.646L6 10.207l-1.854 1.439a.5.5 0 0 0 .146.854H8.5a.5.5 0 0 0 .354-.854z"/>
                </svg>
            </i>
            <span class="mjhu">Dashboard</span>
            </a>
            
        </li>
        
        <li class="under_t luck outside" onclick="myFunctiontt(87)" id="i87">
          <i><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512">
              <path d="M304 128a80 80 0 1 0 -160 0 80 80 0 1 0 160 0zM96 128a128 128 0 1 1 256 0A128 128 0 1 1 96 128zM49.3 464H398.7c-8.9-63.3-63.3-112-129-112H178.3c-65.7 0-120.1 48.7-129 112zM0 482.3C0 383.8 79.8 304 178.3 304h91.4C368.2 304 448 383.8 448 482.3c0 16.4-13.3 29.7-29.7 29.7H29.7C13.3 512 0 498.7 0 482.3z" />
            </svg></i>
          <span class="mjhu">Production Setup</span>
          <ul class="under_drop outsideav" id="myDIVwwep87" style="display: none;">
            <li class="pura" id="pura871">
              <a class="dropdown-item" target="_blank" href="{{URL('production-lineup/dashboard')}}">
                <i><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 640 512">
                    <path d="M144 0a80 80 0 1 1 0 160A80 80 0 1 1 144 0zM512 0a80 80 0 1 1 0 160A80 80 0 1 1 512 0zM0 298.7C0 239.8 47.8 192 106.7 192h42.7c15.9 0 31 3.5 44.6 9.7c-1.3 7.2-1.9 14.7-1.9 22.3c0 38.2 16.8 72.5 43.3 96c-.2 0-.4 0-.7 0H21.3C9.6 320 0 310.4 0 298.7zM405.3 320c-.2 0-.4 0-.7 0c26.6-23.5 43.3-57.8 43.3-96c0-7.6-.7-15-1.9-22.3c13.6-6.3 28.7-9.7 44.6-9.7h42.7C592.2 192 640 239.8 640 298.7c0 11.8-9.6 21.3-21.3 21.3H405.3zM224 224a96 96 0 1 1 192 0 96 96 0 1 1 -192 0zM128 485.3C128 411.7 187.7 352 261.3 352H378.7C452.3 352 512 411.7 512 485.3c0 14.7-11.9 26.7-26.7 26.7H154.7c-14.7 0-26.7-11.9-26.7-26.7z" />
                  </svg></i>

                <span class="mjhu"> Production Setup </span>
              </a>
            </li>
          </ul>
        </li>
        
        @if(in_array(20,$Department) || auth()->user()->id== "545")
        <li class="under_t luck outside" onclick="myFunctiontt(27)" id="i27">
          <i><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512">
              <path d="M304 128a80 80 0 1 0 -160 0 80 80 0 1 0 160 0zM96 128a128 128 0 1 1 256 0A128 128 0 1 1 96 128zM49.3 464H398.7c-8.9-63.3-63.3-112-129-112H178.3c-65.7 0-120.1 48.7-129 112zM0 482.3C0 383.8 79.8 304 178.3 304h91.4C368.2 304 448 383.8 448 482.3c0 16.4-13.3 29.7-29.7 29.7H29.7C13.3 512 0 498.7 0 482.3z" />
            </svg></i>
          <span class="mjhu">Employee</span>
          <ul class="under_drop outsideav" id="myDIVwwep27" style="display: none;">
            <li class="pura" id="pura271">
              <a class="dropdown-item" href="{{URL('Master/Add_Employee')}}">
                <i><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 640 512">
                    <path d="M144 0a80 80 0 1 1 0 160A80 80 0 1 1 144 0zM512 0a80 80 0 1 1 0 160A80 80 0 1 1 512 0zM0 298.7C0 239.8 47.8 192 106.7 192h42.7c15.9 0 31 3.5 44.6 9.7c-1.3 7.2-1.9 14.7-1.9 22.3c0 38.2 16.8 72.5 43.3 96c-.2 0-.4 0-.7 0H21.3C9.6 320 0 310.4 0 298.7zM405.3 320c-.2 0-.4 0-.7 0c26.6-23.5 43.3-57.8 43.3-96c0-7.6-.7-15-1.9-22.3c13.6-6.3 28.7-9.7 44.6-9.7h42.7C592.2 192 640 239.8 640 298.7c0 11.8-9.6 21.3-21.3 21.3H405.3zM224 224a96 96 0 1 1 192 0 96 96 0 1 1 -192 0zM128 485.3C128 411.7 187.7 352 261.3 352H378.7C452.3 352 512 411.7 512 485.3c0 14.7-11.9 26.7-26.7 26.7H154.7c-14.7 0-26.7-11.9-26.7-26.7z" />
                  </svg></i>
                <span class="mjhu"> Add Employee </span>
              </a>
            </li>
            <li class="pura" id="pura272">
              <a class="dropdown-item" href="{{URL('Master/Assign_Step')}}">
                <i><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                    <path d="M256 0c4.6 0 9.2 1 13.4 2.9L457.7 82.8c22 9.3 38.4 31 38.3 57.2c-.5 99.2-41.3 280.7-213.6 363.2c-16.7 8-36.1 8-52.8 0C57.3 420.7 16.5 239.2 16 140c-.1-26.2 16.3-47.9 38.3-57.2L242.7 2.9C246.8 1 251.4 0 256 0zm0 66.8V444.8C394 378 431.1 230.1 432 141.4L256 66.8l0 0z" />
                  </svg></i>
                <span class="mjhu">Assign Authority</span>
              </a>
            </li>
          </ul>
        </li>
        @endif
        @if(in_array(21,$Department))
        <li class="under_t luck outside" onclick="myFunctiontt(6)" id="i6">
          <i><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
              <path d="M174.7 45.1C192.2 17 223 0 256 0s63.8 17 81.3 45.1l38.6 61.7 27-15.6c8.4-4.9 18.9-4.2 26.6 1.7s11.1 15.9 8.6 25.3l-23.4 87.4c-3.4 12.8-16.6 20.4-29.4 17l-87.4-23.4c-9.4-2.5-16.3-10.4-17.6-20s3.4-19.1 11.8-23.9l28.4-16.4L283 79c-5.8-9.3-16-15-27-15s-21.2 5.7-27 15l-17.5 28c-9.2 14.8-28.6 19.5-43.6 10.5c-15.3-9.2-20.2-29.2-10.7-44.4l17.5-28zM429.5 251.9c15-9 34.4-4.3 43.6 10.5l24.4 39.1c9.4 15.1 14.4 32.4 14.6 50.2c.3 53.1-42.7 96.4-95.8 96.4L320 448v32c0 9.7-5.8 18.5-14.8 22.2s-19.3 1.7-26.2-5.2l-64-64c-9.4-9.4-9.4-24.6 0-33.9l64-64c6.9-6.9 17.2-8.9 26.2-5.2s14.8 12.5 14.8 22.2v32l96.2 0c17.6 0 31.9-14.4 31.8-32c0-5.9-1.7-11.7-4.8-16.7l-24.4-39.1c-9.5-15.2-4.7-35.2 10.7-44.4zm-364.6-31L36 204.2c-8.4-4.9-13.1-14.3-11.8-23.9s8.2-17.5 17.6-20l87.4-23.4c12.8-3.4 26 4.2 29.4 17L182 241.2c2.5 9.4-.9 19.3-8.6 25.3s-18.2 6.6-26.6 1.7l-26.5-15.3L68.8 335.3c-3.1 5-4.8 10.8-4.8 16.7c-.1 17.6 14.2 32 31.8 32l32.2 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-32.2 0C42.7 448-.3 404.8 0 351.6c.1-17.8 5.1-35.1 14.6-50.2l50.3-80.5z" />
            </svg></i>
          <span class="mjhu">Master</span>
          <ul class="under_drop extra outsideav" id="myDIVwwep6" style="display: none;">
            <li class="under_t luck insideav" onclick="myFunctionttss(1)" id='is1'>
              <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-alt" viewBox="0 0 16 16">
                  <path d="M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A31.493 31.493 0 0 1 8 14.58a31.481 31.481 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94zM8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10z" />
                  <path d="M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                </svg></i>
              <span class="mjhu"> Address</span>
              <ul class="under_drop inside" id="myDIVwweps1" style="display: none;">
                <li class="pura" id="pura11">
                  <a class="dropdown-item" href="{{URL('Master/country')}}">
                    <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-globe" viewBox="0 0 16 16">
                        <path d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm7.5-6.923c-.67.204-1.335.82-1.887 1.855A7.97 7.97 0 0 0 5.145 4H7.5V1.077zM4.09 4a9.267 9.267 0 0 1 .64-1.539 6.7 6.7 0 0 1 .597-.933A7.025 7.025 0 0 0 2.255 4H4.09zm-.582 3.5c.03-.877.138-1.718.312-2.5H1.674a6.958 6.958 0 0 0-.656 2.5h2.49zM4.847 5a12.5 12.5 0 0 0-.338 2.5H7.5V5H4.847zM8.5 5v2.5h2.99a12.495 12.495 0 0 0-.337-2.5H8.5zM4.51 8.5a12.5 12.5 0 0 0 .337 2.5H7.5V8.5H4.51zm3.99 0V11h2.653c.187-.765.306-1.608.338-2.5H8.5zM5.145 12c.138.386.295.744.468 1.068.552 1.035 1.218 1.65 1.887 1.855V12H5.145zm.182 2.472a6.696 6.696 0 0 1-.597-.933A9.268 9.268 0 0 1 4.09 12H2.255a7.024 7.024 0 0 0 3.072 2.472zM3.82 11a13.652 13.652 0 0 1-.312-2.5h-2.49c.062.89.291 1.733.656 2.5H3.82zm6.853 3.472A7.024 7.024 0 0 0 13.745 12H11.91a9.27 9.27 0 0 1-.64 1.539 6.688 6.688 0 0 1-.597.933zM8.5 12v2.923c.67-.204 1.335-.82 1.887-1.855.173-.324.33-.682.468-1.068H8.5zm3.68-1h2.146c.365-.767.594-1.61.656-2.5h-2.49a13.65 13.65 0 0 1-.312 2.5zm2.802-3.5a6.959 6.959 0 0 0-.656-2.5H12.18c.174.782.282 1.623.312 2.5h2.49zM11.27 2.461c.247.464.462.98.64 1.539h1.835a7.024 7.024 0 0 0-3.072-2.472c.218.284.418.598.597.933zM10.855 4a7.966 7.966 0 0 0-.468-1.068C9.835 1.897 9.17 1.282 8.5 1.077V4h2.355z" />
                      </svg></i>
                    <span class="mjhu"> Country</span>
                  </a>
                </li>
                {{-- <li class="pura" id="pura12">
                  <a class="dropdown-item" href="{{URL('Master/state')}}">
                    <i>
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-globe-americas" viewBox="0 0 16 16">
                        <path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0ZM2.04 4.326c.325 1.329 2.532 2.54 3.717 3.19.48.263.793.434.743.484-.08.08-.162.158-.242.234-.416.396-.787.749-.758 1.266.035.634.618.824 1.214 1.017.577.188 1.168.38 1.286.983.082.417-.075.988-.22 1.52-.215.782-.406 1.48.22 1.48 1.5-.5 3.798-3.186 4-5 .138-1.243-2-2-3.5-2.5-.478-.16-.755.081-.99.284-.172.15-.322.279-.51.216-.445-.148-2.5-2-1.5-2.5.78-.39.952-.171 1.227.182.078.099.163.208.273.318.609.304.662-.132.723-.633.039-.322.081-.671.277-.867.434-.434 1.265-.791 2.028-1.12.712-.306 1.365-.587 1.579-.88A7 7 0 1 1 2.04 4.327Z" />
                      </svg>
                    </i>
                    <span class="mjhu">State</span>
                  </a>
                </li>
                <li class="pura" id="pura13">
                  <a class="dropdown-item" href="{{URL('Master/district')}}">
                    <i>
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-globe-europe-africa" viewBox="0 0 16 16">
                        <path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0ZM3.668 2.501l-.288.646a.847.847 0 0 0 1.479.815l.245-.368a.809.809 0 0 1 1.034-.275.809.809 0 0 0 .724 0l.261-.13a1 1 0 0 1 .775-.05l.984.34c.078.028.16.044.243.054.784.093.855.377.694.801-.155.41-.616.617-1.035.487l-.01-.003C8.274 4.663 7.748 4.5 6 4.5 4.8 4.5 3.5 5.62 3.5 7c0 1.96.826 2.166 1.696 2.382.46.115.935.233 1.304.618.449.467.393 1.181.339 1.877C6.755 12.96 6.674 14 8.5 14c1.75 0 3-3.5 3-4.5 0-.262.208-.468.444-.7.396-.392.87-.86.556-1.8-.097-.291-.396-.568-.641-.756-.174-.133-.207-.396-.052-.551a.333.333 0 0 1 .42-.042l1.085.724c.11.072.255.058.348-.035.15-.15.415-.083.489.117.16.43.445 1.05.849 1.357L15 8A7 7 0 1 1 3.668 2.501Z" />
                      </svg></i>
                    <span class="mjhu">District</span>
                  </a>
                </li>
                <li class="pura" id="pura14">
                  <a class="dropdown-item" href="{{URL('Master/organization')}}">
                    <i>
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-diagram-3" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M6 3.5A1.5 1.5 0 0 1 7.5 2h1A1.5 1.5 0 0 1 10 3.5v1A1.5 1.5 0 0 1 8.5 6v1H14a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-1 0V8h-5v.5a.5.5 0 0 1-1 0V8h-5v.5a.5.5 0 0 1-1 0v-1A.5.5 0 0 1 2 7h5.5V6A1.5 1.5 0 0 1 6 4.5v-1zM8.5 5a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1zM0 11.5A1.5 1.5 0 0 1 1.5 10h1A1.5 1.5 0 0 1 4 11.5v1A1.5 1.5 0 0 1 2.5 14h-1A1.5 1.5 0 0 1 0 12.5v-1zm1.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1zm4.5.5A1.5 1.5 0 0 1 7.5 10h1a1.5 1.5 0 0 1 1.5 1.5v1A1.5 1.5 0 0 1 8.5 14h-1A1.5 1.5 0 0 1 6 12.5v-1zm1.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1zm4.5.5a1.5 1.5 0 0 1 1.5-1.5h1a1.5 1.5 0 0 1 1.5 1.5v1a1.5 1.5 0 0 1-1.5 1.5h-1a1.5 1.5 0 0 1-1.5-1.5v-1zm1.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1z" />
                      </svg></i>
                    <span class="mjhu">Organization</span>
                  </a>
                </li>
                <li class="pura" id="pura15">
                  <a class="dropdown-item" href="{{URL('Master/name_of_unit')}}"><i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-universal-access-circle" viewBox="0 0 16 16">
                        <path d="M8 4.143A1.071 1.071 0 1 0 8 2a1.071 1.071 0 0 0 0 2.143Zm-4.668 1.47 3.24.316v2.5l-.323 4.585A.383.383 0 0 0 7 13.14l.826-4.017c.045-.18.301-.18.346 0L9 13.139a.383.383 0 0 0 .752-.125L9.43 8.43v-2.5l3.239-.316a.38.38 0 0 0-.047-.756H3.379a.38.38 0 0 0-.047.756Z" />
                        <path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0ZM1 8a7 7 0 1 1 14 0A7 7 0 0 1 1 8Z" />
                      </svg></i>
                    <span class="mjhu">Name Of Unit</span>
                  </a>
                </li> --}}
              </ul>
            </li>
            <li class="under_t luck insideav" onclick="myFunctionttss(2)" id="is2">
              <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-hourglass" viewBox="0 0 16 16">
                  <path d="M2 1.5a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-1v1a4.5 4.5 0 0 1-2.557 4.06c-.29.139-.443.377-.443.59v.7c0 .213.154.451.443.59A4.5 4.5 0 0 1 12.5 13v1h1a.5.5 0 0 1 0 1h-11a.5.5 0 1 1 0-1h1v-1a4.5 4.5 0 0 1 2.557-4.06c.29-.139.443-.377.443-.59v-.7c0-.213-.154-.451-.443-.59A4.5 4.5 0 0 1 3.5 3V2h-1a.5.5 0 0 1-.5-.5zm2.5.5v1a3.5 3.5 0 0 0 1.989 3.158c.533.256 1.011.791 1.011 1.491v.702c0 .7-.478 1.235-1.011 1.491A3.5 3.5 0 0 0 4.5 13v1h7v-1a3.5 3.5 0 0 0-1.989-3.158C8.978 9.586 8.5 9.052 8.5 8.351v-.702c0-.7.478-1.235 1.011-1.491A3.5 3.5 0 0 0 11.5 3V2h-7z" />
                </svg></i>
              <span class="mjhu">Statutory</span>
              <ul class="under_drop inside" id="myDIVwweps2" style="display: none;">
                <li class="pura" id="pura21">
                  <a class="dropdown-item" href="{{URL('Master/factory_license')}}">
                    <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-card-heading" viewBox="0 0 16 16">
                        <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z" />
                        <path d="M3 8.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5zm0-5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5v-1z" />
                      </svg></i>
                    <span class="mjhu">Factory License No.</span>
                  </a>
                </li>
                {{-- <li class="pura" id="pura22">
                  <a class="dropdown-item" href="{{URL('Master/GST')}}">
                    <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list-ol" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M5 11.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5z" />
                        <path d="M1.713 11.865v-.474H2c.217 0 .363-.137.363-.317 0-.185-.158-.31-.361-.31-.223 0-.367.152-.373.31h-.59c.016-.467.373-.787.986-.787.588-.002.954.291.957.703a.595.595 0 0 1-.492.594v.033a.615.615 0 0 1 .569.631c.003.533-.502.8-1.051.8-.656 0-1-.37-1.008-.794h.582c.008.178.186.306.422.309.254 0 .424-.145.422-.35-.002-.195-.155-.348-.414-.348h-.3zm-.004-4.699h-.604v-.035c0-.408.295-.844.958-.844.583 0 .96.326.96.756 0 .389-.257.617-.476.848l-.537.572v.03h1.054V9H1.143v-.395l.957-.99c.138-.142.293-.304.293-.508 0-.18-.147-.32-.342-.32a.33.33 0 0 0-.342.338v.041zM2.564 5h-.635V2.924h-.031l-.598.42v-.567l.629-.443h.635V5z" />
                      </svg></i>
                    <span class="mjhu">GST In No.</span>
                  </a>
                </li> --}}
                <li class="pura" id="pura23">
                  <a class="dropdown-item" href="{{URL('Master/pan')}}">
                    <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-vector-pen" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M10.646.646a.5.5 0 0 1 .708 0l4 4a.5.5 0 0 1 0 .708l-1.902 1.902-.829 3.313a1.5 1.5 0 0 1-1.024 1.073L1.254 14.746 4.358 4.4A1.5 1.5 0 0 1 5.43 3.377l3.313-.828L10.646.646zm-1.8 2.908-3.173.793a.5.5 0 0 0-.358.342l-2.57 8.565 8.567-2.57a.5.5 0 0 0 .34-.357l.794-3.174-3.6-3.6z" />
                        <path fill-rule="evenodd" d="M2.832 13.228 8 9a1 1 0 1 0-1-1l-4.228 5.168-.026.086.086-.026z" />
                      </svg></i>
                    <span class="mjhu">PAN No.</span>
                  </a>
                </li>
                <li class="pura" id="pura24">
                  <a class="dropdown-item" href="{{URL('Master/polution_certificate')}}">
                    <i class="fa fa-certificate" aria-hidden="true"></i>
                    <span class="mjhu"> Polution Certificate No.</span>
                  </a>
                </li>
                <li class="pura" id="pura25">
                  <a class="dropdown-item" href="{{URL('Master/labour_license')}}">
                    <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-card-list" viewBox="0 0 16 16">
                        <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z" />
                        <path d="M5 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 5 8zm0-2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm0 5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm-1-5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zM4 8a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zm0 2.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0z" />
                      </svg></i>
                    <span class="mjhu">Labour License No.</span>
                  </a>
                </li>
              </ul>
            </li>
            <li class="under_t  luck insideav" onclick="myFunctionttss(3)" id='is3'>
              <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-flower2" viewBox="0 0 16 16">
                  <path d="M8 16a4 4 0 0 0 4-4 4 4 0 0 0 0-8 4 4 0 0 0-8 0 4 4 0 1 0 0 8 4 4 0 0 0 4 4zm3-12c0 .073-.01.155-.03.247-.544.241-1.091.638-1.598 1.084A2.987 2.987 0 0 0 8 5c-.494 0-.96.12-1.372.331-.507-.446-1.054-.843-1.597-1.084A1.117 1.117 0 0 1 5 4a3 3 0 0 1 6 0zm-.812 6.052A2.99 2.99 0 0 0 11 8a2.99 2.99 0 0 0-.812-2.052c.215-.18.432-.346.647-.487C11.34 5.131 11.732 5 12 5a3 3 0 1 1 0 6c-.268 0-.66-.13-1.165-.461a6.833 6.833 0 0 1-.647-.487zm-3.56.617a3.001 3.001 0 0 0 2.744 0c.507.446 1.054.842 1.598 1.084.02.091.03.174.03.247a3 3 0 1 1-6 0c0-.073.01-.155.03-.247.544-.242 1.091-.638 1.598-1.084zm-.816-4.721A2.99 2.99 0 0 0 5 8c0 .794.308 1.516.812 2.052a6.83 6.83 0 0 1-.647.487C4.66 10.869 4.268 11 4 11a3 3 0 0 1 0-6c.268 0 .66.13 1.165.461.215.141.432.306.647.487zM8 9a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
                </svg></i>
              <span class="mjhu">Plant & Machinery</span>
              <ul class="under_drop inside" id="myDIVwweps3" style="display: none;">
                {{-- <li class="pura" id="pura31">
                  <a class="dropdown-item" href="{{URL('Master/plant_name')}}">
                    <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-flower3" viewBox="0 0 16 16">
                        <path d="M11.424 8c.437-.052.811-.136 1.04-.268a2 2 0 0 0-2-3.464c-.229.132-.489.414-.752.767C9.886 4.63 10 4.264 10 4a2 2 0 1 0-4 0c0 .264.114.63.288 1.035-.263-.353-.523-.635-.752-.767a2 2 0 0 0-2 3.464c.229.132.603.216 1.04.268-.437.052-.811.136-1.04.268a2 2 0 1 0 2 3.464c.229-.132.489-.414.752-.767C6.114 11.37 6 11.736 6 12a2 2 0 1 0 4 0c0-.264-.114-.63-.288-1.035.263.353.523.635.752.767a2 2 0 1 0 2-3.464c-.229-.132-.603-.216-1.04-.268zM9 4a1.468 1.468 0 0 1-.045.205c-.039.132-.1.295-.183.484a12.88 12.88 0 0 1-.637 1.223L8 6.142a21.73 21.73 0 0 1-.135-.23 12.88 12.88 0 0 1-.637-1.223 4.216 4.216 0 0 1-.183-.484A1.473 1.473 0 0 1 7 4a1 1 0 1 1 2 0zM3.67 5.5a1 1 0 0 1 1.366-.366 1.472 1.472 0 0 1 .156.142c.094.1.204.233.326.4.245.333.502.747.742 1.163l.13.232a21.86 21.86 0 0 1-.265.002 12.88 12.88 0 0 1-1.379-.06 4.214 4.214 0 0 1-.51-.083 1.47 1.47 0 0 1-.2-.064A1 1 0 0 1 3.67 5.5zm1.366 5.366a1 1 0 0 1-1-1.732c.001 0 .016-.008.047-.02.037-.013.087-.028.153-.044.134-.032.305-.06.51-.083a12.88 12.88 0 0 1 1.379-.06c.09 0 .178 0 .266.002a21.82 21.82 0 0 1-.131.232c-.24.416-.497.83-.742 1.163a4.1 4.1 0 0 1-.327.4 1.483 1.483 0 0 1-.155.142zM9 12a1 1 0 0 1-2 0 1.476 1.476 0 0 1 .045-.206c.039-.131.1-.294.183-.483.166-.378.396-.808.637-1.223L8 9.858l.135.23c.241.415.47.845.637 1.223.083.19.144.352.183.484A1.338 1.338 0 0 1 9 12zm3.33-6.5a1 1 0 0 1-.366 1.366 1.478 1.478 0 0 1-.2.064c-.134.032-.305.06-.51.083-.412.045-.898.061-1.379.06-.09 0-.178 0-.266-.002l.131-.232c.24-.416.497-.83.742-1.163a4.1 4.1 0 0 1 .327-.4c.046-.05.085-.086.114-.11.026-.022.04-.03.041-.032a1 1 0 0 1 1.366.366zm-1.366 5.366a1.494 1.494 0 0 1-.155-.141 4.225 4.225 0 0 1-.327-.4A12.88 12.88 0 0 1 9.74 9.16a22 22 0 0 1-.13-.232l.265-.002c.48-.001.967.015 1.379.06.205.023.376.051.51.083.066.016.116.031.153.044l.048.02a1 1 0 1 1-1 1.732zM8 9a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
                      </svg></i>
                    <span class="mjhu">Plant Name</span>
                  </a>
                </li>
                <li class="pura" id="pura32">
                  <a class="dropdown-item" href="{{URL('Master/Production_Capacity')}}">
                    <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-basket" viewBox="0 0 16 16">
                        <path d="M5.757 1.071a.5.5 0 0 1 .172.686L3.383 6h9.234L10.07 1.757a.5.5 0 1 1 .858-.514L13.783 6H15a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1v4.5a2.5 2.5 0 0 1-2.5 2.5h-9A2.5 2.5 0 0 1 1 13.5V9a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h1.217L5.07 1.243a.5.5 0 0 1 .686-.172zM2 9v4.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V9H2zM1 7v1h14V7H1zm3 3a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 4 10zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 6 10zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 8 10zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 1 .5-.5zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 1 .5-.5z" />
                      </svg></i>
                    <span class="mjhu">Production Capacity</span>
                  </a>
                </li>
                <li class="pura" id="pura33">
                  <a class="dropdown-item" href="{{URL('Master/product')}}">
                    <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-basket" viewBox="0 0 16 16">
                        <path d="M5.757 1.071a.5.5 0 0 1 .172.686L3.383 6h9.234L10.07 1.757a.5.5 0 1 1 .858-.514L13.783 6H15a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1v4.5a2.5 2.5 0 0 1-2.5 2.5h-9A2.5 2.5 0 0 1 1 13.5V9a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h1.217L5.07 1.243a.5.5 0 0 1 .686-.172zM2 9v4.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V9H2zM1 7v1h14V7H1zm3 3a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 4 10zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 6 10zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 8 10zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 1 .5-.5zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 1 .5-.5z" />
                      </svg></i>
                    <span class="mjhu">Product</span>
                  </a>
                </li>
                <li class="pura" id="pura34">
                  <a class="dropdown-item" href="{{URL('Master/Subproduct')}}">
                    <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-basket2" viewBox="0 0 16 16">
                        <path d="M4 10a1 1 0 0 1 2 0v2a1 1 0 0 1-2 0v-2zm3 0a1 1 0 0 1 2 0v2a1 1 0 0 1-2 0v-2zm3 0a1 1 0 1 1 2 0v2a1 1 0 0 1-2 0v-2z" />
                        <path d="M5.757 1.071a.5.5 0 0 1 .172.686L3.383 6h9.234L10.07 1.757a.5.5 0 1 1 .858-.514L13.783 6H15.5a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-.623l-1.844 6.456a.75.75 0 0 1-.722.544H3.69a.75.75 0 0 1-.722-.544L1.123 8H.5a.5.5 0 0 1-.5-.5v-1A.5.5 0 0 1 .5 6h1.717L5.07 1.243a.5.5 0 0 1 .686-.172zM2.163 8l1.714 6h8.246l1.714-6H2.163z" />
                      </svg></i>
                    <span class="mjhu">Sub Product</span>
                  </a>
                </li>
                <li class="pura" id="pura35">
                  <a class="dropdown-item" href="{{URL('Master/subsubproduct')}}">
                    <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-basket3" viewBox="0 0 16 16">
                        <path d="M5.757 1.071a.5.5 0 0 1 .172.686L3.383 6h9.234L10.07 1.757a.5.5 0 1 1 .858-.514L13.783 6H15.5a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H.5a.5.5 0 0 1-.5-.5v-1A.5.5 0 0 1 .5 6h1.717L5.07 1.243a.5.5 0 0 1 .686-.172zM3.394 15l-1.48-6h-.97l1.525 6.426a.75.75 0 0 0 .729.574h9.606a.75.75 0 0 0 .73-.574L15.056 9h-.972l-1.479 6h-9.21z" />
                      </svg></i>
                    <span class="mjhu">Sub Sub Product</span>
                  </a>
                </li> --}}
                <li class="pura" id="pura36">
                  <a class="dropdown-item" href="{{URL('Master/uoms')}}">
                    <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-up-right-circle" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8zm15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.854 10.803a.5.5 0 1 1-.708-.707L9.243 6H6.475a.5.5 0 1 1 0-1h3.975a.5.5 0 0 1 .5.5v3.975a.5.5 0 1 1-1 0V6.707l-4.096 4.096z" />
                      </svg></i>
                    <span class="mjhu">UOM</span>
                  </a>
                </li>
                {{-- <li class="pura" id="pura37">
                  <a class="dropdown-item" href="{{URL('Master/Duration')}}">
                    <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar2-week" viewBox="0 0 16 16">
                        <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM2 2a1 1 0 0 0-1 1v11a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1H2z" />
                        <path d="M2.5 4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H3a.5.5 0 0 1-.5-.5V4zM11 7.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm-3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm-5 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z" />
                      </svg></i>
                    <span class="mjhu">Duration</span>
                  </a>
                </li> --}}
                <li class="pura" id="pura38">
                  <a class="dropdown-item" href="{{URL('Master/Machine_Name')}}">
                    <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-easel" viewBox="0 0 16 16">
                        <path d="M8.5 6a.5.5 0 1 0-1 0h-2A1.5 1.5 0 0 0 4 7.5v2A1.5 1.5 0 0 0 5.5 11h.473l-.447 1.342a.5.5 0 1 0 .948.316L7.027 11H7.5v1a.5.5 0 0 0 1 0v-1h.473l.553 1.658a.5.5 0 1 0 .948-.316L10.027 11h.473A1.5 1.5 0 0 0 12 9.5v-2A1.5 1.5 0 0 0 10.5 6h-2zM5 7.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5h-5a.5.5 0 0 1-.5-.5v-2z" />
                        <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z" />
                      </svg></i>
                    <span class="mjhu">Machine Name</span>
                  </a>
                </li>
                {{-- <li class="pura" id="pura39">
                  <a class="dropdown-item" href="{{URL('Master/Machine_Code')}}">
                    <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-code-square" viewBox="0 0 16 16">
                        <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z" />
                        <path d="M6.854 4.646a.5.5 0 0 1 0 .708L4.207 8l2.647 2.646a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 0 1 .708 0zm2.292 0a.5.5 0 0 0 0 .708L11.793 8l-2.647 2.646a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708 0z" />
                      </svg></i>
                    <span class="mjhu">Machine Code</span>
                  </a>
                </li>
                <li class="pura" id="pura310">
                  <a class="dropdown-item" href="{{URL('Master/Accessories')}}">
                    <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-boxes" viewBox="0 0 16 16">
                        <path d="M7.752.066a.5.5 0 0 1 .496 0l3.75 2.143a.5.5 0 0 1 .252.434v3.995l3.498 2A.5.5 0 0 1 16 9.07v4.286a.5.5 0 0 1-.252.434l-3.75 2.143a.5.5 0 0 1-.496 0l-3.502-2-3.502 2.001a.5.5 0 0 1-.496 0l-3.75-2.143A.5.5 0 0 1 0 13.357V9.071a.5.5 0 0 1 .252-.434L3.75 6.638V2.643a.5.5 0 0 1 .252-.434L7.752.066ZM4.25 7.504 1.508 9.071l2.742 1.567 2.742-1.567L4.25 7.504ZM7.5 9.933l-2.75 1.571v3.134l2.75-1.571V9.933Zm1 3.134 2.75 1.571v-3.134L8.5 9.933v3.134Zm.508-3.996 2.742 1.567 2.742-1.567-2.742-1.567-2.742 1.567Zm2.242-2.433V3.504L8.5 5.076V8.21l2.75-1.572ZM7.5 8.21V5.076L4.75 3.504v3.134L7.5 8.21ZM5.258 2.643 8 4.21l2.742-1.567L8 1.076 5.258 2.643ZM15 9.933l-2.75 1.571v3.134L15 13.067V9.933ZM3.75 14.638v-3.134L1 9.933v3.134l2.75 1.571Z" />
                      </svg></i>
                    <span class="mjhu">Accessories</span>
                  </a>
                </li>
                <li class="pura" id="pura311">
                  <a class="dropdown-item" href="{{URL('Master/Specification')}}">
                    <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-briefcase" viewBox="0 0 16 16">
                        <path d="M6.5 1A1.5 1.5 0 0 0 5 2.5V3H1.5A1.5 1.5 0 0 0 0 4.5v8A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-8A1.5 1.5 0 0 0 14.5 3H11v-.5A1.5 1.5 0 0 0 9.5 1h-3zm0 1h3a.5.5 0 0 1 .5.5V3H6v-.5a.5.5 0 0 1 .5-.5zm1.886 6.914L15 7.151V12.5a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5V7.15l6.614 1.764a1.5 1.5 0 0 0 .772 0zM1.5 4h13a.5.5 0 0 1 .5.5v1.616L8.129 7.948a.5.5 0 0 1-.258 0L1 6.116V4.5a.5.5 0 0 1 .5-.5z" />
                      </svg></i>
                    <span class="mjhu">Specification</span>
                  </a>
                </li>
                <li class="pura" id="pura312">
                  <a class="dropdown-item" href="{{URL('Master/Make_Model')}}">
                    <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-back" viewBox="0 0 16 16">
                        <path d="M0 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v2h2a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-2H2a2 2 0 0 1-2-2V2zm2-1a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H2z" />
                      </svg></i>
                    <span class="mjhu">Make & Model</span>
                  </a>
                </li> --}}
                <li class="pura" id="pura313">
                  <a class="dropdown-item" href="{{URL('Master/Warranty')}}">
                    <i><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 640 512">
                        <path d="M524.531,69.836a1.5,1.5,0,0,0-.764-.7A485.065,485.065,0,0,0,404.081,32.03a1.816,1.816,0,0,0-1.923.91,337.461,337.461,0,0,0-14.9,30.6,447.848,447.848,0,0,0-134.426,0,309.541,309.541,0,0,0-15.135-30.6,1.89,1.89,0,0,0-1.924-.91A483.689,483.689,0,0,0,116.085,69.137a1.712,1.712,0,0,0-.788.676C39.068,183.651,18.186,294.69,28.43,404.354a2.016,2.016,0,0,0,.765,1.375A487.666,487.666,0,0,0,176.02,479.918a1.9,1.9,0,0,0,2.063-.676A348.2,348.2,0,0,0,208.12,430.4a1.86,1.86,0,0,0-1.019-2.588,321.173,321.173,0,0,1-45.868-21.853,1.885,1.885,0,0,1-.185-3.126c3.082-2.309,6.166-4.711,9.109-7.137a1.819,1.819,0,0,1,1.9-.256c96.229,43.917,200.41,43.917,295.5,0a1.812,1.812,0,0,1,1.924.233c2.944,2.426,6.027,4.851,9.132,7.16a1.884,1.884,0,0,1-.162,3.126,301.407,301.407,0,0,1-45.89,21.83,1.875,1.875,0,0,0-1,2.611,391.055,391.055,0,0,0,30.014,48.815,1.864,1.864,0,0,0,2.063.7A486.048,486.048,0,0,0,610.7,405.729a1.882,1.882,0,0,0,.765-1.352C623.729,277.594,590.933,167.465,524.531,69.836ZM222.491,337.58c-28.972,0-52.844-26.587-52.844-59.239S193.056,219.1,222.491,219.1c29.665,0,53.306,26.82,52.843,59.239C275.334,310.993,251.924,337.58,222.491,337.58Zm195.38,0c-28.971,0-52.843-26.587-52.843-59.239S388.437,219.1,417.871,219.1c29.667,0,53.307,26.82,52.844,59.239C470.715,310.993,447.538,337.58,417.871,337.58Z" />
                      </svg></i>
                    <span class="mjhu">Warranty</span>
                  </a>
                </li>
                {{-- <li class="pura" id="pura314">
                  <a class="dropdown-item" href="{{URL('Master/quality_check')}}">
                    <i><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 384 512">
                        <path d="M290.7 311L95 269.7 86.8 309l195.7 41zm51-87L188.2 95.7l-25.5 30.8 153.5 128.3zm-31.2 39.7L129.2 179l-16.7 36.5L293.7 300zM262 32l-32 24 119.3 160.3 32-24zm20.5 328h-200v39.7h200zm39.7 80H42.7V320h-40v160h359.5V320h-40z" />
                      </svg></i>
                    <span class="mjhu">Quality Check</span>
                  </a>
                </li>
                <li class="pura" id="pura315">
                  <a class="dropdown-item" href="{{URL('Master/BU')}}">
                    <i><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                        <path d="M474.31 330.41c-23.66 91.85-94.23 144.59-201.9 148.35V429.6c0-48 26.41-74.39 74.39-74.39 62 0 99.2-37.2 99.2-99.21 0-61.37-36.53-98.28-97.38-99.06-33-69.32-146.5-64.65-177.24 0C110.52 157.72 74 194.63 74 256c0 62.13 37.27 99.41 99.4 99.41 48 0 74.55 26.23 74.55 74.39V479c-134.43-5-211.1-85.07-211.1-223 0-141.82 81.35-223.2 223.2-223.2 114.77 0 189.84 53.2 214.69 148.81H500C473.88 71.51 388.22 8 259.82 8 105 8 12 101.19 12 255.82 12 411.14 105.19 504.34 259.82 504c128.27 0 213.87-63.81 239.67-173.59zM357 182.33c41.37 3.45 64.2 29 64.2 73.67 0 48-26.43 74.41-74.4 74.41-28.61 0-49.33-9.59-61.59-27.33 83.06-16.55 75.59-99.67 71.79-120.75zm-81.68 97.36c-2.46-10.34-16.33-87 56.23-97 2.27 10.09 16.52 87.11-56.26 97zM260 132c28.61 0 49 9.67 61.44 27.61-28.36 5.48-49.36 20.59-61.59 43.45-12.23-22.86-33.23-38-61.6-43.45 12.41-17.69 33.27-27.35 61.57-27.35zm-71.52 50.72c73.17 10.57 58.91 86.81 56.49 97-72.41-9.84-59-86.95-56.25-97zM173.2 330.41c-48 0-74.4-26.4-74.4-74.41 0-44.36 22.86-70 64.22-73.67-6.75 37.2-1.38 106.53 71.65 120.75-12.14 17.63-32.84 27.3-61.14 27.3zm53.21 12.39A80.8 80.8 0 0 0 260 309.25c7.77 14.49 19.33 25.54 33.82 33.55a80.28 80.28 0 0 0-33.58 33.83c-8-14.5-19.07-26.23-33.56-33.83z" />
                      </svg></i>
                    <span class="mjhu">BU</span>
                  </a>
                </li>
                <li class="pura" id="pura316">
                  <a class="dropdown-item" href="{{URL('Master/Manufacturing_unit')}}">
                    <i><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512">
                        <path d="M0 160v96C0 379.7 100.3 480 224 480s224-100.3 224-224V160H320v96c0 53-43 96-96 96s-96-43-96-96V160H0zm0-32H128V64c0-17.7-14.3-32-32-32H32C14.3 32 0 46.3 0 64v64zm320 0H448V64c0-17.7-14.3-32-32-32H352c-17.7 0-32 14.3-32 32v64z" />
                      </svg></i>
                    <span class="mjhu">Manufacturing Unit</span>
                  </a>
                </li>
                <li class="pura" id="pura317">
                  <a class="dropdown-item" href="{{URL('Master/category')}}">
                    <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-book" viewBox="0 0 16 16">
                        <path d="M1 2.828c.885-.37 2.154-.769 3.388-.893 1.33-.134 2.458.063 3.112.752v9.746c-.935-.53-2.12-.603-3.213-.493-1.18.12-2.37.461-3.287.811V2.828zm7.5-.141c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492V2.687zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783z" />
                      </svg></i>
                    <span class="mjhu">Category</span>
                  </a>
                </li>
                <li class="pura" id="pura318">
                  <a class="dropdown-item" href="{{URL('Master/Customer_Name')}}">
                    <i><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512">
                        <path d="M246.9 14.1C234 15.2 224 26 224 39c0 13.8 11.2 25 25 25H400c8.8 0 16-7.2 16-16V17.4C416 8 408 .7 398.7 1.4L246.9 14.1zM240 112c0 44.2 35.8 80 80 80s80-35.8 80-80c0-5.5-.6-10.8-1.6-16H241.6c-1 5.2-1.6 10.5-1.6 16zM72 224c-22.1 0-40 17.9-40 40s17.9 40 40 40H224v89.4L386.8 230.5c-13.3-4.3-27.3-6.5-41.6-6.5H240 72zm345.7 20.9L246.6 416H416V369.7l53.6 90.6c11.2 19 35.8 25.3 54.8 14.1s25.3-35.8 14.1-54.8L462.3 290.8c-11.2-18.9-26.6-34.5-44.6-45.9zM224 448v32c0 17.7 14.3 32 32 32H384c17.7 0 32-14.3 32-32V448H224z" />
                      </svg></i>
                    <span class="mjhu">Customer Name</span>
                  </a>
                </li>
                <li class="pura" id="pura319">
                  <a class="dropdown-item" href="{{URL('Master/Company_Name')}}">
                    <i><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 384 512">
                        <path d="M64 48c-8.8 0-16 7.2-16 16V448c0 8.8 7.2 16 16 16h80V400c0-26.5 21.5-48 48-48s48 21.5 48 48v64h80c8.8 0 16-7.2 16-16V64c0-8.8-7.2-16-16-16H64zM0 64C0 28.7 28.7 0 64 0H320c35.3 0 64 28.7 64 64V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V64zm88 40c0-8.8 7.2-16 16-16h48c8.8 0 16 7.2 16 16v48c0 8.8-7.2 16-16 16H104c-8.8 0-16-7.2-16-16V104zM232 88h48c8.8 0 16 7.2 16 16v48c0 8.8-7.2 16-16 16H232c-8.8 0-16-7.2-16-16V104c0-8.8 7.2-16 16-16zM88 232c0-8.8 7.2-16 16-16h48c8.8 0 16 7.2 16 16v48c0 8.8-7.2 16-16 16H104c-8.8 0-16-7.2-16-16V232zm144-16h48c8.8 0 16 7.2 16 16v48c0 8.8-7.2 16-16 16H232c-8.8 0-16-7.2-16-16V232c0-8.8 7.2-16 16-16z" />
                      </svg></i>
                    <span class="mjhu">Company Name</span>
                  </a>
                </li>
                <li class="pura" id="pura320">
                  <a class="dropdown-item" href="{{URL('Master/Work_Order_Status')}}">
                    <i><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 384 512">
                        <path d="M192 0c-41.8 0-77.4 26.7-90.5 64H64C28.7 64 0 92.7 0 128V448c0 35.3 28.7 64 64 64H320c35.3 0 64-28.7 64-64V128c0-35.3-28.7-64-64-64H282.5C269.4 26.7 233.8 0 192 0zm0 64a32 32 0 1 1 0 64 32 32 0 1 1 0-64zM72 272a24 24 0 1 1 48 0 24 24 0 1 1 -48 0zm104-16H304c8.8 0 16 7.2 16 16s-7.2 16-16 16H176c-8.8 0-16-7.2-16-16s7.2-16 16-16zM72 368a24 24 0 1 1 48 0 24 24 0 1 1 -48 0zm88 0c0-8.8 7.2-16 16-16H304c8.8 0 16 7.2 16 16s-7.2 16-16 16H176c-8.8 0-16-7.2-16-16z" />
                      </svg></i>
                    <span class="mjhu">Work Order Status</span>
                  </a>
                </li> --}}
              </ul>
            </li>
            <li class="under_t luck insideav" onclick="myFunctionttss(4)" id="is4">
              <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-building-check" viewBox="0 0 16 16">
                  <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Zm1.679-4.493-1.335 2.226a.75.75 0 0 1-1.174.144l-.774-.773a.5.5 0 0 1 .708-.708l.547.548 1.17-1.951a.5.5 0 1 1 .858.514Z" />
                  <path d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6.5a.5.5 0 0 1-1 0V1H3v14h3v-2.5a.5.5 0 0 1 .5-.5H8v4H3a1 1 0 0 1-1-1V1Z" />
                  <path d="M4.5 2a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm-6 3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm-6 3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Z" />
                </svg></i>
              <span class="mjhu">Land & Building</span>
              <ul class="under_drop inside" id="myDIVwweps4" style="display: none;">
                <li class="pura" id="pura41">
                  <a class="dropdown-item" href="{{URL('Master/land_type')}}">
                    <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-building" viewBox="0 0 16 16">
                        <path d="M4 2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1ZM4 5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1ZM7.5 5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1ZM4.5 8a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Z" />
                        <path d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V1Zm11 0H3v14h3v-2.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5V15h3V1Z" />
                      </svg></i>
                    <span class="mjhu">Land Ownership</span>
                  </a>
                </li>
                {{-- <li class="pura" id="pura42">
                  <a class="dropdown-item" href="{{URL('Master/LandArea')}}">
                    <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-building" viewBox="0 0 16 16">
                        <path d="M4 2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1ZM4 5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1ZM7.5 5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1ZM4.5 8a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Z" />
                        <path d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V1Zm11 0H3v14h3v-2.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5V15h3V1Z" />
                      </svg></i>
                    <span class="mjhu">Land Area</span>
                  </a>
                </li>
                <li class="pura" id="pura43">
                  <a class="dropdown-item" href="{{URL('Master/OpenArea')}}">
                    <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-building" viewBox="0 0 16 16">
                        <path d="M4 2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1ZM4 5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1ZM7.5 5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1ZM4.5 8a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Z" />
                        <path d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V1Zm11 0H3v14h3v-2.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5V15h3V1Z" />
                      </svg></i>
                    <span class="mjhu">Open Area</span>
                  </a>
                </li>
                <li class="pura" id="pura44">
                  <a class="dropdown-item" href="{{URL('Master/CoverArea')}}">
                    <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-building" viewBox="0 0 16 16">
                        <path d="M4 2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1ZM4 5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1ZM7.5 5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1ZM4.5 8a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Z" />
                        <path d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V1Zm11 0H3v14h3v-2.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5V15h3V1Z" />
                      </svg></i>
                    <span class="mjhu">Cover Area</span>
                  </a>
                </li> --}}
                {{-- <li class="pura" id="pura45">
                  <a class="dropdown-item" href="{{URL('Master/BuildingArea')}}">
                    <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-building" viewBox="0 0 16 16">
                        <path d="M4 2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1ZM4 5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1ZM7.5 5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1ZM4.5 8a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Z" />
                        <path d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V1Zm11 0H3v14h3v-2.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5V15h3V1Z" />
                      </svg></i>
                    <span class="mjhu">Building Area</span>
                  </a>
                </li>
                <li class="pura" id="pura46">
                  <a class="dropdown-item" href="{{URL('Master/BuildingType')}}">
                    <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-building" viewBox="0 0 16 16">
                        <path d="M4 2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1ZM4 5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1ZM7.5 5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1ZM4.5 8a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Z" />
                        <path d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V1Zm11 0H3v14h3v-2.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5V15h3V1Z" />
                      </svg></i>
                    <span class="mjhu">Building Type</span>
                  </a>
                </li> --}}
                {{-- <li class="pura" id="pura47">
                  <a class="dropdown-item" href="{{URL('Master/BoundaryHeight')}}">
                    <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-building" viewBox="0 0 16 16">
                        <path d="M4 2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1ZM4 5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1ZM7.5 5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1ZM4.5 8a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Z" />
                        <path d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V1Zm11 0H3v14h3v-2.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5V15h3V1Z" />
                      </svg></i>
                    <span class="mjhu">Boundary Height</span>
                  </a>
                </li>
                <li class="pura" id="pura48">
                  <a class="dropdown-item" href="{{URL('Master/BoundaryWidth')}}">
                    <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-building" viewBox="0 0 16 16">
                        <path d="M4 2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1ZM4 5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1ZM7.5 5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1ZM4.5 8a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Z" />
                        <path d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V1Zm11 0H3v14h3v-2.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5V15h3V1Z" />
                      </svg></i>
                    <span class="mjhu">Boundary Width</span>
                  </a>
                </li> --}}
                <li class="pura" id="pura49">
                  <a class="dropdown-item" href="{{URL('Master/BoundaryType')}}">
                    <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-building" viewBox="0 0 16 16">
                        <path d="M4 2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1ZM4 5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1ZM7.5 5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1ZM4.5 8a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Z" />
                        <path d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V1Zm11 0H3v14h3v-2.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5V15h3V1Z" />
                      </svg></i>
                    <span class="mjhu">Boundary Type</span>
                  </a>
                </li>
                {{-- <li class="pura" id="pura410">
                  <a class="dropdown-item" href="{{URL('Master/Window')}}">
                    <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-building" viewBox="0 0 16 16">
                        <path d="M4 2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1ZM4 5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1ZM7.5 5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1ZM4.5 8a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Z" />
                        <path d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V1Zm11 0H3v14h3v-2.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5V15h3V1Z" />
                      </svg></i>
                    <span class="mjhu">Window</span>
                  </a>
                </li>
                <li class="pura" id="pura411">
                  <a class="dropdown-item" href="{{URL('Master/Gate')}}">
                    <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-building" viewBox="0 0 16 16">
                        <path d="M4 2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1ZM4 5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1ZM7.5 5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1ZM4.5 8a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Z" />
                        <path d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V1Zm11 0H3v14h3v-2.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5V15h3V1Z" />
                      </svg></i>
                    <span class="mjhu">Gate</span>
                  </a>
                </li> --}}
              </ul>
            </li>
            <li class="under_t luck insideav" onclick="myFunctionttss(10)" id="is10">
              <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-building-check" viewBox="0 0 16 16">
                  <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Zm1.679-4.493-1.335 2.226a.75.75 0 0 1-1.174.144l-.774-.773a.5.5 0 0 1 .708-.708l.547.548 1.17-1.951a.5.5 0 1 1 .858.514Z" />
                  <path d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6.5a.5.5 0 0 1-1 0V1H3v14h3v-2.5a.5.5 0 0 1 .5-.5H8v4H3a1 1 0 0 1-1-1V1Z" />
                  <path d="M4.5 2a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm-6 3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm-6 3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Z" />
                </svg></i>
              <span class="mjhu">Gatepass</span>
              <ul class="under_drop inside" id="myDIVwweps10" style="display: none;">
                {{-- <li class="pura" id="pura101">
                  <a class="dropdown-item" href="{{URL('Master/Gate_Pass_Required')}}">
                    <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-building" viewBox="0 0 16 16">
                        <path d="M4 2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1ZM4 5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1ZM7.5 5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1ZM4.5 8a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Z" />
                        <path d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V1Zm11 0H3v14h3v-2.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5V15h3V1Z" />
                      </svg></i>
                    <span class="mjhu">Gatepass Required</span>
                  </a>
                </li> --}}
                <li class="pura" id="pura102">
                  <a class="dropdown-item" href="{{URL('Master/request_type')}}">
                    <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-building" viewBox="0 0 16 16">
                        <path d="M4 2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1ZM4 5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1ZM7.5 5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1ZM4.5 8a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Z" />
                        <path d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V1Zm11 0H3v14h3v-2.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5V15h3V1Z" />
                      </svg></i>
                    <span class="mjhu">Request Type</span>
                  </a>
                </li>
                {{-- <li class="pura" id="pura103">
                  <a class="dropdown-item" href="{{URL('Master/person_to_meet')}}">
                    <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-building" viewBox="0 0 16 16">
                        <path d="M4 2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1ZM4 5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1ZM7.5 5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1ZM4.5 8a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Z" />
                        <path d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V1Zm11 0H3v14h3v-2.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5V15h3V1Z" />
                      </svg></i>
                    <span class="mjhu">Person To Meet</span>
                  </a>
                </li> --}}
                <li class="pura" id="pura104">
                  <a class="dropdown-item" href="{{URL('Master/request_through')}}">
                    <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-building" viewBox="0 0 16 16">
                        <path d="M4 2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1ZM4 5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1ZM7.5 5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1ZM4.5 8a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Z" />
                        <path d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V1Zm11 0H3v14h3v-2.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5V15h3V1Z" />
                      </svg></i>
                    <span class="mjhu">Request Through</span>
                  </a>
                </li>
                {{-- <li class="pura" id="pura105">
                  <a class="dropdown-item" href="{{URL('Master/department')}}">
                    <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-building" viewBox="0 0 16 16">
                        <path d="M4 2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1ZM4 5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1ZM7.5 5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1ZM4.5 8a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Z" />
                        <path d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V1Zm11 0H3v14h3v-2.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5V15h3V1Z" />
                      </svg></i>
                    <span class="mjhu">Department</span>
                  </a>
                </li> --}}
                {{-- <li class="pura" id="pura106">
                  <a class="dropdown-item" href="{{URL('Master/contact_person')}}">
                    <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-building" viewBox="0 0 16 16">
                        <path d="M4 2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1ZM4 5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1ZM7.5 5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1ZM4.5 8a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Z" />
                        <path d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V1Zm11 0H3v14h3v-2.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5V15h3V1Z" />
                      </svg></i>
                    <span class="mjhu">Contact Person</span>
                  </a>
                </li> --}}
              </ul>
            </li>
            <li class="under_t  luck insideav" onclick="myFunctionttss(11)" id='is11'>
              <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-flower2" viewBox="0 0 16 16">
                  <path d="M8 16a4 4 0 0 0 4-4 4 4 0 0 0 0-8 4 4 0 0 0-8 0 4 4 0 1 0 0 8 4 4 0 0 0 4 4zm3-12c0 .073-.01.155-.03.247-.544.241-1.091.638-1.598 1.084A2.987 2.987 0 0 0 8 5c-.494 0-.96.12-1.372.331-.507-.446-1.054-.843-1.597-1.084A1.117 1.117 0 0 1 5 4a3 3 0 0 1 6 0zm-.812 6.052A2.99 2.99 0 0 0 11 8a2.99 2.99 0 0 0-.812-2.052c.215-.18.432-.346.647-.487C11.34 5.131 11.732 5 12 5a3 3 0 1 1 0 6c-.268 0-.66-.13-1.165-.461a6.833 6.833 0 0 1-.647-.487zm-3.56.617a3.001 3.001 0 0 0 2.744 0c.507.446 1.054.842 1.598 1.084.02.091.03.174.03.247a3 3 0 1 1-6 0c0-.073.01-.155.03-.247.544-.242 1.091-.638 1.598-1.084zm-.816-4.721A2.99 2.99 0 0 0 5 8c0 .794.308 1.516.812 2.052a6.83 6.83 0 0 1-.647.487C4.66 10.869 4.268 11 4 11a3 3 0 0 1 0-6c.268 0 .66.13 1.165.461.215.141.432.306.647.487zM8 9a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
                </svg></i>
              <span class="mjhu">Godown</span>
              <ul class="under_drop inside" id="myDIVwweps11" style="display: none;">
                <!--<li class="pura" id="pura111">-->
                <!--  <a class="dropdown-item" href="{{URL('Master/Godown_Name')}}">-->
                <!--    <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-flower3" viewBox="0 0 16 16">-->
                <!--        <path d="M11.424 8c.437-.052.811-.136 1.04-.268a2 2 0 0 0-2-3.464c-.229.132-.489.414-.752.767C9.886 4.63 10 4.264 10 4a2 2 0 1 0-4 0c0 .264.114.63.288 1.035-.263-.353-.523-.635-.752-.767a2 2 0 0 0-2 3.464c.229.132.603.216 1.04.268-.437.052-.811.136-1.04.268a2 2 0 1 0 2 3.464c.229-.132.489-.414.752-.767C6.114 11.37 6 11.736 6 12a2 2 0 1 0 4 0c0-.264-.114-.63-.288-1.035.263.353.523.635.752.767a2 2 0 1 0 2-3.464c-.229-.132-.603-.216-1.04-.268zM9 4a1.468 1.468 0 0 1-.045.205c-.039.132-.1.295-.183.484a12.88 12.88 0 0 1-.637 1.223L8 6.142a21.73 21.73 0 0 1-.135-.23 12.88 12.88 0 0 1-.637-1.223 4.216 4.216 0 0 1-.183-.484A1.473 1.473 0 0 1 7 4a1 1 0 1 1 2 0zM3.67 5.5a1 1 0 0 1 1.366-.366 1.472 1.472 0 0 1 .156.142c.094.1.204.233.326.4.245.333.502.747.742 1.163l.13.232a21.86 21.86 0 0 1-.265.002 12.88 12.88 0 0 1-1.379-.06 4.214 4.214 0 0 1-.51-.083 1.47 1.47 0 0 1-.2-.064A1 1 0 0 1 3.67 5.5zm1.366 5.366a1 1 0 0 1-1-1.732c.001 0 .016-.008.047-.02.037-.013.087-.028.153-.044.134-.032.305-.06.51-.083a12.88 12.88 0 0 1 1.379-.06c.09 0 .178 0 .266.002a21.82 21.82 0 0 1-.131.232c-.24.416-.497.83-.742 1.163a4.1 4.1 0 0 1-.327.4 1.483 1.483 0 0 1-.155.142zM9 12a1 1 0 0 1-2 0 1.476 1.476 0 0 1 .045-.206c.039-.131.1-.294.183-.483.166-.378.396-.808.637-1.223L8 9.858l.135.23c.241.415.47.845.637 1.223.083.19.144.352.183.484A1.338 1.338 0 0 1 9 12zm3.33-6.5a1 1 0 0 1-.366 1.366 1.478 1.478 0 0 1-.2.064c-.134.032-.305.06-.51.083-.412.045-.898.061-1.379.06-.09 0-.178 0-.266-.002l.131-.232c.24-.416.497-.83.742-1.163a4.1 4.1 0 0 1 .327-.4c.046-.05.085-.086.114-.11.026-.022.04-.03.041-.032a1 1 0 0 1 1.366.366zm-1.366 5.366a1.494 1.494 0 0 1-.155-.141 4.225 4.225 0 0 1-.327-.4A12.88 12.88 0 0 1 9.74 9.16a22 22 0 0 1-.13-.232l.265-.002c.48-.001.967.015 1.379.06.205.023.376.051.51.083.066.016.116.031.153.044l.048.02a1 1 0 1 1-1 1.732zM8 9a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />-->
                <!--      </svg></i>-->
                <!--    <span class="mjhu">Godown Name</span>-->
                <!--  </a>-->
                <!--</li>-->
                <li class="pura" id="pura112">
                  <a class="dropdown-item" href="{{URL('Master/Raw_Material')}}">
                    <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-basket" viewBox="0 0 16 16">
                        <path d="M5.757 1.071a.5.5 0 0 1 .172.686L3.383 6h9.234L10.07 1.757a.5.5 0 1 1 .858-.514L13.783 6H15a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1v4.5a2.5 2.5 0 0 1-2.5 2.5h-9A2.5 2.5 0 0 1 1 13.5V9a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h1.217L5.07 1.243a.5.5 0 0 1 .686-.172zM2 9v4.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V9H2zM1 7v1h14V7H1zm3 3a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 4 10zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 6 10zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 8 10zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 1 .5-.5zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 1 .5-.5z" />
                      </svg></i>
                    <span class="mjhu">Manual Stock Entry</span>
                  </a>
                </li>
                <!--<li class="pura" id="pura113">-->
                <!--  <a class="dropdown-item" href="{{URL('Master/OB')}}">-->
                <!--    <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-basket" viewBox="0 0 16 16">-->
                <!--        <path d="M5.757 1.071a.5.5 0 0 1 .172.686L3.383 6h9.234L10.07 1.757a.5.5 0 1 1 .858-.514L13.783 6H15a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1v4.5a2.5 2.5 0 0 1-2.5 2.5h-9A2.5 2.5 0 0 1 1 13.5V9a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h1.217L5.07 1.243a.5.5 0 0 1 .686-.172zM2 9v4.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V9H2zM1 7v1h14V7H1zm3 3a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 4 10zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 6 10zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 8 10zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 1 .5-.5zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 1 .5-.5z" />-->
                <!--      </svg></i>-->
                <!--    <span class="mjhu">OB</span>-->
                <!--  </a>-->
                <!--</li>-->
                <!-- <li class="pura" id="pura114">
                  <a class="dropdown-item" href="{{URL('Master/Received_QTY')}}">
                    <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-basket2" viewBox="0 0 16 16">
                        <path d="M4 10a1 1 0 0 1 2 0v2a1 1 0 0 1-2 0v-2zm3 0a1 1 0 0 1 2 0v2a1 1 0 0 1-2 0v-2zm3 0a1 1 0 1 1 2 0v2a1 1 0 0 1-2 0v-2z" />
                        <path d="M5.757 1.071a.5.5 0 0 1 .172.686L3.383 6h9.234L10.07 1.757a.5.5 0 1 1 .858-.514L13.783 6H15.5a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-.623l-1.844 6.456a.75.75 0 0 1-.722.544H3.69a.75.75 0 0 1-.722-.544L1.123 8H.5a.5.5 0 0 1-.5-.5v-1A.5.5 0 0 1 .5 6h1.717L5.07 1.243a.5.5 0 0 1 .686-.172zM2.163 8l1.714 6h8.246l1.714-6H2.163z" />
                      </svg></i>
                    <span class="mjhu">Received QTY</span>
                  </a>
                </li> -->
                <li class="pura" id="pura115">
                  <a class="dropdown-item" href="{{URL('Master/Rack_No')}}">
                    <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-basket3" viewBox="0 0 16 16">
                        <path d="M5.757 1.071a.5.5 0 0 1 .172.686L3.383 6h9.234L10.07 1.757a.5.5 0 1 1 .858-.514L13.783 6H15.5a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H.5a.5.5 0 0 1-.5-.5v-1A.5.5 0 0 1 .5 6h1.717L5.07 1.243a.5.5 0 0 1 .686-.172zM3.394 15l-1.48-6h-.97l1.525 6.426a.75.75 0 0 0 .729.574h9.606a.75.75 0 0 0 .73-.574L15.056 9h-.972l-1.479 6h-9.21z" />
                      </svg></i>
                    <span class="mjhu">Rack No</span>
                  </a>
                </li>
                <li class="pura" id="pura116">
                  <a class="dropdown-item" href="{{URL('Master/Sub_Rack_No')}}">
                    <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-up-right-circle" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8zm15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.854 10.803a.5.5 0 1 1-.708-.707L9.243 6H6.475a.5.5 0 1 1 0-1h3.975a.5.5 0 0 1 .5.5v3.975a.5.5 0 1 1-1 0V6.707l-4.096 4.096z" />
                      </svg></i>
                    <span class="mjhu">Sub Rack No</span>
                  </a>
                </li>
                <li class="pura" id="pura117">
                  <a class="dropdown-item" href="{{URL('Master/Bin_No')}}">
                    <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar2-week" viewBox="0 0 16 16">
                        <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM2 2a1 1 0 0 0-1 1v11a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1H2z" />
                        <path d="M2.5 4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H3a.5.5 0 0 1-.5-.5V4zM11 7.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm-3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm-5 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z" />
                      </svg></i>
                    <span class="mjhu">Bin No</span>
                  </a>
                </li>
                <li class="pura" id="pura118">
                  <a class="dropdown-item" href="{{URL('Master/Sub_Bin_No')}}">
                    <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-easel" viewBox="0 0 16 16">
                        <path d="M8.5 6a.5.5 0 1 0-1 0h-2A1.5 1.5 0 0 0 4 7.5v2A1.5 1.5 0 0 0 5.5 11h.473l-.447 1.342a.5.5 0 1 0 .948.316L7.027 11H7.5v1a.5.5 0 0 0 1 0v-1h.473l.553 1.658a.5.5 0 1 0 .948-.316L10.027 11h.473A1.5 1.5 0 0 0 12 9.5v-2A1.5 1.5 0 0 0 10.5 6h-2zM5 7.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5h-5a.5.5 0 0 1-.5-.5v-2z" />
                        <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z" />
                      </svg></i>
                    <span class="mjhu">Sub Bin No</span>
                  </a>
                </li>
              </ul>
            </li>
            <li class="under_t luck insideav" onclick="myFunctionttss(17)" id='is17'>
              <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-alt" viewBox="0 0 16 16">
                  <path d="M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A31.493 31.493 0 0 1 8 14.58a31.481 31.481 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94zM8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10z" />
                  <path d="M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                </svg></i>
              <span class="mjhu"> BOM </span>
              <ul class="under_drop inside" id="myDIVwweps17" style="display: none;">
                {{-- <li class="pura" id="pura171">
                  <a class="dropdown-item" href="{{URL('Master/Code')}}">
                    <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-globe" viewBox="0 0 16 16">
                        <path d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm7.5-6.923c-.67.204-1.335.82-1.887 1.855A7.97 7.97 0 0 0 5.145 4H7.5V1.077zM4.09 4a9.267 9.267 0 0 1 .64-1.539 6.7 6.7 0 0 1 .597-.933A7.025 7.025 0 0 0 2.255 4H4.09zm-.582 3.5c.03-.877.138-1.718.312-2.5H1.674a6.958 6.958 0 0 0-.656 2.5h2.49zM4.847 5a12.5 12.5 0 0 0-.338 2.5H7.5V5H4.847zM8.5 5v2.5h2.99a12.495 12.495 0 0 0-.337-2.5H8.5zM4.51 8.5a12.5 12.5 0 0 0 .337 2.5H7.5V8.5H4.51zm3.99 0V11h2.653c.187-.765.306-1.608.338-2.5H8.5zM5.145 12c.138.386.295.744.468 1.068.552 1.035 1.218 1.65 1.887 1.855V12H5.145zm.182 2.472a6.696 6.696 0 0 1-.597-.933A9.268 9.268 0 0 1 4.09 12H2.255a7.024 7.024 0 0 0 3.072 2.472zM3.82 11a13.652 13.652 0 0 1-.312-2.5h-2.49c.062.89.291 1.733.656 2.5H3.82zm6.853 3.472A7.024 7.024 0 0 0 13.745 12H11.91a9.27 9.27 0 0 1-.64 1.539 6.688 6.688 0 0 1-.597.933zM8.5 12v2.923c.67-.204 1.335-.82 1.887-1.855.173-.324.33-.682.468-1.068H8.5zm3.68-1h2.146c.365-.767.594-1.61.656-2.5h-2.49a13.65 13.65 0 0 1-.312 2.5zm2.802-3.5a6.959 6.959 0 0 0-.656-2.5H12.18c.174.782.282 1.623.312 2.5h2.49zM11.27 2.461c.247.464.462.98.64 1.539h1.835a7.024 7.024 0 0 0-3.072-2.472c.218.284.418.598.597.933zM10.855 4a7.966 7.966 0 0 0-.468-1.068C9.835 1.897 9.17 1.282 8.5 1.077V4h2.355z" />
                      </svg></i>
                    <span class="mjhu"> Code</span>
                  </a>
                </li> --}}
                <li class="pura" id="pura172">
                  <a class="dropdown-item" href="{{URL('Master/Color')}}">
                    <i>
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-globe-americas" viewBox="0 0 16 16">
                        <path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0ZM2.04 4.326c.325 1.329 2.532 2.54 3.717 3.19.48.263.793.434.743.484-.08.08-.162.158-.242.234-.416.396-.787.749-.758 1.266.035.634.618.824 1.214 1.017.577.188 1.168.38 1.286.983.082.417-.075.988-.22 1.52-.215.782-.406 1.48.22 1.48 1.5-.5 3.798-3.186 4-5 .138-1.243-2-2-3.5-2.5-.478-.16-.755.081-.99.284-.172.15-.322.279-.51.216-.445-.148-2.5-2-1.5-2.5.78-.39.952-.171 1.227.182.078.099.163.208.273.318.609.304.662-.132.723-.633.039-.322.081-.671.277-.867.434-.434 1.265-.791 2.028-1.12.712-.306 1.365-.587 1.579-.88A7 7 0 1 1 2.04 4.327Z" />
                      </svg></i>
                    <span class="mjhu">Manpower Skill</span>
                  </a>
                </li>
                <li class="pura" id="pura173">
                  <a class="dropdown-item" href="{{URL('Master/Consumbles')}}">
                    <i>
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-globe-europe-africa" viewBox="0 0 16 16">
                        <path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0ZM3.668 2.501l-.288.646a.847.847 0 0 0 1.479.815l.245-.368a.809.809 0 0 1 1.034-.275.809.809 0 0 0 .724 0l.261-.13a1 1 0 0 1 .775-.05l.984.34c.078.028.16.044.243.054.784.093.855.377.694.801-.155.41-.616.617-1.035.487l-.01-.003C8.274 4.663 7.748 4.5 6 4.5 4.8 4.5 3.5 5.62 3.5 7c0 1.96.826 2.166 1.696 2.382.46.115.935.233 1.304.618.449.467.393 1.181.339 1.877C6.755 12.96 6.674 14 8.5 14c1.75 0 3-3.5 3-4.5 0-.262.208-.468.444-.7.396-.392.87-.86.556-1.8-.097-.291-.396-.568-.641-.756-.174-.133-.207-.396-.052-.551a.333.333 0 0 1 .42-.042l1.085.724c.11.072.255.058.348-.035.15-.15.415-.083.489.117.16.43.445 1.05.849 1.357L15 8A7 7 0 1 1 3.668 2.501Z" />
                      </svg></i>
                    <span class="mjhu">Consumbles</span>
                  </a>
                </li>
                <li class="pura" id="pura174">
                  <a class="dropdown-item" href="{{URL('Master/GST_Percentage')}}">
                    <i>
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-diagram-3" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M6 3.5A1.5 1.5 0 0 1 7.5 2h1A1.5 1.5 0 0 1 10 3.5v1A1.5 1.5 0 0 1 8.5 6v1H14a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-1 0V8h-5v.5a.5.5 0 0 1-1 0V8h-5v.5a.5.5 0 0 1-1 0v-1A.5.5 0 0 1 2 7h5.5V6A1.5 1.5 0 0 1 6 4.5v-1zM8.5 5a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1zM0 11.5A1.5 1.5 0 0 1 1.5 10h1A1.5 1.5 0 0 1 4 11.5v1A1.5 1.5 0 0 1 2.5 14h-1A1.5 1.5 0 0 1 0 12.5v-1zm1.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1zm4.5.5A1.5 1.5 0 0 1 7.5 10h1a1.5 1.5 0 0 1 1.5 1.5v1A1.5 1.5 0 0 1 8.5 14h-1A1.5 1.5 0 0 1 6 12.5v-1zm1.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1zm4.5.5a1.5 1.5 0 0 1 1.5-1.5h1a1.5 1.5 0 0 1 1.5 1.5v1a1.5 1.5 0 0 1-1.5 1.5h-1a1.5 1.5 0 0 1-1.5-1.5v-1zm1.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1z" />
                      </svg></i>
                    <span class="mjhu">GST Percentage</span>
                  </a>
                </li>
                <li class="pura" id="pura175">
                  <a class="dropdown-item" href="{{URL('Master/Management_Expenses')}}"><i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-universal-access-circle" viewBox="0 0 16 16">
                        <path d="M8 4.143A1.071 1.071 0 1 0 8 2a1.071 1.071 0 0 0 0 2.143Zm-4.668 1.47 3.24.316v2.5l-.323 4.585A.383.383 0 0 0 7 13.14l.826-4.017c.045-.18.301-.18.346 0L9 13.139a.383.383 0 0 0 .752-.125L9.43 8.43v-2.5l3.239-.316a.38.38 0 0 0-.047-.756H3.379a.38.38 0 0 0-.047.756Z" />
                        <path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0ZM1 8a7 7 0 1 1 14 0A7 7 0 0 1 1 8Z" />
                      </svg></i>
                    <span class="mjhu">Management Expenses</span>
                  </a>
                </li>
                {{-- <li class="pura" id="pura176">
                  <a class="dropdown-item" href="{{URL('Master/Material')}}">
                    <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-globe" viewBox="0 0 16 16">
                        <path d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm7.5-6.923c-.67.204-1.335.82-1.887 1.855A7.97 7.97 0 0 0 5.145 4H7.5V1.077zM4.09 4a9.267 9.267 0 0 1 .64-1.539 6.7 6.7 0 0 1 .597-.933A7.025 7.025 0 0 0 2.255 4H4.09zm-.582 3.5c.03-.877.138-1.718.312-2.5H1.674a6.958 6.958 0 0 0-.656 2.5h2.49zM4.847 5a12.5 12.5 0 0 0-.338 2.5H7.5V5H4.847zM8.5 5v2.5h2.99a12.495 12.495 0 0 0-.337-2.5H8.5zM4.51 8.5a12.5 12.5 0 0 0 .337 2.5H7.5V8.5H4.51zm3.99 0V11h2.653c.187-.765.306-1.608.338-2.5H8.5zM5.145 12c.138.386.295.744.468 1.068.552 1.035 1.218 1.65 1.887 1.855V12H5.145zm.182 2.472a6.696 6.696 0 0 1-.597-.933A9.268 9.268 0 0 1 4.09 12H2.255a7.024 7.024 0 0 0 3.072 2.472zM3.82 11a13.652 13.652 0 0 1-.312-2.5h-2.49c.062.89.291 1.733.656 2.5H3.82zm6.853 3.472A7.024 7.024 0 0 0 13.745 12H11.91a9.27 9.27 0 0 1-.64 1.539 6.688 6.688 0 0 1-.597.933zM8.5 12v2.923c.67-.204 1.335-.82 1.887-1.855.173-.324.33-.682.468-1.068H8.5zm3.68-1h2.146c.365-.767.594-1.61.656-2.5h-2.49a13.65 13.65 0 0 1-.312 2.5zm2.802-3.5a6.959 6.959 0 0 0-.656-2.5H12.18c.174.782.282 1.623.312 2.5h2.49zM11.27 2.461c.247.464.462.98.64 1.539h1.835a7.024 7.024 0 0 0-3.072-2.472c.218.284.418.598.597.933zM10.855 4a7.966 7.966 0 0 0-.468-1.068C9.835 1.897 9.17 1.282 8.5 1.077V4h2.355z" />
                      </svg></i>
                    <span class="mjhu"> Material</span>
                  </a>
                </li> --}}
                <li class="pura" id="pura177">
                  <a class="dropdown-item" href="{{URL('Master/Services')}}"><i>
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-globe-americas" viewBox="0 0 16 16">
                        <path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0ZM2.04 4.326c.325 1.329 2.532 2.54 3.717 3.19.48.263.793.434.743.484-.08.08-.162.158-.242.234-.416.396-.787.749-.758 1.266.035.634.618.824 1.214 1.017.577.188 1.168.38 1.286.983.082.417-.075.988-.22 1.52-.215.782-.406 1.48.22 1.48 1.5-.5 3.798-3.186 4-5 .138-1.243-2-2-3.5-2.5-.478-.16-.755.081-.99.284-.172.15-.322.279-.51.216-.445-.148-2.5-2-1.5-2.5.78-.39.952-.171 1.227.182.078.099.163.208.273.318.609.304.662-.132.723-.633.039-.322.081-.671.277-.867.434-.434 1.265-.791 2.028-1.12.712-.306 1.365-.587 1.579-.88A7 7 0 1 1 2.04 4.327Z" />
                      </svg></i>
                    <span class="mjhu">Services</span>
                  </a>
                </li>
                {{-- <li class="pura" id="pura178">
                  <a class="dropdown-item" href="{{URL('Master/Machine_Specification')}}"><i>
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-globe-europe-africa" viewBox="0 0 16 16">
                        <path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0ZM3.668 2.501l-.288.646a.847.847 0 0 0 1.479.815l.245-.368a.809.809 0 0 1 1.034-.275.809.809 0 0 0 .724 0l.261-.13a1 1 0 0 1 .775-.05l.984.34c.078.028.16.044.243.054.784.093.855.377.694.801-.155.41-.616.617-1.035.487l-.01-.003C8.274 4.663 7.748 4.5 6 4.5 4.8 4.5 3.5 5.62 3.5 7c0 1.96.826 2.166 1.696 2.382.46.115.935.233 1.304.618.449.467.393 1.181.339 1.877C6.755 12.96 6.674 14 8.5 14c1.75 0 3-3.5 3-4.5 0-.262.208-.468.444-.7.396-.392.87-.86.556-1.8-.097-.291-.396-.568-.641-.756-.174-.133-.207-.396-.052-.551a.333.333 0 0 1 .42-.042l1.085.724c.11.072.255.058.348-.035.15-.15.415-.083.489.117.16.43.445 1.05.849 1.357L15 8A7 7 0 1 1 3.668 2.501Z" />
                      </svg></i>
                    <span class="mjhu">Machine Specification</span>
                  </a>
                </li> --}}
              </ul>
            </li>
          </ul>
        </li>
        @endif
        @if(in_array(1,$Department))
        <li class="under_t luck outside" onclick="myFunctiontt(5)" id="i5">
          <i class="fa fa-industry" aria-hidden="true"></i>
          <span class="mjhu"> Factory</span>
          @if((!empty($EXT[1]) && isset($EXT[1]['approver'])) || isset($EXT[1]['Forward']))
          @if($FACTORY > 0 )
              <span class="badge" style="font-weight: bold; background-color: rgb(255, 0, 0); color: black;">{{$FACTORY}}</span>
          @endif
          @endif
          <ul class="under_drop outsideav" id="myDIVwwep5" style="display: block;">
            <li class="pura" id="pura51">
              <a class="dropdown-item" href="{{url('FactoryCreater/List')}}">
                <i><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512">
                    <path d="M160 80c0-26.5 21.5-48 48-48h32c26.5 0 48 21.5 48 48V432c0 26.5-21.5 48-48 48H208c-26.5 0-48-21.5-48-48V80zM0 272c0-26.5 21.5-48 48-48H80c26.5 0 48 21.5 48 48V432c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V272zM368 96h32c26.5 0 48 21.5 48 48V432c0 26.5-21.5 48-48 48H368c-26.5 0-48-21.5-48-48V144c0-26.5 21.5-48 48-48z" />
                  </svg></i>
                <span class="mjhu">Factory List</span>
              </a>
            </li>
            @if((!empty($EXT[1]) && isset($EXT[1]['approver'])) || isset($EXT[1]['Forward']))
            <li class="pura" id="pura52">
              <a class="dropdown-item" href="{{url('FactoryCreater/factory-approve')}}">
                <i><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                    <path d="M128 64v96h64V64H386.7L416 93.3V160h64V93.3c0-17-6.7-33.3-18.7-45.3L432 18.7C420 6.7 403.7 0 386.7 0H192c-35.3 0-64 28.7-64 64zM0 160V480c0 17.7 14.3 32 32 32H64c17.7 0 32-14.3 32-32V160c0-17.7-14.3-32-32-32H32c-17.7 0-32 14.3-32 32zm480 32H128V480c0 17.7 14.3 32 32 32H480c17.7 0 32-14.3 32-32V224c0-17.7-14.3-32-32-32zM256 256a32 32 0 1 1 0 64 32 32 0 1 1 0-64zm96 32a32 32 0 1 1 64 0 32 32 0 1 1 -64 0zm32 96a32 32 0 1 1 0 64 32 32 0 1 1 0-64zM224 416a32 32 0 1 1 64 0 32 32 0 1 1 -64 0z" />
                  </svg></i>
                <span class="mjhu">Factory Approve</span>
                @if($FACTORY > 0 )
                  <span class="badge" style="font-weight: bold; background-color: rgb(255, 198, 28); color: black;">{{$FACTORY}}</span>
                @endif
              </a>
            </li>
            {{-- <li class="pura" id="pura52">
              <a class="dropdown-item" href="{{url('erp/abc.php')}}">
                <i><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                    <path d="M128 64v96h64V64H386.7L416 93.3V160h64V93.3c0-17-6.7-33.3-18.7-45.3L432 18.7C420 6.7 403.7 0 386.7 0H192c-35.3 0-64 28.7-64 64zM0 160V480c0 17.7 14.3 32 32 32H64c17.7 0 32-14.3 32-32V160c0-17.7-14.3-32-32-32H32c-17.7 0-32 14.3-32 32zm480 32H128V480c0 17.7 14.3 32 32 32H480c17.7 0 32-14.3 32-32V224c0-17.7-14.3-32-32-32zM256 256a32 32 0 1 1 0 64 32 32 0 1 1 0-64zm96 32a32 32 0 1 1 64 0 32 32 0 1 1 -64 0zm32 96a32 32 0 1 1 0 64 32 32 0 1 1 0-64zM224 416a32 32 0 1 1 64 0 32 32 0 1 1 -64 0z" />
                  </svg></i>
                <span class="mjhu">Factory Approve</span>
                @if($FACTORY > 0 )
                  <span class="badge" style="font-weight: bold; background-color: rgb(255, 198, 28); color: black;">{{$FACTORY}}</span>
                @endif
              </a>
            </li> --}}
            @endif
          </ul>
        </li>
        @endif
        @php
          setUserSessionData();
          $CustDepartment=Session::get('CustDepartment');
          $CUSTEXT=Session::get('CUSTEXT');
          $CUSTSTEP=Session::get('CUSTSTEP');
        @endphp
         @if(in_array(2,$CustDepartment))
        <li class="under_t luck outside" onclick="myFunctiontt(7)" id="i7">
          <i><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 640 512">
              <path d="M64 64l224 0 0 9.8c0 39-23.7 74-59.9 88.4C167.6 186.5 128 245 128 310.2l0 73.8s0 0 0 0H64V64zm288 0l224 0V384H508.3l-3.7-4.5-75.2-90.2c-9.1-10.9-22.6-17.3-36.9-17.3l-71.1 0-41-63.1c-.3-.5-.6-1-1-1.4c44.7-29 72.5-79 72.5-133.6l0-9.8zm73 320H379.2l42.7 64H592c26.5 0 48-21.5 48-48V48c0-26.5-21.5-48-48-48H48C21.5 0 0 21.5 0 48V400c0 26.5 21.5 48 48 48H308.2l33.2 49.8c9.8 14.7 29.7 18.7 44.4 8.9s18.7-29.7 8.9-44.4L310.5 336l74.6 0 40 48zm-159.5 0H192s0 0 0 0l0-73.8c0-10.2 1.6-20.1 4.7-29.5L265.5 384zM192 128a48 48 0 1 0 -96 0 48 48 0 1 0 96 0z" />
            </svg></i>
          <span class="mjhu"> GatePass</span>
          @php
            $EMPLOYEECOUNT = $INEMPLOYEEGATEPASSCOUNT + $OUTEMPLOYEEGATEPASSCOUNT + $INVISITORGATEPASSCOUNT + $OUTVISITORGATEPASSCOUNT;
          @endphp
          @if((!empty($CUSTEXT[2]) && (isset($CUSTEXT[2]['approver'])) || isset($CUSTEXT[2]['Forward'])) && ($EMPLOYEECOUNT > 0))
            <span class="badge" style="font-weight: bold; background-color: rgb(255, 198, 28); color: black;">{{$EMPLOYEECOUNT}}</span>
          @endif
          <ul class="under_drop outsideav" id="myDIVwwep7" style="display: none;">
            <li class="pura" id="pura71">
              <a class="dropdown-item" href="{{url('GatePass/List')}}">
                <i><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512">
                    <path d="M320 32c0-9.9-4.5-19.2-12.3-25.2S289.8-1.4 280.2 1l-179.9 45C79 51.3 64 70.5 64 92.5V448H32c-17.7 0-32 14.3-32 32s14.3 32 32 32H96 288h32V480 32zM256 256c0 17.7-10.7 32-24 32s-24-14.3-24-32s10.7-32 24-32s24 14.3 24 32zm96-128h96V480c0 17.7 14.3 32 32 32h64c17.7 0 32-14.3 32-32s-14.3-32-32-32H512V128c0-35.3-28.7-64-64-64H352v64z" />
                  </svg></i>
                <span class="mjhu">Employee Gate Pass</span>
                
              </a>
            </li>
            @if((!empty($CUSTEXT[2]) && (isset($CUSTEXT[2]['approver'])) || isset($CUSTEXT[2]['Forward'])))
              <li class="pura" id="pura72">
                <a class="dropdown-item" href="{{url('GatePass/Employee_Gatepass_Approval')}}">
                  <i><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512">
                      <path d="M320 32c0-9.9-4.5-19.2-12.3-25.2S289.8-1.4 280.2 1l-179.9 45C79 51.3 64 70.5 64 92.5V448H32c-17.7 0-32 14.3-32 32s14.3 32 32 32H96 288h32V480 32zM256 256c0 17.7-10.7 32-24 32s-24-14.3-24-32s10.7-32 24-32s24 14.3 24 32zm96-128h96V480c0 17.7 14.3 32 32 32h64c17.7 0 32-14.3 32-32s-14.3-32-32-32H512V128c0-35.3-28.7-64-64-64H352v64z" />
                    </svg></i>
                    
                  <span class="mjhu">Emp G.Pass Approval</span>
                  @if((!empty($CUSTEXT[2]) && (isset($CUSTEXT[2]['approver'])) || isset($CUSTEXT[2]['Forward'])))
                      @if($INEMPLOYEEGATEPASSCOUNT > 0 )
                      <span class="badge" style="font-weight: bold; background-color: rgb(255, 0, 0); color: black;">{{$INEMPLOYEEGATEPASSCOUNT}}</span>
                      @endif
                  @endif
                </a>
              </li>
              <li class="pura" id="pura73">
                <a class="dropdown-item" href="{{url('GatePass/Employee_Gatepass_Out_Approval')}}">
                  <i><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512">
                      <path d="M320 32c0-9.9-4.5-19.2-12.3-25.2S289.8-1.4 280.2 1l-179.9 45C79 51.3 64 70.5 64 92.5V448H32c-17.7 0-32 14.3-32 32s14.3 32 32 32H96 288h32V480 32zM256 256c0 17.7-10.7 32-24 32s-24-14.3-24-32s10.7-32 24-32s24 14.3 24 32zm96-128h96V480c0 17.7 14.3 32 32 32h64c17.7 0 32-14.3 32-32s-14.3-32-32-32H512V128c0-35.3-28.7-64-64-64H352v64z" />
                    </svg></i>
                  <span class="mjhu">Emp G.Pass Out Approval</span>
                  @if((!empty($CUSTEXT[2]) && (isset($CUSTEXT[2]['approver'])) || isset($CUSTEXT[2]['Forward'])))
                      @if($OUTEMPLOYEEGATEPASSCOUNT > 0 )
                      <span class="badge" style="font-weight: bold; background-color: rgb(255, 0, 0); color: black;">{{$OUTEMPLOYEEGATEPASSCOUNT}}</span>
                      @endif
                  @endif
                </a>
              </li>
            @endif
            <li class="pura" id="pura74">
              <a class="dropdown-item" href="{{url('GatePass/visitor-list')}}">
                <i><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                    <path d="M336.6 156.5c1.3 1.1 2.7 2.2 3.9 3.3c9.3 8.2 23 10.5 33.4 3.6l67.6-45.1c11.4-7.6 14.2-23.2 5.1-33.4C430 66.6 410.9 50.6 389.7 37.6c-11.9-7.3-26.9-1.4-32.1 11.6l-30.5 76.2c-4.5 11.1 .2 23.6 9.5 31.2zM328 36.8c5.1-12.8-1.6-27.4-15-30.5C294.7 2.2 275.6 0 256 0s-38.7 2.2-57 6.4C185.5 9.4 178.8 24 184 36.8l30.3 75.8c4.5 11.3 16.8 17.2 29 16c4.2-.4 8.4-.6 12.7-.6s8.6 .2 12.7 .6c12.1 1.2 24.4-4.7 29-16L328 36.8zM65.5 85c-9.1 10.2-6.3 25.8 5.1 33.4l67.6 45.1c10.3 6.9 24.1 4.6 33.4-3.6c1.3-1.1 2.6-2.3 4-3.3c9.3-7.5 13.9-20.1 9.5-31.2L154.4 49.2c-5.2-12.9-20.3-18.8-32.1-11.6C101.1 50.6 82 66.6 65.5 85zm314 137.1c.9 3.3 1.7 6.6 2.3 10c2.5 13 13 23.9 26.2 23.9h80c13.3 0 24.1-10.8 22.9-24c-2.5-27.2-9.3-53.2-19.7-77.3c-5.5-12.9-21.4-16.6-33.1-8.9l-68.6 45.7c-9.8 6.5-13.2 19.2-10 30.5zM53.9 145.8c-11.6-7.8-27.6-4-33.1 8.9C10.4 178.8 3.6 204.8 1.1 232c-1.2 13.2 9.6 24 22.9 24h80c13.3 0 23.8-10.8 26.2-23.9c.6-3.4 1.4-6.7 2.3-10c3.1-11.4-.2-24-10-30.5L53.9 145.8zM104 288H24c-13.3 0-24 10.7-24 24v48c0 13.3 10.7 24 24 24h80c13.3 0 24-10.7 24-24V312c0-13.3-10.7-24-24-24zm304 0c-13.3 0-24 10.7-24 24v48c0 13.3 10.7 24 24 24h80c13.3 0 24-10.7 24-24V312c0-13.3-10.7-24-24-24H408zM24 416c-13.3 0-24 10.7-24 24v48c0 13.3 10.7 24 24 24h80c13.3 0 24-10.7 24-24V440c0-13.3-10.7-24-24-24H24zm384 0c-13.3 0-24 10.7-24 24v48c0 13.3 10.7 24 24 24h80c13.3 0 24-10.7 24-24V440c0-13.3-10.7-24-24-24H408zM272 192c0-8.8-7.2-16-16-16s-16 7.2-16 16V464c0 8.8 7.2 16 16 16s16-7.2 16-16V192zm-64 32c0-8.8-7.2-16-16-16s-16 7.2-16 16V464c0 8.8 7.2 16 16 16s16-7.2 16-16V224zm128 0c0-8.8-7.2-16-16-16s-16 7.2-16 16V464c0 8.8 7.2 16 16 16s16-7.2 16-16V224z" />
                  </svg></i>
                <span class="mjhu">Visitor Gate Pass</span>
              </a>
            </li>
            @if((!empty($CUSTEXT[2]) && (isset($CUSTEXT[2]['approver'])) || isset($CUSTEXT[2]['Forward'])))
              <li class="pura" id="pura75">
                <a class="dropdown-item" href="{{url('GatePass/Visitor_Gatepass_Approval')}}">
                  <i><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512">
                      <path d="M320 32c0-9.9-4.5-19.2-12.3-25.2S289.8-1.4 280.2 1l-179.9 45C79 51.3 64 70.5 64 92.5V448H32c-17.7 0-32 14.3-32 32s14.3 32 32 32H96 288h32V480 32zM256 256c0 17.7-10.7 32-24 32s-24-14.3-24-32s10.7-32 24-32s24 14.3 24 32zm96-128h96V480c0 17.7 14.3 32 32 32h64c17.7 0 32-14.3 32-32s-14.3-32-32-32H512V128c0-35.3-28.7-64-64-64H352v64z" />
                    </svg></i>
                    
                  <span class="mjhu">Visit G.Pass Approval</span>
                  @if((!empty($CUSTEXT[2]) && (isset($CUSTEXT[2]['approver'])) || isset($CUSTEXT[2]['Forward'])))
                      @if($INVISITORGATEPASSCOUNT > 0 )
                      <span class="badge" style="font-weight: bold; background-color: rgb(255, 0, 0); color: black;">{{$INVISITORGATEPASSCOUNT}}</span>
                      @endif
                  @endif
                </a>
              </li>
              <li class="pura" id="pura76">
                <a class="dropdown-item" href="{{url('GatePass/Visitor_Gatepass_Out_Approval')}}">
                  <i><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512">
                      <path d="M320 32c0-9.9-4.5-19.2-12.3-25.2S289.8-1.4 280.2 1l-179.9 45C79 51.3 64 70.5 64 92.5V448H32c-17.7 0-32 14.3-32 32s14.3 32 32 32H96 288h32V480 32zM256 256c0 17.7-10.7 32-24 32s-24-14.3-24-32s10.7-32 24-32s24 14.3 24 32zm96-128h96V480c0 17.7 14.3 32 32 32h64c17.7 0 32-14.3 32-32s-14.3-32-32-32H512V128c0-35.3-28.7-64-64-64H352v64z" />
                    </svg></i>
                  <span class="mjhu">Visit G.Pass Out Approval</span>
                  @if((!empty($CUSTEXT[2]) && (isset($CUSTEXT[2]['approver'])) || isset($CUSTEXT[2]['Forward'])))
                      @if($OUTVISITORGATEPASSCOUNT > 0 )
                      <span class="badge" style="font-weight: bold; background-color: rgb(255, 0, 0); color: black;">{{$OUTVISITORGATEPASSCOUNT}}</span>
                      @endif
                  @endif
                </a>
              </li>
            @endif
            <li class="pura" id="pura77">
              <a class="dropdown-item" href="{{url('GatePass/material-list')}}">
                <i><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                    <path d="M0 80c0 26.5 21.5 48 48 48H64v64h64V128h96v64h64V128h96v64h64V128h16c26.5 0 48-21.5 48-48V13.4C512 6 506 0 498.6 0c-1.7 0-3.4 .3-5 1l-49 19.6C425.7 28.1 405.5 32 385.2 32H126.8c-20.4 0-40.5-3.9-59.4-11.4L18.4 1c-1.6-.6-3.3-1-5-1C6 0 0 6 0 13.4V80zM64 288V480c0 17.7 14.3 32 32 32s32-14.3 32-32V288H384V480c0 17.7 14.3 32 32 32s32-14.3 32-32V288h32c17.7 0 32-14.3 32-32s-14.3-32-32-32H32c-17.7 0-32 14.3-32 32s14.3 32 32 32H64z" />
                  </svg></i>
                <span class="mjhu">Material Gate Pass</span>
              </a>
            </li>
            @if((!empty($CUSTEXT[2]) && (isset($CUSTEXT[2]['approver'])) || isset($CUSTEXT[2]['Forward'])))
              <li class="pura" id="pura78">
                <a class="dropdown-item" href="{{url('GatePass/Material_Gatepass_Approval')}}">
                  <i><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                      <path d="M0 80c0 26.5 21.5 48 48 48H64v64h64V128h96v64h64V128h96v64h64V128h16c26.5 0 48-21.5 48-48V13.4C512 6 506 0 498.6 0c-1.7 0-3.4 .3-5 1l-49 19.6C425.7 28.1 405.5 32 385.2 32H126.8c-20.4 0-40.5-3.9-59.4-11.4L18.4 1c-1.6-.6-3.3-1-5-1C6 0 0 6 0 13.4V80zM64 288V480c0 17.7 14.3 32 32 32s32-14.3 32-32V288H384V480c0 17.7 14.3 32 32 32s32-14.3 32-32V288h32c17.7 0 32-14.3 32-32s-14.3-32-32-32H32c-17.7 0-32 14.3-32 32s14.3 32 32 32H64z" />
                    </svg></i>
                  <span class="mjhu">Material G.Pass Approval</span>
                  @if((!empty($CUSTEXT[2]) && (isset($CUSTEXT[2]['approver'])) || isset($CUSTEXT[2]['Forward'])))
                      @if($INMATERIALGATEPASSCOUNT > 0 )
                      <span class="badge" style="font-weight: bold; background-color: rgb(255, 0, 0); color: black;">{{$INMATERIALGATEPASSCOUNT}}</span>
                      @endif
                  @endif
                </a>
              </li>
              <li class="pura" id="pura79">
                <a class="dropdown-item" href="{{url('GatePass/Material_Gatepass_Out_Approval')}}">
                  <i><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                      <path d="M0 80c0 26.5 21.5 48 48 48H64v64h64V128h96v64h64V128h96v64h64V128h16c26.5 0 48-21.5 48-48V13.4C512 6 506 0 498.6 0c-1.7 0-3.4 .3-5 1l-49 19.6C425.7 28.1 405.5 32 385.2 32H126.8c-20.4 0-40.5-3.9-59.4-11.4L18.4 1c-1.6-.6-3.3-1-5-1C6 0 0 6 0 13.4V80zM64 288V480c0 17.7 14.3 32 32 32s32-14.3 32-32V288H384V480c0 17.7 14.3 32 32 32s32-14.3 32-32V288h32c17.7 0 32-14.3 32-32s-14.3-32-32-32H32c-17.7 0-32 14.3-32 32s14.3 32 32 32H64z" />
                    </svg></i>
                  <span class="mjhu">Material G.Pass Out Approval</span>
                  @if((!empty($CUSTEXT[2]) && (isset($CUSTEXT[2]['approver'])) || isset($CUSTEXT[2]['Forward'])))
                      @if($OUTMATERIALGATEPASSCOUNT > 0 )
                      <span class="badge" style="font-weight: bold; background-color: rgb(255, 0, 0); color: black;">{{$OUTMATERIALGATEPASSCOUNT}}</span>
                      @endif
                  @endif
                </a>
              </li>
            @endif
          </ul>
        </li>
        @endif
        
        @if(in_array(4,$Department))
        <li class="under_t luck outside" onclick="myFunctiontt(9)" id="i9">
          <i><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
              <path d="M257.5 27.6c-.8-5.4-4.9-9.8-10.3-10.6c-22.1-3.1-44.6 .9-64.4 11.4l-74 39.5C89.1 78.4 73.2 94.9 63.4 115L26.7 190.6c-9.8 20.1-13 42.9-9.1 64.9l14.5 82.8c3.9 22.1 14.6 42.3 30.7 57.9l60.3 58.4c16.1 15.6 36.6 25.6 58.7 28.7l83 11.7c22.1 3.1 44.6-.9 64.4-11.4l74-39.5c19.7-10.5 35.6-27 45.4-47.2l36.7-75.5c9.8-20.1 13-42.9 9.1-64.9c-.9-5.3-5.3-9.3-10.6-10.1c-51.5-8.2-92.8-47.1-104.5-97.4c-1.8-7.6-8-13.4-15.7-14.6c-54.6-8.7-97.7-52-106.2-106.8zM208 144a32 32 0 1 1 0 64 32 32 0 1 1 0-64zM144 336a32 32 0 1 1 64 0 32 32 0 1 1 -64 0zm224-64a32 32 0 1 1 0 64 32 32 0 1 1 0-64z" />
            </svg></i>
            @if((!empty($EXT[4]) && isset($EXT[4]['approver'])) || isset($EXT[4]['Forward']))
                @if($MATERIAL_MNG > 0 )
                <span class="badge" style="font-weight: bold; background-color: rgb(255, 0, 0); color: black;">{{$MATERIAL_MNG}}</span>
                @endif
            @endif
          <span class="mjhu">Material Management</span>
          
          <ul class="under_drop outsideav" id="myDIVwwep9" style="display: none;">
            <li class="pura" id="pura91">
              <a class="dropdown-item" href="{{url('MaterialManagement/MaterialList')}}" class="">
                <i><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                    <path d="M2.3 412.2c-4.5 7.6-2.1 17.5 5.5 22.2l105.9 65.2c7.7 4.7 17.7 2.4 22.4-5.3 0-.1.1-.2.1-.2 67.1-112.2 80.5-95.9 280.9-.7 8.1 3.9 17.8.4 21.7-7.7.1-.1.1-.3.2-.4l50.4-114.1c3.6-8.1-.1-17.6-8.1-21.3-22.2-10.4-66.2-31.2-105.9-50.3C127.5 179 44.6 345.3 2.3 412.2zm507.4-312.1c4.5-7.6 2.1-17.5-5.5-22.2L398.4 12.8c-7.5-5-17.6-3.1-22.6 4.4-.2.3-.4.6-.6 1-67.3 112.6-81.1 95.6-280.6.9-8.1-3.9-17.8-.4-21.7 7.7-.1.1-.1.3-.2.4L22.2 141.3c-3.6 8.1.1 17.6 8.1 21.3 22.2 10.4 66.3 31.2 106 50.4 248 120 330.8-45.4 373.4-112.9z" />
                  </svg></i>
                <span class="mjhu">Material List</span>
              </a>
            </li>
            @if((!empty($EXT[4]) && isset($EXT[4]['approver'])) || isset($EXT[4]['Forward']))
            <li class="pura" id="pura92">
              <a class="dropdown-item" href="{{url('MaterialManagement/MaterialApproveList')}}" class="">
                <i><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                    <path d="M45.4 305c14.4 67.1 26.4 129 68.2 175H34c-18.7 0-34-15.2-34-34V66c0-18.7 15.2-34 34-34h57.7C77.9 44.6 65.6 59.2 54.8 75.6c-45.4 70-27 146.8-9.4 229.4zM478 32h-90.2c21.4 21.4 39.2 49.5 52.7 84.1l-137.1 29.3c-14.9-29-37.8-53.3-82.6-43.9-24.6 5.3-41 19.3-48.3 34.6-8.8 18.7-13.2 39.8 8.2 140.3 21.1 100.2 33.7 117.7 49.5 131.2 12.9 11.1 33.4 17 58.3 11.7 44.5-9.4 55.7-40.7 57.4-73.2l137.4-29.6c3.2 71.5-18.7 125.2-57.4 163.6H478c18.7 0 34-15.2 34-34V66c0-18.8-15.2-34-34-34z" />
                  </svg></i>
                <span class="mjhu">Material Approve List</span>
                    @if($MATERIAL_MNG > 0 )
                    <span class="badge" style="font-weight: bold; background-color: rgb(255, 198, 28); color: black;">{{$MATERIAL_MNG}}</span>
                    @endif
              </a>
            </li>
            @endif
          </ul>
        </li>
        @endif
        @if(in_array(6,$Department))
        <li class="under_t luck outside" onclick="myFunctiontt(12)" id="i12">
          <i><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512">
              <path d="M96 151.4V360.6c9.7 5.6 17.8 13.7 23.4 23.4H328.6c0-.1 .1-.2 .1-.3l-4.5-7.9-32-56 0 0c-1.4 .1-2.8 .1-4.2 .1c-35.3 0-64-28.7-64-64s28.7-64 64-64c1.4 0 2.8 0 4.2 .1l0 0 32-56 4.5-7.9-.1-.3H119.4c-5.6 9.7-13.7 17.8-23.4 23.4zM384.3 352c35.2 .2 63.7 28.7 63.7 64c0 35.3-28.7 64-64 64c-23.7 0-44.4-12.9-55.4-32H119.4c-11.1 19.1-31.7 32-55.4 32c-35.3 0-64-28.7-64-64c0-23.7 12.9-44.4 32-55.4V151.4C12.9 140.4 0 119.7 0 96C0 60.7 28.7 32 64 32c23.7 0 44.4 12.9 55.4 32H328.6c11.1-19.1 31.7-32 55.4-32c35.3 0 64 28.7 64 64c0 35.3-28.5 63.8-63.7 64l-4.5 7.9-32 56-2.3 4c4.2 8.5 6.5 18 6.5 28.1s-2.3 19.6-6.5 28.1l2.3 4 32 56 4.5 7.9z" />
            </svg></i>
          <span class="mjhu">Raw Material</span>
          @if((!empty($EXT[6]) && isset($EXT[6]['approver'])) || isset($EXT[6]['Forward']))
            @if($RAWMATERIAL > 0 )
            <span class="badge" style="font-weight: bold; background-color: rgb(255, 0, 0); color: black;">{{$RAWMATERIAL}}</span>
            @endif
          @endif
          <ul class="under_drop outsideav" id="myDIVwwep12" style="display: none;">
            <li class="pura" id="pura121">
              <a class="dropdown-item" href="{{url('RawMaterial/RawMaterialList')}}" class="">
                <i><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                    <path d="M288 32c0-17.7-14.3-32-32-32s-32 14.3-32 32V43.5c0 49.9-60.3 74.9-95.6 39.6L120.2 75C107.7 62.5 87.5 62.5 75 75s-12.5 32.8 0 45.3l8.2 8.2C118.4 163.7 93.4 224 43.5 224H32c-17.7 0-32 14.3-32 32s14.3 32 32 32H43.5c49.9 0 74.9 60.3 39.6 95.6L75 391.8c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l8.2-8.2c35.3-35.3 95.6-10.3 95.6 39.6V480c0 17.7 14.3 32 32 32s32-14.3 32-32V468.5c0-49.9 60.3-74.9 95.6-39.6l8.2 8.2c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3l-8.2-8.2c-35.3-35.3-10.3-95.6 39.6-95.6H480c17.7 0 32-14.3 32-32s-14.3-32-32-32H468.5c-49.9 0-74.9-60.3-39.6-95.6l8.2-8.2c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-8.2 8.2C348.3 118.4 288 93.4 288 43.5V32zM176 224a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm128 56a24 24 0 1 1 0 48 24 24 0 1 1 0-48z" />
                  </svg></i>
                <span class="mjhu">Raw Material List</span>
              </a>
            </li>
            @if((!empty($EXT[6]) && isset($EXT[6]['approver'])) || isset($EXT[6]['Forward']))
            <li class="pura" id="pura122">
              <a class="dropdown-item" href="{{url('RawMaterial/RawMaterialApproveList')}}" class="">
                <i><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 640 512">
                    <path d="M192 0c13.3 0 24 10.7 24 24V37.5c0 35.6 43.1 53.5 68.3 28.3l9.5-9.5c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-9.5 9.5C293 124.9 310.9 168 346.5 168H360c13.3 0 24 10.7 24 24s-10.7 24-24 24H346.5c-35.6 0-53.5 43.1-28.3 68.3l9.5 9.5c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-9.5-9.5C259.1 293 216 310.9 216 346.5V360c0 13.3-10.7 24-24 24s-24-10.7-24-24V346.5c0-35.6-43.1-53.5-68.3-28.3l-9.5 9.5c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l9.5-9.5C91 259.1 73.1 216 37.5 216H24c-13.3 0-24-10.7-24-24s10.7-24 24-24H37.5c35.6 0 53.5-43.1 28.3-68.3l-9.5-9.5c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l9.5 9.5C124.9 91 168 73.1 168 37.5V24c0-13.3 10.7-24 24-24zm48 224a16 16 0 1 0 0-32 16 16 0 1 0 0 32zm-48-64a32 32 0 1 0 -64 0 32 32 0 1 0 64 0zm320 80c0 33 39.9 49.5 63.2 26.2c6.2-6.2 16.4-6.2 22.6 0s6.2 16.4 0 22.6C574.5 312.1 591 352 624 352c8.8 0 16 7.2 16 16s-7.2 16-16 16c-33 0-49.5 39.9-26.2 63.2c6.2 6.2 6.2 16.4 0 22.6s-16.4 6.2-22.6 0C551.9 446.5 512 463 512 496c0 8.8-7.2 16-16 16s-16-7.2-16-16c0-33-39.9-49.5-63.2-26.2c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6C417.5 423.9 401 384 368 384c-8.8 0-16-7.2-16-16s7.2-16 16-16c33 0 49.5-39.9 26.2-63.2c-6.2-6.2-6.2-16.4 0-22.6s16.4-6.2 22.6 0C440.1 289.5 480 273 480 240c0-8.8 7.2-16 16-16s16 7.2 16 16zm0 112a32 32 0 1 0 -64 0 32 32 0 1 0 64 0z" />
                  </svg></i>
                <span class="mjhu">Approve List</span>
                  @if($RAWMATERIAL > 0 )
                  <span class="badge" style="font-weight: bold; background-color: rgb(255, 198, 28); color: black;">{{$RAWMATERIAL}}</span>
                  @endif
              </a>
            </li>
            @endif
          </ul>
        </li>
        @endif
        @if(in_array(5,$Department))
        <li class="under_t luck outside" onclick="myFunctiontt(8)" id="i8">
          <i><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
              <path d="M326.3 218.8c0 20.5-16.7 37.2-37.2 37.2h-70.3v-74.4h70.3c20.5 0 37.2 16.7 37.2 37.2zM504 256c0 137-111 248-248 248S8 393 8 256 119 8 256 8s248 111 248 248zm-128.1-37.2c0-47.9-38.9-86.8-86.8-86.8H169.2v248h49.6v-74.4h70.3c47.9 0 86.8-38.9 86.8-86.8z" />
            </svg></i>
          <span class="mjhu"> Finished Good</span>
          @if((!empty($EXT[5]) && isset($EXT[5]['approver'])) || isset($EXT[5]['Forward']))
            @if($FINISHED_GOOD > 0 )
            <span class="badge" style="font-weight: bold; background-color: rgb(255, 0, 0); color: black;">{{$FINISHED_GOOD}}</span>
            @endif
          @endif
          <ul class="under_drop outsideav" id="myDIVwwep8" style="display: none;">
            <li class="pura" id="pura81">
              <a class="dropdown-item" href="{{url('ProductCategories/ProductList')}}">
                <i><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                    <path d="M176 56V96H336V56c0-4.4-3.6-8-8-8H184c-4.4 0-8 3.6-8 8zM128 96V56c0-30.9 25.1-56 56-56H328c30.9 0 56 25.1 56 56V96v32V480H128V128 96zM64 96H96V480H64c-35.3 0-64-28.7-64-64V160c0-35.3 28.7-64 64-64zM448 480H416V96h32c35.3 0 64 28.7 64 64V416c0 35.3-28.7 64-64 64z" />
                  </svg></i>
                <span class="mjhu">Finished Good List</span>
              </a>
            </li>
            @if((!empty($EXT[5]) && isset($EXT[5]['approver'])) || isset($EXT[5]['Forward']))
            <li class="pura" id="pura82">
              <a class="dropdown-item" href="{{url('ProductCategories/ProductApproveList')}}">
                <i><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                    <path d="M176 56V96H336V56c0-4.4-3.6-8-8-8H184c-4.4 0-8 3.6-8 8zM128 96V56c0-30.9 25.1-56 56-56H328c30.9 0 56 25.1 56 56V96v32V480H128V128 96zM64 96H96V480H64c-35.3 0-64-28.7-64-64V160c0-35.3 28.7-64 64-64zM448 480H416V96h32c35.3 0 64 28.7 64 64V416c0 35.3-28.7 64-64 64z" />
                  </svg></i>
                <span class="mjhu">Approve List</span>
                @if($FINISHED_GOOD > 0 )
                <span class="badge" style="font-weight: bold; background-color: rgb(255, 198, 28); color: black;">{{$FINISHED_GOOD}}</span>
                  @endif
              </a>
            </li>
            @endif
          </ul>
        </li>
        @endif
        @if(in_array(11,$Department))
        <li class="under_t luck outside" onclick="myFunctiontt(18)" id="i18">
          <i><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 384 512">
              <path d="M0 208C0 104.4 75.7 18.5 174.9 2.6C184 1.2 192 8.6 192 17.9V81.2c0 8.4 6.5 15.3 14.7 16.5C307 112.5 384 199 384 303.4c0 103.6-75.7 189.5-174.9 205.4c-9.2 1.5-17.1-5.9-17.1-15.2V430.2c0-8.4-6.5-15.3-14.7-16.5C77 398.9 0 312.4 0 208zm288 48A96 96 0 1 0 96 256a96 96 0 1 0 192 0zm-96-32a32 32 0 1 1 0 64 32 32 0 1 1 0-64z" />
            </svg></i>
          <span class="mjhu">BOM</span>
          @if((!empty($EXT[11]) && isset($EXT[11]['approver'])) || isset($EXT[11]['Forward']))
            @if($BOM > 0 )
            <span class="badge" style="font-weight: bold; background-color: rgb(255, 0, 0); color: black;">{{$BOM}}</span>
            @endif
          @endif
          <ul class="under_drop outsideav" id="myDIVwwep18" style="display: none;">
            <li class="pura" id="pura181">
              <a class="dropdown-item" href="{{url('BOM/BOMList')}}" class="">
                <i><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                    <path d="M488.6 23.4c31.2 31.2 31.2 81.9 0 113.1l-352 352c-31.2 31.2-81.9 31.2-113.1 0s-31.2-81.9 0-113.1l352-352c31.2-31.2 81.9-31.2 113.1 0zM443.3 92.7c-6.2-6.2-16.4-6.2-22.6 0c-12.5 12.5-23.8 15.1-37.5 17.6l-2.5 .4c-13.8 2.5-31.6 5.6-48 22c-16.7 16.7-20.9 36-24.1 50.9l0 0v0l-.2 1c-3.4 15.6-6 26.4-15.7 36.1s-20.5 12.3-36.1 15.7l-1 .2c-14.9 3.2-34.2 7.4-50.9 24.1s-20.9 36-24.1 50.9l-.2 1c-3.4 15.6-6 26.4-15.7 36.1c-9.2 9.2-18 10.8-32.7 13.4l0 0-.9 .2c-15.6 2.8-34.9 6.9-54.4 26.4c-6.2 6.2-6.2 16.4 0 22.6s16.4 6.2 22.6 0c12.5-12.5 23.8-15.1 37.5-17.6l2.5-.4c13.8-2.5 31.6-5.6 48-22c16.7-16.7 20.9-36 24.1-50.9l.2-1c3.4-15.6 6-26.4 15.7-36.1s20.5-12.3 36.1-15.7l1-.2c14.9-3.2 34.2-7.4 50.9-24.1s20.9-36 24.1-50.9l.2-1c3.4-15.6 6-26.4 15.7-36.1c9.2-9.2 18-10.8 32.7-13.4l.9-.2c15.6-2.8 34.9-6.9 54.4-26.4c6.2-6.2 6.2-16.4 0-22.6zM191.2 479.2l288-288L495 207c10.9 10.9 17 25.6 17 41s-6.1 30.1-17 41L289 495c-10.9 10.9-25.6 17-41 17s-30.1-6.1-41-17l-15.8-15.8zM17 305C6.1 294.1 0 279.4 0 264s6.1-30.1 17-41L223 17C233.9 6.1 248.6 0 264 0s30.1 6.1 41 17l15.8 15.8-288 288L17 305z" />
                  </svg></i>
                <span class="mjhu">BOM List</span>
              </a>
            </li>
            @if((!empty($EXT[11]) && isset($EXT[11]['approver'])) || isset($EXT[11]['Forward']))
            <li class="pura" id="pura182">
              <a class="dropdown-item" href="{{url('BOM/BOMApproveList')}}" class="">
                <i><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512">
                    <path d="M252.4 103.8l27 48c2.8 5 8.2 8.2 13.9 8.2l53.3 0c5.8 0 11.1-3.1 13.9-8.2l27-48c2.7-4.9 2.7-10.8 0-15.7l-27-48c-2.8-5-8.2-8.2-13.9-8.2H293.4c-5.8 0-11.1 3.1-13.9 8.2l-27 48c-2.7 4.9-2.7 10.8 0 15.7zM68.3 87C43.1 61.8 0 79.7 0 115.3V432c0 44.2 35.8 80 80 80H396.7c35.6 0 53.5-43.1 28.3-68.3L68.3 87zM504.2 403.6c4.9 2.7 10.8 2.7 15.7 0l48-27c5-2.8 8.2-8.2 8.2-13.9V309.4c0-5.8-3.1-11.1-8.2-13.9l-48-27c-4.9-2.7-10.8-2.7-15.7 0l-48 27c-5 2.8-8.2 8.2-8.2 13.9v53.3c0 5.8 3.1 11.1 8.2 13.9l48 27zM192 64a32 32 0 1 0 -64 0 32 32 0 1 0 64 0zM384 288a32 32 0 1 0 0-64 32 32 0 1 0 0 64z" />
                  </svg></i>
                <span class="mjhu">BOM Approve</span>
                  @if($BOM > 0 )
                  <span class="badge" style="font-weight: bold; background-color: rgb(255, 198, 28); color: black;">{{$BOM}}</span>
                  @endif
              </a>
            </li>
            @endif
          </ul>
        </li>
        @endif
        @if(in_array(7,$Department))
        <li class="under_t luck outside" onclick="myFunctiontt(13)" id="i13">
          <i><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512">
              <path d="M256 32c-17.7 0-32 14.3-32 32v2.3 99.6c0 5.6-4.5 10.1-10.1 10.1c-3.6 0-7-1.9-8.8-5.1L157.1 87C83 123.5 32 199.8 32 288v64H544l0-66.4c-.9-87.2-51.7-162.4-125.1-198.6l-48 83.9c-1.8 3.2-5.2 5.1-8.8 5.1c-5.6 0-10.1-4.5-10.1-10.1V66.3 64c0-17.7-14.3-32-32-32H256zM16.6 384C7.4 384 0 391.4 0 400.6c0 4.7 2 9.2 5.8 11.9C27.5 428.4 111.8 480 288 480s260.5-51.6 282.2-67.5c3.8-2.8 5.8-7.2 5.8-11.9c0-9.2-7.4-16.6-16.6-16.6H16.6z" />
            </svg></i>
          <span class="mjhu">PP Finished Good</span>
          @if((!empty($EXT[7]) && isset($EXT[7]['approver'])) || isset($EXT[7]['Forward']))
            @if($PPFINISHEDGOOD > 0 )
            <span class="badge" style="font-weight: bold; background-color: rgb(255, 0, 0); color: black;">{{$PPFINISHEDGOOD}}</span>
            @endif
          @endif
          <ul class="under_drop outsideav" id="myDIVwwep13" style="display: none;">
            <li class="pura" id="pura131">
              <a class="dropdown-item" href="{{url('PPFinishedGood/PPFinishedGoodList')}}" class="">
                <i><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 640 512">
                    <path d="M314.2 3.3C309.1 12.1 296 36.6 296 56c0 13.3 10.7 24 24 24s24-10.7 24-24c0-19.4-13.1-43.9-18.2-52.7C324.6 1.2 322.4 0 320 0s-4.6 1.2-5.8 3.3zm-288 48C21.1 60.1 8 84.6 8 104c0 13.3 10.7 24 24 24s24-10.7 24-24c0-19.4-13.1-43.9-18.2-52.7C36.6 49.2 34.4 48 32 48s-4.6 1.2-5.8 3.3zM88 104c0 13.3 10.7 24 24 24s24-10.7 24-24c0-19.4-13.1-43.9-18.2-52.7c-1.2-2.1-3.4-3.3-5.8-3.3s-4.6 1.2-5.8 3.3C101.1 60.1 88 84.6 88 104zm82.2-52.7C165.1 60.1 152 84.6 152 104c0 13.3 10.7 24 24 24s24-10.7 24-24c0-19.4-13.1-43.9-18.2-52.7c-1.2-2.1-3.4-3.3-5.8-3.3s-4.6 1.2-5.8 3.3zM216 104c0 13.3 10.7 24 24 24s24-10.7 24-24c0-19.4-13.1-43.9-18.2-52.7c-1.2-2.1-3.4-3.3-5.8-3.3s-4.6 1.2-5.8 3.3C229.1 60.1 216 84.6 216 104zM394.2 51.3C389.1 60.1 376 84.6 376 104c0 13.3 10.7 24 24 24s24-10.7 24-24c0-19.4-13.1-43.9-18.2-52.7c-1.2-2.1-3.4-3.3-5.8-3.3s-4.6 1.2-5.8 3.3zM440 104c0 13.3 10.7 24 24 24s24-10.7 24-24c0-19.4-13.1-43.9-18.2-52.7c-1.2-2.1-3.4-3.3-5.8-3.3s-4.6 1.2-5.8 3.3C453.1 60.1 440 84.6 440 104zm82.2-52.7C517.1 60.1 504 84.6 504 104c0 13.3 10.7 24 24 24s24-10.7 24-24c0-19.4-13.1-43.9-18.2-52.7c-1.2-2.1-3.4-3.3-5.8-3.3s-4.6 1.2-5.8 3.3zM584 104c0 13.3 10.7 24 24 24s24-10.7 24-24c0-19.4-13.1-43.9-18.2-52.7c-1.2-2.1-3.4-3.3-5.8-3.3s-4.6 1.2-5.8 3.3C597.1 60.1 584 84.6 584 104zM112 160c-8.8 0-16 7.2-16 16v96 16h32V272 176c0-8.8-7.2-16-16-16zm64 0c-8.8 0-16 7.2-16 16v96 16h32V272 176c0-8.8-7.2-16-16-16zm64 0c-8.8 0-16 7.2-16 16v96 16h32V272 176c0-8.8-7.2-16-16-16zm160 0c-8.8 0-16 7.2-16 16v96 16h32V272 176c0-8.8-7.2-16-16-16zm64 0c-8.8 0-16 7.2-16 16v96 16h32V272 176c0-8.8-7.2-16-16-16zm64 0c-8.8 0-16 7.2-16 16v96 16h32V272 176c0-8.8-7.2-16-16-16zM352 144c0-17.7-14.3-32-32-32s-32 14.3-32 32V320H96c-17.7 0-32-14.3-32-32V192c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 53 43 96 96 96H288v64H160c-17.7 0-32 14.3-32 32s14.3 32 32 32H320 480c17.7 0 32-14.3 32-32s-14.3-32-32-32H352V384H544c53 0 96-43 96-96V192c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7-14.3 32-32 32H352V144z" />
                  </svg></i>
                <span class="mjhu">PP Finished Good List</span>
              </a>
            </li>
            @if((!empty($EXT[7]) && isset($EXT[7]['approver'])) || isset($EXT[7]['Forward']))
            <li class="pura" id="pura132">
              <a class="dropdown-item" href="{{url('PPFinishedGood/PPFinishedGoodApproveList')}}" class="">
                <i><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512">
                    <path d="M178.7 78.4c0-24.7 5.4-32.4 13.9-39.4-69.5 8.5-149.3 34-176.3 66.4-5.4 7.7-9.3 20.8-9.3 37.1C7 246 113.8 480 191.1 480c36.3 0 97.3-59.5 146.7-139-7 2.3-11.6 2.3-18.5 2.3-57.2 0-140.6-198.5-140.6-264.9zM301.5 32c-30.1 0-41.7 5.4-41.7 36.3 0 66.4 53.8 198.5 101.7 198.5 26.3 0 78.8-99.7 78.8-182.3 0-40.9-67-52.5-138.8-52.5z" />
                  </svg></i>
                <span class="mjhu">Approve List</span>
                 @if($PPFINISHEDGOOD > 0 )
                 <span class="badge" style="font-weight: bold; background-color: rgb(255, 198, 28); color: black;">{{$PPFINISHEDGOOD}}</span>
                  @endif
              </a>
            </li>
            @endif
          </ul>
        </li>
        @endif
        @if(in_array(19,$Department))
        <li class="under_t luck outside" onclick="myFunctiontt(26)" id="i26">
          <i><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 384 512">
              <path d="M42.9 240.32l99.62 48.61c19.2 9.4 16.2 37.51-4.5 42.71L30.5 358.45a22.79 22.79 0 0 1-28.21-19.6 197.16 197.16 0 0 1 9-85.32 22.8 22.8 0 0 1 31.61-13.21zm44 239.25a199.45 199.45 0 0 0 79.42 32.11A22.78 22.78 0 0 0 192.94 490l3.9-110.82c.7-21.3-25.5-31.91-39.81-16.1l-74.21 82.4a22.82 22.82 0 0 0 4.09 34.09zm145.34-109.92l58.81 94a22.93 22.93 0 0 0 34 5.5 198.36 198.36 0 0 0 52.71-67.61A23 23 0 0 0 364.17 370l-105.42-34.26c-20.31-6.5-37.81 15.8-26.51 33.91zm148.33-132.23a197.44 197.44 0 0 0-50.41-69.31 22.85 22.85 0 0 0-34 4.4l-62 91.92c-11.9 17.7 4.7 40.61 25.2 34.71L366 268.63a23 23 0 0 0 14.61-31.21zM62.11 30.18a22.86 22.86 0 0 0-9.9 32l104.12 180.44c11.7 20.2 42.61 11.9 42.61-11.4V22.88a22.67 22.67 0 0 0-24.5-22.8 320.37 320.37 0 0 0-112.33 30.1z" />
            </svg></i>
          <span class="mjhu">Production Process</span>
          @if((!empty($EXT[19]) && isset($EXT[19]['approver'])) || isset($EXT[19]['Forward']))
            @if($PRODUCTIONPROCESS > 0 )
              <span class="badge" style="font-weight: bold; background-color: rgb(255, 0, 0); color: black;">{{$PRODUCTIONPROCESS}}</span>
            @endif
          @endif
          <ul class="under_drop outsideav" id="myDIVwwep26" style="display: none;">
            <li class="pura" id="pura261">
              <a class="dropdown-item" href="{{url('ProductionProcess/ProductionProcessList')}}" class="">
                <i><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 496 512">
                    <path d="M248 8C111 8 0 119 0 256s111 248 248 248 248-111 248-248S385 8 248 8zm52.7 93c8.8-15.2 28.3-20.5 43.5-11.7 15.3 8.8 20.5 28.3 11.7 43.6-8.8 15.2-28.3 20.5-43.5 11.7-15.3-8.9-20.5-28.4-11.7-43.6zM87.4 287.9c-17.6 0-31.9-14.3-31.9-31.9 0-17.6 14.3-31.9 31.9-31.9 17.6 0 31.9 14.3 31.9 31.9 0 17.6-14.3 31.9-31.9 31.9zm28.1 3.1c22.3-17.9 22.4-51.9 0-69.9 8.6-32.8 29.1-60.7 56.5-79.1l23.7 39.6c-51.5 36.3-51.5 112.5 0 148.8L172 370c-27.4-18.3-47.8-46.3-56.5-79zm228.7 131.7c-15.3 8.8-34.7 3.6-43.5-11.7-8.8-15.3-3.6-34.8 11.7-43.6 15.2-8.8 34.7-3.6 43.5 11.7 8.8 15.3 3.6 34.8-11.7 43.6zm.3-69.5c-26.7-10.3-56.1 6.6-60.5 35-5.2 1.4-48.9 14.3-96.7-9.4l22.5-40.3c57 26.5 123.4-11.7 128.9-74.4l46.1.7c-2.3 34.5-17.3 65.5-40.3 88.4zm-5.9-105.3c-5.4-62-71.3-101.2-128.9-74.4l-22.5-40.3c47.9-23.7 91.5-10.8 96.7-9.4 4.4 28.3 33.8 45.3 60.5 35 23.1 22.9 38 53.9 40.2 88.5l-46 .6z" />
                  </svg></i>
                <span class="mjhu">Production Process List</span>
              </a>
            </li>
            @if((!empty($EXT[19]) && isset($EXT[19]['approver'])) || isset($EXT[19]['Forward']))
            <li class="pura" id="pura262">
              <a class="dropdown-item" href="{{url('ProductionProcess/ProductionProcessApproveList')}}" class="">
                <i><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512">
                    <path d="M178.7 78.4c0-24.7 5.4-32.4 13.9-39.4-69.5 8.5-149.3 34-176.3 66.4-5.4 7.7-9.3 20.8-9.3 37.1C7 246 113.8 480 191.1 480c36.3 0 97.3-59.5 146.7-139-7 2.3-11.6 2.3-18.5 2.3-57.2 0-140.6-198.5-140.6-264.9zM301.5 32c-30.1 0-41.7 5.4-41.7 36.3 0 66.4 53.8 198.5 101.7 198.5 26.3 0 78.8-99.7 78.8-182.3 0-40.9-67-52.5-138.8-52.5z" />
                  </svg></i>
                <span class="mjhu">Approve List</span>
                @if($PRODUCTIONPROCESS > 0 )
                  <span class="badge" style="font-weight: bold; background-color: rgb(255, 198, 28); color: black;">{{$PRODUCTIONPROCESS}}</span>
                @endif
              </a>
            </li>
            @endif
          </ul>
        </li>
        @endif
        @if(in_array(18,$Department))
        <li class="under_t luck outside" onclick="myFunctiontt(25)" id="i25">
          <i><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512">
              <path d="M448 80v48c0 44.2-100.3 80-224 80S0 172.2 0 128V80C0 35.8 100.3 0 224 0S448 35.8 448 80zM393.2 214.7c20.8-7.4 39.9-16.9 54.8-28.6V288c0 44.2-100.3 80-224 80S0 332.2 0 288V186.1c14.9 11.8 34 21.2 54.8 28.6C99.7 230.7 159.5 240 224 240s124.3-9.3 169.2-25.3zM0 346.1c14.9 11.8 34 21.2 54.8 28.6C99.7 390.7 159.5 400 224 400s124.3-9.3 169.2-25.3c20.8-7.4 39.9-16.9 54.8-28.6V432c0 44.2-100.3 80-224 80S0 476.2 0 432V346.1z" />
            </svg></i>
          <span class="mjhu">Procurement Request</span>
          @if((!empty($EXT[18]) && isset($EXT[18]['approver'])) || isset($EXT[18]['Forward']))
            @if($PROCUREMENTREQUEST > 0 )
              <span class="badge" style="font-weight: bold; background-color: rgb(255, 0, 0); color: black;">{{$PROCUREMENTREQUEST}}</span>
            @endif
          @endif
          <ul class="under_drop outsideav" id="myDIVwwep25" style="display: none;">
            <li class="pura" id="pura251">
              <a class="dropdown-item" href="{{url('orderRequirement/orderRequirementStockList')}}" class="">
                <i><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 640 512">
                    <path d="M256 64H384v64H256V64zM240 0c-26.5 0-48 21.5-48 48v96c0 26.5 21.5 48 48 48h48v32H32c-17.7 0-32 14.3-32 32s14.3 32 32 32h96v32H80c-26.5 0-48 21.5-48 48v96c0 26.5 21.5 48 48 48H240c26.5 0 48-21.5 48-48V368c0-26.5-21.5-48-48-48H192V288H448v32H400c-26.5 0-48 21.5-48 48v96c0 26.5 21.5 48 48 48H560c26.5 0 48-21.5 48-48V368c0-26.5-21.5-48-48-48H512V288h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H352V192h48c26.5 0 48-21.5 48-48V48c0-26.5-21.5-48-48-48H240zM96 448V384H224v64H96zm320-64H544v64H416V384z" />
                  </svg></i>
                <span class="mjhu">Procurement List</span>
              </a>
            </li>
            @if((!empty($EXT[18]) && isset($EXT[18]['approver'])) || isset($EXT[18]['Forward']))
            <li class="pura" id="pura252">
              <a class="dropdown-item" href="{{url('orderRequirement/orderRequirementApproveList')}}" class="">
                <i><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512">
                    <path d="M178.7 78.4c0-24.7 5.4-32.4 13.9-39.4-69.5 8.5-149.3 34-176.3 66.4-5.4 7.7-9.3 20.8-9.3 37.1C7 246 113.8 480 191.1 480c36.3 0 97.3-59.5 146.7-139-7 2.3-11.6 2.3-18.5 2.3-57.2 0-140.6-198.5-140.6-264.9zM301.5 32c-30.1 0-41.7 5.4-41.7 36.3 0 66.4 53.8 198.5 101.7 198.5 26.3 0 78.8-99.7 78.8-182.3 0-40.9-67-52.5-138.8-52.5z" />
                  </svg></i>
                <span class="mjhu">Approve List</span>
                @if($PROCUREMENTREQUEST > 0 )
                   <span class="badge" style="font-weight: bold; background-color: rgb(255, 198, 28); color: black;">{{$PROCUREMENTREQUEST}}</span>
                @endif
              </a>
            </li>
            @endif
          </ul>
        </li>
        @endif
        @if(in_array(15,$Department))
        <li class="under_t luck outside" onclick="myFunctiontt(22)" id="i22">
          <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
            <path d="M256 0C116.1 0 2 112.7 0 252.1C-2 393.6 112.9 510.8 254.5 511.6c43.7 .3 85.9-10.4 123.3-30.7c3.6-2 4.2-7 1.1-9.7l-24-21.2c-4.9-4.3-11.8-5.5-17.8-3c-26.1 11.1-54.5 16.8-83.7 16.4C139 461.9 46.5 366.8 48.3 252.4C50.1 139.5 142.6 48.2 256 48.2H463.7V417.2L345.9 312.5c-3.8-3.4-9.7-2.7-12.7 1.3c-18.9 25-49.7 40.6-83.9 38.2c-47.5-3.3-85.9-41.5-89.5-88.9c-4.2-56.6 40.6-103.9 96.3-103.9c50.4 0 91.9 38.8 96.2 88c.4 4.4 2.4 8.5 5.7 11.4l30.7 27.2c3.5 3.1 9 1.2 9.9-3.4c2.2-11.8 3-24.2 2.1-36.8c-4.9-72-63.3-130-135.4-134.4c-82.7-5.1-151.8 59.5-154 140.6c-2.1 78.9 62.6 147 141.6 148.7c33 .7 63.6-9.6 88.3-27.6L495 509.4c6.6 5.8 17 1.2 17-7.7V9.7c0-5.4-4.4-9.7-9.7-9.7H256z" />
          </svg>
          <span class="mjhu">Store Requistion</span>
          @if((!empty($EXT[15]) && isset($EXT[15]['approver'])) || isset($EXT[15]['Forward']))
            @if($STOREREQUISITION > 0 )
              <span class="badge" style="font-weight: bold; background-color: rgb(255, 0, 0); color: black;">{{$STOREREQUISITION}}</span>
            @endif
          @endif
          <ul class="under_drop outsideav" id="myDIVwwep22" style="display: none;">
            <li class="pura" id="pura221">
              <a class="dropdown-item" href="{{url('StoreRequistion/StoreRequistionList')}}" class="">
                <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512">
                  <path d="M152.8 37.2c-32.2 38.1-56.1 82.6-69.9 130.5c0 .2-.1 .3-.1 .5C43.5 184.4 16 223 16 268c0 59.6 48.4 108 108 108s108-48.4 108-108c0-53.5-38.9-97.9-90-106.5c15.7-41.8 40.4-79.6 72.3-110.7c1.8-1.6 4-2.6 6.3-3.1c37.2-11.5 76.7-13.3 114.8-5.2C454.7 67.6 534 180.7 517.1 301.3c-8.4 62.6-38.6 112.7-87.7 151.4c-50.1 39.7-107.5 54.3-170.2 52.2l-24-1c12.4 2.8 25 4.9 37.6 6.3c40.7 4.2 81.4 2.1 120.1-12.5c94-35.5 149.3-102.3 162.9-202.5c4.8-52.6-5.8-105.4-30.8-152C454.6 11.3 290.8-38.4 159 32c-2.4 1.4-4.5 3.1-6.3 5.2zM309.4 433.9c-2.1 11.5-4.2 21.9-14.6 31.3c53.2-1 123.2-29.2 161.8-97.1c39.7-69.9 37.6-139.9-6.3-207.8C413.8 105 360.5 77.9 293.7 73.7c1.5 2.3 3.2 4.4 5.2 6.3l5.2 6.3c25.1 31.3 37.6 67.9 42.8 107.5c2.1 15.7-1 30.3-13.6 41.8c-4.2 3.1-5.2 6.3-4.2 10.4l7.3 17.7L365.7 318c5.2 11.5 4.2 19.8-6.3 28.2c-3.2 2.5-6.7 4.6-10.4 6.3l-18.8 8.4 3.1 13.6c3.1 6.3 1 12.5-3.1 17.7c-2.5 2.4-3.8 5.9-3.1 9.4c2.1 11.5-2.1 19.8-12.5 25.1c-2.1 1-4.2 5.2-5.2 7.3zm-133.6-3.1c16.7 11.5 34.5 20.9 53.2 26.1c24 5.2 41.8-6.3 44.9-30.3c1-8.4 5.2-14.6 12.5-17.7c7.3-4.2 8.4-7.3 2.1-13.6l-9.4-8.4 13.6-4.2c6.3-2.1 7.3-5.2 5.2-11.5c-1.4-3-2.4-6.2-3.1-9.4c-3.1-14.6-2.1-15.7 11.5-18.8c8.4-3.1 15.7-6.3 21.9-12.5c3.1-2.1 3.1-4.2 1-8.4l-16.7-30.3c-1-1.9-2.1-3.8-3.1-5.7c-6.4-11.7-13-23.6-15.7-37.1c-2.1-9.4-1-17.7 8.4-24c5.2-4.2 8.4-9.4 8.4-16.7c-.4-10.1-1.5-20.3-3.1-30.3c-6.3-37.6-23-68.9-51.2-95c-5.2-4.2-9.4-6.3-16.7-4.2L203.9 91.5c2 1.2 4 2.4 6 3.6l0 0c6.3 3.7 12.2 7.3 17 12.1c30.3 26.1 41.8 61.6 45.9 100.2c1 8.4 0 16.7-7.3 21.9c-8.4 5.2-10.4 12.5-7.3 20.9c4.9 13.2 10.4 26 16.7 38.6L291.6 318c-6.3 8.4-13.6 11.5-21.9 14.6c-12.5 3.1-14.6 7.3-10.4 20.9c.6 1.5 1.4 2.8 2.1 4.2c2.1 5.2 1 8.4-4.2 10.4l-12.5 3.1 5.2 4.2 4.2 4.2c4.2 5.2 4.2 8.4-2.1 10.4c-7.3 4.2-11.5 9.4-11.5 17.7c0 12.5-7.3 19.8-18.8 24c-3.8 1-7.6 1.5-11.5 1l-34.5-2.1z" />
                </svg>
                <span class="mjhu">Store Requistion List</span>
              </a>
            </li>
            @if((!empty($EXT[15]) && isset($EXT[15]['approver'])) || isset($EXT[15]['Forward']))
            <li class="pura" id="pura222">
              <a class="dropdown-item" href="{{url('StoreRequistion/StoreRequistionApproveList')}}" class="">
                <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 640 512">
                  <path d="M224 109.3V217.6L183.3 242c-14.5 8.7-23.3 24.3-23.3 41.2V512h96V416c0-35.3 28.7-64 64-64s64 28.7 64 64v96h96V283.2c0-16.9-8.8-32.5-23.3-41.2L416 217.6V109.3c0-8.5-3.4-16.6-9.4-22.6L331.3 11.3c-6.2-6.2-16.4-6.2-22.6 0L233.4 86.6c-6 6-9.4 14.1-9.4 22.6zM24.9 330.3C9.5 338.8 0 354.9 0 372.4V464c0 26.5 21.5 48 48 48h80V273.6L24.9 330.3zM592 512c26.5 0 48-21.5 48-48V372.4c0-17.5-9.5-33.6-24.9-42.1L512 273.6V512h80z" />
                </svg>
                <span class="mjhu">Approve List</span>
                @if($STOREREQUISITION > 0 )
                  <span class="badge" style="font-weight: bold; background-color: rgb(255, 198, 28); color: black;">{{$STOREREQUISITION}}</span>
                @endif
              </a>
            </li>
            @endif
          </ul>
        </li>
        @endif
        @if(in_array(16,$Department))
        <li class="under_t luck outside" onclick="myFunctiontt(23)" id="i23">
          <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pass" viewBox="0 0 16 16">
              <path d="M5.5 5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5Zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3Z" />
              <path d="M8 2a2 2 0 0 0 2-2h2.5A1.5 1.5 0 0 1 14 1.5v13a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-13A1.5 1.5 0 0 1 3.5 0H6a2 2 0 0 0 2 2Zm0 1a3.001 3.001 0 0 1-2.83-2H3.5a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-13a.5.5 0 0 0-.5-.5h-1.67A3.001 3.001 0 0 1 8 3Z" />
            </svg></i>
          <span class="mjhu">Store Issue</span>
          @if($total_cout_issue > 0 )
            <span class="badge" style="font-weight: bold; background-color: rgb(255, 0, 0); color: black;">{{$total_cout_issue}}</span>
          @endif
          <ul class="under_drop outsideav" id="myDIVwwep23" style="display: none;">
            @if(empty($EXT[16]) || (!empty($EXT[16]) && isset($EXT[16]['inputer'])))
            <li class="pura" id="pura231">
              <a class="dropdown-item" href="{{url('Storeissue/StoreissueList')}}" class="">
                <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pass" viewBox="0 0 16 16">
                    <path d="M5.5 5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5Zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3Z" />
                    <path d="M8 2a2 2 0 0 0 2-2h2.5A1.5 1.5 0 0 1 14 1.5v13a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-13A1.5 1.5 0 0 1 3.5 0H6a2 2 0 0 0 2 2Zm0 1a3.001 3.001 0 0 1-2.83-2H3.5a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-13a.5.5 0 0 0-.5-.5h-1.67A3.001 3.001 0 0 1 8 3Z" />
                  </svg></i>
                <span class="mjhu">Store Issue List</span>
              </a>
            </li>
            @endif
            @if(isset($storeapprove) && $storeapprove>0)
            <li class="pura" id="pura232">
              <a class="dropdown-item" href="{{url('Storeissue/StoreissueApproveList')}}" class="">
                <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pass" viewBox="0 0 16 16">
                    <path d="M5.5 5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5Zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3Z" />
                    <path d="M8 2a2 2 0 0 0 2-2h2.5A1.5 1.5 0 0 1 14 1.5v13a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-13A1.5 1.5 0 0 1 3.5 0H6a2 2 0 0 0 2 2Zm0 1a3.001 3.001 0 0 1-2.83-2H3.5a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-13a.5.5 0 0 0-.5-.5h-1.67A3.001 3.001 0 0 1 8 3Z" />
                  </svg></i>
                @if($total_cout_issue > 0 )
                  <span class="badge" style="font-weight: bold; background-color: rgb(255, 198, 28); color: black;">{{$total_cout_issue}}</span>
                @endif
                <span class="mjhu">Store Issue Approve List</span>

              </a>
            </li>
            @endif
          </ul>
        </li>
        @endif
        @if(in_array(17,$Department))
        <li class="under_t luck outside" onclick="myFunctiontt(24)" id="i24">
          <i><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
              <path d="M256 0C116.1 0 2 112.7 0 252.1C-2 393.6 112.9 510.8 254.5 511.6c43.7 .3 85.9-10.4 123.3-30.7c3.6-2 4.2-7 1.1-9.7l-24-21.2c-4.9-4.3-11.8-5.5-17.8-3c-26.1 11.1-54.5 16.8-83.7 16.4C139 461.9 46.5 366.8 48.3 252.4C50.1 139.5 142.6 48.2 256 48.2H463.7V417.2L345.9 312.5c-3.8-3.4-9.7-2.7-12.7 1.3c-18.9 25-49.7 40.6-83.9 38.2c-47.5-3.3-85.9-41.5-89.5-88.9c-4.2-56.6 40.6-103.9 96.3-103.9c50.4 0 91.9 38.8 96.2 88c.4 4.4 2.4 8.5 5.7 11.4l30.7 27.2c3.5 3.1 9 1.2 9.9-3.4c2.2-11.8 3-24.2 2.1-36.8c-4.9-72-63.3-130-135.4-134.4c-82.7-5.1-151.8 59.5-154 140.6c-2.1 78.9 62.6 147 141.6 148.7c33 .7 63.6-9.6 88.3-27.6L495 509.4c6.6 5.8 17 1.2 17-7.7V9.7c0-5.4-4.4-9.7-9.7-9.7H256z" />
            </svg></i>
          <span class="mjhu">Production</span>
          @if((!empty($EXT[17]) && isset($EXT[17]['approver'])) || isset($EXT[17]['Forward']))
            @if($PRODUCTIONCOUNT > 0 )
            <span class="badge" style="font-weight: bold; background-color: rgb(255, 0, 0); color: black;">{{$PRODUCTIONCOUNT}}</span>
            @endif
          @endif
          <ul class="under_drop outsideav" id="myDIVwwep24" style="display: none;">
          @if(empty($EXT[17]) || (!empty($EXT[17])))
            <li class="pura" id="pura241">
              <a class="dropdown-item" href="{{url('Production/ProductionList')}}" class="">
                <i><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                    <path d="M0 96C0 60.7 28.7 32 64 32H448c35.3 0 64 28.7 64 64V416c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V96zm64 0v64h64V96H64zm384 0H192v64H448V96zM64 224v64h64V224H64zm384 0H192v64H448V224zM64 352v64h64V352H64zm384 0H192v64H448V352z" />
                  </svg></i>
                <span class="mjhu">Production List</span>
              </a>
            </li>
            @endif
            @if((!empty($EXT[17]) && isset($EXT[17]['approver'])) || isset($EXT[17]['Forward']))
            <li class="pura" id="pura242">
              <a class="dropdown-item" href="{{url('Production/ProductionApproverList')}}" class="">
                <i><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                    <path d="M0 96C0 60.7 28.7 32 64 32H448c35.3 0 64 28.7 64 64V416c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V96zm64 0v64h64V96H64zm384 0H192v64H448V96zM64 224v64h64V224H64zm384 0H192v64H448V224zM64 352v64h64V352H64zm384 0H192v64H448V352z" />
                  </svg></i>
                <span class="mjhu">Production Approve</span>
                @if($PRODUCTIONCOUNT > 0 )
                    <span class="badge" style="font-weight: bold; background-color: rgb(255, 198, 28); color: black;">{{$PRODUCTIONCOUNT}}</span>
                  @endif
              </a>
            </li>
            @endif
          </ul>
        </li>
        @endif
        @if(in_array(9,$Department))
        <li class="under_t luck outside" onclick="myFunctiontt(15)" id="i15">
          <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pass" viewBox="0 0 16 16">
              <path d="M5.5 5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5Zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3Z" />
              <path d="M8 2a2 2 0 0 0 2-2h2.5A1.5 1.5 0 0 1 14 1.5v13a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-13A1.5 1.5 0 0 1 3.5 0H6a2 2 0 0 0 2 2Zm0 1a3.001 3.001 0 0 1-2.83-2H3.5a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-13a.5.5 0 0 0-.5-.5h-1.67A3.001 3.001 0 0 1 8 3Z" />
            </svg></i>
          <span class="mjhu">QC Sample Testing</span>
          @if((!empty($EXT[9]) && isset($EXT[9]['approver'])) || isset($EXT[9]['Forward']))
            @if($QCCOUNT > 0 )
                <span class="badge" style="font-weight: bold; background-color: rgb(255, 0, 0); color: black;">{{$QCCOUNT}}</span>
            @endif
            @endif
          <ul class="under_drop outsideav" id="myDIVwwep15" style="display: none;">
            @if(empty($EXT[9]) || (!empty($EXT[9])))
            <li class="pura" id="pura151">
              <a class="dropdown-item" href="{{url('QCSampleTesting/STDFinishedGoodsList')}}" class="">
                <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pass" viewBox="0 0 16 16">
                    <path d="M5.5 5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5Zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3Z" />
                    <path d="M8 2a2 2 0 0 0 2-2h2.5A1.5 1.5 0 0 1 14 1.5v13a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-13A1.5 1.5 0 0 1 3.5 0H6a2 2 0 0 0 2 2Zm0 1a3.001 3.001 0 0 1-2.83-2H3.5a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-13a.5.5 0 0 0-.5-.5h-1.67A3.001 3.001 0 0 1 8 3Z" />
                  </svg></i>
                <span class="mjhu">QC Finished Goods List</span>
              </a>
            </li>
            @endif
            @if((!empty($EXT[9]) && isset($EXT[9]['approver'])) || isset($EXT[9]['Forward']))
            <li class="pura" id="pura152">
              <a class="dropdown-item" href="{{url('QCSampleTesting/STDFinishedGoodsApproverList')}}" class="">
                <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pass" viewBox="0 0 16 16">
                    <path d="M5.5 5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5Zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3Z" />
                    <path d="M8 2a2 2 0 0 0 2-2h2.5A1.5 1.5 0 0 1 14 1.5v13a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-13A1.5 1.5 0 0 1 3.5 0H6a2 2 0 0 0 2 2Zm0 1a3.001 3.001 0 0 1-2.83-2H3.5a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-13a.5.5 0 0 0-.5-.5h-1.67A3.001 3.001 0 0 1 8 3Z" />
                  </svg></i>
                <span class="mjhu">QC FG Approve List</span>
                  @if($QCCOUNT > 0 )
                    <span class="badge" style="font-weight: bold; background-color: rgb(255, 198, 28); color: black;">{{$QCCOUNT}}</span>
                  @endif
              </a>
            </li>
            @endif
          </ul>
        </li>
        @endif
        @if(in_array(14,$Department))
        <li class="under_t luck outside" onclick="myFunctiontt(21)" id="i21">
          <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pass" viewBox="0 0 16 16">
              <path d="M5.5 5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5Zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3Z" />
              <path d="M8 2a2 2 0 0 0 2-2h2.5A1.5 1.5 0 0 1 14 1.5v13a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-13A1.5 1.5 0 0 1 3.5 0H6a2 2 0 0 0 2 2Zm0 1a3.001 3.001 0 0 1-2.83-2H3.5a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-13a.5.5 0 0 0-.5-.5h-1.67A3.001 3.001 0 0 1 8 3Z" />
            </svg></i>
          <span class="mjhu">Inventory Manage</span>
          @if((!empty($EXT[14]) && isset($EXT[14]['approver'])) || isset($EXT[14]['Forward']))
            @if($INVENTORYCOUNT > 0 )
                <span class="badge" style="font-weight: bold; background-color: rgb(255, 0, 0); color: black;">{{$INVENTORYCOUNT}}</span>
            @endif
           @endif
          <ul class="under_drop outsideav" id="myDIVwwep21" style="display: none;">
            @if(empty($EXT[14]) || (!empty($EXT[14]) ))
            <li class="pura" id="pura211">
              <a class="dropdown-item" href="{{url('InventoryManagement/InventoryManagementList')}}" class="">
                <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pass" viewBox="0 0 16 16">
                    <path d="M5.5 5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5Zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3Z" />
                    <path d="M8 2a2 2 0 0 0 2-2h2.5A1.5 1.5 0 0 1 14 1.5v13a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-13A1.5 1.5 0 0 1 3.5 0H6a2 2 0 0 0 2 2Zm0 1a3.001 3.001 0 0 1-2.83-2H3.5a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-13a.5.5 0 0 0-.5-.5h-1.67A3.001 3.001 0 0 1 8 3Z" />
                  </svg></i>
                <span class="mjhu">Inventory Management</span>
              </a>
            </li>
            @endif
            @if((!empty($EXT[14]) && isset($EXT[14]['approver'])) || isset($EXT[14]['Forward']))
            <li class="pura" id="pura212">
              <a class="dropdown-item" href="{{url('InventoryManagement/InventoryManagementApproverList')}}" class="">
                <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pass" viewBox="0 0 16 16">
                    <path d="M5.5 5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5Zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3Z" />
                    <path d="M8 2a2 2 0 0 0 2-2h2.5A1.5 1.5 0 0 1 14 1.5v13a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-13A1.5 1.5 0 0 1 3.5 0H6a2 2 0 0 0 2 2Zm0 1a3.001 3.001 0 0 1-2.83-2H3.5a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-13a.5.5 0 0 0-.5-.5h-1.67A3.001 3.001 0 0 1 8 3Z" />
                  </svg></i>
                <span class="mjhu">Inventory Approve</span>
                  @if($INVENTORYCOUNT > 0 )
                    <span class="badge" style="font-weight: bold; background-color: rgb(255, 198, 28); color: black;">{{$INVENTORYCOUNT}}</span>
                  @endif
              </a>
            </li>
            @endif
          </ul>
        </li>
        @endif
        @if(in_array(10,$Department))
        <li class="under_t luck outside" onclick="myFunctiontt(16)" id="i16">
          <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pass" viewBox="0 0 16 16">
              <path d="M5.5 5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5Zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3Z" />
              <path d="M8 2a2 2 0 0 0 2-2h2.5A1.5 1.5 0 0 1 14 1.5v13a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-13A1.5 1.5 0 0 1 3.5 0H6a2 2 0 0 0 2 2Zm0 1a3.001 3.001 0 0 1-2.83-2H3.5a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-13a.5.5 0 0 0-.5-.5h-1.67A3.001 3.001 0 0 1 8 3Z" />
            </svg></i>
          <span class="mjhu">Sample & Free Good</span>
            @if($SAMPLEFOODCOUNT > 0 )
              <span class="badge" style="font-weight: bold; background-color: rgb(255, 0, 0); color: black;">{{$SAMPLEFOODCOUNT}}</span>
            @endif
          <ul class="under_drop outsideav" id="myDIVwwep16" style="display: none;">
            @if(empty($EXT[10]) || (!empty($EXT[10]) ))
            <li class="pura" id="pura161">
              <a class="dropdown-item" href="{{url('SampleFreeGood/SampleFreeGoodList')}}" class="">
                <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pass" viewBox="0 0 16 16">
                    <path d="M5.5 5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5Zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3Z" />
                    <path d="M8 2a2 2 0 0 0 2-2h2.5A1.5 1.5 0 0 1 14 1.5v13a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-13A1.5 1.5 0 0 1 3.5 0H6a2 2 0 0 0 2 2Zm0 1a3.001 3.001 0 0 1-2.83-2H3.5a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-13a.5.5 0 0 0-.5-.5h-1.67A3.001 3.001 0 0 1 8 3Z" />
                  </svg></i>
                <span class="mjhu">Sample & Free Good List</span>
              </a>
            </li>
            @endif
            @if((!empty($EXT[10]) && isset($EXT[10]['approver'])) || isset($EXT[10]['Forward']))
            <li class="pura" id="pura162">
              <a class="dropdown-item" href="{{url('SampleFreeGood/SampleFreeGoodApproveList')}}" class="">
                <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pass" viewBox="0 0 16 16">
                    <path d="M5.5 5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5Zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3Z" />
                    <path d="M8 2a2 2 0 0 0 2-2h2.5A1.5 1.5 0 0 1 14 1.5v13a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-13A1.5 1.5 0 0 1 3.5 0H6a2 2 0 0 0 2 2Zm0 1a3.001 3.001 0 0 1-2.83-2H3.5a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-13a.5.5 0 0 0-.5-.5h-1.67A3.001 3.001 0 0 1 8 3Z" />
                  </svg></i>
                <span class="mjhu">Free Good Approver List</span>
                   @if($SAMPLEFOODCOUNT > 0 )
                    <span class="badge" style="font-weight: bold; background-color: rgb(255, 198, 28); color: black;">{{$SAMPLEFOODCOUNT}}</span>
                   @endif
              </a>
            </li>
            @endif
          </ul>
        </li>
        @endif
        @if(in_array(8,$Department))
        <!--<li class="under_t luck outside" onclick="myFunctiontt(14)" id="i14">-->
        <!--  <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pass" viewBox="0 0 16 16">-->
        <!--      <path d="M5.5 5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5Zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3Z" />-->
        <!--      <path d="M8 2a2 2 0 0 0 2-2h2.5A1.5 1.5 0 0 1 14 1.5v13a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-13A1.5 1.5 0 0 1 3.5 0H6a2 2 0 0 0 2 2Zm0 1a3.001 3.001 0 0 1-2.83-2H3.5a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-13a.5.5 0 0 0-.5-.5h-1.67A3.001 3.001 0 0 1 8 3Z" />-->
        <!--    </svg></i>-->
        <!--  <span class="mjhu">Certificate Details</span>-->
        <!--  <ul class="under_drop outsideav" id="myDIVwwep14" style="display: none;">-->
        <!--    @if(empty($EXT[8]) || (!empty($EXT[8]) && isset($EXT[8]['inputer'])))-->
        <!--    <li class="pura" id="pura141">-->
        <!--      <a class="dropdown-item" href="{{url('CertificateDetails/CertificateDetailsList')}}" class="">-->
        <!--        <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pass" viewBox="0 0 16 16">-->
        <!--            <path d="M5.5 5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5Zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3Z" />-->
        <!--            <path d="M8 2a2 2 0 0 0 2-2h2.5A1.5 1.5 0 0 1 14 1.5v13a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-13A1.5 1.5 0 0 1 3.5 0H6a2 2 0 0 0 2 2Zm0 1a3.001 3.001 0 0 1-2.83-2H3.5a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-13a.5.5 0 0 0-.5-.5h-1.67A3.001 3.001 0 0 1 8 3Z" />-->
        <!--          </svg></i>-->
        <!--        <span class="mjhu">Certificate Details List</span>-->
        <!--      </a>-->
        <!--    </li>-->
        <!--    @endif-->
        <!--  </ul>-->
        <!--</li>-->
        <li class="under_t luck outside" onclick="myFunctiontt(14)" id="i14">
              <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pass" viewBox="0 0 16 16">
                  <path d="M5.5 5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5Zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3Z" />
                  <path d="M8 2a2 2 0 0 0 2-2h2.5A1.5 1.5 0 0 1 14 1.5v13a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-13A1.5 1.5 0 0 1 3.5 0H6a2 2 0 0 0 2 2Zm0 1a3.001 3.001 0 0 1-2.83-2H3.5a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-13a.5.5 0 0 0-.5-.5h-1.67A3.001 3.001 0 0 1 8 3Z" />
                </svg></i>
              <span class="mjhu">Serial Number</span>
              @if((!empty($EXT[8]) && isset($EXT[8]['approver'])) || isset($EXT[8]['Forward']))
              @if($SERIALCOUNT > 0 )
              <span class="badge" style="font-weight: bold; background-color: rgb(255, 0, 0); color: black;">{{$SERIALCOUNT}}</span>
              @endif
              @endif
              <ul class="under_drop outsideav" id="myDIVwwep14" style="display: none;">
                @if(empty($EXT[8]) || (!empty($EXT[8]) && isset($EXT[8]['inputer'])))
                <li class="pura" id="pura141">
                  <a class="dropdown-item" href="{{url('SerialNumber/SerialnumberList')}}" class="">
                    <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pass" viewBox="0 0 16 16">
                        <path d="M5.5 5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5Zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3Z" />
                        <path d="M8 2a2 2 0 0 0 2-2h2.5A1.5 1.5 0 0 1 14 1.5v13a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-13A1.5 1.5 0 0 1 3.5 0H6a2 2 0 0 0 2 2Zm0 1a3.001 3.001 0 0 1-2.83-2H3.5a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-13a.5.5 0 0 0-.5-.5h-1.67A3.001 3.001 0 0 1 8 3Z" />
                      </svg></i>
                    <span class="mjhu">Serial No. Issue</span>
                  </a>
                </li>
                @endif
                @if((!empty($EXT[8]) && isset($EXT[8]['approver'])) || isset($EXT[8]['Forward']))
                <li class="pura" id="pura142">
                  <a class="dropdown-item" href="{{url('SerialNumber/SerialApproveList')}}" class="">
                    <i><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                        <path d="M0 96C0 60.7 28.7 32 64 32H448c35.3 0 64 28.7 64 64V416c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V96zm64 0v64h64V96H64zm384 0H192v64H448V96zM64 224v64h64V224H64zm384 0H192v64H448V224zM64 352v64h64V352H64zm384 0H192v64H448V352z" />
                      </svg></i>
                    <span class="mjhu">Serial Number Approve</span>
                    @if($SERIALCOUNT > 0 )
                    <span class="badge" style="font-weight: bold; background-color: rgb(255, 198, 28); color: black;">{{$SERIALCOUNT}}</span>
                   @endif
                   
                  </a>
                </li>
                @endif
              </ul>
            </li>
        @endif

        @if(in_array(12,$Department))
        <li class="under_t luck outside" onclick="myFunctiontt(19)" id="i19">
          <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pass" viewBox="0 0 16 16">
              <path d="M5.5 5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5Zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3Z" />
              <path d="M8 2a2 2 0 0 0 2-2h2.5A1.5 1.5 0 0 1 14 1.5v13a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-13A1.5 1.5 0 0 1 3.5 0H6a2 2 0 0 0 2 2Zm0 1a3.001 3.001 0 0 1-2.83-2H3.5a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-13a.5.5 0 0 0-.5-.5h-1.67A3.001 3.001 0 0 1 8 3Z" />
            </svg>
          </i>
          <span class="mjhu">Maintenance</span>
          <ul class="under_drop outsideav" id="myDIVwwep19" style="display: none;">
            @if(empty($EXT[12]) || (!empty($EXT[12]) && isset($EXT[12]['inputer'])))
            <li class="pura" id="pura191">
              <a class="dropdown-item" href="{{url('Maintenance/AssignList')}}" class="">
                <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pass" viewBox="0 0 16 16">
                    <path d="M5.5 5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5Zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3Z" />
                    <path d="M8 2a2 2 0 0 0 2-2h2.5A1.5 1.5 0 0 1 14 1.5v13a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-13A1.5 1.5 0 0 1 3.5 0H6a2 2 0 0 0 2 2Zm0 1a3.001 3.001 0 0 1-2.83-2H3.5a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-13a.5.5 0 0 0-.5-.5h-1.67A3.001 3.001 0 0 1 8 3Z" />
                  </svg></i>
                <span class="mjhu">Assign</span>
              </a>
            </li>
            <li class="pura" id="pura192">
              <a class="dropdown-item" href="{{url('Maintenance/MaintenanceList')}}" class="">
                <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pass" viewBox="0 0 16 16">
                    <path d="M5.5 5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5Zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3Z" />
                    <path d="M8 2a2 2 0 0 0 2-2h2.5A1.5 1.5 0 0 1 14 1.5v13a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-13A1.5 1.5 0 0 1 3.5 0H6a2 2 0 0 0 2 2Zm0 1a3.001 3.001 0 0 1-2.83-2H3.5a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-13a.5.5 0 0 0-.5-.5h-1.67A3.001 3.001 0 0 1 8 3Z" />
                  </svg></i>
                <span class="mjhu">Preventive Maintenance</span>
              </a>
            </li>
            <li class="pura" id="pura193">
              <a class="dropdown-item" href="{{url('Maintenance/MachineShutdownDetailsList')}}" class="">
                <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pass" viewBox="0 0 16 16">
                    <path d="M5.5 5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5Zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3Z" />
                    <path d="M8 2a2 2 0 0 0 2-2h2.5A1.5 1.5 0 0 1 14 1.5v13a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-13A1.5 1.5 0 0 1 3.5 0H6a2 2 0 0 0 2 2Zm0 1a3.001 3.001 0 0 1-2.83-2H3.5a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-13a.5.5 0 0 0-.5-.5h-1.67A3.001 3.001 0 0 1 8 3Z" />
                  </svg></i>
                <span class="mjhu">Machine Shutdown Details</span>
              </a>
            </li>
            <li class="pura" id="pura194">
              <a class="dropdown-item" href="{{url('Maintenance/BreakdownList')}}" class="">
                <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pass" viewBox="0 0 16 16">
                    <path d="M5.5 5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5Zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3Z" />
                    <path d="M8 2a2 2 0 0 0 2-2h2.5A1.5 1.5 0 0 1 14 1.5v13a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-13A1.5 1.5 0 0 1 3.5 0H6a2 2 0 0 0 2 2Zm0 1a3.001 3.001 0 0 1-2.83-2H3.5a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-13a.5.5 0 0 0-.5-.5h-1.67A3.001 3.001 0 0 1 8 3Z" />
                  </svg></i>
                <span class="mjhu">Breakdown Maintenanace Details</span>
              </a>
            </li>
            @endif
          </ul>
        </li>
        @endif
        @if(in_array(22,$Department))
        <li class="under_t luck outside" onclick="myFunctiontt(28)" id="i28">
          <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pass" viewBox="0 0 16 16">
              <path d="M5.5 5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5Zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3Z" />
              <path d="M8 2a2 2 0 0 0 2-2h2.5A1.5 1.5 0 0 1 14 1.5v13a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-13A1.5 1.5 0 0 1 3.5 0H6a2 2 0 0 0 2 2Zm0 1a3.001 3.001 0 0 1-2.83-2H3.5a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-13a.5.5 0 0 0-.5-.5h-1.67A3.001 3.001 0 0 1 8 3Z" />
            </svg></i>
          <span class="mjhu">Manual FG</span>
          @if((!empty($EXT[22]) && isset($EXT[22]['approver'])) || isset($EXT[22]['Forward']))
            @if($MANUALFINISHEDGOODCOUNT > 0 )
            <span class="badge" style="font-weight: bold; background-color: rgb(255, 0, 0); color: black;">{{$MANUALFINISHEDGOODCOUNT}}</span>
            @endif
          @endif
          <ul class="under_drop outsideav" id="myDIVwwep28" style="display: none;">
           
            <li class="pura" id="pura281">
              
              <a class="dropdown-item" href="{{url('FinishedGood/Finished_Good_List')}}" class="">
                <i>
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pass" viewBox="0 0 16 16">
                    <path d="M5.5 5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5Zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3Z" />
                    <path d="M8 2a2 2 0 0 0 2-2h2.5A1.5 1.5 0 0 1 14 1.5v13a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-13A1.5 1.5 0 0 1 3.5 0H6a2 2 0 0 0 2 2Zm0 1a3.001 3.001 0 0 1-2.83-2H3.5a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-13a.5.5 0 0 0-.5-.5h-1.67A3.001 3.001 0 0 1 8 3Z" />
                  </svg>
                </i>
                <span class="mjhu">Manual FG List</span>
              </a>
            </li>

            @if((!empty($EXT[22]) && isset($EXT[22]['approver'])) || isset($EXT[22]['Forward']))
            <li class="pura" id="pura282">
              <a class="dropdown-item" href="{{url('FinishedGood/Finished_Good_Approver_List')}}" class="">
                <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pass" viewBox="0 0 16 16">
                    <path d="M5.5 5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5Zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3Z" />
                    <path d="M8 2a2 2 0 0 0 2-2h2.5A1.5 1.5 0 0 1 14 1.5v13a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-13A1.5 1.5 0 0 1 3.5 0H6a2 2 0 0 0 2 2Zm0 1a3.001 3.001 0 0 1-2.83-2H3.5a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-13a.5.5 0 0 0-.5-.5h-1.67A3.001 3.001 0 0 1 8 3Z" />
                  </svg></i>
                <span class="mjhu">Manual FG Approve</span>
                @if($MANUALFINISHEDGOODCOUNT > 0 )
                <span class="badge" style="font-weight: bold; background-color: rgb(255, 198, 28); color: black;">{{$MANUALFINISHEDGOODCOUNT}}</span>
               @endif
              </a>
            </li>
            @endif
          </ul>
        </li>
        @endif
        @if(in_array(23,$Department))
        <li class="under_t luck outside" onclick="myFunctiontt(29)" id="i29">
          <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pass" viewBox="0 0 16 16">
              <path d="M5.5 5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5Zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3Z" />
              <path d="M8 2a2 2 0 0 0 2-2h2.5A1.5 1.5 0 0 1 14 1.5v13a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-13A1.5 1.5 0 0 1 3.5 0H6a2 2 0 0 0 2 2Zm0 1a3.001 3.001 0 0 1-2.83-2H3.5a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-13a.5.5 0 0 0-.5-.5h-1.67A3.001 3.001 0 0 1 8 3Z" />
            </svg></i>
          <span class="mjhu">Stock Transfer</span>

          @if((!empty($EXT[23]) && isset($EXT[23]['approver'])) || isset($EXT[23]['Forward']))
            @if($MRNSTOCKTRANSFERCOUNT > 0 )
              <span class="badge" style="font-weight: bold; background-color: rgb(255, 0, 0); color: black;">{{$MRNSTOCKTRANSFERCOUNT}}</span>
            @endif
          @endif

          <ul class="under_drop outsideav" id="myDIVwwep29" style="display: none;">
            @if((!empty($EXT[23]) && isset($EXT[23]['inputer'])))
            <li class="pura" id="pura291">
              <a class="dropdown-item" href="{{ url('StockTransfer/TransferRequestList') }}">
                <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pass" viewBox="0 0 16 16">
                    <path d="M5.5 5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5Zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3Z" />
                    <path d="M8 2a2 2 0 0 0 2-2h2.5A1.5 1.5 0 0 1 14 1.5v13a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-13A1.5 1.5 0 0 1 3.5 0H6a2 2 0 0 0 2 2Zm0 1a3.001 3.001 0 0 1-2.83-2H3.5a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-13a.5.5 0 0 0-.5-.5h-1.67A3.001 3.001 0 0 1 8 3Z" />
                  </svg></i>
                <span class="mjhu">Request Transfer</span>
              </a>
            </li>
            @endif

            @if((!empty($EXT[23]) && isset($EXT[23]['approver'])) || isset($EXT[23]['Forward']))
            <li class="pura" id="pura292">
              <a class="dropdown-item" href="{{ url('StockTransfer/ApprovalList') }}">
                <i class="mr-0"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pass" viewBox="0 0 16 16">
                    <path d="M5.5 5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5Zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3Z" />
                    <path d="M8 2a2 2 0 0 0 2-2h2.5A1.5 1.5 0 0 1 14 1.5v13a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-13A1.5 1.5 0 0 1 3.5 0H6a2 2 0 0 0 2 2Zm0 1a3.001 3.001 0 0 1-2.83-2H3.5a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-13a.5.5 0 0 0-.5-.5h-1.67A3.001 3.001 0 0 1 8 3Z" />
                  </svg></i>
                <span class="mjhu">Approve Transfers</span>
                @if($MRNSTOCKTRANSFERCOUNT > 0 )
                <span class="badge" style="font-weight: bold; background-color: rgb(255, 198, 28); color: black;">{{$MRNSTOCKTRANSFERCOUNT}}</span>
                @endif
              </a>
            </li>
            @endif
          </ul>
        </li>
        @endif
        @if(in_array(13,$Department))
             <li class="under_t luck outside" onclick="myFunctiontt(20)" id="i20">
              <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pass" viewBox="0 0 16 16">
                  <path d="M5.5 5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5Zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3Z" />
                  <path d="M8 2a2 2 0 0 0 2-2h2.5A1.5 1.5 0 0 1 14 1.5v13a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-13A1.5 1.5 0 0 1 3.5 0H6a2 2 0 0 0 2 2Zm0 1a3.001 3.001 0 0 1-2.83-2H3.5a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-13a.5.5 0 0 0-.5-.5h-1.67A3.001 3.001 0 0 1 8 3Z" />
                </svg></i>
              <span class="mjhu">Reports</span>
              <ul class="under_drop outsideav" id="myDIVwwep20" style="display: none;">
                @if(empty($EXT[13]) || (!empty($EXT[13]) && isset($EXT[13]['inputer'])))
                <li class="pura" id="pura201">
                  <a class="dropdown-item" href="{{url('Report/storestockreport')}}" class="">
                    <i>
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pass" viewBox="0 0 16 16">
                        <path d="M5.5 5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5Zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3Z" />
                        <path d="M8 2a2 2 0 0 0 2-2h2.5A1.5 1.5 0 0 1 14 1.5v13a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-13A1.5 1.5 0 0 1 3.5 0H6a2 2 0 0 0 2 2Zm0 1a3.001 3.001 0 0 1-2.83-2H3.5a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-13a.5.5 0 0 0-.5-.5h-1.67A3.001 3.001 0 0 1 8 3Z" />
                      </svg>
                    </i>
                    <span class="mjhu">Store Stock Report</span>
                  </a>
                </li>
                <li class="pura" id="pura202">
                  <a class="dropdown-item" href="{{url('Report/plantstockreport')}}" class="">
                    <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pass" viewBox="0 0 16 16">
                        <path d="M5.5 5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5Zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3Z" />
                        <path d="M8 2a2 2 0 0 0 2-2h2.5A1.5 1.5 0 0 1 14 1.5v13a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-13A1.5 1.5 0 0 1 3.5 0H6a2 2 0 0 0 2 2Zm0 1a3.001 3.001 0 0 1-2.83-2H3.5a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-13a.5.5 0 0 0-.5-.5h-1.67A3.001 3.001 0 0 1 8 3Z" />
                      </svg></i>
                    <span class="mjhu">Plant Stock Report</span>
                  </a>
                </li>
                 <li class="pura" id="pura203">
                  <a class="dropdown-item" href="{{URL('Report/material-stock-report')}}" class="">
                    <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pass" viewBox="0 0 16 16">
                        <path d="M5.5 5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5Zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3Z" />
                        <path d="M8 2a2 2 0 0 0 2-2h2.5A1.5 1.5 0 0 1 14 1.5v13a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-13A1.5 1.5 0 0 1 3.5 0H6a2 2 0 0 0 2 2Zm0 1a3.001 3.001 0 0 1-2.83-2H3.5a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-13a.5.5 0 0 0-.5-.5h-1.67A3.001 3.001 0 0 1 8 3Z" />
                      </svg></i>
                    <span class="mjhu">Finished Good Stock Report</span>
                  </a>
                </li>
                <li class="pura" id="pura204">
                  <a class="dropdown-item" href="{{URL('Report/sl_no_avlbl-report')}}" class="">
                    <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pass" viewBox="0 0 16 16">
                        <path d="M5.5 5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5Zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3Z" />
                        <path d="M8 2a2 2 0 0 0 2-2h2.5A1.5 1.5 0 0 1 14 1.5v13a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-13A1.5 1.5 0 0 1 3.5 0H6a2 2 0 0 0 2 2Zm0 1a3.001 3.001 0 0 1-2.83-2H3.5a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-13a.5.5 0 0 0-.5-.5h-1.67A3.001 3.001 0 0 1 8 3Z" />
                      </svg></i>
                    <span class="mjhu">Sl. No. Available Report</span>
                </a>
             </li>
             <li class="pura" id="pura205">
              <a class="dropdown-item" href="{{URL('Report/wip_sl_no_avlbl-report')}}" class="">
                <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pass" viewBox="0 0 16 16">
                    <path d="M5.5 5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5Zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3Z" />
                    <path d="M8 2a2 2 0 0 0 2-2h2.5A1.5 1.5 0 0 1 14 1.5v13a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-13A1.5 1.5 0 0 1 3.5 0H6a2 2 0 0 0 2 2Zm0 1a3.001 3.001 0 0 1-2.83-2H3.5a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-13a.5.5 0 0 0-.5-.5h-1.67A3.001 3.001 0 0 1 8 3Z" />
                  </svg></i>
                <span class="mjhu">WIP Sl. No. Report</span>
              </a>
            </li>
                <li class="pura" id="pura206">
                  <a class="dropdown-item" href="{{URL('Report/dis_sl_no_avlbl-report')}}" class="">
                    <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pass" viewBox="0 0 16 16">
                        <path d="M5.5 5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5Zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3Z" />
                        <path d="M8 2a2 2 0 0 0 2-2h2.5A1.5 1.5 0 0 1 14 1.5v13a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-13A1.5 1.5 0 0 1 3.5 0H6a2 2 0 0 0 2 2Zm0 1a3.001 3.001 0 0 1-2.83-2H3.5a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-13a.5.5 0 0 0-.5-.5h-1.67A3.001 3.001 0 0 1 8 3Z" />
                      </svg></i>
                    <span class="mjhu">Dispatch Sl. No. Report</span>
                  </a>
                </li>
                @endif
                
                @if((!empty($EXT[13]) && isset($EXT[13]['approver'])) || isset($EXT[13]['Forward']))
                <li class="pura" id="pura203">
                  <a class="dropdown-item" href="{{url('Report/plantstockreport')}}" class="">
                    <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pass" viewBox="0 0 16 16">
                        <path d="M5.5 5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5Zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3Z" />
                        <path d="M8 2a2 2 0 0 0 2-2h2.5A1.5 1.5 0 0 1 14 1.5v13a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-13A1.5 1.5 0 0 1 3.5 0H6a2 2 0 0 0 2 2Zm0 1a3.001 3.001 0 0 1-2.83-2H3.5a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-13a.5.5 0 0 0-.5-.5h-1.67A3.001 3.001 0 0 1 8 3Z" />
                      </svg></i>
                    <span class="mjhu">Third Party Approve List</span>
                  </a>
                </li>
                @endif
              </ul>
            </li>
        @endif
        
        
        <li class="under_t  luck" id="javab">
          <a href="{{route('admin.logout')}}">
            <i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z" />
                <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z" />
              </svg></i>
            <span class="mjhu"> Logout</span>
          </a>
        </li>
      </ul>
    </div>