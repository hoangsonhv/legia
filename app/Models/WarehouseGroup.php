<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseGroup extends Model
{
    const MANUFACTURE_TYPE_METAL = 0;
    const MANUFACTURE_TYPE_NON_METAL = 1;
    const ARR_MANUFACTURE_TYPE = [
        self::MANUFACTURE_TYPE_METAL => 'Kim loại',
        self::MANUFACTURE_TYPE_NON_METAL => 'Phi kim loại'
    ];

    const TYPE_MANUFACTURE = 1;
    const TYPE_COMMERCE = 0;
    const ARR_TYPE = [
        self::TYPE_MANUFACTURE => 'Sản xuất',
        self::TYPE_COMMERCE => 'Thương mại'
    ];

    const ARR_WAREHOUSE = [
        'not_yet_thanh_pham_bia' => 'Thành phẩm BÌA',
        'not_yet_thanh_pham_cao_su' => 'Thành phẩm CAO SU',
        'not_yet_thanh_pham_cao_su-vietnam-za' => 'Thành phẩm CAO SU-VIETNAM-ZA',
        'not_yet_thanh_pham_ceramic' => 'Thành phẩm CERAMIC',
        'not_yet_thanh_pham_graphite' => 'Thành phẩm  Graphite',
        'not_yet_thanh_pham_ptfe' => 'Thành phẩm  PTFE',
        'bia' => 'Tấm BÌA',
        'caosu' => 'Tấm CAO SU',
        'caosuvnza' => 'Tấm CAO SU-VIETNAM-ZA',
        'ceramic' => 'Tấm CERAMIC',
        'graphite' => 'Tấm Graphite',
        'ptfe' => 'Tấm PTFE',
        'thanhphamswg' => 'Thành Phẩm SWG',
        'vanhtinhinnerswg' => 'Vành tinh Inner',
        'vanhtinhouterswg' => 'Vành tinh Outer',
        'tamkimloai' => 'Tấm kim loại',
        'hoop' => 'Hoop',
        'filler' => 'Filler',
        'rtj' => 'RTJ',
        'oring' => 'O-ring',
        'not_yet_day_cao_su_silicone' => 'Dây cao su, silicone',
        'not_yet_day_ceramic' => 'Dây Ceramic',
        'not_yet_gland_packing' => 'Gland packing',
        'glandpackinglatty' => 'Gland packing-Latty',
        'tamnhua' => 'Tấm nhựa',
        'not_yet_nhua_ky_thuat_cay_ong' => 'Nhựa kỹ thuật Cây-Ống',
        'not_yet_ong_glass_epoxy' => 'Ống Glass epoxy',
        'ptfeenvelope' => 'PTFE envelop',
        'ptfetape' => 'PTFE tape',
        'not_yet_ptfe_cay_ong' => 'PTFE Cây-ống',
        'not_yet_nd_loai_khac' => 'ND-loại khác',
        'not_yet_nk_loai_khac' => 'NK-loại khác',
        'not_yet_ccdc' => 'CCDC'
    ];

    protected $fillable = [
        'code',
        'name',
        'manufacture_type',
        'type',
        'admin_id',
        'warehouse_product',
        'warehouse_ingredient'
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}
