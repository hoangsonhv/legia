<?php

namespace App\Helpers;

use App\Models\Bank;
use App\Models\Config;
use App\Models\CoreCustomer;
use App\Models\LogAdmin;
use App\Models\Manufacture;
use App\Models\MerchandiseCode;
use App\Models\MerchandiseGroup;
use App\Models\PriceSurvey;
use App\Models\Repositories\Warehouse\BaseWarehouseRepository;
use App\Models\Warehouse\BaseWarehouseCommon;
use App\Models\WarehouseGroup;
use App\Models\WarehouseProductCode;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Session;

class AdminHelper
{
    public static function message($message, $type='alert-danger') {
        $html = '<div class="alert '.$type.' alert-dismissible fade show" role="alert">
                  '.$message.'
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>';
        return $html;
    }

    public static function hashPassword($username, $password) {
    	return md5($username.md5($password));
    }

    public static function uploadFile($file, $destinationPath='', $opts=array()) {
        $path = $file->getRealPath();
        if ($file->isValid()) {
            if ($file->getClientOriginalExtension() == 'pdf' && $file->getSize() <= 5000000) {
                $isValid = true;
            } elseif (getimagesize($path) && $file->getSize() <= 3000000) {
                $isValid = true;
            }
            if (!empty($isValid)) {
                $fileName = date('YmdHms').$file->getClientOriginalName();
                if (empty($destinationPath)) {
                    $destinationPath = 'admin/uploads';
                }
                // create folder contain root
                if (!file_exists(public_path().'/'.$destinationPath)) {
                    mkdir(public_path().'/'.$destinationPath, 0755, TRUE);
                }
                // move file origin
                $file->move($destinationPath, $fileName);
                /* Upload to Folder image sub */
                if (!empty($opts['imageSub'])) {
                    // Image::make($path)->resize(380, 380)->save($destinationPath.'/'.$fileName);
                    self::cropImage($destinationPath.'/'.$fileName, $destinationPath.'/'.$fileName, 550, 380);
                    return $fileName;
                }
                // copy file
                $route = Route::getCurrentRoute()->getPath();
                if ((strpos($route, '/product/') !== false) || (strpos($route, '/post/') !== false)) {
                    if ((strpos($route, '/post/') !== false)) {
                        $aListFile = array(
                            'list' => array('width' => '300', 'height' => '260'), 
                        );
                    } else {
                        $aListFile = array(
                            'list' => array('width' => '260', 'height' => '250'), 
                            'detail' => array('width' => '550', 'height' => '300'),
                        );
                    }
                    foreach($aListFile as $kFolder => $vFolder) {
                        $pathFile = public_path().'/'.$destinationPath.'/'.$kFolder;
                        if (!file_exists($pathFile)) {
                            mkdir($pathFile, 0755, TRUE);
                        }
                        // Image::make($destinationPath.'/'.$fileName)->resize($vFolder['width'], $vFolder['height'])->save($pathFile.'/'.$fileName);
                        self::cropImage($pathFile.'/'.$fileName, $destinationPath.'/'.$fileName, $vFolder['width'], $vFolder['height']);
                    }
                }
                return $fileName;
            }
        }
        return false;
    }

    public static function cropImage($dtsImg, $srcImg, $newWidth=300, $newHeight=300, $options=array()) {
	// Create image
	$type = strtolower(substr(strrchr($srcImg,"."),1));
	switch($type){
		case 'bmp': $image = imagecreatefromwbmp($srcImg); break;
		case 'gif': $image = imagecreatefromgif($srcImg); break;
		case 'jpg': $image = imagecreatefromjpeg($srcImg); break;
		case 'jpeg': $image = imagecreatefromjpeg($srcImg); break;
		case 'png': $image = imagecreatefrompng($srcImg); break;
		default : return false;
	}
	// Create background
	$thumb = imagecreatetruecolor($newWidth, $newHeight);
	// Set background
	$white = imagecolorallocate($thumb, 255, 255, 255);
	imagefill($thumb, 0, 0, $white);
	// Rotate image
	if (!empty($options['rotate'])) {
		$image = imagerotate($image, $options['rotate'], $white);
	}
	// Get width, height image origin 
	$oriWidth = imagesx($image);
	$oriHeight = imagesy($image);
	// Remain width, height
	$frameWidth = $newWidth;
	$frameHeight = $newHeight;
	// Set width and height thumb
	$oriRatio = $oriWidth / $oriHeight;
	$newRatio = $newWidth / $newHeight;
	if ($newRatio > $oriRatio) {
	   $newWidth = $newHeight * $oriRatio;
	} else {
	   $newHeight = $newWidth / $oriRatio;
	}
	// Resize and crop
	imagecopyresampled($thumb, $image,
	                   0 - ($newWidth - $frameWidth) / 2,
	                   0 - ($newHeight - $frameHeight) / 2,
	                   0, 0,
	                   $newWidth, $newHeight,
	                   $oriWidth, $oriHeight);
	// Export image
	switch($type){
		case 'bmp': imagewbmp($thumb, $dtsImg); break;
		case 'gif': imagegif($thumb, $dtsImg); break;
		case 'jpg': imagejpeg($thumb, $dtsImg); break;
		case 'jpeg': imagejpeg($thumb, $dtsImg); break;
		case 'png': imagepng($thumb, $dtsImg); break;
	}
	// Free the memory
	imagedestroy($thumb);
	if (!empty($options['rotate'])) {
		imagedestroy($image);
	}
	return true;
    }

