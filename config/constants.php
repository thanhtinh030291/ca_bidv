<?php
return[
    'appName' => 'Claim Assistant',
    'appEmail' => env('MAIL_FROM_ADDRESS', 'admin@pacificcross.com.vn'),
    'appLogo'     => "/images/logo.png",
    'formClaimUpload'   => '/public/formClaim/',
    'formClaimStorage'  => '/storage/formClaim/',
    'sortedClaimUpload'   => '/public/sortedClaim/',
    'sotedClaimStorage'  => '/storage/sortedClaim/',
    'company' => 'bidv',
    

    'apiKey' => "AIzaSyBTHTKBDMg9feCwbB5Mp9ceiR-kR3QFL3M",
    'authDomain' =>  "pacific-cross.firebaseapp.com",
    'projectId' =>  "pacific-cross",
    'storageBucket'=> "pacific-cross.appspot.com",
    'messagingSenderId' => "501542859634",
    'appId' =>  "1:501542859634:web:0274ffd7f050783f55a3eb",
    'measurementId' => "G-W2HN0MDWL3",
    'SERVER_API_KEY' =>'AAAAdMZIs3I:APA91bHdAWUe3CpIBaylmF_Wjpti56WBHuLLSml82c77D4sGr9rcupGgXtpCBxueXki88vO1BUaNk8cuJT9g6qH91fFtSLzvxh5dZstdLzi-94dSSDyqA4jebAB8uuYXVAooIwf9Dt7Y',

    'avantarUpload' => '/public/avantar/',
    'avantarStorage' => '/storage/avantar/',
    'signarureUpload' => '/public/signarure/',
    'signarureStorage' => '/storage/signarure/',
    'PUSHER_APP_KEY' => env('PUSHER_APP_KEY'),
    'PUSHER_APP_SECRET' => env('PUSHER_APP_SECRET'),
    'PUSHER_APP_ID' => env('PUSHER_APP_ID'),
    'PUSHER_APP_CLUSTER' => env('PUSHER_APP_CLUSTER'),
    'VAPID_PUBLIC_KEY' => env('VAPID_PUBLIC_KEY'),
    'mount_disk_hbs' => 'bshprod_hbs_report',
    'mount_dlvn' => "http://192.168.0.235/bshprod_hbs_report/",
    
    'attachUpload'   => '/public/attachEmail/',
    
    'paginator' => [
        'itemPerPage' => '10',
    ],
    'limit_list' => [
        10 => 10,
        20 => 20,
        30 => 30,
        40 => 40,
        50 => 50,
    ],
    'field_select' => [
        'content' => 'Content',
        'amount' => 'Amount',
    ],
    'percentSelect' => 70,

    'statusExport' => [
        'new' => 0,
        'edit' => 1,
        'note_save' => 2,
    ],
    'statusExportText' => [
        '0' => "New",
        '1' => 'Edit',
        '2' => 'Note Save',
    ],
    'link_mfile' => '192.168.0.235/mfile/public/api/',
    'account_mfile' => 'admin@pacificcross.com.vn',
    'pass_mfile' => '123456',
    'mode_mfile' => 'bsh',

    
    'token_mantic' => env("token_mantic",""),
    'url_mantic' => env("url_mantic",""),
    'url_mantic_api' => env("url_mantic_api",""),
    'url_cps' => env("url_cps",""),
    'api_cps' => env("api_cps",""),
    'client_id' => env("client_id",""),
    'client_secret' => env("client_secret",""),
    'url_hbs' => env("url_hbs",""),
    'url_mobile_api'  => env("url_mobile_api",""),
    'key_string_hbs' => "hbs1vn@mantis4156",

    'grant_type' => 'client_credentials',
    'url_query_online' => 'https://pcvwebservice.pacificcross.com.vn/bluecross/query_rest.php?id=',
    'claim_result' => [
        1 => 'FULLY PAID' ,
        2 => 'PARTIALLY PAID',
        3 => 'DECLINED' 
    ],
    'statusWorksheet' => [
        0 => 'Mặc Định',
        1 => 'Yêu Cầu Hỗ trợ MD',
        2 => 'Đã Giải Quyết'
    ],

    'notifiRoleMD' => 'Medical',
    'status_mantic_value' => [
        'accepted' => 81,
        'partiallyaccepted' =>82,
        'declined' => 83,
    ],
    'payment_method' =>[
        'TT' => 'Chuyển khoản qua ngân hàng',
        'CA' => 'Nhận tiền mặt tại ngân hàng',
        'CQ' => 'Nhận tiền mặt tại văn phòng',
        'PP' => 'Đóng phí bảo hiểm cho hợp đồng'
    ],
    'debt_type' =>[
        1 => 'nợ được đòi lại',
        2 => 'nợ nhưng đã cấn trừ qua Claim khác',
        3 => 'nợ nhưng khách hàng đã gửi trả lại',
        4 => 'nợ không được đòi lại',
    ],
    'tranfer_status' => [
        10	=> "DELETED",
        20	=> "NEW",
        30	=> "LEADER APPROVAL",
        50	=> "LEADER REJECTED",
        60	=> "MANAGER APPROVAL",
        80	=> "MANAGER REJECTED",
        90	=> "DIRECTOR APPROVAL",
        110	=> "DIRECTOR REJECTED",
        140	=> "DLVN CANCEL",
        145	=> "DLVN PAYPREM",
        150	=> "APPROVED",
        160	=> "SHEET",
        165	=> "SHEET PAYPREM",
        170	=> "SHEET DLVN CANCEL",
        175	=> "SHEET DLVN PAYPREM",
        180	=> "TRANSFERRING",
        185	=> "TRANSFERRING PAYPREM",
        190	=> "TRANSFERRING DLVN CANCEL",
        195	=> "TRANSFERRING DLVN PAYPREM",
        200	=> "TRANSFERRED",
        205	=> "TRANSFERRED PAYPREM",
        210	=> "TRANSFERRED DLVN CANCEL",
        215	=> "TRANSFERRED DLVN PAYPREM",
        216	=> "RETURNED TO CLAIM",
        220	=> "DLVN CLOSED",
    ],
    'claim_type'=>[
        'M' => '(Member)',
        'P' => '(GOP)',
    ],
    'status_request_gop_pay' => [
        'request' => 'Đang đợi xác nhận',
        'accept'  => 'Đã được xác nhận',
        'reject'  => 'Đã bị từ chối',
    ],
    'category_bug' => [
        'Claim' => 15,
        'MCP_Claim' => 16,
        'CS_Claim' => 17
    ],
    'not_provider' => [
        '2095143'
    ],
    'gop_type' =>
    [
        0 => "Accepted: GOP acceptance letter is attached (Chấp nhận: Thư bảo lãnh viện phí được gửi đính kèm)",
        1 => "Client can Pay and Claim (Khách hàng tự thanh toán và gửi hồ sơ yêu cầu bồi thường cho công ty)",
        2 => "Treatment not Covered (Điều trị không được bảo hiểm)"
    ],

    'status_mantic' =>[
        10 => 'new' ,
        11 => 'reopen',
        12 => 'mcp_new', 
        13 => 'new_comment',
        14 => 'pending',
        16 => 'ask_pocy_status',
        20 => 'feedback',
        21 => 'gop_request',
        22 => 'gop_initial_approval',
        23 => 'gop_wait_doc',
        30 => 'acknowledged',
        40 => 'confirmed',
        50 => 'assigned',
        60 => 'open',
        65 => 'mcp_info_request',
        66 => 'mcp_add_doc',
        67 => 'mcp_doc_sufficient',
        68 => 'mcp_hc_received',
        69 => 'mcp_hc_request',
        70 => 'inforequest',
        71 => 'inforequest_review',
        72 => 'inforequest_revised',
        73 => 'inforeceived',
        74 => 'investrequest',
        75 => 'askpartner',
        78 => 'readytosend',
        79 => 'readyforprocess',
        80 => 'resolved',
        81 => 'accepted',
        82 => 'partiallyaccepted',
        83 => 'declined',
        84 => 'answered',
        90 => 'closed',
        91 => 'mcp_closed'
    ],
    'status_mantic_value' => [
        'accepted' => 81,
        'partiallyaccepted' =>82,
        'declined' => 83,
        'inforequest' => 70,
    ],
    'invoice_type' => [
        'original_invoice' => 'Hóa đơn góc',
        'e_invoice' => 'Hóa đơn điện tử',
        'converted_invoice' => 'Hóa đơn đã chuyển đổi',
    ],

    'benhead' => [
        'vis_yr' => "ngày/năm",
        'vis_day' => "lần/mỗi Ngày",
        'amt_vis' => "đồng/mỗi lần thăm khám",
        'amt_yr' => "đồng/năm",
        'amt_dis_yr' => "đồng/mỗi bệnh/năm",
        'amt_life' => "đồng/trọn đời",
        'day_dis_yr' => "ngày/mỗi bệnh/năm",
        'amt_day' => "đồng/ngày",
        'amt_dis_vis' => "đồng/mỗi bệnh/mỗi lần",
        'amt_dis_life' => "đồng/mỗi bệnh/trọn đời",
    ],

    'benhead_en' => [
        'vis_yr' => "day/year",
        'vis_day' => "time/everyday",
        'amt_vis' => "dong/visit",
        'amt_yr' => "dong/year",
        'amt_dis_yr' => "dong/diagnose/year",
        'amt_life' => "dong/life",
        'day_dis_yr' => "day/diagnose/year",
        'amt_day' => "dong/day",
        'amt_dis_vis' => "dong/diagnose/time",
        'amt_dis_life' => "dong/diagnose/life",
    ],
    
    'plan_amt' =>[
        '0001' => 200000,
        '0002' => 500000,
        '0003' => 1000000,
        '0004' => 2000000,
    ]

];