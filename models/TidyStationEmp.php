<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tidy_station_emp".
 *
 * @property int $station_id 网点id
 * @property string $empid 员工id
 * @property int $user_id 网点人员user_id
 * @property int $take_count 累计取件次数
 * @property int $ok_count 累计加工次数
 * @property int $yun_count 累计送件次数
 * @property int $is_glder 0=派送员，1=店长
 * @property string $__#alibaba_rds_row_id#__ Implicit Primary Key by RDS
 */
class TidyStationEmp extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tidy_station_emp';
    }
    public static function getDb()
    {
        return Yii::$app->get('db');
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['station_id', 'empid', 'user_id', 'take_count', 'ok_count', 'yun_count'], 'required'],
            [['station_id', 'user_id', 'take_count', 'ok_count', 'yun_count', 'is_glder'], 'integer'],
            [['empid'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'station_id' => 'Station ID',
            'empid' => 'Empid',
            'user_id' => 'User ID',
            'take_count' => 'Take Count',
            'ok_count' => 'Ok Count',
            'yun_count' => 'Yun Count',
            'is_glder' => 'Is Glder',
            '__#alibaba_rds_row_id#__' => '#alibaba Rds Row Id#',
        ];
    }
}
