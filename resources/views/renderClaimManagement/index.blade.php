@extends('layouts.admin.master')
@section('title', '	[CLM - Claims]')
@section('stylesheets')
    <link href="{{ asset('css/condition_advance.css?vision=') .$vision }}" media="all" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('plugins/sweetalert/sweetalert.css?vision=') .$vision }}" media="all" rel="stylesheet" type="text/css"/>
    <link href="{{asset('css/fileinput.css?vision=') .$vision }}" media="all" rel="stylesheet" type="text/css"/>
    <link href="{{asset('css/formclaim.css?vision=') .$vision }}" media="all" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('css/icheck.css?vision=') .$vision }}" media="all" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css"/>
    <style>
        .sweet-alert {
            background-color: #ffffff;
            width: 800px;
            padding: 17px;
            border-radius: 5px;
            text-align: center;
            position: fixed;
            left: 50%;
            top: 50%;
            margin-left: -256px;
            margin-top: -200px;
            overflow: hidden;
            display: none;
            z-index: 2000;
        }
        span.select2 {
            max-width: 320px !important;
            height: 38px;
        }
        .modal-lg {
            max-width: 1300px;
        }
        label {
            margin-top: 0.1px;
            padding: 0.1px;
            margin-bottom: 0.1px;
            font-weight: 400;
        }
        .form-control {
            padding: 0.5px 0.5px;
        }

        .select2-container .select2-selection--single {
        height: 78%;
        padding: 0;
        border: 1px solid #ced4da;
        width: 100% !important;
        }
    </style>
@endsection
@section('content')
@include('layouts.admin.breadcrumb_index', [
    'title'       => '	[CLM - Claims] ',
    'page_name'   => '	[CLM - Claims]',
])
<div class="card border-danger mb-3" >
    <div class="card-header">Tìm kiếm </div>
    <div class="card-body row">
        <div class="col-md-4">
            {{ Form::label('pocy_ref_no_s', "Số Hợp Đồng", ['class' => 'labelas']) }}
            <br>
            {{ Form::select('pocy_ref_no_s',  [], null, ['id' => 'pocy_ref_no_s' ,'class' => 'form-control' ]) }}
        </div>
        <div class="col-md-4">
            {{ Form::label('mbr_ref_no_s', "Mã Thành Viên", ['class' => 'labelas']) }}
            {{ Form::text('mbr_ref_no_s',  null, ['id' => 'mbr_ref_no_s' ,'class' => 'form-control' ]) }}
        </div>
        <div class="col-md-4">
            {{ Form::label('mbr_name_s', "Tên Thành Viên", ['class' => 'labelas']) }}
            {{ Form::text('mbr_name_s',   null, ['id' => 'mbr_name_s' ,'class' => 'form-control text-uppercase' ]) }}
        </div>
        <div class="col-md-4">
            {{ Form::label('mbr_dob_s', "Ngày Sinh", ['class' => 'labelas']) }}
            {{ Form::text('mbr_dob_s', null, ['id' => 'mbr_dob_s' ,'class' => 'form-control datepicker' ]) }}
        </div>
        <div class="col-md-1">
            {{ Form::label('mbr_dob', " _", ['class' => 'labelas']) }}
            <button type="button" class="btn btn-info form-control" onclick="search_member()" > Tìm Kiếm</button>
        </div>
    </div>
