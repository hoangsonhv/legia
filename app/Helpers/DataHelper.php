<?php

namespace App\Helpers;

use App\Models\Document;

class DataHelper
{
    const DINH_KY        = 'dinh_ky';
    const VAN_PHONG_PHAM = 'van_phong_pham';
    const KHO            = 'kho';

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
                    self::VAN_PHONG_PHAM.'-khac'     => 'khác'
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

    public function getCategories($aDiplay=[]) {
        $res  = [];
        $data = self::getCategoryPayment();
        foreach($data as $kRoot => $vRoot) {
            if ($aDiplay && !in_array($kRoot, $aDiplay)) {
                continue;
            }
            $res[$kRoot] = $vRoot['label'];
            foreach($vRoot['option'] as $key => $value) {
                $res[$key] = '&gt;&gt;&gt;&nbsp;'.$value;
            }
        }
        return $res;
    }

    public function getStatusCO($status=null) {
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

    public function getUnits($unit=null) {
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

    public function getFiles($file=null) {
        $files = [
            'image' => 'Hình ảnh',
            'file'  => 'File khác',
        ];
        if ($file && isset($files[$file])) {
            return $files[$file];
        }
        return $files;
    }

    public  static function getExtensionImport($ext) {
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
                'graphite'   => 'Kho Graphite',
                'ptfe'       => 'Kho PTFE',
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

    public function getInfoShipments($ship=null) {
        $ships = [
            1 => 'Giá chưa bao gồm chi phí đóng gói và chi phí vận chuyển.',
        ];
        if ($ship && isset($ships[$ship])) {
            return $ships[$ship];
        }
        return $ships;
    }

    public function getInfoPending($label, $key=0) {
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

    public function stepPay()
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
}
