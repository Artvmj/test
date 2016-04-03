<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\modules\transaction_category\api\Category;
use app\modules\transaction\models\SspTransaction;
use app\components\Reports;
use app\modules\reports\Last;
use app\modules\reports\Type;
use app\modules\reports\Category as CategoryChart;
use app\modules\reports\Table;
use app\modules\reports\Editable;

class SiteController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function beforeAction($action) {
        $this->layout = 'frontend'; //your layout name
        return parent::beforeAction($action);
    }

    /**
     * Создает таблицу на главной странице, с возможностью добавлять транзакции
     * Для добавления транзакции необходимо авторизоваться
     * 
     * @return mixed
     */
    public function actionIndex() {
 
        if (\Yii::$app->user->isGuest)
            return $this->render('messages/message', []);
        else
        if (!count(Category::GetList())) {
            return $this->render('messages/message_fill', []);
        } else {

            $result = Type::getList(Yii::$app->user->identity->id);
            return $this->render('index', ["result" => $result["table"], "list" => Editable::getList(Yii::$app->user->identity->id)]);
        }
    }

    /**
     * Создает  общую таблицу,  транзакции группируются по категориям
     * Если в категории содержится больше 1 транзакции, отображается раскрывающийся список транзакций
     * Внизу таблицы вывоятся общие суммы по расходу и доходу 

     * 
     * @return mixed
     */
    public function actionTable() {

        if (\Yii::$app->user->isGuest)
            return $this->render('message', []);
        else {
            return $this->render('table', ["list" => Table::getList(Yii::$app->user->identity->id)]);
        }
    }

    /**
     * Готовит данные для диаграммы  в виде колонок 
     * Подсчитывается сумма транзакций для каждой категории
     * Если категория относится к доходу - колонка направлена вверх, если к расходу, то вниз
     * Высота колонки определяется суммой транзакций в категории

     * 
     * @return mixed
     */
    public function actionCategory() {

        if (\Yii::$app->user->isGuest)
            return $this->render('message', []);
        else {
            return $this->render('categories', ["chart" => CategoryChart::getList(Yii::$app->user->identity->id)]);
        }
    }

    /**
     * Готовит данные для диаграммы в виде колонок 
     * Отображаются транзакции, произведенные за последний час 
     * Высота колонки определяется количеством транзакций за интервал времени в 2 минуты

     * 
     * @return mixed
     */
    public function actionLast() {

        if (\Yii::$app->user->isGuest)
            return $this->render('message', []);
        else {
            return $this->render('last', ['chart' => Last::getList(Yii::$app->user->identity->id)]);
        }
    }

    /**
     * Готовит данные для круговых диаграмм
     * Подсчитыватся вся сумма по типам транзакций (расходу и доходу)
     * Подсчитыватся количество транзакций по типам транзакций (расходу и доходу)
     * Для первой диаграммы сумма отображается в виде секторов
     * Для второй диаграммы количество отображается в виде секторов 

     * 
     * @return mixed
     */
    public function actionType() {

        if (\Yii::$app->user->isGuest)
            return $this->render('message', []);
        else {
            return $this->render('type', [
                        'chart' => Type::getList(Yii::$app->user->identity->id),
            ]);
        }
    }

    /**
     * Готовит данные для редактируемой  строки  редактируемой таблицы,  
     * При нажатии на плюсик в хедере таблицы, в начало таблицы добавляется редактируемая строка
     * Содержит раскрывающийся список с выбором категории транзакции, поле ввода для суммы транзакции
     * При нажатии на плюсик справа, данные сохраняются и редактируемая строка заменяется обычной строкой 
     * 
     * @return mixed
     */
    public function actionLine() {
        return $this->renderPartial('editable_table/editable_line', ["LIST" => Category::GetList()]);
    }

    /**
     *  Готовит данные для нередактируемой строки  редактируемой таблицы 
     *  Содержит данные о категории, типе, сумме транзакции 
     *  Возвращает 2 html  шаблона  строка с итоговыми данными внизу, и  новую созданную строку
     * Новая строка заменяет редактируемую строку, а итоговая строка обновляет итоговые суммы внизу таблицы
     * @return mixed
     */
    public function actionLinetext() {
        $post = \Yii::$app->request->post();

        if (isset($post["ID"])) {
            $ID = $post["ID"];
            $transaction = SspTransaction::findOne($ID);

            echo \Yii::$app->view->renderFile('@app/views/site/editable_table/text_line.php', [
                "category" => $transaction->category->name,
                "type" => $transaction->category->type->name,
                "sum" => $transaction->sum,
                "type_value" => $transaction->category->type->type,
                "id" => $transaction->id,
                    ]
            );
            $result = Type::getList(Yii::$app->user->identity->id);

            echo \Yii::$app->view->renderFile('@app/views/site/editable_table/result_line.php', [
                "plus" => $result["table"]["plus"]["sum"],
                "minus" => $result["table"]["minus"]["sum"],
                "sum" => $result["table"]["plus"]["sum"] - $result["table"]["minus"]["sum"],
                    ]
            );
        }
    }

}
