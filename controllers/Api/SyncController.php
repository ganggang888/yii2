<?php
/*
 * 同步API
 */
namespace app\controllers\api;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\Msg;
class SyncController extends Controller
{

    public $enableCsrfValidation = false;

    /*
     * 根据传输的json md5后的手机号匹配是否在库中存在
     * @param string data   ['34d8c298cbc15376d0d0006e5096cf74','c8716ba9ace204814dfb8378b09952dd']
     */
    public function actionIndex()
    {
        set_time_limit(0);
        error_reporting(0);
        ini_set('memory_limit', '2048M');
        if (Yii::$app->request->post()) {
            //验证ip、key是否正确
            $allowIp = ['112.64.233.130','116.226.81.142','::1','192.168.10.2'];
            $userIp = Yii::$app->request->getUserIP();
            //var_dump($userIp);exit;
            $key = Yii::$app->request->post('key');
            if (!array_search($userIp,$allowIp) || $key != md5("tidyInformation".date("Y-m-d H"))) {
                Msg::AjaxReturn(30,Msg::errorMsg(30),0,1);
            }
            $data = Yii::$app->request->post('data');
            //先将所有数据插入临时表temp_user_check
            $data = json_decode($data,true);
            count($data) > 10000 ? Msg::AjaxReturn(40,Msg::errorMsg(40),0,1) : '';
            $insert = "INSERT INTO temp_user_check (md5_phone,check_time) VALUES ";
            foreach ($data as $vo) {
                $insert .= "('{$vo}',now()),";
            }
            $insert = rtrim($insert,',');
            $insert .= " ON DUPLICATE KEY UPDATE md5_phone = VALUES(md5_phone),check_time=VALUES(check_time)";
            //var_dump($insert);exit;
            //$insert = mysql_real_escape_string($insert);
            Yii::$app->db201606->createCommand($insert)->execute();

            //开始进行left join 查询匹配
            $sql = "SELECT b.phone,b.md5_phone FROM temp_user_check a LEFT JOIN temp_user_customer b ON (a.md5_phone = b.md5_phone) limit 10000";

            $result = Yii::$app->db1->createCommand($sql)->queryAll();
            //将查询出来的数据做log记录
            $insert = "INSERT INTO temp_user_check_log (md5_phone,phone,return_time) VALUES ";
            foreach ($result as $vo) {
                $insert .= "('{$vo['md5_phone']}',{$vo['phone']},now()),";
            }
            $insert = rtrim($insert,',');
            Yii::$app->db201606->createCommand($insert)->execute();

            //释放资源
            Yii::$app->db201606->createCommand("DELETE FROM temp_user_check WHERE 1")->execute();
                //列出查询结果并返回
            $result ? Msg::AjaxReturn(0,0,$result,0) : Msg::AjaxReturn(20,Msg::errorMsg(20),0,1);
        } else {
            Msg::AjaxReturn(0,Msg::errorMsg(10),0,1);
        }
    }
}