<?php

namespace gong\example\Validate;

use gong\tool\Validate\LaravelValidate;

class TestValidate extends LaravelValidate
{
    public function rules(): array
    {
        return [
            ['user_number.*', 'required|array|each', 'int'],
        ];
    }

    public function scenarios(): array
    {
        return [
            'checkStaffThereAsset' => ['user_number.*']
        ];
    }

    public function translate(): array
    {
        return [
            'id'                         => '资产ID',
            'code'                       => '资产编码',
            'dw_start_at'                => '生效时间',
            'dw_end_at'                  => '失效时间',
            'name'                       => '资产名称',
            'specification'              => '规则型号',
            'category_id'                => '类目ID',
            'category_path'              => '类目, 静止数据',
            'category_path_name'         => '类目名称, 静止数据',
            'material_id'                => '物资ID',
            'pending_asset_id'           => '待入库资产ID',
            'exception_pending_asset_id' => '异常待入库资产ID',
            'purchase_goods_id'          => '采购商品ID',
            'purchase_material_id'       => '采购物资ID',
            'order_id'                   => '采购订单ID',
            'application_id'             => '采购申请ID',
            'price'                      => '资产金额',
            'entered_at'                 => '首次入库时间',
            'user_name'                  => '当前使用人姓名',
            'user_number'                => '使用人工号',
            'user_number.*'              => '使用人工号',
            'status'                     => '资产状态',
            'source'                     => '资产来源',
            'warehouse_id'               => '所属仓库ID',
            'expired_at'                 => '有效期',
            'company_id'                 => '所属公司ID',
            'company_path'               => '所属公司',
            'company_path_name'          => '所属公司',
            'department_id'              => '当前使用人的部门ID',
            'department_path'            => '部门名称',
            'department_path_name'       => '部门名称',
            'image_id'                   => '照片ID',
            'remark'                     => '备注',
            'change_type'                => '异动类型',
            'requisition_id'             => '',
            'requisition_item_id'        => '',
            'approval_id'                => '',
        ];
    }

    public function methodMessage(): array
    {
        return [];
    }
}

$params = [
    'a' => 1
];
TestValidate::validator($params, 'checkStaffThereAsset');