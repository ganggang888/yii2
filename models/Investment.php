<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "investment".
 *
 * @property int $id
 * @property int $parent_id
 * @property string $name 招商部名称
 * @property string $area 所属区县
 * @property string $address 地址
 * @property string $telephone 固定电话
 * @property string $phone 手机号
 * @property string $the_name 联系人
 */
class Investment extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'investment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'phone'], 'integer'],
            [['name', 'area', 'address', 'phone'], 'required'],
            [['name', 'area'], 'string', 'max' => 50],
            [['address'], 'string', 'max' => 150],
            [['telephone', 'the_name'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => 'Parent ID',
            'name' => 'Name',
            'area' => 'Area',
            'address' => 'Address',
            'telephone' => 'Telephone',
            'phone' => 'Phone',
            'the_name' => 'The Name',
        ];
    }

    //返回列表
    public static function getList()
    {
        $data = self::find()->asArray()->all();
        $arr = [];
        foreach ($data as $vo) {
            $arr[$vo['id']] = $vo['name'];
        }
        return $arr;
    }
}
