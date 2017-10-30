<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SearchCompany */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="company-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'term_id') ?>

    <?= $form->field($model, 'enterprise_code') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'address') ?>

    <?php // echo $form->field($model, 'area') ?>

    <?php // echo $form->field($model, 'place') ?>

    <?php // echo $form->field($model, 'industry') ?>

    <?php // echo $form->field($model, 'telephone') ?>

    <?php // echo $form->field($model, 'establish_day') ?>

    <?php // echo $form->field($model, 'postal_code') ?>

    <?php // echo $form->field($model, 'phone') ?>

    <?php // echo $form->field($model, 'credit_code') ?>

    <?php // echo $form->field($model, 'capital') ?>

    <?php // echo $form->field($model, 'tax_name') ?>

    <?php // echo $form->field($model, 'tax_telephone') ?>

    <?php // echo $form->field($model, 'tax_phone') ?>

    <?php // echo $form->field($model, 'increment') ?>

    <?php // echo $form->field($model, 'business') ?>

    <?php // echo $form->field($model, 'income') ?>

    <?php // echo $form->field($model, 'personal') ?>

    <?php // echo $form->field($model, 'manage') ?>

    <?php // echo $form->field($model, 'settlement') ?>

    <?php // echo $form->field($model, 'bank_name') ?>

    <?php // echo $form->field($model, 'bank_number') ?>

    <?php // echo $form->field($model, 'is_cognizance') ?>

    <?php // echo $form->field($model, 'is_ratepaying') ?>

    <?php // echo $form->field($model, 'is_close') ?>

    <?php // echo $form->field($model, 'remark') ?>

    <?php // echo $form->field($model, 'certificates') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
