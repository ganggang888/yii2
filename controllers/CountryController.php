<?php
/**
 * Created by PhpStorm.
 * User: ganggang
 * Date: 2017/5/9
 * Time: 13:53
 */
namespace app\controllers;
use yii\web\Controller;
use yii\data\Pagination;
use app\models\Country;
class CountryController extends Controller
{
    public function actionIndex()
    {
        //call_user_func_array(array('self','actionInfo'),array('messages'));
        $query = call_user_func_array(array(new Country(),'find'),array());
        //$query = Country::find();
        $pagination = new Pagination([
            'defaultPageSize' => 5,
            'totalCount' => $query->count(),
        ]);

        $countries = $query->orderBy('name')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render('index',compact('pagination','countries'));
    }

    public function actionInfo()
    {
        echo func_get_args()[0];
    }
}
