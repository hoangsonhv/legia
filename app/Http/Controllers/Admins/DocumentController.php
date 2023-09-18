<?php

namespace App\Http\Controllers\Admins;

use App\Helpers\PermissionHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Repositories\DocumentRepository;
use App\Models\Admin;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    private $disk = 'local';
    /**
     * @var
     */
    protected $docRepo;
    /**
     * @var array
     */
    public $menu;

    function __construct(DocumentRepository $docRepo)
    {
        $this->docRepo = $docRepo;
        $this->menu = [
            'root' => 'Quản lý Chứng từ',
            'data' => [
                'parent' => [
                    'href' => route('admin.documents.index'),
                    'label' => 'Chứng từ'
                ]
            ]
        ];
    }

    public function index(Request $request)
    {
        $breadcrumb = $this->menu;
        $breadcrumb['data']['list'] = ['label' => 'Danh sách'];
        $titleForLayout = $breadcrumb['data']['list']['label'];
        $params = array();
        $limit = 10;

        // search
        if ($request->has('key_word')) {
            $params['key_word'] = $request->key_word;
        }

        $user = Session::get('login');
        if (!PermissionHelper::hasPermission('admin.documents.index-all')) {
            $params['admin_id'] = $user->id;
        }

        $datas = $this->docRepo->findExtend($params)->orderBy('id', 'DESC')->paginate($limit);
        $request->flash();
        return view('admins.document.index', compact('breadcrumb', 'titleForLayout', 'datas'));
    }

    public function create(Request $request)
    {
        $breadcrumb = $this->menu;
        $breadcrumb['data']['list'] = ['label' => 'Thêm mới'];
        $titleForLayout = $breadcrumb['data']['list']['label'];
        $permissions = config('permission.permissions');
        return view('admins.document.create', compact('breadcrumb', 'titleForLayout', 'permissions'));
    }

    public function store(DocumentRequest $request)
    {
        $input = $request->input();
        $input['admin_id'] = Session::get('login')->id;
        try {
            \DB::beginTransaction();
            $files = $request->file('file');
            $paths = [];
            if ($files) {
                $path = 'uploads/documents/documents';
                foreach ($files as $file) {
                    $fileSave = Storage::disk($this->disk)->put($path, $file);
                    if (!$fileSave) {
                        if ($paths) {
                            foreach ($paths as $path) {
                                Storage::disk($this->disk)->delete($path);
                            }
                        }
                        return redirect()->back()->withInput()->with('error', 'File upload bị lỗi! Vui lòng kiểm tra lại file.');
                    }
                    $paths[] = ['name' => $file->getClientOriginalName(), 'path' => $fileSave];
                }
            }
            $input['path'] = json_encode($paths);
            $this->docRepo->insert($input);
            \DB::commit();
            return redirect()->route('admin.documents.index')->with('success', 'Tạo chứng từ thành công!');
        } catch (\Exception $ex) {
            \DB::rollback();
            report($ex);
        }
        return redirect()->route('admin.documents.index')->with('error', 'Tạo chứng từ thất bại!');
    }

    public function edit(Request $request, $id)
    {
        $breadcrumb = $this->menu;
        $breadcrumb['data']['list'] = ['label' => 'Cập nhật'];
        $titleForLayout = $breadcrumb['data']['list']['label'];
        $model = $this->docRepo->find($id);
        if ($model) {
            $user = Session::get('login');
            if (!PermissionHelper::hasPermission('admin.documents.index-all') && $model->admin_id != $user->id) {
                return redirect()->route('admin.manufacture.index')->with('error', 'Bạn không có quyền truy cập!');
            }
            $permissions = config('permission.permissions');
            return view('admins.document.edit', compact('breadcrumb', 'titleForLayout', 'model',
                'permissions'));
        }
        return redirect()->route('admin.documents.index')->with('error', 'Chứng từ không tồn tại!');
    }

    public function update(DocumentRequest $request, $id)
    {
        $model = $this->docRepo->find($id);
        if ($model) {
            $inputs = $request->input();
//            try {
                $files = $request->file('file');
                $paths = [];
                // Upload file
                if ($files) {
                    $path = 'uploads/documents/documents';
                    foreach ($files as $file) {
                        $fileSave = Storage::disk($this->disk)->put($path, $file);
                        if (!$fileSave) {
                            if ($paths) {
                                foreach ($paths as $path) {
                                    Storage::disk($this->disk)->delete($path);
                                }
                            }
                            return redirect()->back()->withInput()->with('error', 'File upload bị lỗi! Vui lòng kiểm tra lại file.');
                        }
                        $paths[] = ['name' => $file->getClientOriginalName(), 'path' => $fileSave];
                    }
                }
                $paths = array_merge(json_decode($model->path, true), $paths);
                $inputs['path'] = json_encode($paths);
                \DB::beginTransaction();
                $model = $this->docRepo->update($inputs, $id);
                \DB::commit();
                return redirect()->route('admin.documents.edit', ['id' => $id])->with('success', 'Cập nhật chứng từ thành công!');
//            } catch (\Exception $ex) {
//                \DB::rollback();
//                report($ex);
//            }
            return redirect()->back()->withInput()->with('error', 'Cập nhật chứng từ không thành công!');
        }
        return redirect()->back()->withInput()->with('error', 'Chứng từ không tồn tại!');
    }

    public function destroy($id)
    {
        $model = $this->docRepo->find($id);
        if ($model) {
            $model->delete();
            return redirect()->route('admin.documents.index')->with('success', 'Xóa chứng từ thành công!');
        }
        return redirect()->back()->with('error', 'Chứng từ không tồn tại!');
    }

    public function removeFile(Request $request)
    {
        try {
            $id = $request->input('id');
            $data = $this->docRepo->find($id);
            if ($data) {
                $files = json_decode($data->path, true);
                if ($files) {
                    $path = $request->input('path');
                    foreach ($files as $key => $file) {
                        if ($file['path'] === $path) {
                            Storage::disk($this->disk)->delete($file['path']);
                            unset($files[$key]);
                            $data->path = json_encode(array_values($files));
                            $data->save();
                            return ['success' => true];
                        }
                    }
                }
            }
        } catch (\Exception $ex) {
            report($ex);
        }
        return ['success' => false];
    }
}
