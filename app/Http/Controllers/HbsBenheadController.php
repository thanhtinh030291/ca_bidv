<?php

namespace App\Http\Controllers;
use App\User;
use Auth;
use App\HbsBenhead;
use App\Http\Requests\reasonInjectRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;


class HbsBenheadController extends Controller
{
    
    //use Authorizable;
    public function __construct()
    {
        $this->authorizeResource(HbsBenhead::class);
        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['search_params'] = [
            'name' => $request->get('name'),
            // 'created_user' => $request->get('created_user'),
            // 'created_at' => $request->get('created_at'),
            // 'updated_user' => $request->get('updated_user'),
            // 'updated_at' => $request->get('updated_at'),
        ];
        $HbsBenhead = HbsBenhead::findByParams($data['search_params'])->orderBy('id', 'desc');
        $data['admin_list'] = User::getListIncharge();
        //pagination result
        $data['limit_list'] = config('constants.limit_list');
        $data['limit'] = $request->get('limit');
        $per_page = !empty($data['limit']) ? $data['limit'] : Arr::first($data['limit_list']);
        $data['data']  = $HbsBenhead->paginate($per_page);
        return view('HbsBenheadManagement.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('HbsBenheadManagement.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(reasonInjectRequest $request)
    {
        $userId = Auth::User()->id;
        $data = $request->except([]);
        $data['created_user'] = $userId;
        $data['updated_user'] = $userId;

        HbsBenhead::create($data);
        $request->session()->flash('status', __('message.reason_inject_create_success')); 
        
        return redirect('/admin/HbsBenhead');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(HbsBenhead $HbsBenhead)
    {
        $data = $HbsBenhead;
        $userCreated = $data->userCreated->name;
        $userUpdated = $data->userUpdated->name;
        return view('HbsBenheadManagement.detail', compact('data', 'userCreated', 'userUpdated'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(HbsBenhead $HbsBenhead)
    {
        $data = $HbsBenhead;
        return view('HbsBenheadManagement.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(reasonInjectRequest $request, HbsBenhead $HbsBenhead)
    {
        $data = $request->except([]);
        $userId = Auth::User()->id;
        $data['updated_user'] = $userId;
        HbsBenhead::updateOrCreate(['id' => $HbsBenhead->id], $data);

        $request->session()->flash('status', __('message.reason_inject_update_success')); 
        return redirect('/admin/HbsBenhead');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(HbsBenhead $HbsBenhead)
    {
        $data = $HbsBenhead;
        $data->delete();
        return redirect('/admin/HbsBenhead')->with('status', __('message.reason_inject_delete_success'));
    }
}
