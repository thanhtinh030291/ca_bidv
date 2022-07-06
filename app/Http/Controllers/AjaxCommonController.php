<?php

namespace App\Http\Controllers;
use App\HBS_CL_CLAIM;
use App\HBS_MR_POLICY_PLAN;
use App\HBS_PV_PROVIDER;
use App\HBS_RT_DIAGNOSIS;
use App\HBS_MR_MEMBER;
use App\HBS_CL_LINE;
use App\ExportLetter;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Claim;
use App\PaymentHistory;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;

class AjaxCommonController extends Controller
{
    
    //ajax load ID claim auto complate 
    public function dataAjaxHBSClaim(Request $request)
    {
        $data = [];
        if($request->has('q')){
            $search = $request->q;
            $datas = HBS_CL_CLAIM::where('cl_no','LIKE',"%$search%")
                    ->select('clam_oid as id', 'cl_no as text')
                    ->limit(20)->get();
            return response()->json($datas);
        }
        return response()->json($data);
    }

    public function dataAjaxHBSGOPClaim(Request $request)
    {
        $data = [];
        $conditionGOP = function($q) {
            $q->where('SCMA_OID_CL_TYPE', 'CL_TYPE_P');
        };
        if($request->has('q')){
            $search = $request->q;
            $datas = HBS_CL_CLAIM::where('cl_no','LIKE',"%$search%")
                    ->whereHas('HBS_CL_LINE' ,$conditionGOP)
                    ->select('clam_oid as id', 'cl_no as text')
                    ->limit(20)->get();
            return response()->json($datas);
        }
        return response()->json($data);
    }

    public function dataAjaxHBSDiagnosis(Request $request)
    {
        $data = [];
        if($request->has('q')){
            $search = mb_strtolower($request->q);
            $datas = HBS_RT_DIAGNOSIS::where('diag_desc_vn','LIKE',"%$search%")->orWhere('diag_code','LIKE',"%$search%")
                    ->select(DB::raw("diag_oid  as id, diag_code ||'-'|| diag_desc_vn as text"))
                    ->limit(100)->get();
            
            
            return response()->json($datas);
        }
        return response()->json($data);
    }

    public function dataAjaxHBSProvByClaim($claim_oid){
        $data = HBS_CL_CLAIM::findOrFail($claim_oid)->provider;
        return response()->json($data);
    }

    //ajax load provider
    public function dataAjaxHBSProv(Request $request)
    {
        $data = [];
        if($request->has('q')){
            $search = mb_strtoupper($request->q);
            $datas = HBS_PV_PROVIDER::where('prov_name','LIKE',"%$search%")
                    ->select('prov_oid as id', 'prov_name as text')
                    ->limit(50)->get();
            return response()->json($datas);
        }
        return response()->json($data);
    }
    
    // jax load info of claim
    public function loadInfoAjaxHBSClaim(Request $request)
    {  
        
        $data = [];
        if($request->has('search')){
            $search = $request->search;
            $datas = HBS_CL_CLAIM::findOrFail($search);
            return response()->json(['member' => $datas->member , 'claim' =>$datas ]);
        }
        return response()->json($data);
    }
    

    // getPaymentHistory mantic
    public static function getPaymentHistory($cl_no){
        $data = GetApiMantic('api/rest/plugins/apimanagement/issues/'. $cl_no);
        $claim = Claim::where('code_claim_show',  $cl_no)->first();
        $HBS_CL_CLAIM = HBS_CL_CLAIM::IOPDiag()->findOrFail($claim->code_claim);
        $approve_amt = $HBS_CL_CLAIM->sumAppAmt;
        return response()->json([ 'data' => $data, 'approve_amt' => $approve_amt]);
    }
    // get  payment of claim  CPS