    public static function sendMail($to, $subject, $body, $opts=array()) {
        $config = Config::where('key_word', 'name')->get();
        if ($config) {
            $prefix = '['.$config->first()->content.'] ';
        } else {
            $prefix = '[Legia] ';
        }
        if (empty($opts['html'])) {
            Mail::send([], [], function($message) use ($to, $subject, $body, $prefix) {
                $message->to($to);
                $message->subject($prefix.$subject);
                $message->setBody($body);
            });
        } else {
            Mail::send($opts['html'], $opts['param'], function($message) use ($to, $subject, $prefix) {
                $message->to($to);
                $message->subject($prefix.$subject);
            });
        }
        // check for failures
        if (Mail::failures()) {
            return false;
        }
        return true;
    }

    public static function removeCharacter($value, $char = ',', $replace = '') {
        $value = str_replace($char, $replace, $value);
        return $value;
    }

    public static function saveLogAdmin($content=null) {
        $data = array(
            'admin_id'   => Session::get('login')->id,
            'admin_name' => Session::get('login')->username,
            'ip'         => $_SERVER['REMOTE_ADDR'],
            'browser'    => $_SERVER['HTTP_USER_AGENT'],
            'link'       => $_SERVER['REQUEST_URI'],
            'content'    => $content
        );
        LogAdmin::create($data);
    }

    public static function convertDate($date, $format = 'Y-m-d') {
        return \Carbon\Carbon::parse(strtotime($date))->setTimezone(config('timezone'))->format($format);
    }

    public static function checkFile($file) {
        $path         = asset($file['path']);
        $extension    = pathinfo($path, PATHINFO_EXTENSION);
        switch($extension) {
            case 'jpg':
            case 'png':
            case 'gif':
            case 'jpeg':
            case 'bmp':
                return '<p>&#45;&#160;'.$file['name'].'</p><a href="'.$path.'" target="_blank"><img height="70" src="'.$path.'" /></a>';
            default:
                return '<p style="color: blue;">&#45;&#160;<a href="'.$path.'" target="_blank">'.$file['name'].'</a></p>';
        }
    }

