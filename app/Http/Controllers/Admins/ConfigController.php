<?php 
namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Models\Repositories\ConfigRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ConfigController extends Controller
{
    private $disk = 'local';

	protected $configRepository;

	public $menu;

	function __construct(ConfigRepository $configRepository)
	{
		$this->configRepository = $configRepository;
		$this->menu               = [
			'root' => 'Cấu hình hệ thống',
			'data' => [
				'parent' => [
					'href'   => route('admin.logadmin.index'),
					'label'  => 'Cấu hình hệ thống'
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

		$configs = $this->configRepository->getConfigs([])->orderBy('id','ASC')->get();
		return view('admins.configs.index', compact('breadcrumb', 'titleForLayout', 'configs'));
	}

	/**
	 * Save resource.
	 */
	public function store(Request $request)
    {
    	try {
            $data    = $request->except(['_token', 'id']);
            $path    = 'uploads/configs';
            $fileBef = [];
            $fileCur = [];
            \DB::beginTransaction();
            foreach($data as $key => $val) {
                $config = $this->configRepository->getConfigs(['key' => $key])->first();
                if ($config->type === 'file') {
                    $file = $request->file($config->key);
                    if ($file) {
                        $fileSave  = Storage::disk($this->disk)->put($path, $file);
                        $fileCur[] = $fileSave;
                        if ($config->value) {
                            $fileBef[] = $config->value;
                        }
                        $val = $fileSave;
                    }
                }
                if ($config) {
                    $config->value = $val;
                    $config->save();
                }
            }
            \DB::commit();
            if ($fileBef) {
                foreach($fileBef as $key => $val) {
                    Storage::disk($this->disk)->delete($val);
                }
            }
            return redirect()->route('admin.config.index')->with('success','Lưu thông tin thành công!');
        } catch(\Exception $ex) {
            if ($fileCur) {
                foreach($fileCur as $key => $val) {
                    Storage::disk($this->disk)->delete($val);
                }
            }
            \DB::rollback();
            report($ex);
        }
        return redirect()->route('admin.config.index')->with('error','Lưu thông tin thất bại!');
    }
}
