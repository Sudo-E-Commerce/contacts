<?php

namespace Sudo\Contact\Http\Controllers;
use Sudo\Base\Http\Controllers\AdminController;

use Illuminate\Http\Request;
use ListData;
use Form;
use ListCategory;

class ContactController extends AdminController
{
    function __construct() {
        $this->models = new \Sudo\Contact\Models\Contact;
        $this->table_name = $this->models->getTable();
        $this->module_name = 'Liên hệ';
        $this->has_seo = false;
        $this->has_locale = false;
        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $requests) {
        $listdata = new ListData($requests, $this->models, 'Contact::table.index', $this->has_locale);

        $status = [
            1 => 'Chưa đọc',
            2 => 'Đã đọc',
        ];

        // Build Form tìm kiếm
        $listdata->search('subject', 'Tiêu đề', 'string');
        $listdata->search('name', 'Tên người gửi', 'string');
        $listdata->search('phone', 'Điện thoại', 'string');
        $listdata->search('email', 'Email', 'string');
        $listdata->search('detail', 'Nội dung', 'string');
        $listdata->search('created_at', 'Ngày tạo', 'range');
        $listdata->search('status', 'Trạng thái', 'array', $status);
        $listdata->searchBtn('Xuất Excel', route('admin.contacts.exports'), 'primary', 'fas fa-file-excel');
        // Build các button hành động
        $listdata->btnAction('status', 1, __('Chưa đọc'), 'info', 'fas fa-window-close');
        $listdata->btnAction('status', 2, __('Đã đọc'), 'success', 'fas fa-edit');
        $listdata->btnAction('delete', -1, __('Table::table.trash'), 'danger', 'fas fa-trash');
        // Build bảng
        $listdata->add('subject', 'Tiêu đề', 1);
        $listdata->add('name', 'Tên người gửi', 1);
        $listdata->add('phone', 'Điện thoại', 1);
        $listdata->add('email', 'Email', 1);
        $listdata->add('detail', 'Nội dung', 1);
        $listdata->add('', 'Thời gian', 0, 'time');
        $listdata->add('status', 'Trạng thái', 1, 'status', $status);
        $listdata->add('', 'Xóa', 0, 'delete');
        $listdata->no_add();

        return $listdata->render();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $requests)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $requests
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $requests, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function exports(Request $requests) {

        // Đưa mảng về các biến có tên là các key của mảng
        extract($requests->all(), EXTR_OVERWRITE);
        // Lấy dữ liệu được bắt theo bộ lọc
        $data_exports = $this->models::query();
        
        // Tiêu đề
        if (isset($subject) && $subject != '') {
            $data_exports = $data_exports->where('subject', 'LIKE', '%'.$subject.'%');
        }
        // Tên người gửi
        if (isset($name) && $name != '') {
            $data_exports = $data_exports->where('name', 'LIKE', '%'.$name.'%');
        }
        // Điên thoại
        if (isset($phone) && $phone != '') {
            $data_exports = $data_exports->where('phone', 'LIKE', '%'.$phone.'%');
        }
        // Email
        if (isset($email) && $email != '') {
            $data_exports = $data_exports->where('email', 'LIKE', '%'.$email.'%');
        }
        // Nội dung
        if (isset($detail) && $detail != '') {
            $data_exports = $data_exports->where('detail', 'LIKE', '%'.$detail.'%');
        }
        // lọc ngày
        if($created_at_end != '' && $created_at_start != '') {
            $data_exports = $data_exports->where('created_at','>',$created_at_start);
            $data_exports = $data_exports->where('created_at','<',$created_at_end);
        }
        // lọc trạng thái
        if (isset($status) && $status != '') {
            $data_query = $data_query->where('status',$status);
        }

        $data_exports = $data_exports->where('status', '<>', -1)->get();

        // Mảng export
        $data = [
            'file_name' => 'contacts-'.time(),
            'fields' => [
                __('Tiêu đề'),
                __('Tên người gửi'),
                __('Điện thoại'),
                __('Email'),
                __('Nội dung'),
                __('Thời gian'),
                __('Trạng thái'),
            ],
            'data' => [
                // 
            ]
        ];
        // Foreach lấy mảng data
        $status = [
            1 => 'Chưa đọc',
            2 => 'Đã đọc',
        ];
        foreach ($data_exports as $key => $value) {
            $data['data'][] = [
                $value->subject,
                $value->name,
                $value->phone,
                $value->email,
                $value->detaul,
                $value->getTime(),
                $status[$value->status] ?? '',
            ];
        }
        return \Excel::download(new \Sudo\Base\Export\GeneralExports($data), $data['file_name'].'.xlsx');
    }
}
