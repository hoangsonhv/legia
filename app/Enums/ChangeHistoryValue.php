<?php

namespace App\Enums;

use App\Http\Requests\Request;
use App\Models\Co;
use App\Models\CoTmp;
use App\Models\Manufacture;
use App\Models\Payment;
use App\Models\Receipt;
use App\Models\WarehouseExport;
use App\Models\WarehouseExportSell;
use App\Models\WarehouseReceipt;

class ChangeHistoryValue
{
    const COTPM  = CoTmp::class;
    const CO  = Co::class;
    const REQUEST = Request::class;
    const RECEIPT  = Receipt::class;
    const PAYMENT  = Payment::class;
    const EXPORT  = WarehouseExport::class;
    const EXPORT_SELL  = WarehouseExportSell::class;
    const WAREHOUSE_RECEIPT  = WarehouseReceipt::class;
    const MANUFACTURE  = Manufacture::class;

    public static function formTypes() {
        return [
            self::COTPM             => 'Phiếu Chào Giá',
            self::CO                => 'Phiếu CO',
            self::REQUEST           => 'Phiếu Yêu Cầu',
            self::RECEIPT           => 'Phiếu Thu',
            self::PAYMENT           => 'Phiếu Chi',
            self::EXPORT            => 'Phiếu Xuất Kho',
            self::EXPORT_SELL       => 'Phiếu Xuất Kho Bán Hàng',
            self::WAREHOUSE_RECEIPT => 'Phiếu Nhập Kho',            
            self::MANUFACTURE       => 'Phiếu Sản Xuất',            
        ];
    }
    // public static function formTypes() {
    //     return [
    //         self::COTPM             => 'Phiếu Chào Giá',
    //         self::CO                => 'Phiếu CO',
    //         self::REQUEST           => 'Phiếu Yêu Cầu',
    //         self::RECEIPT           => 'Phiếu Thu',
    //         self::PAYMENT           => 'Phiếu Chi',
    //         self::EXPORT            => 'Phiếu Xuất Kho',
    //         self::EXPORT_SELL       => 'Phiếu Xuất Kho Bán Hàng',
    //         self::WAREHOUSE_RECEIPT => 'Phiếu Nhập Kho',            
    //         self::MANUFACTURE       => 'Phiếu Sản Xuất',            
    //     ];
    // }
}
