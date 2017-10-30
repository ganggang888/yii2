<?php

namespace app\controllers;

use Yii;
use app\models\Company;
use app\models\SearchCompany;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use app\services\ToolExtend;
use yii\helpers\Url;
/**
 * CompanyController implements the CRUD actions for Company model.
 */
class CompanyController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Company models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchCompany();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Company model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Company model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Company();
        if ($model->load(Yii::$app->request->post())) {
            $model->certificates = json_encode($model->certificates);
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Company model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        if ($model->load(Yii::$app->request->post())) {
            $model->certificates = json_encode($model->certificates);
            if ($model->save()) {
                return $this->redirect(['index']);
            } else {
                return $this->render('update', [
                'model' => $model,
            ]);
            }
            
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Company model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Company model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Company the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Company::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionUploadImages()
    {
        if(Yii::$app->request->isPost){
            $images = UploadedFile::getInstancesByName('Company[certificates]');
            $res = [];
            $initialPreview = [];
            $initialPreviewConfig = [];
            $post = Yii::$app->request->post();
            $action = isset($post['action']) ? $post['action'] : "";
            if(is_array($images)){
                foreach($images as $image){
                    //这里限制图片大小
                    if($image->size > 1 * 1024 *1024){
                        return json_encode(['error' => '图片不能超过1M！']);
                    }
                    //限制图片扩展名
                    $allowExtArr = ['jpg', 'jpeg', 'png', 'gif'];
                    if(!in_array(strtolower($image->extension), $allowExtArr)){
                        return json_encode(['error' => '只支持jpg、jpeg、png、gif格式图片！']);
                    }
                    $dir = '/upload/temp/';
                    //生成UUID保证文件名唯一性
                    $uuid = ToolExtend::genUuid();
                    $fileName = $uuid . '.' . $image->getExtension();
                    //如果文件夹不存在，则新建文件夹
                    $alias = Yii::getAlias('@webroot');
                    if (!file_exists($alias . $dir)) {
                        FileHelper::createDirectory($alias . $dir, 777);
                    }
                    $filePath = realpath($alias . $dir) . '/';
                    $file = $filePath . $fileName;
                    if($image->saveAs($file)){
                        $imagePath = $dir . $fileName;
                        $style = "width:auto;height:160px";
                        array_push($initialPreview, "<img src='" . Yii::getAlias("@web") . $imagePath . "' class='file-preview-image' style='" . $style . "' alt='" . $fileName . "' title='" . $fileName . "'>");
                        $config = [
                            'caption' => $fileName,
                            'width' => '120px',
                            'url' => Url::to(['delete-file','path'=>$file]), // server delete action
                            'key' => $uuid,
                            'extra' => ['filename' => $fileName]
                        ];
                        array_push($initialPreviewConfig, $config);
                        if($action == "update"){
                            $res = [
                                "initialPreview" => $imagePath,
                                "initialPreviewConfig" => $initialPreviewConfig,
                                "imgFile" => "<input name='Company[certificates][]' class='image-hide' id='" . $uuid . "' type='hidden' value='" . $imagePath . "'/>",
                                "key" => $uuid,
                            ];
                        }else{
                            $res = [
                                "initialPreview" => $initialPreview,
                                "initialPreviewConfig" => $initialPreviewConfig,
                                "imgFile" => "<input name='Company[certificates][]' class='image-hide' id='" . $uuid . "' type='hidden' value='" . $imagePath . "'/>",
                                "key" => $uuid,
                            ];
                        }

                    }
                }
            }
            return json_encode($res);
        }
    }
    
    public function actionDeleteFile()
    {
        $path = Yii::$app->request->get('path');
        is_file($path) ? unlink($path) : '';
        $this->ajaxSuccess();
    }
    
    
}
