<?php
namespace app\modules\slider\controllers;

use Yii;
use yii\data\ActiveDataProvider; 
 
use app\modules\transaction\models\Transaction;
 
use  yii\db\ActiveRecord;
use  yii\data\ActiveDataProvider;


class AController extends Controller
{
    

    public function actionIndex()
    {
        $data = new ActiveDataProvider([
            'query' => Transaction::find()->all(),
        ]);
        return $this->render('index', [
              'data' => $data
        ]);
    }

    public function actionCreate()
    {
        $model = new Transaction;

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else{
              
                return $this->refresh();
            }
        }
        else {
            return $this->render('create', [
                'model' => $model
            ]);
        }
    }

    public function actionEdit($id)
    {
        $model = Transaction::findOne($id);

        if($model === null){
            $this->flash('error', Yii::t('easyii', 'Not found'));
            return $this->redirect(['/admin/'.$this->module->id]);
        }

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else{
                if(($fileInstanse = UploadedFile::getInstance($model, 'image')))
                {
                    $model->image = $fileInstanse;
                    if($model->validate(['image'])){
                        $model->image = Image::upload($model->image, 'slider');
                    }
                    else {
                        $this->flash('error', Yii::t('easyii', 'Update error. {0}', $model->formatErrors()));
                        return $this->refresh();
                    }
                }
                else{
                    $model->image = $model->oldAttributes['image'];
                }

                if($model->save()){
                    $this->flash('success', Yii::t('easyii/slider', 'Carousel updated'));
                }
                else{
                    $this->flash('error', Yii::t('easyii/slider','Update error. {0}', $model->formatErrors()));
                }
                return $this->refresh();
            }
        }
        else {
            return $this->render('edit', [
                'model' => $model
            ]);
        }
    }

    public function actionDelete($id)
    {
        if(($model = Carousel::findOne($id))){
            $model->delete();
        } else {
            $this->error = Yii::t('easyii', 'Not found');
        }
        return $this->formatResponse(Yii::t('easyii/slider', 'Carousel item deleted'));
    }
 

    
}