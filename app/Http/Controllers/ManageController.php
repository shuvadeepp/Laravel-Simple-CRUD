<?php

namespace App\Http\Controllers;
use DB;
use Validator;
use Illuminate\Http\Request;
use App\Models\UserDetailsModel;
use App\Models\State;
use App\Models\City;

class ManageController extends Controller
{
    public function index($userID){
        
        $this->viewVars = []; 
        $state_query = DB::table('m_states')
            ->select('state_id','states_name')
            ->get();
            // print_r($state_query);exit;
        $this->viewVars['arrAllRecords'] = $state_query;
        
        if(!empty(request()->all()) && request()->isMethod('post')) {
            $requestData = request()->all(); 
            // print_R($requestData);exit;
            $validator   = \Validator::make($requestData, 
            [
                'username'      => 'bail|required',
                'email'         => 'bail|required',
                'address'       => 'bail|required',
                'State'         => 'bail|required',
                'city'          => 'bail|required',
                'zip'           => 'bail|required',
                'intGender'     => 'bail|required'
            ],
            [
                'username'      => 'Please enter your User Name',
                'email'         => 'Please enter your Email ID',
                'address'       => 'Please enter your Address',
                'State'         => 'Please Select State',
                'city'          => 'Please Select City',
                'zip'           => 'Please Select zip Code',
                'intGender'     => 'Please select your Gender'
            ]);
            if($validator->fails()) {
                return redirect('add-page')->withErrors($validator)->withInput();
            } else {
                if($userID > 0){
                    $chkDup = UserDetailsModel::where([['vchUsername', $requestData['username']], ['vchEmail_ID', $requestData['email']], ['deletedFlag', '=', 0]])->first();
                    if($chkDup){
                        request()->session()->flash('error', 'Duplicate record exist');
                    } else {
                        UserDetailsModel::where('Id', $userID)->update([
                        'vchUsername'   => $requestData['username'],
                        'vchEmail_ID'   => $requestData['email'],
                        'vchAddress'    => $requestData['address'],
                        'intState'      => $requestData['State'],
                        'intCity'       => $requestData['city'],
                        'intZip'        => $requestData['zip'],
                        'intGender'     => $requestData['intGender'],
                        'created_at'    => now(),
                        'updated_at'    => now()
                    ]);
                        request()->session()->flash('success', 'Record updated successfully');
                    }
                } else {
                    $chkDup = UserDetailsModel::where([['vchUsername', $requestData['username']], ['vchEmail_ID', $requestData['email']], ['deletedFlag','=',0]])->first();
                    if($chkDup){
                        request()->session()->flash('error', 'Duplicate record exist');
                    } else {
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
            ->select('UD.vchUsername', 'UD.vchEmail_ID', 'UD.vchAddress', 'UD.intZip', 'UD.intGender', 'UD.created_at', 'MS.states_name', 'MC.city_name')
            ->leftJoin('m_states AS MS', 'UD.intState', '=', 'MS.state_id')
            ->leftJoin('m_citys AS MC', 'UD.intCity', '=', 'MC.city_id')
            ->where('UD.DeletedFlag', 0)
            ->orderBy('Id', 'DESC')
            ->get();
        
        // echo '<pre>';
        // print_r(json_decode(json_encode($select_query), true));
        // exit;
    
        $this->viewVars['arrAllRecords'] = json_decode(json_encode($select_query), true);
        
        return view('view-page', $this->viewVars);
    }
    
}