    public static function getPaymentHistoryCPS($cl_no){
        $token = getTokenCPS();
        $headers = [
            'Content-Type' => 'application/json',
        ];
        $body = [
            'access_token' => $token,
        ];

        $client = new \GuzzleHttp\Client([
            'headers' => $headers
        ]);
        $response = $client->request("POST", config('constants.api_cps').'get_payment/'. $cl_no , ['form_params'=>$body]);
        
        $response =  json_decode($response->getBody()->getContents());
        $response_full = collect($response)->where('TF_STATUS_NAME','!=', "NEW")->where('TF_STATUS_NAME','!=', "DELETED");
        $response = collect($response)->where('TF_STATUS_NAME','!=', "NEW")->where('TF_STATUS_NAME','!=', "DELETED");
            
        $claim = Claim::where('code_claim_show',  $cl_no)->first();
        $HBS_CL_CLAIM = HBS_CL_CLAIM::IOPDiag()->findOrFail($claim->code_claim);
        $approve_amt = $HBS_CL_CLAIM->sumAppAmt;
        $present_amt = $HBS_CL_CLAIM->sumPresAmt;
        $payment_method = str_replace("CL_PAY_METHOD_","",$HBS_CL_CLAIM->payMethod);
        $payment_method = $payment_method == 'CA' ? "CH" : $payment_method;
        $pocy_ref_no = $HBS_CL_CLAIM->Police->pocy_ref_no;
        $memb_ref_no = $HBS_CL_CLAIM->member->mbr_no;
        $member_name = $HBS_CL_CLAIM->memberNameCap;
        $email = $HBS_CL_CLAIM->member->email;
        $hr_email = $HBS_CL_CLAIM->member->hr_email;
        $csr_remark = $HBS_CL_CLAIM->csrRemarkAll;
        return response()->json([ 'data' => $response,
                                'data_full' => $response_full,
                                'approve_amt' => round($approve_amt) , 
                                'present_amt' => round($present_amt) ,
                                'payment_method' => $payment_method,
                                'pocy_ref_no' => $pocy_ref_no,
                                'memb_ref_no' => $memb_ref_no,
                                'member_name' => $member_name,
                                'email' => $email,
                                'hr_email' => $hr_email,
                                'csr_remark' => $csr_remark
                            ]);
    }
    // get  Balance of claim  CPS 
    public static function getBalanceCPS($mem_ref_no , $cl_no){
        $token = getTokenCPS();
        $headers = [
            'Content-Type' => 'application/json',
        ];
        $body = [
            'access_token' => $token,
        ];

        $client = new \GuzzleHttp\Client([
            'headers' => $headers
        ]);
        $response = $client->request("POST", config('constants.api_cps').'get_client_debit/'. $mem_ref_no , ['form_params'=>$body]);
        $response =  json_decode($response->getBody()->getContents());
        /*
            There are 4 types:
            -	1: nợ được đòi lại
            -	2: nợ nhưng đã cấn trừ qua Claim khác
            -	3: nợ nhưng khách hàng đã gửi trả lại
            -	4: nợ không được đòi lại
        */
        if (empty($response)){
            $data =[
                'PCV_EXPENSE' => 0,
                'DEBT_BALANCE' => 0
            ];
            $data_full =[];
        }else{
            $colect_data = collect($response);
            $data =[
                'PCV_EXPENSE' => $colect_data->where('DEBT_CL_NO', $cl_no)->sum('PCV_EXPENSE'),
                'DEBT_BALANCE' => $colect_data->sum('DEBT_BALANCE')
            ];
            $data_full = collect($response);
        }

        return response()->json([ 'data' => $data , 'data_full' =>  $data_full]);
    }
    
    
    public static function setPcvExpense($paym_id, $pcv_expense){
        $token = getTokenCPS();
        $headers = [
            'Content-Type' => 'application/json',
        ];
        $body = [
            'access_token' => $token,
            'pcv_expense' => $pcv_expense,
            'username'    => Auth::user()->name
        ];

        $client = new \GuzzleHttp\Client([
            'headers' => $headers
        ]);
        $response = $client->request("POST", config('constants.api_cps').'set_pcv_expense/'. $paym_id , ['form_params'=>$body]);
        $response =  json_decode($response->getBody()->getContents());
        return $response;
    }

