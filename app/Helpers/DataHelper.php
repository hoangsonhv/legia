<?php

namespace App\Helpers;

use App\Models\Document;

class DataHelper
{
    const DINH_KY        = 'dinh_ky';
    const VAN_PHONG_PHAM = 'van_phong_pham';
    const KHO            = 'kho';
    const HOAT_DONG      = 'HOAT_DONG';

    public static function getCategoryPayment($type=null, $option=null) {
        $typeSpending = [
            self::DINH_KY => [ // PYC, PC, Not CO
                'label' => 'Định kỳ',
                'option' => [
                    self::DINH_KY.'-tien_dien'   => 'Tiền điện',
                    self::DINH_KY.'-tien_nuoc'   => 'Tiền nước',
                    self::DINH_KY.'-mat_bang'    => 'Tiền mặt bằng',
                    self::DINH_KY.'-tien_luong'  => 'Tiền lương',
                    self::DINH_KY.'-dt_internet' => 'Tiền điện thoại, internet',
                    self::DINH_KY.'-khac'        => 'Tiền Khác'
                ]
            ],
            self::VAN_PHONG_PHAM => [ // PYC, PC, Not CO
                'label' => 'Phí sinh hoạt văn phòng',
                'option' => [
                    self::VAN_PHONG_PHAM.'-do_dung'  => 'Đồ dùng văn phòng',
                    self::VAN_PHONG_PHAM.'-thiet_bi' => 'Thiết bị văn phòng',
                    self::VAN_PHONG_PHAM.'-bao_bi_dong_goi'     => 'Bao bì đóng gói',
                    self::VAN_PHONG_PHAM.'-dung_cu_do_luong'    => 'Dụng cụ đo lường',
                    self::VAN_PHONG_PHAM.'-nguyen_vat_lieu_du_tru_de_san_xuat_hang_hoa'  => 'Nguyên vật liệu dự trữ để sản xuất hàng hóa',
                    self::VAN_PHONG_PHAM.'-phu_tung_may_moc'    => 'Phụ tùng, máy móc',
                    self::VAN_PHONG_PHAM.'-vat_tu_tieu_hao'    => 'Vật tư tiêu hao( phục vụ sản xuất)',
                    self::VAN_PHONG_PHAM.'-thiet_bi_van_phong'    => 'Thiết bị văn phòng, văn phòng phẩm',
                    self::VAN_PHONG_PHAM.'-khac'     => 'khác'
                ]
            ],
            self::HOAT_DONG => [ // PYC, PC, Not CO
                'label' => 'Phí sinh hoạt',
                'option' => [
                    self::HOAT_DONG.'-do_dung'             => 'Công tác phí',
                    self::HOAT_DONG.'-cong_tac_xa_hoi'     => 'Công tác xã hội',
                    self::HOAT_DONG.'-thue'                => 'Thuế',
                    self::HOAT_DONG.'-dich_vu_hai_quan'    => 'Dịch vụ hải quan',
                    self::HOAT_DONG.'-dich_vu_thue'        => 'Dịch vụ thuế',
                    self::HOAT_DONG.'-khac'                => 'khác',
                ]
            ],
            self::KHO => [  // PYC, PC, PT, CO
                'label' => 'Kho',
                'option' => [
                    self::KHO.'-nguyen_vat_lieu' => 'Nguyên vật liệu sản xuất',
                    self::KHO.'-thiet_bi'        => 'Thiết bị máy móc sản xuất',
                    self::KHO.'-chiet_khau'      => 'Chiết khấu',
                    self::KHO.'-khac'            => 'khác'
                ]
            ],
        ];

        if ($type && isset($typeSpending[$type])) {
            $data = $typeSpending[$type];
            if ($option && isset($data[$option])) {
                $data = $data[$option];
            }
            return $data;
        }
        return $typeSpending;
    }

    public static function getCategories($aDiplay=[]) {
        $res  = [];
        $data = self::getCategoryPayment();
        foreach($data as $kRoot => $vRoot) {
            if ($aDiplay && !in_array($kRoot, $aDiplay)) {
                continue;
            }
            $res[$vRoot['label']] = $vRoot['option'];
        }
        return $res;
    }
    public static function getCategoriesForIndex($aDiplay=[]) {
        $res  = [];
        $data = self::getCategoryPayment();
        foreach($data as $kRoot => $vRoot) {
            if ($aDiplay && !in_array($kRoot, $aDiplay)) {
                continue;
            }
            $res[$kRoot] = $vRoot['label'];
            foreach($vRoot['option'] as $key => $value) {
                $res[$key] = $value;
            }
        }
        return $res;
    }

    public static function getStatusCO($status=null) {
        $statuses = [
            0 => 'Tất cả',
            1 => 'Chưa hoàn thành',
            2 => 'Hoàn thành'
        ];
        if ($status && isset($statuses[$status])) {
            return $statuses[$status];
        }
        return $statuses;
    }

    public static function getUnits($unit=null) {
        $units = [
            '1' => 'Tấm',
            '2' => 'Bộ',
            '3' => 'VND',
            '4' => 'Cái',
        ];
        if ($unit && isset($units[$unit])) {
            return $units[$unit];
        }
        return $units;
    }

