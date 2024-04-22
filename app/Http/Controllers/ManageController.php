<?php

namespace App\Http\Controllers;
use DB;
use Validator;
use Illuminate\Http\Request;
use App\Models\UserDetailsModel;
use App\Models\State;
use App\Models\City;
use Illuminate\Support\Facades\Storage;

class ManageController extends Controller
{
    public function index($strEnc){
        $this->viewVars = [];
        $userId = ($strEnc) ? json_decode(decrypt($strEnc),true) : 0;
        $this->viewVars['buttonVal'] = ($userId > 0)?' Update ':' Submit ';
        
        $redirectUrl     = ($userId)?'/Manage/edit/'.encrypt($userId):'/Manage/';

        $getDataObj = UserDetailsModel::find($userId);
        $this->viewVars['getDataObj'] = json_decode(json_encode($getDataObj), true);
        //  echo'<pre>';print_r($this->viewVars['getDataObj']);exit;

         
        $state_query = DB::table('m_states')
            ->select('state_id','states_name')
            ->get();
            // print_r($state_query);exit;
        $this->viewVars['arrAllRecords'] = $state_query;
        $url = url('/personalAssessment/crud_pratice_19042024/manage/view');
        if(!empty(request()->all()) && request()->isMethod('post')) {
            $requestData = request()->all(); 
            // echo'<pre>';print_R($requestData);exit;
            $validator   = \Validator::make($requestData, 
            [
                'username'      => 'bail|required',
                'email'         => 'bail|required',
                'address'       => 'bail|required',
                'State'         => 'bail|required',
                'city'          => 'bail|required',
                'zip'           => 'bail|required',
                // 'doc'           => 'bail|required',
                // 'doc'           => 'image|mimes:jpg,png,jpeg|max:1024|required_without:hdndoc', 
            ],
            [
                'username'      => 'Please enter your User Name',
                'email'         => 'Please enter your Email ID',
                'address'       => 'Please enter your Address',
                'State'         => 'Please Select State',
                'city'          => 'Please Select City',
                'zip'           => 'Please Select zip Code',
                // 'doc.required_without' => 'Please Upload Document', 
                // 'doc.mimes'     => 'Document should be jpg,png,jpeg', 
                // 'doc.max'       => 'Document should not be more than 1 mb', 
            ]);
            if($validator->fails()) {
                return redirect($redirectUrl)->withErrors($validator)->withInput();
            } else {
                // echo 111;exit;
                if($userId > 0){
                    // echo $userId;exit;
                    $chkDup = UserDetailsModel::where([['vchUsername', $requestData['username']], ['vchEmail_ID', $requestData['email']], ['deletedFlag', '=', 0]])->first();
                    if($chkDup){
                        request()->session()->flash('error', 'Duplicate record exist');
                    } else {
                        $doc = request()->file('doc');
                        // echo'<pre>';print_r($doc);exit;
                        $docStores = 'doc_' . time() . '.' . $doc->getClientOriginalExtension();
                        $destinationPath = "public/Documents";
                        $res = request()->file('doc')->move($destinationPath . '/', $docStores);  

                        UserDetailsModel::where('Id', $userId)->update([
                        'vchUsername'   => $requestData['username'],
                        'vchEmail_ID'   => $requestData['email'],
                        'vchAddress'    => $requestData['address'],
                        'intState'      => $requestData['State'],
                        'intCity'       => $requestData['city'],
                        'intZip'        => $requestData['zip'],
                        'intGender'     => $requestData['intGender'],
                        'vchDocument'   => $docStores,
                        'created_at'    => now(),
                        'updated_at'    => now()
                    ]);
                        request()->session()->flash('success', 'Record updated successfully');
                        return redirect($url);
                    }
                } else {
                    $chkDup = UserDetailsModel::where([['vchUsername', $requestData['username']], ['vchEmail_ID', $requestData['email']], ['deletedFlag','=',0]])->first();
                    if($chkDup){
                        request()->session()->flash('error', 'Duplicate record exist');
                    } else {
                        // $newFlName = "";
                        // echo '<pre>';print_r($requestData);exit;
                        $userDetailsModel = new UserDetailsModel();

                        $userDetailsModel->vchUsername      = $requestData['username'];
                        $userDetailsModel->vchEmail_ID      = $requestData['email'];
                        $userDetailsModel->vchAddress       = $requestData['address'];
                        $userDetailsModel->intState         = $requestData['State'];
                        $userDetailsModel->intCity          = $requestData['city'];
                        $userDetailsModel->intZip           = $requestData['zip'];
                        $userDetailsModel->intGender        = $requestData['intGender'];
                        $userDetailsModel->created_at        = now();
                        $userDetailsModel->updated_at       = now();  

                        $doc = request()->file('doc');
                        // echo'<pre>';print_r($doc);exit;
                        $docStores = 'doc_' . time() . '.' . $doc->getClientOriginalExtension();
                        $destinationPath = "public/Documents";
                        $res = request()->file('doc')->move($destinationPath . '/', $docStores); 
                        $userDetailsModel->vchDocument = $docStores; 

                        $userDetailsModel->save();
                        request()->session()->flash('success', 'Record Added Successfully');
                    }
                }
            }
        }
        
        return view('add-page', $this->viewVars);
    }

    public function cityData(){ 
        $requestData = request()->all();
        // print_r($requestData);exit;
        $stateData = $requestData['stateData'];
        $stateType = $requestData['stateType'];
        $city_query = DB::table('m_citys')->where('state_id', $stateData)->get();
        // print('<pre>');print_r($city_query);exit;
        $htm = '<option value=""> --Choose-- </option>';
        foreach ($city_query as $city_list) {
            $select = ($stateType === $city_list->city_id) ? 'selected' : '';
            $htm .= '<option value="' . $city_list->city_id . '"' . $select . '>' . $city_list->city_name . '</option>';
        }
        if(!empty($htm)){
            return json_encode(array('status' => 200, 'city_resp' => $htm));
        } 
    }

    public function view(){
        $select_query = DB::table('t_user_details AS UD')
            ->select('UD.Id', 'UD.vchUsername', 'UD.vchEmail_ID', 'UD.vchAddress', 'UD.intZip', 'UD.intGender', 'UD.created_at', 'MS.states_name', 'MC.city_name', 'UD.vchDocument')
            ->leftJoin('m_states AS MS', 'UD.intState', '=', 'MS.state_id')
            ->leftJoin('m_citys AS MC', 'UD.intCity', '=', 'MC.city_id')
            ->where('UD.DeletedFlag', 0)
            ->orderByDesc('UD.Id')
            // ->get();
            ->paginate(10);  

        $this->viewVars['arrAllRecords'] = $select_query;
        
        return view('view-page', $this->viewVars); 
    }
    
}