    public static function sendPayment($request, $id_claim){
        $token = getTokenCPS();
        $headers = [
            'Content-Type' => 'application/json',
        ];
        $body = [
            'access_token' => $token,
            'memb_name' => $request->memb_name,
            'pocy_ref_no' => $request->pocy_no,
            'memb_ref_no' => $request->memb_no,
            'pres_amt' => $request->pres_amt,
            'app_amt' => $request->app_amt,
            'tf_amt' => $request->tf_amt,
            'deduct_amt' => $request->deduct_amt,
            'payment_method' => $request->payment_method,
            'mantis_id' => $request->mantis_id,
            'username'    => 'claimassistant'
        ];
        $client = new \GuzzleHttp\Client([
            'headers' => $headers
        ]);
        $response = $client->request("POST", config('constants.api_cps').'send_payment/'. $request->cl_no , ['form_params'=>$body]);
        
        $response =  json_decode($response->getBody()->getContents());
        $rs=data_get($response,'code');
        if(data_get($response,'code') == "00" && data_get($response,'data') != null){
            try {
                DB::beginTransaction();
                PaymentHistory::updateOrCreate([
                    'PAYM_ID' => data_get($response, "data.PAYM_ID"),
                    'CL_NO' => data_get($response, "data.CL_NO"),
                ], [
                    'ACCT_NAME' => data_get($response, "data.ACCT_NAME"),
                    'ACCT_NO' => data_get($response, "data.ACCT_NO"),
                    'BANK_NAME' => data_get($response, "data.BANK_NAME"),
                    'BANK_CITY' => data_get($response, "data.BANK_CITY"),
                    'BANK_BRANCH' => data_get($response, "data.BANK_BRANCH"),
                    'BENEFICIARY_NAME' => data_get($response, "data.BENEFICIARY_NAME"),
                    'PP_DATE' => data_get($response, "data.PP_DATE"),
                    'PP_PLACE' => data_get($response, "data.PP_PLACE"),
                    'PP_NO' => data_get($response, "data.PP_NO"),
                    'CL_TYPE' => data_get($response, "data.CL_TYPE"),
                    'BEN_TYPE' => data_get($response, "data.BEN_TYPE"),
                    'PAYMENT_TIME' => data_get($response, "data.PAYMENT_TIME"),
                    'TF_STATUS' => data_get($response, "data.TF_STATUS_ID"),
                    'TF_DATE' => data_get($response, "data.TF_DATE"),
                    
                    'VCB_SEQ' => data_get($response, "data.VCB_SEQ"),
                    'VCB_CODE' => data_get($response, "data.VCB_CODE"),

                    'MEMB_NAME' => data_get($response, "data.MEMB_NAME"),
                    'POCY_REF_NO' => data_get($response, "data.POCY_REF_NO"),
                    'MEMB_REF_NO' => data_get($response, "data.MEMB_REF_NO"),
                    'PRES_AMT' => data_get($response, "data.PRES_AMT"),
                    'APP_AMT' => data_get($response, "data.APP_AMT"),
                    'TF_AMT' => data_get($response, "data.TF_AMT"),
                    'DEDUCT_AMT' => data_get($response, "data.DEDUCT_AMT"),
                    'PAYMENT_METHOD' => data_get($response, "data.PAYMENT_METHOD"),
                    'PAYMENT_METHOD' => data_get($response, "data.PAYMENT_METHOD"),
                    'MANTIS_ID' => data_get($response, "data.MANTIS_ID"),

                    'update_file' => 0,
                    'update_hbs' => 0,
                    'updated_user' => Auth::user()->id,
                    'created_user' => Auth::user()->id,
                    'notify_renew' => 0,
                    'reason_renew' => null,
                    'claim_id' => $id_claim,
                ]);
                DB::commit();
            } catch (Exception $e) {
                Log::error(generateLogMsg($e));
                DB::rollback();
            }
        }
        return $response;
    }

    public static function setDebt($debt_id){
        $token = getTokenCPS();
        $headers = [
            'Content-Type' => 'application/json',
        ];
        $body = [
            'access_token' => $token,
            'username'    => Auth::user()->name
        ];
        
        $client = new \GuzzleHttp\Client([
            'headers' => $headers
        ]);
        $response = $client->request("POST", config('constants.api_cps').'set_debt/'. $debt_id , ['form_params'=>$body]);
        $response =  json_decode($response->getBody()->getContents());
        return $response;
    }

    public static function payDebt($request , $paid_amt){
        $token = getTokenCPS();
        $headers = [
            'Content-Type' => 'application/json',
        ];
        $body = [
            'access_token' => $token,
            'paid_amt' => $paid_amt,
            'username'    => Auth::user()->name,
            'cl_no' => $request->cl_no,
            'memb_name' => $request->memb_name,
        ];
        
        $client = new \GuzzleHttp\Client([
            'headers' => $headers
        ]);
        $response = $client->request("POST", config('constants.api_cps').'pay_debt/'. $request->memb_ref_no , ['form_params'=>$body]);
        $response =  json_decode($response->getBody()->getContents());
        return $response;
    }

