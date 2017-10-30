<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "company".
 *
 * @property int $id
 * @property int $term_id 招商部ID
 * @property string $enterprise_code 企业代码
 * @property string $name 企业名称
 * @property string $address 企业地址
 * @property string $area 区县
 * @property string $place 所别
 * @property string $industry 行业分类
 * @property string $telephone 联系电话
 * @property string $establish_day 成立日期
 * @property int $postal_code 邮编
 * @property string $phone 手机
 * @property string $credit_code 信用代码
 * @property string $capital 注册资金
 * @property string $tax_name 财务姓名
 * @property string $tax_telephone 固定电话
 * @property string $tax_phone 联系手机
 * @property string $increment 增值税比例
 * @property string $business 营业税比例
 * @property string $income 企业所得税比例
 * @property string $personal 个人所得税比例
 * @property string $manage 管理费
 * @property string $settlement 结算比例
 * @property string $bank_name
 * @property string $bank_number
 * @property string $is_cognizance 是否认定
 * @property string $is_ratepaying 是否纳税
 * @property string $is_close 是否歇业
 * @property string $remark 备注
 * @property string $certificates 证件资料
 */
class Company extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'company';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['term_id', 'enterprise_code', 'name', 'address', 'telephone', 'establish_day', 'postal_code', 'phone', 'credit_code', 'capital', 'tax_phone', 'increment', 'business', 'income', 'personal', 'manage', 'bank_number', 'is_cognizance', 'is_ratepaying', 'is_close'], 'required'],
            [['term_id', 'postal_code', 'phone', 'tax_phone'], 'integer'],
            [['establish_day'], 'safe'],
            [['capital', 'increment', 'business', 'income', 'personal', 'manage'], 'number'],
            [['is_cognizance', 'is_ratepaying', 'is_close', 'certificates'], 'string'],
            [['enterprise_code', 'name', 'place', 'industry', 'credit_code', 'tax_name', 'bank_number'], 'string', 'max' => 50],
            [['address'], 'string', 'max' => 120],
            [['area', 'telephone', 'tax_telephone'], 'string', 'max' => 15],
            [['settlement'], 'string', 'max' => 10],
            [['bank_name'], 'string', 'max' => 100],
            [['remark'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'term_id' => '招商部',
            'enterprise_code' => '企业代码',
            'name' => '企业名称',
            'address' => '企业地址',
            'area' => '区县',
            'place' => '所别',
            'industry' => '行业分类',
            'telephone' => '联系电话',
            'establish_day' => '成立日期',
            'postal_code' => '邮编',
            'phone' => '手机',
            'credit_code' => '信用代码',
            'capital' => '注册资金',
            'tax_name' => '财务姓名',
            'tax_telephone' => '财务座机',
            'tax_phone' => '财务手机',
            'increment' => '增值税比例',
            'business' => '营业税比例',
            'income' => '企业所得税比例',
            'personal' => '个人所得税比例',
            'manage' => '管理费',
            'settlement' => '结算比例',
            'bank_name' => '开户行名称',
            'bank_number' => '开户行账号',
            'is_cognizance' => '是否认定',
            'is_ratepaying' => '是否纳税',
            'is_close' => '是否歇业',
            'remark' => '备注',
            'certificates' => '证件资料',
            'legal_person' => '法人'
        ];
    }
}
