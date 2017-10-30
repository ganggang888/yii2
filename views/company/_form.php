<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Investment;
use kartik\file\FileInput;
use yii\helpers\Url;
use kartik\date\DatePicker;
/* @var $this yii\web\View */
/* @var $model app\models\Company */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="company-form">
  <?php $form = ActiveForm::begin([
    'options' => ['id' => 'form1','class' => 'form-horizontal'],
    'fieldConfig' => [
        'template' => "<div class='col-xs-3 col-sm-2 text-right'>{label}</div><div class='col-xs-9 col-sm-7'>{input}</div><div class='col-xs-12 col-xs-offset-3 col-sm-3 col-sm-offset-0'>{error}</div>",
    ]
]); ?>
  <table class="table">
    <tr>
      <td><?= $form->field($model, 'term_id')->dropDownList(Investment::getList()) ?></td>
      <td><?= $form->field($model, 'place',['inputOptions' => [
        'placeholder' => '16所',
		'class' => 'form-control'
    ]])->textInput(['maxlength' => true]) ?></td>
    </tr>
    <tr>
      <td><?= $form->field($model, 'enterprise_code',['inputOptions' => [
        'placeholder' => '053037341',
		'class' => 'form-control'
    ]])->textInput(['maxlength' => true]) ?></td>
      <td><?= $form->field($model, 'name',['inputOptions' => [
        'placeholder' => '上海XXXX有限公司',
		'class' => 'form-control'
    ]])->textInput(['maxlength' => true]) ?></td>
    </tr>
    <tr>
      <td><?= $form->field($model, 'area')->dropDownList(Investment::getArea()) ?></td>
      <td><?= $form->field($model, 'postal_code',['inputOptions' => [
        'placeholder' => '200031',
		'class' => 'form-control'
    ]])->textInput() ?></td>
    </tr>
    <tr>
      <td><?= $form->field($model, 'address',['inputOptions' => [
        'placeholder' => '延安西路2000号',
		'class' => 'form-control'
    ]])->textInput(['maxlength' => true]) ?></td>
      <td><?= $form->field($model, 'legal_person',['inputOptions' => [
        'placeholder' => '王可可',
		'class' => 'form-control'
    ]])->textInput() ?></td>
    </tr>
    <tr>
      <td><?= $form->field($model, 'telephone',['inputOptions' => [
        'placeholder' => '40012345678',
		'class' => 'form-control'
    ]])->textInput(['maxlength' => true]) ?></td>
      <td><?= $form->field($model, 'phone',['inputOptions' => [
        'placeholder' => '12345678911',
		'class' => 'form-control'
    ]])->textInput(['maxlength' => true]) ?></td>
    </tr>
    <tr>
      <td><?= $form->field($model, 'credit_code',['inputOptions' => [
        'placeholder' => '91304215421421X',
		'class' => 'form-control'
    ]])->textInput(['maxlength' => true]) ?></td>
      <td><?= $form->field($model, 'establish_day')->widget(DatePicker::classname(), [ 
    'options' => ['placeholder' => ''], 
    'pluginOptions' => [ 
        'autoclose' => true, 
        'todayHighlight' => true, 
		'format' => 'yyyy-mm-dd',
    ] 
]); ?></td>
    </tr>
    <tr>
      <td><?= $form->field($model, 'industry')->dropDownList(Investment::industry()) ?></td>
      <td><?= $form->field($model, 'capital',['inputOptions' => [
        'placeholder' => '万为单位，示例：20.0',
		'class' => 'form-control'
    ]])->textInput(['maxlength' => true]) ?></td>
    </tr>
    <tr class="todo">
      <td>财务信息</td>
      <td></td>
    </tr>
    <tr>
      <td><?= $form->field($model, 'tax_name')->textInput(['maxlength' => true]) ?></td>
      <td><?= $form->field($model, 'tax_telephone')->textInput(['maxlength' => true]) ?></td>
    </tr>
    <tr>
      <td><?= $form->field($model, 'tax_phone')->textInput(['maxlength' => true]) ?></td>
      <td></td>
    </tr>
    <tr class="todo">
      <td>其他信息</td>
      <td></td>
    </tr>
    <tr>
      <td><?= $form->field($model, 'increment')->textInput(['maxlength' => true]) ?></td>
      <td><?= $form->field($model, 'business')->textInput(['maxlength' => true]) ?></td>
    </tr>
    <tr>
      <td><?= $form->field($model, 'income')->textInput(['maxlength' => true]) ?></td>
      <td><?= $form->field($model, 'personal')->textInput(['maxlength' => true]) ?></td>
    </tr>
    <tr>
      <td><?= $form->field($model, 'manage')->textInput(['maxlength' => true]) ?></td>
      <td><?= $form->field($model, 'settlement')->textInput(['maxlength' => true]) ?></td>
    </tr>
    <tr>
      <td><?= $form->field($model, 'bank_name')->textInput(['maxlength' => true]) ?></td>
      <td><?= $form->field($model, 'bank_number')->textInput(['maxlength' => true]) ?></td>
    </tr>
    <tr>
      <td><?= $form->field($model, 'is_cognizance')->dropDownList([ 'Y' => 'Y','N' => 'N']) ?></td>
      <td><?= $form->field($model, 'is_ratepaying')->dropDownList([ 'Y' => 'Y','N' => 'N']) ?></td>
    </tr>
    <tr>
      <td><?= $form->field($model, 'remark')->textarea(['rows'=>6]) ?></td>
      <td><?= $form->field($model, 'is_close')->dropDownList([ 'Y' => 'Y','N' => 'N']) ?></td>
    </tr>
    <tr>
      <td><?php
  $initialPreview = $initialPreviewConfig =  [];
	$imgFile = "";
        $files = [];
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
        
        ];
  if ($model->certificates) {
  	$files = json_decode($model->certificates,true);
	
	foreach ($files as $vo)
	{
		$initialPreview[] = Yii::getAlias("@web") .$vo;
		$initialPreviewConfig[] = [
			'caption' => $vo,
			'width' => '120px',
			'url' => Url::to(['delete-file','path'=>Yii::getAlias('@webroot') .$vo]), // server delete action
			'extra' => ['filename' => $vo]
		];
		$imgFile .=  "<input name='Company[certificates]'  type='hidden' value='" . $vo . "'/>";
	}
	$arr['initialPreview'] = $initialPreview;
	$arr['initialPreviewAsData'] = true;
	$arr['initialPreviewConfig'] = $initialPreviewConfig;
	$arr['imgFile'] = $imgFile;
  }
  ?>
        <?= $form->field($model, 'certificates')->widget(FileInput::className(), [
    'options' => [
        'multiple' => true
    ],
    'pluginOptions' => $arr,
    'pluginEvents' => [
        //上传完成触发
        'fileuploaded' => 'function(event, data, previewId, index) {
                             //$(".image-hide").remove();
							 alert(data.response.imgFile);
                         $(event.currentTarget.closest("form")).append(data.response.imgFile);
                        }',
        //删除前事件
        'filepredelete' => "function(event, key) {

                    }",
        //删除后事件
        'filedeleted' => 'function(event, key) {
                        //$(event.currentTarget.closest("form")).find("#"+key).remove();
                    }',
    ],
])?></td>
      <td></td>
    </tr>
  </table>
  <?php
if ($files) {
    foreach ($files as $vo) {
        echo "<input name='Company[certificates][]' class='image-hide' type='hidden' value='{$vo}'/>";
    }
    
}

?>
  <div class="form-group">
    <?= Html::submitButton('保存信息', ['class' => 'btn btn-success']) ?>
  </div>
  <?php ActiveForm::end(); ?>
</div>
<?php
$css = <<<CSS
.company-create h1{ background:#333; color:#FFF; font-size:20px!important; padding: 15px 15px}
.company-create .todo{ background:#333; color:#FFF; font-size:20px!important; padding: 15px 15px}
CSS;
$this->registerCss($css);
?>