    public function renderEmailProv(Request $request){
        $user = Auth::User();
        $claim_id = $request->claim_id;
        $id = $request->export_letter_id;
        $export_letter = ExportLetter::findOrFail($id);
        $claim  = Claim::itemClaimReject()->findOrFail($claim_id);
        $HBS_CL_CLAIM = HBS_CL_CLAIM::IOPDiag()->findOrFail($claim->code_claim);
        $diag_code = $HBS_CL_CLAIM->HBS_CL_LINE->pluck('diag_oid')->unique()->toArray();
        $match_form_gop = preg_match('/(FORM GOP)/', $export_letter->letter_template->name , $matches);
        $template = $match_form_gop ? 'templateEmail.sendProviderTemplate_input' : 'templateEmail.sendProviderTemplate_output';
        
        $data['diag_text'] = implode(",",$HBS_CL_CLAIM->HBS_CL_LINE->pluck('RT_DIAGNOSIS.diag_desc_vn')->unique()->toArray());
        $incurDateTo = Carbon::parse($HBS_CL_CLAIM->FirstLine->incur_date_to);
        $incurDateFrom = Carbon::parse($HBS_CL_CLAIM->FirstLine->incur_date_from);
        $data['incurDateTo'] = $incurDateTo->format('d-m-Y');
        $data['incurDateFrom'] = $incurDateFrom->format('d-m-Y');
        $data['diffIncur'] =  $incurDateTo->diffInDays($incurDateFrom);
        $data['email_reply'] = $user->email;
        
        //benifit
        $request2 = new Request([
            'diag_code' => $diag_code,
            'id_claim' => $claim->code_claim
        ]);

        $data['HBS_CL_CLAIM'] = $HBS_CL_CLAIM;
        $data['Diagnosis'] = data_get($claim->hospital_request,'diagnosis',null) ?  data_get($claim->hospital_request,'diagnosis') : $HBS_CL_CLAIM->FirstLine->RT_DIAGNOSIS->diag_desc_vn;
        $html = view($template, compact('data'))->render();
        return response()->json([ 'data' => $html]);
    }

