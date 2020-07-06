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
}
