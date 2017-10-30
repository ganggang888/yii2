<?php
/*
 * 同步API
 */
namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\Msg;
use yii\helpers\HtmlPurifier;
class SyncController extends Controller
{

    public $enableCsrfValidation = false;

    /*
     * 根据传输的json md5后的手机号匹配是否在库中存在
     * @param string data   ['34d8c298cbc15376d0d0006e5096cf74','c8716ba9ace204814dfb8378b09952dd']
     */
    public function actionIndex()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        set_time_limit(0);
        error_reporting(0);
        ini_set('memory_limit', '2048M');
        throw new \yii\web\NotFoundHttpException;
        $allowIp = ['112.64.233.130','116.226.81.142','::1','192.168.10.2','112.64.233.130'];
        $userIp = Yii::$app->request->getUserIP();

        if (Yii::$app->request->post()) {
            //验证ip、key是否正确

            //var_dump($userIp);exit;
            $key = Yii::$app->request->post('key');
            //不在IP返回404
            if (!in_array($userIp,$allowIp)) {
                Header("HTTP/1.1 404 Not Found");exit;
            }
            if (!in_array($userIp,$allowIp) || $key != md5("tidyInformation".date("Y-m-d"))) {
               return Msg::AjaxReturn(10,Msg::errorMsg(10),0,1);
            }
            $data = Yii::$app->request->post('data');
            //xss过滤
            $data = HtmlPurifier::process($data);
            //先将所有数据插入临时表temp_user_check
            $data = json_decode($data,true);
            //大于1W并且不为json数据时。
            if (count($data) > 10000 || !$data) {
                return Msg::AjaxReturn(40,Msg::errorMsg(40),0,1);
            }
            $insert = "INSERT INTO temp_user_check (md5_phone,check_time) VALUES ";
            $time = time();
            foreach ($data as $key=>$vo) {
                //var_dump($vo);exit;
                if (strlen($vo) != 32) {
                    //不为32位md5数据时返回错误
                    return Msg::AjaxReturn(10,Msg::errorMsg(10),0,1);
                }
                $insert .= "(:md5_phone$key,$time),";
                $bindData["md5_phone".$key] = $vo;
            }


            $insert = rtrim($insert,',');
            $insert .= " ON DUPLICATE KEY UPDATE md5_phone = VALUES(md5_phone),check_time=VALUES(check_time)";
            //var_dump($insert);exit;
            //$insert = mysql_real_escape_string($insert);
            Yii::$app->db201606->createCommand($insert)->bindValues($bindData)->execute();

            //开始进行left join 查询匹配
            $sql = "SELECT b.phone,b.md5_phone FROM temp_user_check a LEFT JOIN temp_user_customer b ON (a.md5_phone = b.md5_phone) limit 10000";

            $result = Yii::$app->db1->createCommand($sql)->queryAll();

            //将查询出来的数据做log记录
            $time = time();
            $insert = "INSERT INTO temp_user_check_log (md5_phone,phone,return_time) VALUES ";
            foreach ($result as $key=>$vo) {
                $insert .= "(:md5_phone$key,:phone$key,$time),";
                $binds["md5_phone".$key] = $vo['md5_phone'];
                $binds["phone".$key] = $vo['phone'];
            }
            $insert = rtrim($insert,',');
            Yii::$app->db201606->createCommand($insert)->bindValues($binds)->execute();
            //释放资源
            Yii::$app->db201606->createCommand("DELETE FROM temp_user_check WHERE 1")->execute();
                //列出查询结果并返回
            return ($result ? Msg::AjaxReturn(0,0,$result,0) : Msg::AjaxReturn(20,Msg::errorMsg(20),0,1));
        } else {
            //var_dump($userIp);var_dump($allowIp);
            if (in_array($userIp,$allowIp)) {
                return Msg::AjaxReturn(10,Msg::errorMsg(10),0,1);
            } else {
                Header("HTTP/1.1 404 Not Found");exit;

            }

        }
    }
}