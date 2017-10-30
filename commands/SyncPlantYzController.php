<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\commands;

use Yii;
use yii\base\Exception;
use yii\console\Controller;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class SyncPlantYzController extends Controller {

    public function init() {
        set_time_limit(0);
        ini_set('memory_limit', -1);
    }
    
    /*
     * 友赞获取需要更新的数据
     */
    public function actionAysnMessage()
    {
        
        $sql = "SELECT * FROM tidy_order_yz WHERE sync=0";
        $orderArray = Yii::$app->db40->createCommand($sql)->queryAll();
        foreach ($orderArray as $key=>$values) {
            foreach ($values as $k=>$v) {
                if (is_array(json_decode($v,true))) {
                    $values[$k] = json_decode($v,true);
                }
            }
            $orderArray[$key] = $values;
        }
        if (empty($orderArray)) {
            die('no message');
        }
        $count = count($orderArray);
        $unifiedOrderStr = "";
        for($i = 0; $i < $count; $i++){
            $unifiedOrderStr .= "('plant', CURRENT_TIMESTAMP()),";
        }
        $sqlUnifiedOrder = "INSERT INTO tidy_unified_order_yz (group_type, create_datetime) VALUES " . rtrim($unifiedOrderStr, ",");
        Yii::$app->db40->createCommand($sqlUnifiedOrder)->execute();
        $lastInsertID = Yii::$app->db40->lastInsertID;
        for ($j = 0; $j < $count; $j++){
            $orderIdArray[] = $lastInsertID + $j;
        }
        $insertOrder = "INSERT INTO tidy_plant_order_yz (id, show_id, city_id, order_reprice, is_pay, pay_type, pay_datetime, create_datetime, send_count, total_period, done_datetime,phone,shou_phone,server_id,current_takeuserid,shou_name,shou_address,channel,yz_id,`status`) VALUES ";
        $insertPeriod = "INSERT INTO tidy_plant_order_period_yz (order_id, send_count,predict_datetime,city_id,done_datetime,`status`,create_datetime,takeuserid,mark,delivery_mark,server_id,shou_phone,shou_name,shou_address) VALUES";
        $insertItem = "INSERT INTO tidy_plant_order_item_yz (order_id, goods_id, title, num, total_reprice) VALUES ";
        $insertPayLog = "INSERT INTO tidy_pay_log_plant_yz (session_id, pay_type, price, pay_time,group_type,serial_number) VALUES ";
        $insertSync = " INSERT INTO tidy_order_yz (id) VALUES ";
        //对订单循环，并将订单同步到绿植订单表与周期表中
        foreach ($orderArray as $key => $item) {
            $orderId = $orderIdArray[$key];
            $showId = $this->encrypt($orderId);
            //收货地址
            $shouAddress = $item['receiver_state'].$item['receiver_city'].$item['receiver_district']." ".$item['receiver_address'];
            $shouPhone = $item['receiver_mobile'];//手机号
            $shouName = $item['receiver_name'];//人名
            
            //开始计算周期网点以及小哥
            $info = $this->getYzInfo($item['receiver_city'],$item['receiver_address'],$item['lng'],$item['lat']);
            $city_id = $info['city_id'];$station_id = $info['station_id'];$xiaoge = $info['xiaoge'];
            //再获取商品信息以及配送周期
            $goodsInfo = $this->getGoodsInfo($city_id,$item['title'],$item['price']);
            $period = $goodsInfo['period'];
            $goods_id = $goodsInfo['goods_id'];
            $pay_datetime = strtotime($item['pay_time']);
            switch ($item['pay_type'])
            {
                case 'WEIXIN':
                    $pay_type = 'wxpay';
                    break;
                case 'WEIXIN_DAIXIAO':
                    $pay_type = 'wxpay';
                    break;
                case 'ALIPAY':
                    $pay_type = 'alipay';
                    break;
                default :
                    $pay_type = 'cash';
                    break;
            }
            $channel = '有赞';
            $mark = $item['buyer_message'];
            $delivery_mark = $item['orders'][0]['sku_properties_name'];
            
            //开始处理订单状态
            switch ($item['status'])
            {
                case 'WAIT_BUYER_PAY':
                    $status = 'xdcg';
                    break;
                case 'WAIT_GROUP':
                    $status = 'xdcg';
                    break;
                case 'WAIT_SELLER_SEND_GOODS':
                    $status = 'dps';
                    break;
                case 'WAIT_BUYER_CONFIRM_GOODS':
                    $status = 'ywc';
                    break;
                case 'TRADE_BUYER_SIGNED':
                    $status = 'khqs';
                    break;
                case 'TRADE_CLOSED_BY_USER':
                    $status = 'qxdd';
                    break;
                case 'TRADE_CLOSED':
                    $status = 'qxdd';
                    break;
            }
            $lastPredictHour = mt_rand(8, 22);
            $lastPredictMinute = mt_rand(0, 1) == 0 ? "00" : "30";
            $lastPredictDatetime = strtotime(date("Y-m-d", strtotime($item['created'])) . " $lastPredictHour:$lastPredictMinute:00") + 3 * 86400;
            $orderDoneDatetime = 0;
            $orderStatus = 'xdcg';
            for ($i = 1; $i <= $period; $i++) {
                $predictDatetime = 0;
                $doneDatetime = 0;
                if ($i == 1) {
                    $predictDatetime = $lastPredictDatetime;
                } else {
                    $predictDatetime = $lastPredictDatetime + 7 * 86400;
                }
                $lastPredictDatetime = $predictDatetime;
                if ($i <= $period) {
                    $hour = mt_rand(8, 22);
                    $minute = mt_rand(0, 59);
                    $second = mt_rand(0, 59);
                    if ($hour < 10) {
                        $hour = "0" . $hour;
                    }
                    if ($minute < 10) {
                        $minute = "0" . $minute;
                    }
                    if ($second < 10) {
                        $second = "0" . $second;
                    }
                    $doneDatetime = strtotime(date("Y-m-d", $predictDatetime) . " $hour:$minute:$second");
                    $theStatus = 'swps';
                }
                $orderDoneDatetime = $doneDatetime;
                $insertPeriod .= "('{$orderId}', '{$i}', '{$predictDatetime}', '{$city_id}', 0, '{$theStatus}', '{$item['created']}','{$xiaoge}','{$mark}','{$delivery_mark}','{$station_id}','{$shouPhone}','{$shouName}','{$shouAddress}'),";
            }
            if ($period == count($item['orders'])) {
                $orderStatus = 'khqs';
            }
            $insertOrder .= "('{$orderId}', '{$showId}', '{$city_id}', '{$item['price']}', 1, '{$pay_type}', '{$pay_datetime}', '{$item['created']}', 0, '{$period}', 0,'{$shouPhone}','{$shouPhone}','{$station_id}','{$xiaoge}','{$shouName}','{$shouAddress}','{$channel}','{$item['id']}','{$status}'),";
            $insertItem .= "('{$orderId}','{$goods_id}','{$goodsInfo['title']}',1,'{$item['price']}'),";
            $insertPayLog .= "('{$orderId}','{$pay_type}','{$item['price']}','{$item['pay_time']}','plant',0),";
            $insertSync .= "('{$item['id']}'),";
        }
        $insertSync = rtrim($insertSync,',')."ON DUPLICATE KEY UPDATE id = VALUES(`id`),sync=1";
//        echo rtrim($insertOrder,',').";";
//        echo rtrim($insertPeriod,',').";";
//        echo rtrim($insertItem,',').";";
//        echo rtrim($insertPayLog,',').";";exit;
        $res = Yii::$app->db40->beginTransaction();
        $a = Yii::$app->db40->createCommand(rtrim($insertOrder,','))->execute();
        $b = Yii::$app->db40->createCommand(rtrim($insertPeriod,','))->execute();
        $c = Yii::$app->db40->createCommand(rtrim($insertItem,','))->execute();
        $d = Yii::$app->db40->createCommand(rtrim($insertPayLog,','))->execute();
        $e = Yii::$app->db40->createCommand($insertSync)->execute();
        if ($a && $b && $c && $d && $e) {
            $res->commit();
            return true;
        } else {      
            $res->rollBack();
            return false;
        }
    }
    
    
    //获取city_id，网点id,小哥ID
    public function getYzInfo($receiver_city,$receiver_address,$lng, $lat)
    {
        //虚拟账号列表getStation
        $array = [
            35=>['num'=>900001,'phone'=>'12345678901','account'=>'xnzh01'],
            36=>['num'=>900002,'phone'=>'12345678902','account'=>'xnzh02'],
            21=>['num'=>900003,'phone'=>'12345678903','account'=>'xnzh03'],
            20=>['num'=>900004,'phone'=>'12345678904','account'=>'xnzh04'],
            34=>['num'=>900005,'phone'=>'12345678905','account'=>'xnzh05'],
            33=>['num'=>900006,'phone'=>'12345678906','account'=>'xnzh06'],
            32=>['num'=>900007,'phone'=>'12345678907','account'=>'xnzh07'],
            31=>['num'=>900008,'phone'=>'12345678908','account'=>'xnzh08'],
            30=>['num'=>900009,'phone'=>'12345678909','account'=>'xnzh09'],
            29=>['num'=>900010,'phone'=>'123456789010','account'=>'xnzh10'],
            162=>['num'=>900011,'phone'=>'12345678911','account'=>'xnzh11'],
            169=>['num'=>900012,'phone'=>'12345678912','account'=>'xnzh12'],
        ];
        //先获取城市ID
        $cityName = mb_substr($receiver_city,0,2,'utf-8');
        $cityId = Yii::$app->db->createCommand("SELECT cid FROM tidy_area WHERE name LIKE '%$cityName%' AND pid = 0")->queryScalar();
        //获取小哥网点信息
        $station_id = $this->getStation($cityId,$lng,$lat);
        if (!$station_id) {
            $station_id = 0;
            $xiaogeId = $array[$cityId]['num'];
        } else {
            $xiaogeId = $this->getXiaogeIdFromServer($station_id,$lat,$lng)['xiaogeId'];
            if ($xiaogeId == false) {
                $xiaogeId = $array[$cityId]['num'];
            }
        }
        return array('city_id'=>$cityId,'station_id'=>$station_id,'xiaoge'=>$xiaogeId);
    }
    
    //获取商品id以及订单周期
    public function getGoodsInfo($cityId,$title,$price)
    {
        $title = mb_substr($title,0,4,'utf-8');
        $sql = "SELECT goods_id,period,title FROM tidy_plant_goods WHERE city_id = {$cityId} AND title LIKE '%$title%' AND disprice = '{$price}'";
        $info = Yii::$app->db->createCommand($sql)->queryOne();
        if (!$info) {
            $info = Yii::$app->db->createCommand("SELECT goods_id,period,title FROM tidy_plant_goods WHERE city_id = {$cityId} AND disprice = '{$price}'")->queryOne();
        }
        if (!$info) {
            $info = Yii::$app->db->createCommand("SELECT goods_id,period,title FROM tidy_plant_goods WHERE disprice = '{$price}'")->queryOne();
        }
        return $info;
    }
    /**
     * 加密
     * 订单号由四部分组成  业务号（2位）+平台号（2位）+日期（6位）+6位随机数(目前取小订单号后六位，不足六位前面补足0)
     */

    public function encrypt($data, $groupType='plant',$platformName='tidy')
    {
        $service = '10'; //业务号，默认为洗涤
        $platform = '00'; //平台号, 默认为泰笛
        $date = '000000'; //日期号
        $random = '000000'; //随机数
        switch($groupType){
            case 'wash':$service = '10';break;
            case 'periodwash':$service = '11';break;
            case 'plant':$service = '20';break;
            case 'plant_rent':$service = '30';break;
        }
        switch($platformName){
            case 'tidy':$platform = '00';break;
        }
        $date = date('ymd');
        $random = substr(strrev($data).'000000',0,6);
        return $service . $platform . $date . $random;
    }
    
    
    /**
     * 网点选择
     * @param unknown_type $cityId
     * @param unknown_type $lng
     * @param unknown_type $lat
     * @return boolean|unknown
     */
    public function getStation( $cityId, $lng, $lat ){
        $sql_s = "SELECT id,lng,lat,scope FROM `tidy_service_info` WHERE city_id=:cityId AND `status`=1 ORDER BY lng";
        $list_s = Yii::$app->db->createCommand($sql_s)->bindValues(array(":cityId"=>$cityId))->queryAll();
        if( empty( $list_s ) ) return false;
        $heap['id']=$heap['lng']=$heap['lat']=$heap['scope']=$heap['value']=0;
        foreach ( $list_s as $val ){
            $value = $this->getDis( (double)$lng, (double)$lat, (double)$val[ 'lng' ], (double)$val[ 'lat' ] );
            if($val[ 'scope' ]>=$value){
                if($heap['id']==0 || $heap['value']>$value){
                    $heap['id']=$val['id'];
                    $heap['lng']=$val['lng'];
                    $heap['lat']=$val['lat'];
                    $heap['scope']=$val['scope'];
                    $heap['value']=$value;
                }
            }
        }
        if( $heap['id'] != 0 ) return $heap['id'];
        else return false;
    }
    
    /**
     * 根据经纬度计算俩点间的直线距离
     * @param unknown_type $lng1
     * @param unknown_type $lat1
     * @param unknown_type $lng2
     * @param unknown_type $lat2
     * @return number
     */
    private function getDis( $lng1, $lat1, $lng2, $lat2 ){
        $earthRadius = 6378.137;
        $lat1 = ($lat1 * pi() ) / 180;
        $lng1 = ($lng1 * pi() ) / 180;
        $lat2 = ($lat2 * pi() ) / 180;
        $lng2 = ($lng2 * pi() ) / 180;
        $calcLongitude = $lng2 - $lng1;
        $calcLatitude = $lat2 - $lat1;
        $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
        $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
        $calculatedDistance = $earthRadius * $stepTwo;
        return round($calculatedDistance);
    }
    
    /**
     * 获取网点下的小哥
     *
     * @param int $serviceId 网点id
     * @param $lat
     * @param $lng
     * @return array|bool
     */
    public function getXiaogeIdFromServer($serviceId, $lat, $lng)
    {
        // 扇形分配小哥ID(根据方位)
        $sql = "SELECT `lat`, `lng` FROM `tidy_service_info` WHERE id = {$serviceId}";
        $res = Yii::$app->db->createCommand($sql)->queryOne();
        $x = $lat - $res['lat'];
        $y = $lng - $res['lng'];
        $deg = rad2deg(atan2($y, $x)); // 弧度转化为角度
        if ($deg < 0) {
            $deg = $deg + 360;
        }
        // 获取方位
        $position = 1;
        $degArr = array(0, 45, 90, 135, 180, 225, 270, 315, 360);
        foreach ($degArr as $key => $val) {
            if ($deg >= $degArr[$key] && $deg < $degArr[$key + 1]) {
                $position = $key + 1;
                break;
            }
        }

        // 随机分配一个当前网点的小哥
        $sql = "SELECT e.`user_id` FROM `tidy_station_emp_position` AS p
            LEFT JOIN `tidy_station_emp` AS e ON(p.station_id = e.station_id AND p.user_id = e.user_id)
            WHERE p.station_id = {$serviceId} AND p.position = {$position} order by rand() limit 1";
        $result = Yii::$app->db->createCommand($sql)->queryOne();
        $userId = $result['user_id'];

        // 当前网点没有小哥，找周围网点的
        if (empty($userId)) {
            // 算出周围两个方位
            $positionList = array();
            $positionList[0] = ($position - 1) == 0 ? 8 : $position - 1;
            $positionList[1] = ($position + 1) == 9 ? 1 : $position + 1;
            $positionList = implode(',', $positionList);
            $sql = "SELECT e.`user_id` FROM `tidy_station_emp_position` AS p
                LEFT JOIN `tidy_station_emp` AS e ON(p.station_id = e.station_id AND p.user_id = e.user_id)
                WHERE p.station_id = {$serviceId} AND p.position IN($positionList) limit 1";
            $result = Yii::$app->db->createCommand($sql)->queryOne();
            $userId = $result['user_id'];
        }

        // 超出每日接单量则自动转单
        if ($userId) {
            $todayZero = date('Y-m-d 00:00:00');
            $tomorrowZero = date('Y-m-d 00:00:00', strtotime($todayZero) + 86400);
            $sql = "SELECT count(*) AS count FROM `tidy_plant_order` WHERE `current_takeuserid` = {$userId}
               AND `create_datetime` > '{$todayZero}' AND `create_datetime` < '{$tomorrowZero}'
               UNION ALL SELECT DISTINCT count(*) AS count FROM `tidy_session_order`
               WHERE (`takeuserid` = {$userId} OR `yunuserid` = {$userId}) AND `create_datetime` > '{$todayZero}'
               AND `create_datetime` < '{$tomorrowZero}'";
            $result = Yii::$app->db->createCommand($sql)->queryAll();
            $count = $result[0]['count'] + $result[1]['count'];
            if ($count >= 5) {
                $userId = "";
            }
        }

        // 网点分配不到，分配给店长。没有店长就分配给送货最多的
        if (empty($userId)) {
            $sql = "SELECT `user_id` FROM `tidy_station_emp` WHERE `station_id` = {$serviceId} AND `is_glder` = 1";
            $result = Yii::$app->db->createCommand($sql)->queryOne();
            $userId = $result['user_id'];
            if (empty($userId)) {
                $sql = "SELECT `user_id` FROM `tidy_station_emp` WHERE `station_id` = {$serviceId} ORDER BY `take_count` ASC LIMIT 1";
                $result = Yii::$app->db->createCommand($sql)->queryOne();
                $userId = $result['user_id'];
                if (empty($userId)) {
                    return false;
                }
            }
        }

        $sql = "UPDATE `tidy_station_emp` SET take_count = take_count + 1, yun_count = yun_count + 1 WHERE user_id = {$userId}";
        Yii::$app->db->createCommand($sql)->execute();
        return array("stationId" => $serviceId, 'xiaogeId' => $userId);
    }
}