    public static function getPaymentMethods($payment=null) {
        $payments = [
//            1 => 'Tiền mặt',
            2 => 'Chuyển khoản',
            3 => 'Khác',
        ];
        if ($payment && isset($payments[$payment])) {
            return $payments[$payment];
        }
        return $payments;
    }

    public static function getFiles($file=null) {
        $files = [
            'image' => 'Hình ảnh',
            'file'  => 'File khác',
        ];
        if ($file && isset($files[$file])) {
            return $files[$file];
        }
        return $files;
    }

    public static function getExtensionImport($ext) {
        $files = [
            'xlsx' => \Maatwebsite\Excel\Excel::XLSX,
            'xls'  => \Maatwebsite\Excel\Excel::XLS,
            'csv'  => \Maatwebsite\Excel\Excel::CSV,
        ];
        if ($ext && isset($files[$ext])) {
            return $files[$ext];
        }
        return false;
    }

    public static function getModelWarehouses($warehouse=null, $model=null) {
        $data = [
            'plate' => [
                'bia'        => 'Kho BIA',
                'caosuvnza'  => 'Kho CAO SU Viet Nam ZA',
                'caosu'      => 'Kho CAO SU',
                'ceramic'    => 'Kho CERAMIC',
                'ptfe'       => 'Kho PTFE',
                'graphite'   => 'Kho Graphite',
                'tamkimloai' => 'Kho Tấm Kim Loại',
                'tamnhua'    => 'Kho Tấm nhựa'
            ],
            'spw' => [
                'filler'            => 'Kho Filler',
                'glandpackinglatty' => 'Kho Gland Packing-Latty',
                'hoop'              => 'Kho Hoop',
                'oring'             => 'Kho O-ring',
                'ptfeenvelope'      => 'Kho PTFE ENVELOPE',
                'ptfetape'          => 'Kho PTFE tape',
                'rtj'               => 'Kho RTJ',
                'thanhphamswg'      => 'Kho Thành phẩm-SWG',
                'vanhtinhinnerswg'  => 'Kho Vành tinh -inner-SWG',
                'vanhtinhouterswg'  => 'Kho Vành tinh -outer-SWG',
            ],
            'remain' => [
                'ccdc'              => 'Kho CCDC',
                'daycaosusilicone'  => 'Kho Dây Cao Su, Silicone',
                'dayceramic'        => 'Kho Dây Ceramic',
                'glandpacking'      => 'Kho Gland Packing',
                'nhuakythuatcayong' => 'Kho Nhựa Kỹ Thuật-Cây Ống',
                'ongglassepoxy'     => 'Kho Ống glass epoxy',
                'phutungdungcu'     => 'Kho Phụ Tùng, Dụng Cụ',
                'ptfecayong'        => 'Kho PTFE Cây Ống',
                'ndloaikhac'        => 'Kho ND loại khác',
                'nkloaikhac'        => 'Kho NK loại khác',
                'tpphikimloai'      => 'Kho thành phẩm phi kim loại'
                // 'tpkimloai'      => 'Kho thành phẩm kim loại',
            ],
            'supply' => [
                'supply'            => 'Kho vật dụng',
            ],
        ];
        if ($warehouse && isset($data[$warehouse])) {
            if ($model && isset($data[$warehouse][$model])) {
                return $data[$warehouse][$model];
            }
            return $data[$warehouse];
        }
        return $data;
    }

    public static function getInfoShipments($ship=null) {
        $ships = [
            1 => 'Giá chưa bao gồm chi phí đóng gói và chi phí vận chuyển.',
        ];
        if ($ship && isset($ships[$ship])) {
            return $ships[$ship];
        }
        return $ships;
    }

    public static function getInfoPending($label, $key=0) {
        $info = [
            0 => '&nbsp;',
            1 => $label . ' chưa xét duyệt',
            2 => $label . ' chưa xử lý',
        ];
        if ($key && isset($info[$key])) {
            return $info[$key];
        }
        return $info;
    }

    public static function stepPay()
    {
        return [
            0 => [
                'field' => 'truoc_khi_lam_hang',
                'text' => 'trước khi làm hàng'
            ],
            1 => [
                'field' => 'truoc_khi_giao_hang',
                'text' => 'trước khi giao hàng'
            ],
            2 => [
                'field' => 'ngay_khi_giao_hang',
                'text' => 'ngay khi giao hàng'
            ],
            3 => [
                'field' => 'sau_khi_giao_hang_va_cttt',
                'text' => 'sau khi giao hàng và chứng từ thanh toán'
            ],
            4 => [
                'field' => 'thanh_toan_no',
                'text' => 'Thanh toán nợ còn lại'
            ],
        ];
    }

    public static function documents()
    {
        $documents = Document::get(['id', 'name']);
        $arrDocuments[0] = '--- Chọn chứng từ ---';
        foreach ($documents as $index => $document) {
            $arrDocuments[$document->id] = $document->name;
        }
        return $arrDocuments;
    }

    public static function logoBanks($name_bank) {
        $name_bank = strtolower($name_bank);
        switch ($name_bank) {
            case 'vietinbank':
                return asset('images/Logo-VietinBank-CTG-Te.png');
            case 'vietcombank':
                return asset('images/Logo-Vietcombank.png');
            default:
                return null;
        }
    }
}