    public static function sendMfile($claim_id){
        $claim  = Claim::itemClaimReject()->findOrFail($claim_id);
        if($claim->url_file_sorted == null ){
            \App\LogMfile::updateOrCreate([
                'claim_id' => $claim_id,
            ],[
                'cl_no' => $claim->code_claim_show,
                'm_errorCode' => 999,
                'have_ca' => 1,
                'have_mfile' => 0
            ]);
            return response()->json(['errorCode' => 999 ,'errorMsg' => 'File không tồn tại']);
        }
        $HBS_CL_CLAIM = HBS_CL_CLAIM::IOPDiag()->findOrFail($claim->code_claim);
        $poho_oids = \App\HBS_MR_POLICYHOLDER::where('poho_ref_no', $HBS_CL_CLAIM->PolicyHolder->poho_ref_no)->pluck('poho_oid')->toArray();
        $pocy_ref_nos =  \App\HBS_MR_POLICY::whereIn('poho_oid',$poho_oids)->pluck('pocy_ref_no')->unique()->toArray();



        $handle = fopen(storage_path("app/public/sortedClaim/{$claim->url_file_sorted}"),'r');
        $treamfile = stream_get_contents($handle);
        $token = getTokenMfile();
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'bearer '.$token
        ];
        $body = [
            'mode' => config('constants.mode_mfile'),
            'policy_holder' => [
                "policy_holder_name" => strtoupper(vn_to_str($HBS_CL_CLAIM->PolicyHolder->poho_name_1))." (".$HBS_CL_CLAIM->PolicyHolder->poho_ref_no.")",
                "policy_holder_no" =>  !empty($HBS_CL_CLAIM->PolicyHolder->poho_ref_no) ? $HBS_CL_CLAIM->PolicyHolder->poho_ref_no : $HBS_CL_CLAIM->PolicyHolder->poho_no,
                "policy_holder_note" =>  "PO. " . implode(" + ", $pocy_ref_nos),

            ],
            'member' => [
                "member_name" => strtoupper(vn_to_str($HBS_CL_CLAIM->member->mbr_last_name. " " .$HBS_CL_CLAIM->member->mbr_first_name)) ." (".$HBS_CL_CLAIM->member->mbr_no.")",
                "member_no" =>  !empty($HBS_CL_CLAIM->member->memb_ref_no) ? $HBS_CL_CLAIM->member->memb_ref_no : $HBS_CL_CLAIM->member->mbr_no,
                "is_terminated" => "0",
                "member_notes"=> ""
        
            ],
            'claim' => [
                "claim_info" => [
                    "claim_no" => $claim->code_claim_show,
                    "payee" => $claim->claim_type == "M" ? "Insured" : strtoupper(Str::slug($HBS_CL_CLAIM->Provider->prov_name , ' ')),
                    "claim_note" => "Note something",
                    "claim_type" => "",
                    "claim_lever" => "",
                ],
                "claim_file" =>  [
                    "file_extension" => "pdf",
                    "file_content" => $treamfile
                ]
            ]
        ];
        $client = new \GuzzleHttp\Client([
            'headers' => $headers
        ]);
        $response = $client->request("POST", config('constants.link_mfile').'uploadmfile' , ['form_params'=>$body]);
        $response =  json_decode($response->getBody()->getContents());
        if($response->errorCode == 0){
            \App\LogMfile::updateOrCreate([
                'claim_id' => $claim_id,
            ],[
                'cl_no' => $claim->code_claim_show,
                'm_errorCode' => $response->errorCode,
                'm_errorMsg' => $response->errorMsg,
                'm_policy_holder_id' => $response->info_policy_holder->policy_holder_id,
                'm_policy_holder_latest_version' => $response->info_policy_holder->policy_holder_latest_version,
                'm_member_id' => $response->info_member->member_id,
                'm_member_latest_version' => $response->info_member->member_latest_version,
                'm_claim_id' => $response->info_claim->claim_id,
                'm_claim_latest_version' => $response->info_claim->claim_latest_version,
                'm_claim_file_id' => $response->info_claim->claim_file_id,
                'm_claim_file_latest_version' => $response->info_claim->claim_file_latest_version,
                'have_ca' => 1
            ]);
        }
        return response()->json(['errorCode' => $response->errorCode ,'errorMsg' => $response->errorMsg]);
    }

    public function viewMfile($mfile_claim_id, $mfile_claim_file_id){
        $token = getTokenMfile();
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'bearer '.$token
        ];
        $body = [
            'mode' => config('constants.mode_mfile'),
            'claim_id' => $mfile_claim_id,
            'claim_file_id' => $mfile_claim_file_id
        ];
        $client = new \GuzzleHttp\Client([
            'headers' => $headers
        ]);
        $response = $client->request("POST", config('constants.link_mfile').'downloadfile' , ['form_params'=>$body]);
        $response =  $response->getBody()->getContents();
        header("Content-Type: application/pdf");
        header("Expires: 0");//no-cache
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");//no-cache
        header("content-disposition: attachment;filename=mfile.pdf");
        
        echo $response;
    }

    public function searchMember(Request $request){
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'memb_ref_no' => 'required_without_all:pocy_ref_no,mbr_name,dob',
            'pocy_ref_no' => 'required_without_all:memb_ref_no,mbr_name,dob',
            'mbr_name' => 'required_without_all:memb_ref_no,pocy_ref_no,dob',
            'dob' => 'required_without_all:memb_ref_no,pocy_ref_no,mbr_name',
           
        ]);
        if($validator->fails()){
            return  response()->json($validator->errors()->all(), 401);
        }
        $data = DB::connection('oracle')->table('vw_mr_member')
        ->where('mbr_name','like', "%".strtoupper($request->mbr_name)."%");
        if($request->memb_ref_no){
            $data = $data->where('memb_ref_no','like', "%".$request->memb_ref_no."%");
        }
        if($request->pocy_ref_no){
            $data = $data->where('pocy_ref_no','like', "%".$request->pocy_ref_no."%");
        }
        if($request->dob){
            $data = $data->whereDate('dob', '=', $request->dob);
        }
        
        return response()->json(['data' => $data->get()]);
    }

    public function getPocyRefNo(Request $request)
    {
        $data = [];
        if($request->has('q')){
            $search = $request->q;
            $datas = \App\HBS_MR_POLICY::where('pocy_ref_no','LIKE',"%$search%")
                    ->select('pocy_ref_no as id', 'pocy_ref_no as text')
                    ->limit(20)->get();
            return response()->json($datas);
        }
        return response()->json($data);
    }

    public function getMemberInfo(Request $request)
    {
        $memb_oid = $request->memb_oid;
        $member = \App\HBS_MR_MEMBER::where('MEMB_OID',$memb_oid)->first();
        $data['claim_line'] = $member->ClaimLine;
        $data['pocy_eff_date'] = $member->PocyEffdate;
        $data['plan'] = $member->plan;
        $data['member'] = $member;
        $data['poho_name_1'] = $member->MR_POLICYHOLDER->poho_name_1;
        $data['pocy_no'] = $member->MR_MEMBER_PLAN[0]->MR_POLICY_PLAN->MR_POLICY->pocy_no;
        $data['popl_oid'] = $member->MR_MEMBER_PLAN[0]->MR_POLICY_PLAN->popl_oid; 
        $data['plan_amt'] = data_get(config("constants.plan_amt"),$member->MR_MEMBER_PLAN[0]->MR_POLICY_PLAN->PD_PLAN->plan_id, 0);
        $data['occupation'] = $member->MrMemberEvent->where('scma_oid_event_code', 'EVENT_CODE_EXPL')->first() ? $member->MrMemberEvent->where('scma_oid_event_code', 'EVENT_CODE_EXPL')->first()->event_desc : "";
        $data['exclusion'] = implode("", $member->MrMemberEvent->where('scma_oid_event_code', 'EVENT_CODE_EXCL')->map(function ($item, $key) {
            return " <p>{{$item->event_desc}}--({{$item->event_date}})</p>";
        })->toArray());
        return response()->json($data);
    }

    public function getRelToHosp(Request $request)
    {
        if(!in_array($request->benhead, ['CASH-IMIS','CASH-ICU','CASH-PA','CASH-INCUR','CASH-SURG'])){
            return response()->json(['data' =>  null ]);
        }
        
        $memb_oid = $request->memb_oid;
        $incur_date_from =  $request->incur_date_from;
        $diag_code = $request->diag_code;
        $HBS_RT_DIAGNOSIS =  \App\HBS_RT_DIAGNOSIS::where('diag_code',$diag_code)->first();
        $benhead = $request->benhead;
        $checkdate = $benhead=='CASH-SURG' ? 60 : 90;
        $dt = Carbon::parse($incur_date_from);
        $sub_dt = $dt->subDays($checkdate)->format('Y-m-d');
        $dt = Carbon::parse($incur_date_from);
        $add_dt = $dt->addDays($checkdate)->format('Y-m-d');
        $HBS_CL_LINE = \App\HBS_CL_LINE::selectRaw('
        cl_no,
        line_no , 
        VW_CL_TXN_LINE.clam_oid , 
        behd_oid ,
        incur_date_from , 
        incur_date_to ,
        diag_oid,
        REL_TO_HOSP,
        VW_CL_TXN_LINE.REL_TO_HOSP_CLAIM_NO,
        VW_CL_TXN_LINE.REL_TO_HOSP_LINE_NO,
        app_amt,
        VW_CL_TXN_LINE.rel_to_hosp_claim_no, ABS(incur_date_to - incur_date_from)+NVL(sum_use_days_ref,0)  as use_days ,
        (NVL(sum_use_app_amt_ref,0) + NVL(app_amt,0)) as use_amt_surg,
        sum_use_days_ref
        ')
        
        ->leftJoin('cl_claim', function($join) {
            $join->on('CL_CLAIM.clam_oid', '=', 'vw_cl_txn_line.clam_oid');
        })
        ->leftJoin(DB::raw('(Select rel_to_hosp_claim_no,rel_to_hosp_line_no, sum(ABS(incur_date_to - incur_date_from)) as sum_use_days_ref  
        from vw_cl_txn_line cl_txn
        join pd_ben_head 
        on cl_txn.behd_oid = pd_ben_head.behd_oid
        where rel_to_hosp_claim_no IS NOT NULL AND
        pd_ben_head.ben_head = \'CASH-IMIS\'
        GROUP BY rel_to_hosp_claim_no, rel_to_hosp_line_no) jt'), 
        function($join)
        {
            $join->on('CL_CLAIM.cl_no', '=', 'jt.rel_to_hosp_claim_no');
        })

        ->leftJoin(DB::raw('(Select rel_to_hosp_claim_no,rel_to_hosp_line_no, sum(NVL(app_amt,0)) as sum_use_app_amt_ref  
        from vw_cl_txn_line cl_txn2
        join pd_ben_head 
        on cl_txn2.behd_oid = pd_ben_head.behd_oid
        where rel_to_hosp_claim_no IS NOT NULL AND
        pd_ben_head.ben_head IN (\'CASH-SURG1\',\'CASH-SURG2\',\'CASH-SURG3\',\'CASH-SURG4\')
        GROUP BY rel_to_hosp_claim_no, rel_to_hosp_line_no) jt2'), 
        function($join)
        {
            $join->on('CL_CLAIM.cl_no', '=', 'jt2.rel_to_hosp_claim_no');
        })

        ->where('MEMB_OID',$memb_oid)
        ->whereNull('REL_TO_HOSP')
        ->whereNull('VW_CL_TXN_LINE.REL_TO_HOSP_CLAIM_NO')
        ->whereNull('VW_CL_TXN_LINE.REL_TO_HOSP_LINE_NO')
        ->with('PD_BEN_HEAD')
        ->with('HBS_CL_CLAIM')
        ->where('INCUR_DATE_FROM' ,">=",  $sub_dt)
        ->where('INCUR_DATE_FROM' ,"<=",  $add_dt);
        if($benhead=='CASH-SURG'){
            $HBS_CL_LINE = $HBS_CL_LINE->whereHas(
                'PD_BEN_HEAD', function($q){
                    $q->whereIn('BEN_HEAD', ['CASH-SURG1','CASH-SURG2','CASH-SURG3','CASH-SURG4']);
                }
            );
        }else{
            $HBS_CL_LINE = $HBS_CL_LINE->whereHas(
                'PD_BEN_HEAD', function($q){
                    $q->where('BEN_HEAD', 'CASH-IMIS');
                }
            );
        }
        $HBS_CL_LINE = $HBS_CL_LINE ->get()->groupBy('HBS_CL_CLAIM.cl_no');
        return response()->json(['data' => $HBS_CL_LINE->count() == 0 ? null :$HBS_CL_LINE]);
    }

    public function sendClaimHBS(Request $request){
        $IV = $request->IV;
        $encrypted = $request->encrypted;
        $auth = "ABCDEFabcdef1234567890";
        $url = config("constants.url_hbs")."api/rest/claim/add";
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => $auth
        ];
        $body = [
            "encrypted" => $encrypted,
            'IV' => $IV,
        ];
        
        try {
            $client = new \GuzzleHttp\Client([
                'headers' => $headers
            ]);
            $response = $client->post( $url ,['body'=>json_encode($body)]);
            $code = $response->getStatusCode();
            $response =  json_decode($response->getBody()->getContents());
            
            return response()->json($response, $code);
        }catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse()->getBody(true);
            $code = $e->getCode();
            return response()->json(json_decode($response),$code);
        }   
    }
    
     // jax load info of claim
    public function createClaimDB(Request $request)
    {  
        
        $data = [];
        if($request->has('cl_no')){
            $cl_no = $request->cl_no;
            $data = HBS_CL_CLAIM::where('cl_no',$cl_no)->first();
            $user = Auth::User();
            $userId = $user->id;
            $mem = $data->member;
            $dataNew = [
                'claim_type' => $request->claim_type,
                'code_claim' => $data->clam_oid,
                'code_claim_show' => $cl_no,
                'member_name' => $mem->mbr_last_name ." ". $mem->mbr_mid_name ." ". $mem->mbr_first_name,
                'barcode' => $request->barcode,
                'mantis_id' => $request->barcode,
                "created_user" =>  $userId,
                "updated_user" =>  $userId
            ];
            $claim = Claim::updateOrCreate([
                'barcode' => $request->barcode,
            ],$dataNew);
            return response()->json([ 'claim' =>$claim ]);
        }
        return response()->json($data);
    }
}
