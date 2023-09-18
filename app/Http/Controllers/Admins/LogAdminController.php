<?php 
namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Models\Repositories\LogAdminRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogAdminController extends Controller
{
	protected $logAdminRepository;

	public $menu;

	function __construct(LogAdminRepository $logAdminRepository)
	{
		$this->logAdminRepository = $logAdminRepository;
		$this->menu               = [
			'root' => 'Log hệ thống',
			'data' => [
				'parent' => [
					'href'   => route('admin.logadmin.index'),
					'label'  => 'Log hệ thống'
				]
			]
		];
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		$breadcrumb                 = $this->menu;
		$breadcrumb['data']['list'] = ['label'  => 'Danh sách'];
		$titleForLayout             = $breadcrumb['data']['list']['label'];
		$limit                      = 10;
		$params                     = [];
		// search
		if($request->has('key_word')) {
			$params['key_word'] = $request->key_word;
		}

		$logadmins = $this->logAdminRepository->getLogAdmins($params)->orderBy('id','DESC')->paginate($limit);
		$request->flash();
		return view('admins.log-admins.index', compact('breadcrumb', 'titleForLayout', 'logadmins'));
	}

	/**
	 * Show data from ID.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$breadcrumb                 = $this->menu;
		$breadcrumb['data']['show'] = ['label'  => 'Hiển thị chi tiết'];
		$titleForLayout             = $breadcrumb['data']['show']['label'];
		$logadmin                   = $this->logAdminRepository->find($id);
		if (empty($logadmin)) {
			return redirect()->back()->with('error', 'Log không tồn tại!');
		}
		return view('admins.log-admins.show', compact('breadcrumb', 'titleForLayout', 'logadmin'));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$logadmin = $this->logAdminRepository->find($id);
		if (empty($logadmin)) {
			return redirect()->back()->with('error', 'Log không tồn tại!');
		}
		if (!$logadmin->delete()) {
			return redirect()->back()->with('error', 'Xóa log thất bại!');
		}
		return redirect()->back()->with('success', 'Đã xóa log thành công!');
	}

	public function destroyMul(Request $request)
	{
		try {
			if ($request->has('delete_all')) {
				if (!$request->has('check-logadmin')) {
					throw new \Exception('Vui lòng chọn log muốn xóa!');
				}
				DB::beginTransaction();
				$listDelete = $request['check-logadmin'];
				foreach ($listDelete as $keyDel => $valDel) {
					$logadmin = $this->logAdminRepository->find($valDel);
					if (empty($logadmin)) {
						throw new \Exception('Log không tồn tại!');
					}
					if (!$logadmin->delete()) {
						throw new \Exception('Xóa log thất bại!');
					}
				}
				DB::commit();
				return redirect()->back()->with('success', 'Đã xóa log thành công!');
			}
			return redirect()->back();
		} catch(\Exception $ex) {
			DB::rollback();
			report($ex);
			return redirect()->back()->with('error', $ex->getMessage());
		}
	}
}
