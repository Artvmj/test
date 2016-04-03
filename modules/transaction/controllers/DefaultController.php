<?php

namespace app\modules\transaction\controllers;

use Yii;
use app\modules\transaction\models\sspTransaction;
use app\modules\transaction\models\sspTransactionSearch;
use  app\modules\transaction_category\models\SspTransactionCategory;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\reports\Type;

/**
 * TransactionController implements the CRUD actions for sspTransaction model.
 */
class DefaultController extends Controller
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
     * Lists all sspTransaction models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new sspTransactionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single sspTransaction model.
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
     * Creates a new sspTransaction model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new sspTransaction();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            
           // return $this->redirect(['view', 'id' => $model->id]);
            
             return $this->render('update', [
                'model' => $model,
            ]);
            
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
	 
	 
	 /**
     * Creates a new sspTransaction model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionAjaxcreate()
    {
	//	sum:sum,  category_id
		$error = array();
		$post = \Yii::$app->request->post();
		 
		$model = new sspTransaction();
		
		if(isset($post["sum"]))
		     $model->sum = $post["sum"];
              //   else
                    //  $error["sum"] = "сумма должна включать в себя только цифры";
                
		
		if(isset($post["category_id"]))
		{ 
		      $model->category_id = $post["category_id"]; 
            
		}
		
		
		if(isset(Yii::$app->user->identity->id))
		{
			$model->user_id = Yii::$app->user->identity->id;
			 
		}
		 
		 $model->save(); 
		 $error = $model->getErrors(); 
		
		  if(!count($error))
		  {
			  
		         echo json_encode(["success"=> $model->getPrimaryKey()]);
		   }  
	      else
			 echo json_encode(["error"=>$error]); 
		
		  
    }
	  

    
     /**
     * Удаляет транзакцию через ajax - запрос
      * Возвращает html - строку таблицы с итоговыми суммани
      * Эта строка обновляет итоги внизу таблицы, после того как транзакция будет удалена
     */
    public function actionAjaxdelete()
    {      
              $post = \Yii::$app->request->post();
                
	       if(isset($post["id"]) )
               { 
                   $model =  sspTransaction::findOne($post["id"]); 
                   $model->delete();  
                   
                      $result =   Type::getList(Yii::$app->user->identity->id); 
                      
                        echo \Yii::$app->view->renderFile('@app/views/site/editable_table/result_line.php',
                   [
                               "plus" =>   $result["table"]["plus"]["sum"],
                               "minus" =>  $result["table"]["minus"]["sum"], 
                               "sum" =>    $result["table"]["plus"]["sum"] -  $result["table"]["minus"]["sum"],
                              
                  ]
                );    
      
               } 
                
    }
    
     
    
    /**
     * Updates an existing sspTransaction model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
           
              return $this->render('update', [
                'model' => $model,
            ]);
            
            
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing sspTransaction model.
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
     * Finds the sspTransaction model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return sspTransaction the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = sspTransaction::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
