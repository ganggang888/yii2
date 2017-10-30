<?php
namespace app\models;

use Yii;

class Common extends \yii\db\ActiveRecord
 {

    //获取上海区县
    public static function getArea() {
        return array ( '黄浦区' => '黄浦区', '徐汇区' => '徐汇区', '长宁区' => '长宁区', '静安区' => '静安区', '普陀区' => '普陀区', '虹口区' => '虹口区', '杨浦区' => '杨浦区', '闵行区' => '闵行区', '宝山区' => '宝山区', '嘉定区' => '嘉定区', '浦东区' => '浦东区', '金山区' => '金山区', '松江区' => '松江区', '青浦区' => '青浦区', '奉贤区' => '奉贤区', '崇明区' => '崇明区');
    }

    //获取行业
    public static function industry()
    {
        return array ( '保险业' => '保险业', '采矿' => '采矿', '能源' => '能源', '餐饮' => '餐饮', '宾馆' => '宾馆', '电讯业' => '电讯业', '房地产' => '房地产', '服务' => '服务', '服装业' => '服装业', '公益组织' => '公益组织', '广告业' => '广告业', '航空航天' => '航空航天', '化学' => '化学', '健康' => '健康', '保健' => '保健', '建筑业' => '建筑业', '教育' => '教育', '培训' => '培训', '计算机' => '计算机', '金属冶炼' => '金属冶炼', '警察' => '警察', '消防' => '消防', '军人' => '军人', '会计' => '会计', '美容' => '美容', '媒体' => '媒体', '出版' => '出版', '木材' => '木材', '造纸' => '造纸', '零售' => '零售', '批发' => '批发', '农业' => '农业', '旅游业' => '旅游业', '司法' => '司法', '律师' => '律师', '司机' => '司机', '体育运动' => '体育运动', '学术研究' => '学术研究', '演艺' => '演艺', '医疗服务' => '医疗服务', '艺术' => '艺术', '设计' => '设计', '银行' => '银行', '金融' => '金融', '因特网' => '因特网', '音乐舞蹈' => '音乐舞蹈', '邮政快递' => '邮政快递', '运输业' => '运输业', '政府机关' => '政府机关', '机械制造' => '机械制造', '咨询' => '咨询' );
    }
    
    public static function getFileArr($action)
    {
        $arr = [
        'uploadUrl' => Url::to(['upload-images']),
        'showRemove' => false,
        'previewFileType' => 'image',
        'allowedFileTypes' => ['image'],
        'msgUploadEmpty' => '没有需要上传的图片',
        'uploadExtraData' => [
//                    'album_id' => 20,
//                    'cat_id' => 'Nature'
        ],
        'maxFileCount' => 10,
        'initialPreview' => [
        Yii::getAlias("@web") . $model->certificates
        ],
        'initialPreviewAsData' => true,
        'initialPreviewConfig' => [
        [
        'caption' => $model->certificates,
        'width' => '120px',
        'url' => Url::to(['delete-file']), // server delete action
        'extra' => ['filename' => $model->certificates[0]]
        ],
            [
        'caption' => $model->certificates,
        'width' => '120px',
        'url' => Url::to(['delete-file']), // server delete action
        'extra' => ['filename' => $model->certificates[0]]
        ],
        ],
        "imgFile" => "<input name='Company[certificates]'  type='hidden' value='" . $model->certificates . "'/><input name='Company[certificates]'  type='hidden' value='" . $model->certificates . "'/>"
        ];
        if ($action == 'create') {
            return $arr;
        } else {
            
        }
    }
}