</div>
<div id="view_resurt" class="card border-danger mb-3" >
    <div class="card-header">Kết quả</div>
    <div class="card-body">
        <table id="resurt" class="table table-striped header-fixed">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Tên</th>
                    <th>Ngày Sinh</th>
                    <th>Số Hợp Đồng</th>
                    <th>Mã Thành Viên</th>
                    <th>Chủ Hợp Đồng</th>
                </tr>
            </thead>
            <tbody>
               
            </tbody>
        </table>
        <button id="button_select_member" class="btn btn-info">Chọn</button> 
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row mt-5">
                    <div class="col-md-4">
                    <p class="font-weight-bold">Người Được Bảo Hiểm: <span class="info_member" id="member_name"></span></p>
                    </div>
                    <div class="col-md-3">
                    <p class="font-weight-bold">Ngày Sinh: <span class="info_member" id="member_dob"></span></p>
                    </div>
                    <div class="col-md-2">
                    <p class="font-weight-bold">Giới Tính: <span class="info_member" id="scma_oid_sex"></span></p>
                    </div>
                    <div class="col-md-3">
                        <p class="font-weight-bold">Mã Thành Viên: <span class="info_member" id="member_no"></span></p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                    <p class="font-weight-bold">Chủ Hợp Đồng: <span class="info_member" id="poho_name_1"></span></p>
                    </div>
                    <div class="col-md-4">
                    <p class="font-weight-bold">Hợp Đồng Hiệu Lực Từ: <span class="info_member" id="pocyEffdate"></span></p>
                    </div>
                    <div class="col-md-4">
                    <p class="font-weight-bold">Thành Viên Hiệu Lực Từ: <span class="info_member" id="eff_date"></span></p>
                    </div>
                </div>
                <div>
                    <p class="font-weight-bold">Occupation Loading: 
                        <span class="info_member" id="occupation"></span>
                    </p>
                    <p><span  class="font-weight-bold">Loading:</span> 
                        <span class="info_member" id="loading"></span>
                    </p>
                    <p><span  class="font-weight-bold">Exclusion:</span>
                        <span class="info_member" id="exclusion"></span>
                    </p>
                </div>
                {{-- Plan --}}
                <div class="row">
                    <div class="col-md-2">
                        <p class="font-weight-bold">Plan: </p>
                    </div>
                    <span class="info_member" id="plan"></span>
                </div>
                {{-- CLAIM HISTORY --}}
                <div class="row">
                    <div class="col-md-2">
                        <p class="font-weight-bold">Lịch Sử Bồi Thường: </p>
                    </div>
                    <div class="col-md-4">
                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#claimhistory">Xem</button>
                    </div>
                    <!-- Modal -->
                    <div id="claimhistory" class="modal fade" role="dialog">
                        <div class="modal-dialog modal-lg">
                    
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <table class="table table-striped header-fixed ">
                                    <thead>
                                        <th>Số Bồi Thường</th>
                                        <th>Line No</th>
                                        <th>Ngày Bắt Đầu</th>
                                        <th>Ngày Kết Thúc</th>
                                        <th>Chẩn đoán</th>
                                        <th>Quyền lợi</th>
                                        <th>Kết Quả(Approved)</th>
                                        <th>Mối liên Quan</th>
                                    </thead>
                                    <tbody class="info_member" id="body_history">
                                        
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                            </div>
                        </div>
                    
                        </div>
                    </div>
                    
                </div>
                
                <br />
                {{-- valid check --}}
                <div class="row">
                    <div class="col-md-12">
                        <p class="font-weight-bold btn btn-warning">Thông Tin Của Hồ Sơ :</p>
                    </div>
                    <div class="col-md-4">
                        {{ Form::label('barcode', "Barcode", ['class' => 'labelas']) }}
                        {{ Form::text('barcode', null, ['id' => 'barcode' ,'class' => 'form-control']) }}
                    </div>
                    <div class="col-md-4">
                        {{ Form::label('cl_type', "Loại hồ sơ", ['class' => 'labelas']) }}<span class="text-danger">(*)</span><br>
                        {{ Form::select('cl_type', ['P' => 'Bảo lãnh Viện Phí' , 'M' => 'Cá nhân'],null, ['id' => 'cl_type' ,'class' => 'select2 form-control', 'placeholder' => '']) }}
                    </div>
                    <div class="col-md-3" id="gop_present_amt_div" style="display:none">
                        {{ Form::label('gop_present_amt', "Số tiền bệnh viện yêu cầu chi trả", ['class' => 'labelas']) }}<span class="text-danger">(*)</span>
                        {{ Form::number('gop_present_amt', null, ['id' => 'gop_present_amt' ,'class' => ' form-control']) }}
                    </div>
                    <div class="col-md-4">
                        {{ Form::label('mbr_no', "Số Thành Viên (of PCV).", ['class' => 'labelas']) }}
                        {{ Form::text('mbr_no', null, ['id' => 'mbr_no' ,'class' => 'form-control info_member','readonly']) }}
                    </div>
                    <div class="col-md-4">
                        {{ Form::label('pocy_no', "Số Hợp Đồng (of PCV).", ['class' => 'labelas']) }}
                        {{ Form::text('pocy_no', null, ['id' => 'pocy_no' ,'class' => 'form-control info_member','readonly']) }}
                    </div>
                    <div class="col-md-4">
                        {{ Form::label('popl_oid', "Policy Plan (of PCV).", ['class' => 'labelas']) }}
                        {{ Form::text('popl_oid', null, ['id' => 'popl_oid' ,'class' => 'form-control' ,'readonly']) }}
                    </div>
                    <div class="col-md-4">
                        {{ Form::label('rcv_date', "Ngày Nhận Hồ Sơ (Receive Date)", ['class' => 'labelas']) }}<span class="text-danger">(*)</span>
                        {{ Form::text('rcv_date', Carbon\Carbon::now()->format('Y-m-d') , ['id' => 'rcv_date' ,'class' => 'form-control datepicker']) }}
                    </div>
                    {{-- <div class="col-md-4">
                        {{ Form::label('incur_date_from', "Incur Date From", ['class' => 'labelas']) }}
                        {{ Form::text('incur_date_from', null, ['id' => 'incur_date_from' ,'class' => 'form-control datepicker']) }}
                    </div>
                    <div class="col-md-4">
                        {{ Form::label('incur_date_to', "Incur Date To", ['class' => 'labelas']) }}
                        {{ Form::text('incur_date_to', null, ['id' => 'incur_date_to' ,'class' => 'form-control datepicker']) }}
                    </div> --}}
                    <div class="col-md-4">
                        {{ Form::label('prov_name', "Cớ sở Y Tế", ['class' => 'labelas']) }}<span class="text-danger">(*)</span><br>
                        {{ Form::select('prov_name',$list_provider, null, ['id' => 'prov_name' ,'onchange'=>"prov_name_map(this)",'class' => 'form-control select2','placeholder' => '']) }}
                    </div>
                    <div class="col-md-4">
                        {{ Form::label('prov_code', "Mã Cơ sỏ Y Tế", ['class' => 'labelas']) }}
                        {{ Form::text('prov_code',  null, ['id' => 'prov_code' ,'class' => 'form-control' ,'readonly']) }}
                    </div>
                    
                </div>
                {{-- <div class="row">
                    <div class="col-md-3">
                        {{ Form::label('file1', 'File ORC', array('class' => 'labelas')) }} <span class="text-danger">*(PDF)</span>
                        {{ Form::file('file', array('id' => "fileUpload", 'class' => " file ")) }}
                        <button type="button" class="btn btn-danger mt-2" onclick="btnScan()" ><i class="fa fa-print" aria-hidden="true"></i> Scan</button>
                    </div>
                    <div class="col-md-9">
                        {{ Form::label('file1', 'Result', array('class' => 'labelas')) }} 
                        <div class="table-responsive" id="dvExcel"  style="max-height:500px">
                            
                        </div>
                        <div class="row p-2 mt-3 .bg-light">
                            <div class="form-check col-md-1">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input resize-checkbox" value="" onClick="checkAll(this)" > 
                                        <p class="ml-2 mt-2"> All</p>
                                    </label>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div  class='col-md-4'>
                                        {{ Form::select('_ben_type', $list_ben_type,null, ['id'=>'select-bentype-default' ,'class' => 'select2 form-control p-1 search-input' ]) }}
                                    </div>
                                    <div  class='col-md-4'>
                                        {{ Form::select('_ben_head', $list_ben_head,null, ['id'=>'select-benhead-default' ,'class' => 'select2 form-control p-1 ']) }}
                                    </div>
                                    <button type="button" onclick="clickGo()" class="btn btn-secondar col-md-1">Apply</button>
                                    <button type="button" onclick="InsertClaimLine()" class="btn btn-info col-md-3">Auto insert Claim Line</button>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}

                {{--AUto render claim line--}}
                <br />
                <div class="card border-success mb-3" >
                    <div class="card-header">Hỗ trợ Khởi tạo claim HBS</div>
                    <div class="card-body row">
                        <div class="col-md-2">
                            {{ Form::label('incur_date_from', "Incur Date From", ['class' => 'labelas']) }}<span class="text-danger">(*)</span>
                            {{ Form::text('incur_date_from', null, ['id' => 'incur_date_from' ,'class' => 'form-control', 'onchange' => "getRelToHosp()"] ) }}
                            
                        </div>
                        <div class="col-md-2">
                            {{ Form::label('incur_date_to', "Incur Date To", ['class' => 'labelas']) }}<span class="text-danger">(*)</span>
                            {{ Form::text('incur_date_to', null, ['id' => 'incur_date_to' ,'class' => 'form-control datepicker', 'onchange' => "getRelToHosp()"]) }}
                        </div>
                        <div class="col-md-2">
                            {{ Form::label('diagnosis_code', "Diagnosis Code", ['class' => 'labelas']) }}<span class="text-danger">(*)</span>
                            <br>
                            {{ Form::select('diagnosis_code', $lish_diag,null, ['id' => 'diagnosis_code' ,'class' => 'select2 form-control','placeholder'=>'....', 'onchange' => "getRelToHosp()"]) }}
                        </div>
                        <div class="col-md-3">
                            {{ Form::label('benhead', "Loại Hình Hỗ trợ", ['class' => 'labelas']) }}<span class="text-danger">(*)</span>
                            <br>
                            {{ Form::select('benhead', $type_support_lish,null, ['id' => 'benhead_select' ,'class' => 'select2 form-control','placeholder'=>'....', 'onchange' => "getRelToHosp()"]) }}
                        </div>
                        <div class="col-md-3" id="treatment_group_surg" style="display:none"><span class="text-danger">(*)</span>
                            {{ Form::label('treatment_group_1', "Nhóm Điều Trị Phẫu Thuật", ['class' => 'labelas']) }}
                            <br>
                            {{ Form::select('treatment_group_1', $treatment_group_surg,null, ['id' => 'treatment_group_1' ,'class' => 'select2 form-control','placeholder'=>'....']) }}
                        </div>
                        <div class="col-md-3" id="treatment_group_not_surg" style="display:none"><span class="text-danger">(*)</span>
                            {{ Form::label('treatment_group_2', "Nhóm Điều Trị", ['class' => 'labelas']) }}
                            <br>
                            {{ Form::select('treatment_group_2', $treatment_group_not_surg,null, ['id' => 'treatment_group_2' ,'class' => 'select2 form-control','placeholder'=>'....']) }}
                        </div>
                        
                    </div>
                    <div id="rel_hospital" class="row p-2" style="display:none">
                        <div class="col-md-2" id="rel_to_hosp_div">
                            {{ Form::label('rel_to_hosp', "Liên quan đến nhập viện", ['class' => 'labelas']) }}
                            <br>
                            {{ Form::select('rel_to_hosp', ['N' => 'Không' ,'Y' => 'Có'],null, ['id' => 'rel_to_hosp' ,'class' => 'select2 form-control','placeholder'=>'....']) }}
                        </div>
                        <div class="col-md-2" id="rel_to_hosp_claim_no_div">
                            {{ Form::label('rel_to_hosp_claim_no', "Số claim Liên Quan", ['class' => 'labelas']) }}
                            <br>
                            {{ Form::select('rel_to_hosp_claim_no', [],null, ['id' => 'rel_to_hosp_claim_no' ,'class' => 'select2 form-control','placeholder'=>'....']) }}
                        </div>
                        <div class="col-md-2" id="rel_to_hosp_line_no_div" >
                            {{ Form::label('rel_to_hosp_line_no', "Số line", ['class' => 'labelas']) }}
                            <br>
                            {{ Form::select('rel_to_hosp_line_no',[],null, ['id' => 'rel_to_hosp_line_no' ,'class' => 'select2 form-control','placeholder'=>'....']) }}
                        </div>
                    </div>
                    <div class="col-md-5" >
                        <br>
                        <button type="button" class="btn btn-success float-right" onclick="Apply()" >Apply</button>
                    </div>
                    
                </div>
                <div class="card border-success mb-3" >
                    <div class="card-header">CSR Remark</div>
                    <div class="card-body" class="info_member" id = "csr_remark">
                        
                    </div>
                    
                </div>
                
                <div class="row">
                    <table id="season_price_tbl" class="table table-striped table-bordered table-responsive">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Incur Date <span class="text-danger">(*)</span></th>
                                <th>REL Hospital <span class="text-danger"></span></th>
                                <th>Ben Type - Ben Head - Diag Code<span class="text-danger"></span></th>
                                <th>Pre Amt<span class="text-danger">(*)</span></th>
                                <th>Inv</th>
                                <th>Manual Reject <button type="button" class="btn btn-success float-right" onclick="addInputItem()" >Add New Line</button></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr id="empty_item" style="display: none;">
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr id="clone_item" style="display: none;">
                                <td style="width:50px">
                                    <button type="button" class="delete_btn btn btn-danger" style="height : 40px">&#x2613;</button>
                                </td>
                                <td style="width:160px">
                                    {{ Form::label('incur_date_from', "from", ['class' => 'labelas']) }}
                                    {{ Form::text('_incur_date_from', null, ['class' => 'incur_date_from form-control datepicker' ,'required']) }}
                                    {{ Form::label('incur_date_from', "to", ['class' => 'labelas']) }}
                                    {{ Form::text('_incur_date_to', null, ['class' => 'incur_date_from form-control datepicker' ,'required']) }}
                                    {{ Form::label('actual_incur_date_to', "actual date to", ['class' => 'labelas']) }}
                                    {{ Form::text('_actual_incur_date_to', null, ['class' => 'actual_incur_date_to form-control datepicker' ,'required']) }}
                                </td>
                                <td style="width:160px">
                                    {{ Form::label('rel_to_hosp', "rel to hosp", ['class' => 'labelas']) }}
                                    {{ Form::text('_rel_to_hosp', null, ['class' => 'rel_to_hosp form-control' ,'required']) }}
                                    {{ Form::label('rel_to_hosp_claim_no', "claim no", ['class' => 'labelas']) }}
                                    {{ Form::text('_rel_to_hosp_claim_no', null, ['class' => 'rel_to_hosp_claim_no form-control' ,'required']) }}
                                    {{ Form::label('rel_to_hosp_line_no', "line no", ['class' => 'labelas']) }}
                                    {{ Form::text('_rel_to_hosp_line_no', null, ['class' => 'rel_to_hosp_line_no form-control' ,'required']) }}
                                </td>
                                <td style="width:380px">
                                    {{ Form::label('label', "Ben Type", ['class' => 'labelas']) }}<br>
                                    {{ Form::select('_ben_type', $list_ben_type,null, ['class' => 'form-control p-1 search-input' ]) }}
                                    {{ Form::label('label', "Ben Head", ['class' => 'labelas']) }}<br>
                                    {{ Form::select('_ben_head', $list_ben_head,null, ['class' => 'form-control p-1 ']) }}
                                    {{ Form::label('label', "Diag Code", ['class' => 'labelas']) }}<br>
                                    {{ Form::select('_diag_code', $lish_diag,null, ['class' => ' form-control' ,'required','placeholder' => '']) }}
                                </td>
                                
                                <td style="width:200px">
                                    {{ Form::label('label', "Present AMT", ['class' => 'labelas']) }}
                                    {{ Form::text('_pre_amt', null, ['class' => 'item-price form-control ' ,'required']) }}
                                    {{ Form::label('label', "Present AMT of Provider", ['class' => 'labelas']) }}
                                    {{ Form::text('_pre_amt_provider', null, ['class' => 'item-price form-control ' ,'required']) }}
                                </td>
                                <td style="width:160px">
                                    {{ Form::text('_inv', null, ['class' => ' form-control' ]) }}
                                </td>
                                <td style="width:300px">
                                    {{ Form::label('man_rej', "Số tiền từ chối", ['class' => 'labelas']) }}
                                    {{ Form::text('_man_rej_amt', null, ['class' => 'item-price form-control ' ]) }}
                                    {{ Form::label('man_rej', "Code", ['class' => 'labelas']) }}<br>
                                    {{ Form::select('_man_rej_code_1', $list_reject,null, ['class' => 'form-control ', 'style'=>"width:150px" ,'placeholder' => '']) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        
                        <button type="button" class="btn btn-success float-right ml-3" onclick="callapihbs('N')" >Validation & New Claim to HBS</button>
                        <button type="button" class="btn btn-info float-right" onclick="callapihbs('Y')" >Quick Validation</button>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script src="{{asset('js/fileinput.js?vision=') .$vision }}"></script>
<script src="{{asset('js/lengthchange.js?vision=') .$vision }}"></script>
<script src="{{asset('js/format-price.js?vision=') .$vision }}"></script>
<script src="{{asset('plugins/sweetalert/sweetalert.js?vision=') .$vision }}"></script>
<script src="{{ asset('js/icheck.min.js?vision=') .$vision }}"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/select/1.4.0/js/dataTables.select.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>
<script type="text/javascript">
    var respon_hol = {}; 
    var treatment_group_all = @json($treatment_group_all);
    var HbsBenhead = @json($HbsBenhead);
    var plan_amt = 0;
    var tableo;
    var member_oid = "";
    $(document).ready(function() {
        
        $('#pocy_ref_no_s').select2({          
        minimumInputLength: 2,
        ajax: {
        url: "/admin/getPocyRefNo",
            dataType: 'json',
            data: function (params) {
                return {
                    q: $.trim(params.term)
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        }
        });
        $("#button_select_member").click(function () {
            var count = tableo.rows( { selected: true } ).count();
            if(count == 0){
                alert("vui lòng chọn 1 member phù hợp");
                return 1;
            }
            $(".info_member").html("");
            var data_select = tableo.rows({selected: true });
            var memb_oid_select = data_select.data()[0][0];
            member_oid = memb_oid_select;
            $(".loader").show();
            axios.post("/admin/getMemberInfo",{
                    'memb_oid' : memb_oid_select
            })
            .then(function (response) {
                $(".loader").fadeOut("slow");
                console.log( response.data);
                $("#member_name").html(response.data.member.mbr_first_name);
                $("#member_dob").html(response.data.member.dob.substr(0, 10));
                $("#scma_oid_sex").html(response.data.member.scma_oid_sex.replace("SEX_",""));
                $("#occupation").html(response.data.occupation);
                $("#exclusion").html(response.data.exclusion);
                $("#member_no").html(response.data.member.memb_ref_no);
                $("#eff_date").html(response.data.member.eff_date.substr(0, 10));
                $("#pocyEffdate").html(response.data.pocy_eff_date.substr(0, 10));
                $("#poho_name_1").html(response.data.poho_name_1);
                $("#pocy_no").val(response.data.pocy_no);
                $("#mbr_no").val(response.data.member.mbr_no);
                $("#popl_oid").val(response.data.popl_oid);
                
                plan_amt = response.data.plan_amt;
                $.each( response.data.plan, function( key, value ) {
                    $("#plan").append(value);
                });
                var table_his = $("#body_history");
                $.each( response.data.claim_line, function( key, value ) {
                    var html  = "<tr>";
                        html  += "<td>" + value.h_b_s__c_l__c_l_a_i_m.cl_no +"</td>";
                        html  += "<td>" + value.line_no +"</td>";
                        html  += "<td>" + value.incur_date_from.substr(0, 10) +"</td>";
                        html  += "<td>" + value.incur_date_to.substr(0, 10) +"</td>";
                        html  += "<td>" + value.r_t__d_i_a_g_n_o_s_i_s.diag_desc_vn + "-" + value.r_t__d_i_a_g_n_o_s_i_s.diag_code +"</td>";
                        html  += "<td>" + value.p_d__b_e_n__h_e_a_d.scma_oid_ben_type.replace("BENEFIT_TYPE_","") + " " + value.p_d__b_e_n__h_e_a_d.ben_head+"</td>";
                        html  += "<td>" + value.app_amt+"</td>";
                        if(value.rel_to_hosp_claim_no != null){
                            html  += "<td>" + value.rel_to_hosp_claim_no +" - " + value.rel_to_hosp_line_no + "</td>";
                        }else{
                            html  += "<td></td>";
                        }
                        
                        html  += "</tr>";
                    table_his.append(html);
                });
                
            }).catch(function (error) {
                $(".loader").fadeOut("slow");
                swal({
                    title: "Thất bại", 
                    html: true,
                    text: error.message,  
                    allowOutsideClick: "true" 
                });

                
            });
        
        });
        $("#benhead_select").change(function(){
            $("#treatment_group_surg").hide();
            $("#treatment_group_not_surg").hide();
            if( $("#benhead_select").val() == "CASH-SURG" ){
                $("#treatment_group_surg").show();
            }else{
                $("#treatment_group_not_surg").show();
            }
        }); 
        $("#cl_type").change(function(){
            if( $("#cl_type").val() == "P" ){
                $("#gop_present_amt_div").show();
            }else{
                $("#gop_present_amt_div").hide();
            }
        });
        tableo = $('#resurt').DataTable( {
            select: true
        });
    
    });

    $("#incur_date_from ").daterangepicker({
        locale: {
            "format": "YYYY-MM-DD"
        },
        onSelect: function(d,i){
            getRelToHosp();
        },
        singleDatePicker: true,
        autoUpdateInput: true,
    });
    $("#rel_to_hosp_claim_no").change(function() {

        var cl_no = $( this ).val();
        $("#rel_to_hosp_line_no").empty();
        var newOption = new Option("...","", false, false);
        $('#rel_to_hosp_line_no').append(newOption);
        $.each( respon_hol, function( key, value ) {
            if (key == cl_no ) {
                $.each( value, function( key2, value2 ) {
                    var newOption = new Option(value2.line_no, value2.line_no, false, false);
                    $('#rel_to_hosp_line_no').append(newOption)
                });
            }
            
        });
        $('#rel_to_hosp_line_no').trigger('change');
    });

    function getRelToHosp()
    {
        respon_hol = {};
        var incur_date_from = $("#incur_date_from").val();
        var diagnosis_code = $("#diagnosis_code").val();
        var benhead_select = $("#benhead_select").val();
        $("#rel_to_hosp_claim_no").empty();
        var newOption = new Option("...","", false, false);
        $('#rel_to_hosp_claim_no').append(newOption)
        if(incur_date_from.length == 0 || diagnosis_code.length == 0 || benhead_select.length == 0 || member_oid.length == 0){
            return 1;
        }
        axios.post("/admin/getRelToHosp",{
                    'memb_oid' : member_oid ,
                    'incur_date_from' : incur_date_from,
                    'diag_code':diagnosis_code,
                    'benhead' : benhead_select,

        }).then(function (response) {
            respon_hol = response.data.data == null ? {} : response.data.data;
            if(jQuery.isEmptyObject(respon_hol)){
                $("#rel_hospital").hide();
            }else{
                $("#rel_hospital").show();
                $.each( respon_hol, function( key, value ) {
                    var newOption = new Option(key, key, false, false);
                    $('#rel_to_hosp_claim_no').append(newOption)
                });
                
                $('#mySelect2').trigger('change');
            }
        }).catch(function (error) {
            console.log("Fail!!!!!", error.message);
        });
    }
    function Apply(){
        var message_error = [];
        var incur_date_from = $("#incur_date_from").val();
        var incur_date_to = $("#incur_date_to").val();
        var actual_incur_date_to = $("#incur_date_to").val();
        var benhead_select = $("#benhead_select").val();
        var diagnosis_code = $("#diagnosis_code").val();
        var rel_to_hosp = "";
        var rel_to_hosp_claim_no = "";
        var rel_to_hosp_line_no = "";
        var use_date = 0;
        var use_amt_surg = 0 ;
        var pre_amt_provider = "";
        if($("#cl_type").val().length == 0)
        {
            alert('Vui Lòng Chọn Loại Hồ Sơ');
            return 1;
        }else{
            if($("#cl_type").val() == 'P'){
                if($("#gop_present_amt").val().length == 0){
                    alert('vui lòng nhập Số tiền bệnh viện yêu cầu chi trả');
                    return 1;
                }
            }
        }
        if(jQuery.isEmptyObject(respon_hol) == false){
            rel_to_hosp = $("#rel_to_hosp").val();
            rel_to_hosp_claim_no = $("#rel_to_hosp_claim_no").val();
            rel_to_hosp_line_no = $("#rel_to_hosp_line_no").val();
            console.log(rel_to_hosp,rel_to_hosp_claim_no,rel_to_hosp_line_no);
            if(rel_to_hosp == "Y"){
                if(rel_to_hosp_claim_no.length == 0 || rel_to_hosp_line_no.length == 0){
                    alert("Vui lòng chọn Claim liên quan đến nhập viện");
                    return 1;
                }
                
                $.each( respon_hol, function( key, value ) {
                    if (key == rel_to_hosp_claim_no ) {
                        $.each( value, function( key2, value2 ) {
                            if (parseInt(value2.line_no) == parseInt(rel_to_hosp_line_no) ){
                                use_date =  value2.use_days;//days
                                use_amt_surg = value2.use_amt_surg; //use_amt_surg
                            }
                        });
                    }
                });
            }
        }
        
        if (plan_amt == 0){
            alert("Không tìm thấy thông tin member , Vui lòng chọn Member");
            return 1;
        }
        if(incur_date_to.length == 0 || incur_date_from.length == 0){
            alert("Vui lòng nhập  Incur Date From và Incur Date From");
            return 1;
        }else{
            var startDay = new Date(incur_date_from);
		    var endDay = new Date(incur_date_to);
            var millisBetween = startDay.getTime() - endDay.getTime();
            var days =  Math.round(Math.abs(millisBetween / (1000 * 3600 * 24)));//days
        }
        if(benhead_select.length == 0 ){
            alert("Vui lòng chọn  loại hình hỗ trợ");
            return 1;
        }else{
            switch (benhead_select) {
                case "CASH-SURG":
                    var treatment_group_1 =  $("#treatment_group_1").val();
                    man_rej_amt = null;
                    man_rej_code_1 = null;
                    if(treatment_group_1.length == 0){
                        alert("Vui lòng chọn Nhóm điều trị");
                        return 1;
                    }else{
                        var treatment_group = treatment_group_all.find(function(post, index) {
                            if(post.id == treatment_group_1)
                                return true;
                        });
                    }
                    var ben_head = treatment_group.code;
                    var ben_type = treatment_group.ben_type;
                    var pre_amt = (treatment_group.amt_times * plan_amt) > treatment_group.value_max ? treatment_group.value_max : (treatment_group.amt_times * plan_amt);
                    if(use_amt_surg == 0){
                        if((treatment_group.amt_times * plan_amt) > treatment_group.value_max){
                            html = "<p>Được Chi trả "+pre_amt+" Vì:</p>"
                            html += ("<p>Giới Hạn của "+treatment_group.desc_vn+" là: " + treatment_group.value_max +"</p>");
                        }
                    }else if (use_amt_surg >= pre_amt){
                        pre_amt = 0;
                        html = "<p>Không được chi trả Vì:</p>"
                        html += ("<p>Đã được chi trả trước đó tại claim số" + rel_to_hosp_claim_no +"</p>");
                        man_rej_code_1 = "M070";
                        man_rej_amt = use_amt_surg;

                    }else if ((treatment_group.amt_times * plan_amt) > treatment_group.value_max){
                        html = "<p>Được Chi trả "+(pre_amt - use_amt_surg) +" Vì:</p>"
                        html +=("<p>Giới Hạn của "+treatment_group.desc_vn+" là: " + treatment_group.value_max +"</p>");
                        html +=("<p>Đã được chi trả trước đó tại claim số " + rel_to_hosp_claim_no + " là " + use_amt_surg+"</p>");
                        man_rej_code_1 = "M070";
                        man_rej_amt = use_amt_surg;
                    }else{
                        html = "<p>Được Chi trả "+(pre_amt - use_amt_surg) +" Vì:</p>"
                        html += ("<p>Đã được chi trả trước đó tại claim số " + rel_to_hosp_claim_no + " là " + use_amt_surg+"</p>");
                        man_rej_code_1 = "M070";
                        man_rej_amt = use_amt_surg;
                    }
                    $("#csr_remark").html(html);
                    addInputItem(ben_type , ben_head, pre_amt , incur_date_from, 
                    incur_date_to, diagnosis_code,actual_incur_date_to, rel_to_hosp ,
                    rel_to_hosp_claim_no, rel_to_hosp_line_no,man_rej_amt,man_rej_code_1);
                    break;
                case "CASH-ICU":
                case "CASH-PA" :
                case "CASH-INCUR":
                    var treatment_group_2 =  $("#treatment_group_2").val();
                    var HbsBenhead_c = HbsBenhead.find(function(post, index) {
                            if(post.code == benhead_select)
                                return true;
                        });
                    if(treatment_group_2.length == 0){
                        alert("Vui lòng chọn Nhóm điều trị");
                        return 1;
                    }else{
                        var treatment_group = treatment_group_all.find(function(post, index) {
                            if(post.id == treatment_group_2)
                                return true;
                        });
                    }

                    var choice_days = 0
                    var type_mess = 1;
                    if(HbsBenhead_c.max_days){
                        choice_days = HbsBenhead_c.max_days >= treatment_group.value_max ? treatment_group.value_max - use_date : HbsBenhead_c.max_days - use_date;
                        type_mess = HbsBenhead_c.max_days >= treatment_group.value_max ? 1 : 2;
                    }else{
                        choice_days = treatment_group.value_max - use_date;
                        var type_mess = 1;
                    }
                    
                    if(days > choice_days){
                        
                        incur_date_change = startDay.getTime() + (choice_days)*1000 * 3600 * 24;
                        d = new Date(incur_date_change);
                        incur_date_to = d.getFullYear()  + "-" + ("0"+(d.getMonth()+1)).slice(-2) + "-" +  ("0" + d.getDate()).slice(-2);
                        var html = " <p>Thời gian điều trị: "+ incur_date_from +" đến " + actual_incur_date_to +" là: " +days+" Ngày</p>";
                        if (type_mess == 2){
                            html += "<p>Được Chi trả "+choice_days+" Ngày Vì:</p>"
                                + "<p> Giới Hạn của "+ $("#benhead_select option:selected").text() +" là: " + HbsBenhead_c.max_days +" Ngày</p>" ;
                            html += use_date == 0 ? "" : "<p> Đã sử dụng " +use_date+ " ngày</p>";
                        }else{
                            html += "<p>Được Chi trả "+choice_days+" Ngày Vì:</p><p> Giới Hạn của "+ $("#treatment_group_2 option:selected").text() +" là: " + treatment_group.value_max  +" Ngày</p>";
                            html += use_date == 0 ? "" : "<p> Đã sử dụng " +use_date+ " ngày</p>";
                        }
                        
                        $("#csr_remark").html(html);
                    }else{
                        choice_days = days
                    }
                    pre_amt_imis = choice_days * plan_amt;
                    addInputItem("IP" , "CASH-IMIS", pre_amt_imis , incur_date_from, incur_date_to ,diagnosis_code,actual_incur_date_to,rel_to_hosp ,  rel_to_hosp_claim_no , rel_to_hosp_line_no);
                    
                    var ben_head = benhead_select;
                    var ben_type = "IP";
                    var pre_amt = choice_days * plan_amt * HbsBenhead_c.amt_times;
                    addInputItem(ben_type , ben_head, pre_amt , incur_date_from, incur_date_to ,diagnosis_code,actual_incur_date_to, rel_to_hosp ,  rel_to_hosp_claim_no , rel_to_hosp_line_no);
                    break;   
                default:
                    var treatment_group_2 =  $("#treatment_group_2").val();
                    var HbsBenhead_c = HbsBenhead.find(function(post, index) {
                            if(post.code == benhead_select)
                                return true;
                        });
                    if(treatment_group_2.length == 0){
                        alert("Vui lòng chọn Nhóm điều trị");
                        return 1;
                    }else{
                        var treatment_group = treatment_group_all.find(function(post, index) {
                            if(post.id == treatment_group_2)
                                return true;
                        });
                    }
                    var choice_days = 0
                    if(days > (treatment_group.value_max - use_date) ){
                        choice_days = treatment_group.value_max - use_date;
                        incur_date_change = startDay.getTime() + (choice_days-1)*1000 * 3600 * 24;
                        d = new Date(incur_date_change);
                        incur_date_to = d.getFullYear()  + "-" + ("0"+(d.getMonth()+1)).slice(-2) + "-" +  ("0" + d.getDate()).slice(-2);
                        var html = " <p>Thời gian điều trị: "+ incur_date_from +" đến " + actual_incur_date_to +" là: " +days+" Ngày</p>";
                        html += "<p>Được Chi trả "+choice_days+" Ngày Vì:</p><p> Giới Hạn của "+ $("#treatment_group_2 option:selected").text() +" là: " + treatment_group.value_max  +" Ngày</p>";
                        html += use_date == 0 ? "" : "<p> Đã sử dụng " +use_date+ " ngày</p>";
                        $("#csr_remark").html(html);
                    }else{
                        choice_days = days
                    }
                    
                    var ben_head = benhead_select;
                    var ben_type = "IP";
                    var pre_amt = choice_days * plan_amt * HbsBenhead_c.amt_times;
                    addInputItem(ben_type , ben_head, pre_amt , incur_date_from, incur_date_to, diagnosis_code,actual_incur_date_to, rel_to_hosp ,  rel_to_hosp_claim_no , rel_to_hosp_line_no);
                    break;
            }
        }
        

    }
    function diag_code(){
        $('.diag_code').select2({          
            minimumInputLength: 2,
            ajax: {
            url: "/admin/dataAjaxHBSDiagnosis",
                dataType: 'json',
                data: function (params) {
                    return {
                        q: $.trim(params.term)
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
    }
    var count = 1;
    function addInputItem(ben_type = null, ben_head = null, pre_amt = null , incur_date_from = null , 
    incur_date_to = null ,diag_code = null , actual_incur_date_to = null,
    rel_to_hosp = null, rel_to_hosp_claim_no = null , rel_to_hosp_line_no = null,
    man_rej_amt = null ,man_rej_code_1 = null){
        let clone =  '<tr id="row-'+count+'">';
        clone +=  $("#clone_item").clone().html() + '</tr>';
        //repalace name
        clone = clone.replace("_incur_date_from", "incur_date_from["+count+"]");
        clone = clone.replace("_incur_date_to", "incur_date_to["+count+"]");
        clone = clone.replace("_actual_incur_date_to", "actual_incur_date_to["+count+"]");
        clone = clone.replace("_ben_type", "ben_type["+count+"]");
        clone = clone.replace("_rel_to_hosp", "rel_to_hosp["+count+"]");
        clone = clone.replace("_rel_to_hosp_line_no", "rel_to_hosp_line_no["+count+"]");
        clone = clone.replace("_rel_to_hosp_claim_no", "rel_to_hosp_claim_no["+count+"]");
        clone = clone.replace("_ben_head", "ben_head["+count+"]");
        clone = clone.replace("_diag_code", "diag_code["+count+"]");
        clone = clone.replace("_pre_amt", "pre_amt["+count+"]");
        clone = clone.replace("_inv", "inv["+count+"]");
        clone = clone.replace("_man_rej_amt", "man_rej_amt["+count+"]");
        clone = clone.replace("_man_rej_code_1", "man_rej_code_1["+count+"]");
        clone = clone.replace("_pre_amt_provider", "pre_amt_provider["+count+"]");
        $("#clone_item").before(clone);
        loadDatepicker();
        var pre_amt_provider = "";
        if($("#cl_type").val() == 'P'){
            pre_amt_provider = $("#gop_present_amt").val();
        }
        $('input[name="pre_amt_provider['+count+']"]').val(pre_amt_provider);
        if(man_rej_amt){
            $('input[name="man_rej_amt['+count+']"]').val(man_rej_amt);
        }
        if(man_rej_code_1){
            $('select[name="man_rej_code_1['+count+']"]').val(man_rej_code_1).change();
        }
        if(rel_to_hosp){
            $('input[name="rel_to_hosp['+count+']"]').val(rel_to_hosp);
        }
        if(rel_to_hosp_claim_no){
            $('input[name="rel_to_hosp_claim_no['+count+']"]').val(rel_to_hosp_claim_no);
        }
        if(rel_to_hosp_line_no){
            $('input[name="rel_to_hosp_line_no['+count+']"]').val(rel_to_hosp_line_no);
        }

        $('input[name="pre_amt['+count+']"]').addClass('pre_amt');
        if(pre_amt){
            $('input[name="pre_amt['+count+']"]').val(pre_amt);
        }
        $('input[name="inv['+count+']"]').addClass('inv');
        $('input[name="man_rej_amt['+count+']"]').addClass('man_rej_amt');
        $('select[name="ben_type['+count+']"]').addClass('ben_type').select2();
        if(ben_type){
            $('select[name="ben_type['+count+']"]').val(ben_type).change();
        }
        $('select[name="ben_head['+count+']"]').addClass('ben_head').select2();
        if(ben_head){
            $('select[name="ben_head['+count+']"]').val(ben_head).change();
        }
        if(incur_date_from){
            $('input[name="incur_date_from['+count+']"]').val(incur_date_from);
        }
        if(incur_date_to){
            $('input[name="incur_date_to['+count+']"]').val(incur_date_to);
        }
        if(actual_incur_date_to){
            $('input[name="actual_incur_date_to['+count+']"]').val(actual_incur_date_to);
        }
        $('select[name="man_rej_code_1['+count+']"]').addClass('man_rej_code_1').select2();
        $('select[name="diag_code['+count+']"]').addClass('diag_code').select2({          
            minimumInputLength: 2,
            ajax: {
            url: "/admin/dataAjaxHBSDiagnosis",
                dataType: 'json',
                data: function (params) {
                    return {
                        q: $.trim(params.term)
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: false
            }
        });
        if(diag_code){
            $('select[name="diag_code['+count+']"]').val(diag_code).trigger('change');
        }
        count++;
    }
    //btn delete table item 
    $(document).on("click", ".delete_btn", function(){
        $(this).closest('tr').remove();
    });

    function encrypt(data, keyString) {
        var IV = CryptoJS.lib.WordArray.random(16);
            // finds the SHA-256 hash for the keyString
        var Key = CryptoJS.SHA256(keyString);
        
        var val = CryptoJS.enc.Utf8.parse(JSON.stringify(data));
        var encrypted = CryptoJS.AES.encrypt(val, Key, { iv: IV }).toString();
        var b64 = CryptoJS.enc.Base64.parse(encrypted).toString(CryptoJS.enc.Hex);
        return {"encrypted" : b64 , "IV" : IV.toString()};
    }

    function decrypt(data , IV, keyString) {
        var IV = CryptoJS.enc.Hex.parse(IV);
         // finds the SHA-256 hash for the keyString
        var Key = CryptoJS.SHA256(keyString);
        
        data = CryptoJS.enc.Hex.parse(data).toString(CryptoJS.enc.Base64);
        var cipherParams = CryptoJS.lib.CipherParams.create({
             ciphertext: CryptoJS.enc.Base64.parse(data)
        });
        var decryptedFromText = CryptoJS.AES.decrypt(cipherParams, Key, { iv: IV});
        return decryptedFromText.toString(CryptoJS.enc.Utf8);
    }
    
    function callapihbs(trial){
        $(".loader").show();
        var ben_type = [];
        $( ".ben_type" ).each(function( index ) {
            ben_type.push($( this ).val());
        });
        var ben_head = [];
        $( ".ben_head" ).each(function( index ) {
            ben_head.push($( this ).val());
        });
        var diag_code = [];
        $( ".diag_code" ).each(function( index ) {
            diag_code.push($( this ).val());
        });
        var pre_amt = [];
        $( ".pre_amt" ).each(function( index ) {
            sumbe = parseInt(removeFormatPrice($( this ).val() == '' ? 0 : $( this ).val()));
            pre_amt.push(sumbe);
        });
        var inv = [];
        $( ".inv" ).each(function( index ) {
            inv.push($( this ).val());
        });

        var man_rej_amt = [];
        $( ".man_rej_amt" ).each(function( index ) {
            sumbe = parseInt(removeFormatPrice($( this ).val() == '' ? 0 : $( this ).val()));
            man_rej_amt.push(sumbe);
        });

        var man_rej_code_1 = [];
        $( ".man_rej_code_1" ).each(function( index ) {
            man_rej_code_1.push($( this ).val());
        });

        var rel_to_hosp = [];
        $( ".rel_to_hosp" ).each(function( index ) {
            rel_to_hosp.push($( this ).val());
        });

        var rel_to_hosp_claim_no = [];
        $( ".rel_to_hosp_claim_no" ).each(function( index ) {
            rel_to_hosp_claim_no.push($( this ).val());
        });

        var rel_to_hosp_line_no = [];
        $( ".rel_to_hosp_line_no" ).each(function( index ) {
            rel_to_hosp_line_no.push($( this ).val());
        });

        var barcode = $( "#barcode" ).val();
        var cl_lines = [];
        var provider_pres_amt = 0;
        if($("#cl_type").val() == 'P'){
                provider_pres_amt = $("#gop_present_amt").val();
        }
        $.each( ben_type, function( key, value ){
            
            cl_lines.push(
                {
                    "db_ref_no": trial == 'Y' ? "9099999" : barcode,
                    "cl_type": $("#cl_type").val(),
                    "incur_date_from":  $( "#incur_date_from" ).val(),
                    "incur_date_to": $( "#incur_date_to" ).val(),
                    "prov_code": $( "#prov_code" ).val(),
                    "prov_name": $( "#prov_name option:selected" ).text(),
                    "ben_type": value,
                    "ben_head": ben_head[key],
                    "diag_code": diag_code[key],
                    "cl_inv_ref": inv[key],
                    "treat_country": "084",
                    "pres_ccy": "VND",
                    "exchange_rate": "1",
                    "pres_ori_amt": pre_amt[key],
                    "pres_amt": pre_amt[key],
                    "man_rej_amt": man_rej_amt[key],
                    "man_rej_code_1": man_rej_code_1[key],
                    "csr_remark": "",
                    "police_report": "",
                    "death_cert": "",
                    "post_motum_report": "",
                    "incident_country": "084",
                    "residence_country": "084",
                    "incident_date":  $( "#incur_date_from" ).val(),
                    "trip_date_from":  $( "#incur_date_from" ).val(),
                    "trip_date_to":  $( "#incur_date_from" ).val(),
                    "popl_oid": $("#popl_oid").val(),
                    "rel_to_hosp": rel_to_hosp[key],
                    "rel_to_hosp_claim_no": rel_to_hosp_claim_no[key],
                    "rel_to_hosp_line_no": rel_to_hosp_line_no[key],
                    "provider_pres_amt" : key == 0 ? provider_pres_amt : 0,
                }
            );
        });
        var data_hb = {
            "pocy_no": $( "#pocy_no" ).val(),
            "mbr_no": $( "#mbr_no" ).val(),
            "trial": trial,
            "rcv_date": $( "#rcv_date" ).val(),
            "cl_lines": cl_lines,
            "crt_user": "{{ Auth::user()->name }}",
            "barcode": trial == 'Y' ? "9099999" : barcode,
            "csr_remark": $('#csr_remark').text(),
        };
        console.log(data_hb);
        var keyString = "{{config('constants.key_string_hbs')}}";
        var data_s = encrypt(data_hb , keyString);
        
        axios.post("/admin/sendClaimHBS",{
            'encrypted' : data_s.encrypted ,
            'IV' : data_s.IV,
        })
        .then(function (response) {
            var decrypt_d = decrypt(response.data.encrypted, response.data.IV, keyString);
            var data_j = JSON.parse(decrypt_d);
            console.log(data_j);
            var reason_rej = "";
            $.each( data_j.lineRejections, function( key, value ) {
                reason_rej += "Line " + value.lineNo + ": " ;
                $.each( value.systemRejectCodes, function( key2, value2 ) {
                    reason_rej += "<p class='text-warning'>" + value2.code + " - " + value2.desc + "</p>";
                });
            });
            
            swal({
                title: data_j.message, 
                html: true,
                text:
                "Tổng số tiền yêu cầu bồi thường: <p class='font-weight-bold text-success'>"+formatPrice(data_j.pre_amt)+ "</p>" +
                "Tổng số tiền Được Chấp nhận:  <p class='font-weight-bold text-danger'>"+formatPrice(data_j.app_amt)+ "</p>" +
                "Nguyên Nhân Từ chối (nếu có): <br />" + reason_rej
                ,  
                allowOutsideClick: "true" 
            });
            if (typeof data_j.cl_no != "undefined") {
                axios.post("/admin/createClaimDB",{
                    'cl_no' : data_j.cl_no ,
                    'barcode' : barcode,
                    'claim_type' : $("#cl_type").val(),
                }).then(function (response) {
                    var rp_data = response.data;
                    if (typeof rp_data.claim.id != "undefined"){
                        window.location.replace("/admin/claim/"+rp_data.claim.id);
                    }
                    $(".loader").fadeOut("slow");
                })
            }else{
                $(".loader").fadeOut("slow");
            }
            
        })
        .catch(function (error) {
            
            var decrypt_d = decrypt(error.response.data.encrypted, error.response.data.IV, keyString);
            var invalidCodes = JSON.parse(decrypt_d).invalidCodes;
            var mss = "";
            $.each(invalidCodes, function( index, value ) {
                mss += "Line" + value.line + ": " +value.message +"; ";
            });
            swal("Fail!!!!!", mss, "error");
            $(".loader").fadeOut("slow");
        });

    }

    function prov_name_map(e){
        $('#prov_code').val(e.value);
    }
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var fileCSV;
    $('#fileUpload').fileinput({
        required: false,
        allowedFileExtensions: ['pdf']
    }).on("filebatchselected", function(event, files) {
        fileCSV = files[0];
    });

    function btnScan(){
        if (typeof fileCSV === 'undefined') {
            alert('Please enter file');
        }else{
            $( "#dvExcel" ).empty();
            $(".loader").show();;
            var formData = new FormData();
            formData.append("upload", fileCSV);
            axios.post("/admin/ocrClaim",formData,{
                headers: {
                'Content-Type': 'multipart/form-data'
                }
            }).then(function (response) {
                    var rp_data = response.data.data;
                    $('#dvExcel').append(arrayToTable(rp_data));
                    $(".loader").fadeOut("slow");
                }).catch(function (error) {
                    swal("Fail!!!!!", error.message, "error");
                    $(".loader").fadeOut("slow");
                });
            
        } 
    }
    function search_member(){
        tableo.clear().draw();
        $('#view_resurt').show();
        $(".loader").show();
        var mbr_ref_no = $( "#mbr_ref_no_s" ).val();
        var pocy_ref_no = $( "#pocy_ref_no_s" ).val();
        var mbr_name = $( "#mbr_name_s" ).val();
        var mbr_dob = $( "#mbr_dob_s" ).val();
       
        axios.post("/admin/searchMember",{
                'memb_ref_no' : mbr_ref_no,
                'pocy_ref_no' : pocy_ref_no,
                'mbr_name' : mbr_name,
                'dob' : mbr_dob
        })
        .then(function (response) {
            $(".loader").fadeOut("slow");
            console.log( response.data);
            $.each( response.data.data, function( key, value ) {
                tableo.row.add([
                    value.memb_oid,
                    value.mbr_name.trim(),
                    value.dob.substr(0, 10),
                    value.pocy_ref_no,
                    value.memb_ref_no,
                    value.poho_name.trim()
                ]).draw(false);
            });
            
        })
        .catch(function (error) {
            $(".loader").fadeOut("slow");
            console.log(error.message);
            swal({
                title: "Thất bại", 
                html: true,
                text: error.response.data.join("<br> "),  
                allowOutsideClick: "true" 
            });

            
        });
        
    }

    function arrayToTable(tableData) {
        var sum_amt = 0;
        var table = $('<table class="table table-striped header-fixed"></table>');
        row = $('<tr></tr>');
        row.append('<th></th>')
            .append("<th>Code</th>")
            .append('<th>Nội dung</th>')
            .append('<th>Thành tiền</th>')
            .append('<th>Ben Head</th>')
            .append('<th>Ben Type</th>')
        table.append(row);

        //option select field
        $(tableData).each(function (k, rowDatas) {
            $(rowDatas).each(function (i, rowData) {
                sum_amt += parseInt(removeFormatPrice(rowData.value));
                var row = $('<tr></tr>');
                    row.append($('<td><input class = "checkbox_class resize-checkbox" type="checkbox"  data-id = "'+k+'_'+i+'" />'))
                    row.append($('<td>'+rowData.code+'</td>'));
                    //content
                    var content = $('<td><input id="item_content'+k+'_'+i+'" class ="form-control" name = "" data-id= "'+k+'_'+i+'" value = "'+rowData.text+'" /></td>');
                    if(rowData.percent == 100){
                        content = content.append("<span class='badge badge-pill badge-primary'>"+rowData.percent+" %</span>");
                    }else if(rowData.percent != 0){
                        content = content.append($("<p> "+rowData.desc+" </p>").append("<span class='badge badge-pill btn-warning'>"+rowData.percent+" %</span>"));
                    }
                    
                    row.append(content);
                    row.append($('<td><input id="item_value'+k+'_'+i+'" class ="form-control item_value" name = "" data-id= "'+k+'_'+i+'" value = "'+rowData.value+'" /></td>'));
                    row.append($('<td><input id="benhead'+k+'_'+i+'" class ="form-control item_benhead" name = "" data-id= "'+k+'_'+i+'" value = "'+rowData.benhead+'" /></td>'));
                    row.append($('<td><input id="bentype'+k+'_'+i+'" class ="form-control item_bentype" name = "" data-id= "'+k+'_'+i+'" value = "'+rowData.bentype+'" /></td>'));
                    table.append(row);
                    
            });
                
        }); 
        row = $('<tr></tr>');
        row.append('<th></th>')
            .append("<th>Tổng Cộng</th>")
            .append('<th></th>')
            .append('<th>'+formatPrice(sum_amt)+'</th>')
            .append('<th></th>')
            .append('<th></th>');
        table.append(row);       
        return table;
    }
    function checkAll(e){
        $(".checkbox_class").prop("checked", e.checked);
    }
    function clickGo(){
        
        var value_benhead = $("#select-benhead-default option:selected").val();
        var value_bentype = $("#select-bentype-default option:selected").val();
        var arrElementcheck = $('.checkbox_class');
        $.each(arrElementcheck, function (index, value) {
            var id = value.dataset.id;
            if(value.checked){           
                $('#benhead'+id).val(value_benhead);
                $('#bentype'+id).val(value_bentype);
            }
        });
        $('.checkbox_class, .form-check-input').attr('checked', false);
    };

    function InsertClaimLine(){
        var data_get = {};
        var mess = "";
        $(".item_bentype").each(function(index, value) {
            var id = value.dataset.id;
            var content = $('#item_content'+id).val();
            var ben_type = $('#bentype'+id).val();
            var ben_head = $('#benhead'+id).val();
            var item_value = removeFormatPrice($('#item_value'+id).val());
            var string_key = ben_type+"#"+ben_head;
            if(data_get.hasOwnProperty(string_key)){
                data_get[string_key] += parseInt(item_value);
            }else{
                data_get[string_key] = parseInt(item_value);
            }
            if(ben_head === "" || ben_type === "" || item_value === ""){
                mess += "Dữ liệu dòng :" + content + "chưa hợp lệ <br />";
            }
            
        });
        
        if(mess != ""){
            swal({
                title: "error", 
                html: true,
                text: mess,  
                allowOutsideClick: "true" 
            })
            return 1;
        }

        for (const key in data_get) {
            console.log(`${key}: ${data_get[key]}`);
            myArray = key.split("#");
            addInputItem(myArray[0], myArray[1],data_get[key] );
        }
        
    }
</script>
@endsection