    /**
     * Get all banks
     * @return Bank[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function getBanks($exceptCash = false)
    {
        if($exceptCash) {
            $banks = Bank::where('type', '!=', Bank::TYPE_CASH)->get()->toArray();
        } else {
            $banks = Bank::all()->toArray();
        }
        $result = [];
        foreach ($banks as $index => $bank) {
            $result[$bank['id']] = $bank['name_bank'] . '  ('. $bank['account_number'].')';
        }
        return $result;
    }

    public static function VndText($amount)
    {
        if($amount <=0)
        {
            return $textnumber="Tiền phải là số nguyên dương lớn hơn số 0";
        }
        $Text=array("không", "một", "hai", "ba", "bốn", "năm", "sáu", "bảy", "tám", "chín");
        $TextLuythua =array("","nghìn", "triệu", "tỷ", "ngàn tỷ", "triệu tỷ", "tỷ tỷ");
        $textnumber = "";
        $length = strlen($amount);

        for ($i = 0; $i < $length; $i++)
            $unread[$i] = 0;

        for ($i = 0; $i < $length; $i++)
        {
            $so = substr($amount, $length - $i -1 , 1);

            if ( ($so == 0) && ($i % 3 == 0) && ($unread[$i] == 0)){
                for ($j = $i+1 ; $j < $length ; $j ++)
                {
                    $so1 = substr($amount,$length - $j -1, 1);
                    if ($so1 != 0)
                        break;
                }

                if (intval(($j - $i )/3) > 0){
                    for ($k = $i ; $k <intval(($j-$i)/3)*3 + $i; $k++)
                        $unread[$k] =1;
                }
            }
        }

        for ($i = 0; $i < $length; $i++)
        {
            $so = substr($amount,$length - $i -1, 1);
            if ($unread[$i] ==1)
                continue;

            if ( ($i% 3 == 0) && ($i > 0))
                $textnumber = $TextLuythua[$i/3] ." ". $textnumber;

            if ($i % 3 == 2 )
                $textnumber = 'trăm ' . $textnumber;

            if ($i % 3 == 1)
                $textnumber = 'mươi ' . $textnumber;


            $textnumber = $Text[$so] ." ". $textnumber;
        }

        //Phai de cac ham replace theo dung thu tu nhu the nay
        $textnumber = str_replace("không mươi", "lẻ", $textnumber);
        $textnumber = str_replace("lẻ không", "", $textnumber);
        $textnumber = str_replace("mươi không", "mươi", $textnumber);
        $textnumber = str_replace("một mươi", "mười", $textnumber);
        $textnumber = str_replace("mươi năm", "mươi lăm", $textnumber);
        $textnumber = str_replace("mươi một", "mươi mốt", $textnumber);
        $textnumber = str_replace("mười năm", "mười lăm", $textnumber);

        return ucfirst($textnumber." đồng chẵn");
    }

    public static function getCoreCustomer()
    {
        $cores = CoreCustomer::where('type', CoreCustomer::TYPE_CUSTOMER)->get();
        $coreCustomers[0] = 'Chọn khách hàng';
        foreach ($cores as $index => $core) {
            $coreCustomers[$core->id] = '('. $core->code .') ' . $core->name;
        }

        return $coreCustomers;
    }

    public static function getCorePriceSurvey()
    {
        $priceSurveies                      = PriceSurvey::all();
        $arrPriceSurvey[0] = 'Chọn khảo sát giá';
        foreach ($priceSurveies as $index => $priceSurvey) {
            $arrPriceSurvey[$priceSurvey->id] = $priceSurvey->product_group
                . ' ('. $priceSurvey->supplier .'  ___   '. number_format($priceSurvey->price, 0) .')';
        }

        return $arrPriceSurvey;
    }

    public static function getTypeBanks()
    {
        return [
            Bank::TYPE_ATM => 'Ngân hàng',
            Bank::TYPE_CASH => 'Tiền mặt'
        ];
    }

    public static function getShippingUnitDelivery()
    {
        return [
            'viettel_post' => 'Viettel Post',
            'vn_spot' => 'Việt Nam Spot (VNpost / EMS)',
            'ghn' => 'Giao Hàng Nhanh',
            'ghtk' => 'Giao Hàng Tiết Kiệm',
            'kerry' => 'Kerry Express',
            'sship' => 'SShip',
            'shipchung' => 'Shipchung',
            'orther' => 'Khác'
        ];
    }

    public static function getShippingMethodDelivery()
    {
        return [
            'roadways' => 'Đường bộ',
            'railways' => 'Đường sắt',
            'airways' => 'Đường hàng không',
            'waterways' => 'Đường thủy',
            'pipeline_transport' => 'Đường ống',
            'orther' => 'Khác'
        ];
    }

    public static function getMaterialTypes()
    {
        return [
            '-1' => '--- Loại ---',
            Manufacture::MATERIAL_TYPE_METAL => 'Kim loại',
            Manufacture::MATERIAL_TYPE_NON_METAL => 'Phi kim loại',
        ];
    }

    public static function detectProductCode($code)
    {
        $result = [
            'manufacture_type' => null,
            'merchandise_group_id' => null,
            'merchandise_group_code' => null,
            'merchandise_code_in_warehouse' => null,
            'merchandise_id' => null,
            'material_type' => null,
            'model_type' => null,
        ];
        $arrCode = explode(" ", strtoupper($code));
        $merchandise_code = MerchandiseCode::where('code', $arrCode[0])
            ->when(count($arrCode) > 1, function($q) use($arrCode) {
                $q->orWhere('code' , $arrCode[0] . ' ' . $arrCode[1]);
            })->first();
        $codeInWarehouseTmp = '';
        foreach ($arrCode as $value) {
            if($merchandise_code) {
                $result['merchandise_group_code'] = $result['merchandise_group_code'] ?? $merchandise_code->infix_code;
                $infix_codes = MerchandiseCode::where('prefix_code', $merchandise_code->infix_code)->where('code', $value)->first();
                $codeInWarehouseTmp = $codeInWarehouseTmp ? $codeInWarehouseTmp . ' ' . $value : $value;
                $codeInWarehouse = BaseWarehouseCommon::where('code', $codeInWarehouseTmp)->first();
                if($codeInWarehouse != null) {
                    $result['merchandise_code_in_warehouse'] = $codeInWarehouseTmp;
                    $result['merchandise_id'] = $codeInWarehouse->l_id;
                    $result['model_type'] = $codeInWarehouse->model_type;
                }
                if(!$infix_codes) {
                    continue;
                }
                $result['merchandise_group_code'] =  $result['merchandise_group_code'] . '_' . $infix_codes->infix_code;

            }
        }
        if($result['merchandise_group_code'] == 'SG') {
            $result['merchandise_group_code'] =  $result['merchandise_group_code'] . '_' . 'SHEET';
        }
        $merchandise_group = MerchandiseGroup::where('code' , $result['merchandise_group_code'])->first();
        $result['manufacture_type'] = $merchandise_group ? $merchandise_group->operation_type : null;
        $result['material_type'] = $merchandise_group ? $merchandise_group->factory_type : null;
        $result['merchandise_group_id'] = $merchandise_group ? $merchandise_group->id : null;
        return $result;
    }

    //product and merchandise
    public static function countProductMerchanInWarehouse($codeInWareHouse, $model_type) {
        $tonKho = 0;
        if ($model_type == null || $codeInWareHouse == null) {
            return $tonKho;
        }

        $baseWarehouseRepository = new BaseWarehouseRepository();
        $baseWarehouseRepository->setModel(WarehouseHelper::getModel($model_type));
        $results = $baseWarehouseRepository->model->where('code', $codeInWareHouse)
            ->where('model_type' , $model_type)->get();
    
        if ($results != null) {
            foreach ($results as $result) {
                $tonKho += $result['ton_kho'][array_keys($result['ton_kho'])[0]];
            }
        }

        return $tonKho;
    }

    //product thành phẩm
    public static function countProductInWarehouse($codeInWareHouse, $manufacture_type) {
        $baseWarehouseRepository = new BaseWarehouseRepository();
        if ($manufacture_type == Manufacture::MATERIAL_TYPE_METAL) {
            $baseWarehouseRepository->setModel(WarehouseHelper::getModel(WarehouseHelper::KHO_THANH_PHAM_KIM_LOAI));
            $result = $baseWarehouseRepository->model->where('code', $codeInWareHouse)
                ->where('model_type' , WarehouseHelper::PRODUCT_WAREHOUSES[Manufacture::MATERIAL_TYPE_METAL])->first();
        }
        else if ($manufacture_type == Manufacture::MATERIAL_TYPE_NON_METAL) {
            $baseWarehouseRepository->setModel(WarehouseHelper::getModel(WarehouseHelper::KHO_THANH_PHAM_PHI_KIM_LOAI));
            $result = $baseWarehouseRepository->model->where('code', $codeInWareHouse)
                ->where('model_type' , WarehouseHelper::PRODUCT_WAREHOUSES[Manufacture::MATERIAL_TYPE_NON_METAL])->first();
        }
        if ($result != null) {
            return $result['ton_kho'][array_keys($result['ton_kho'])[0]];
        }

        return 0;
    }

    public static function findGroupByCode($code)
    {
        $group = WarehouseProductCode::whereRaw("LOWER(warehouse_product_codes.code) = '" . strtolower($code) ."'")
            ->join('warehouse_groups AS WG', 'WG.id', '=',
                'warehouse_product_codes.warehouse_group_id')
            ->select('WG.*')
            ->first();
        return $group;
    }

    public static function findGroup($code)
    {
        return WarehouseGroup::whereRaw("LOWER(code) = '" . strtolower($code) ."'")->first();
    }

    public static function findGroupCodePLS($initialCode = 'PLS', $arrCode)
    {
        $code = (isset($arrCode[1]) && $arrCode[1]) ? $arrCode[1] : null;
        $codeFull = implode(' ', [$initialCode, $code]);
        return self::findGroupByCode($codeFull);
    }

    public static function findGroupCodeSG($arrCode)
    {
        $initialCode = 'SG';
        $code = (isset($arrCode[2]) && $arrCode[2]) ? $arrCode[2] : null;
        $codeFull = implode('_', [$initialCode, 'SHEET']);
        if($code) {
            $group = self::findGroupByCode($code);
            if($group) {
                $codeFull = implode('_', [$initialCode, $group->code]);
            }
        }
        return self::findGroup($codeFull);
    }

    public static function findGroupCodeSWG($arrCode)
    {
        $initialCode = 'SWG';
        $code = (isset($arrCode[3]) && $arrCode[3]) ? $arrCode[3] : null;
        $codeFull = implode('_', [$initialCode, 'SHEET']);
        if($code) {
            $group = self::findGroupByCode($code);
            if($group) {
                $codeFull = implode('_', [$initialCode, $group->code]);
            }
        }
        return self::findGroup($codeFull);
    }
}
