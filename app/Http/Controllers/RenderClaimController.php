<?php

namespace App\Http\Controllers;
use App\User;
use Auth;
use App\PaymentHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\HBS_CL_CLAIM;
use App\MANTIS_BUG;
use App\MANTIS_PROJECT;


class RenderClaimController extends Controller
{
    
    //use Authorizable;
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $HbsBenhead = \App\HbsBenhead::selectRaw('code ,CONCAT(code," - ",CASE WHEN desc_vn IS NULL THEN desc_en ELSE  desc_vn END) as detail,ben_type')->get()->groupBy('ben_type')->toArray();
        $list_reject = \App\HBS_SY_SYS_CODE::selectRaw("REPLACE(scma_oid, 'MAN_REJ_CODE_','') as scma_oid, REPLACE(scma_oid, 'MAN_REJ_CODE_','') ||'-'|| FN_GET_SYS_CODE_DESC(scma_oid, 'en') name")->where("scma_oid","LIKE","%MAN_REJ_CODE_M%")->pluck("name","scma_oid");
        foreach ($HbsBenhead as $key => $value) {
            foreach ($value as $key2 => $value2) {
                $list_ben_head[$value2['code']] =$value2['detail'];       
            }
        }
        $list_ben_type = array_combine(array_keys($HbsBenhead),array_keys($HbsBenhead));
        $list_provider = \App\HBS_PV_PROVIDER::pluck('prov_name','prov_code');
        $type_support_lish = \App\HbsBenhead::whereNotIn('code', ['CASH-SURG1','CASH-SURG2','CASH-SURG3','CASH-SURG4'])->pluck('desc_vn','code');
        $treatment_group_surg = \App\TreatmentGroup::where('type_max','amt')->pluck('treatment_group_name','id');
        $treatment_group_not_surg = \App\TreatmentGroup::where('type_max','days')->pluck('treatment_group_name','id');
        $treatment_group_all = \App\TreatmentGroup::select('treatment_group.id','value_max','amt_times','type_max','hbs_benhead.code','ben_type','desc_vn')->leftJoin('hbs_benhead', 'hbs_benhead.code', '=', 'treatment_group.ben_head_code')->get();
        $HbsBenhead = \App\HbsBenhead::all();
        $type_support_lish["CASH-SURG"] =  'Hỗ trợ chi phí phẫu thuật';
        
        $lish_diag = \App\HBS_RT_DIAGNOSIS::select(DB::raw("diag_code as id, diag_code || ' - ' ||CASE WHEN diag_desc_vn IS NULL THEN diag_desc ELSE  diag_desc_vn END as text"))->pluck('text','id');
        
        return view('renderClaimManagement.index', compact('list_ben_type','list_ben_head','list_provider','lish_diag','list_reject',
        'type_support_lish','treatment_group_surg','treatment_group_not_surg','treatment_group_all','HbsBenhead'));
    }

    
    
    
}
