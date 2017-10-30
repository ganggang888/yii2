<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Investment;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SearchCompany */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Companies';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="company-index">
  <h1>
    <?= Html::encode($this->title) ?>
  </h1>
  <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
  <p>
    <?= Html::a('Create Company', ['create'], ['class' => 'btn btn-success']) ?>
  </p>
  <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                        'attribute' => 'name',
                        'headerOptions' => ['width' => '100px'],
                        'filter' => Html::input('text', 'SearchCompany[title]', $searchModel->name, ['class' => 'form-control input-sm']),
                        'format' => 'raw',
                        'value' => function($data){
                            return Html::a($data->name,[$data->id],[
                            'title' => $data->name,
                            'class' => 'show-image',
                            'data-toggle' => 'modal',
                            'data-target' => '#image-modal',
                        ]);}
                    ],
					'enterprise_code',
					[
                        'attribute' => 'term_id',
						'headerOptions' => ['width' => '120px'],
                        'format' => 'raw',
                        'filter' => Html::dropDownList('SearchCompany[term_id]', $searchModel->term_id,
                            Investment::getList(),
                            ['class' => 'form-control input-sm']
                        ),
                        'value' => function($data){
                            $modules = Investment::getList();
                            return isset($modules[$data->term_id]) ? $modules[$data->term_id] : '--';
                        }
                    ],
					'legal_person',
					'credit_code',
					'establish_day',
					'is_cognizance',
            
            
            // 'area',
            // 'place',
            // 'industry',
            // 'telephone',
            // 'establish_day',
            // 'postal_code',
            // 'phone',
            // 'credit_code',
            // 'capital',
            // 'tax_name',
            // 'tax_telephone',
            // 'tax_phone',
            // 'increment',
            // 'business',
            // 'income',
            // 'personal',
            // 'manage',
            // 'settlement',
            // 'bank_name',
            // 'bank_number',
            // 'is_cognizance',
            // 'is_ratepaying',
            // 'is_close',
            // 'remark',
            // 'certificates:ntext',
		
            [
                        'class' => 'yii\grid\ActionColumn',
						'header' => '操作',
						'headerOptions' => ['width' => '150'],
                        'template' => '{update}        {xieye}',
                        'buttons' => [
'xieye' => function ($url, $model, $key) {
     return  Html::a('<span>歇业</span>', $url, ['title' => '歇业','class'=>'btn btn-success',] ); 
                 },
				 'update' =>function($url,$model,$key) {
					 return  Html::a('<span>修改</span>', $url, ['title' => '修改','class'=>'btn btn-success',] ); 
                 },
],
                    ]
        ],
    ]); ?>
</div>
