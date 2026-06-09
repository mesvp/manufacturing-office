<?php

namespace App\Http\Controllers\Storeissue;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Storeissue\{Store_issue};

class StoreissueController extends Controller
{
    public function AddStoreissue(Request $request)
    {
        $required = [
            'Request_No' => 'required',
            'Request_by' => 'required',
            'date' => 'required',
            'Work_Order_No' => 'required',
        ];

        $request->validate($required);

        if ($request->edit != '') {
            $issue = Store_issue::find($request->edit);
        } else {
            $issue = new Store_issue;
            $issue->userID = auth()->user()->id;
        }
        $issue->Request_No = $request->Request_No;
        $issue->Request_by = $request->Request_by;
        $issue->date = $request->date;
        $issue->Work_Order_No = $request->Work_Order_No;
        $issue->remarks = $request->remarks;

        $issue->save();

        $issue->Issued_No = 'SIN' . str_pad($issue->id, 4, '0', STR_PAD_LEFT);

        $issue->save();

        return redirect('Storeissue/StoreissueList')->with('success', 'Added Successfully....');
    }
}
