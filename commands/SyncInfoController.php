<?php
/**
 * Created by PhpStorm.
 * User: ganggang
 * Date: 2017/5/10
 * Time: 9:31
 */
namespace app\commands;
use Yii;
use yii\console\Controller;

class SyncInfoController extends Controller
{
    /*
     * 线上库用户同步到线下
     */
    public function actionSyncUser()
    {
        set_time_limit(0);
        ini_set('memory_limit', '2048M');
        //先查询数据库是否存在数据
        $find = Yii::$app->db201606->createCommand("SELECT create_time AS end_time FROM temp_user_customer ORDER BY create_time DESC limit 1")->queryOne();
        //查询最后一条日期，然后从最后一条日期里面的数据去先线上库查询,找不到则查询所有
        //先获取线上所有用户手机号信息

        if ($find['end_time']) {
            $sql = "SELECT TRIM(phone) AS phone,MD5(TRIM(phone)) AS md5_phone,city_id,create_datetime FROM tidy_customer WHERE create_datetime > '{$find['end_time']}'";
        } else {
            $sql = "SELECT TRIM(phone) AS phone,MD5(TRIM(phone)) AS md5_phone,city_id,create_datetime FROM tidy_customer";
        }
        $result = Yii::$app->db->createCommand($sql)->queryAll();
        //获取总数
        $allNum = count($result);
        //10000个为一组insert语句插入到线下库
        $num = (ceil($allNum / 10000));
        $time = time();
        $array = array_chunk($result,$num);
        //var_dump($num);
        foreach ($array as $vo) {
            $insert = "INSERT INTO temp_user_customer (phone,md5_phone,city_id,sync_time,create_time) VALUES ";
            foreach ($vo as $v) {
                $insert .= "('{$v['phone']}','{$v['md5_phone']}',{$v['city_id']},$time,'{$v['create_datetime']}'),";

            }
            $insert = rtrim($insert,',');
            $insert .= " ON DUPLICATE KEY UPDATE phone = VALUES(phone),md5_phone=VALUES(md5_phone),create_time=VALUES(create_time)";
            //开始插入
            Yii::$app->db201606->createCommand($insert)->execute();
        }
    }
}