<?php
namespace app\modules\contactus\models;

use Yii;
use yii\easyii\behaviors\CalculateNotice;
use yii\easyii\helpers\Mail;
use yii\easyii\models\Setting;
use app\modules\eu\models\User;

use yii\easyii\validators\ReCaptchaValidator;
use yii\easyii\validators\EscapeValidator;
use yii\helpers\Url;

class Feedback extends \yii\easyii\components\ActiveRecord
{
    const STATUS_NEW = 0;
    const STATUS_VIEW = 1;
    const STATUS_ANSWERED = 2;

    const FLASH_KEY = 'eaysiicms_feedback_send_result';

    public $captcha;

    public static function tableName()
    {
        return 'app_contactus';
    }

    public function rules()
    {
        
       $c = (\Yii::$app->user->isGuest)?'required':"safe";
       $c1 = (\Yii::$app->user->isGuest)?'required':"safe";
        
        return [
            [['name', 'email', 'text'], 'required'],
            [['name', 'email', 'phone', 'title', 'text'], 'trim'],
            [['name','title', 'text'], EscapeValidator::className()],
            ['title', 'string', 'max' => 128],
            ['email', 'email'],
            ['phone', 'match', 'pattern' => '/^[\d\s-\+\(\)]+$/'],
            
            
             ['captcha',  $c1], 
             ['captcha',  $c],
            
           // ['captcha', 'captcha','when' => function($model){  return false;  }],
            
          //  ['captcha', 'required','when' => function($model){  return false;  }]
            
            
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if($insert){
                $this->ip = Yii::$app->request->userIP;
                $this->time = time();
                $this->status = self::STATUS_NEW;
            }
            return true;
        } else {
            return false;
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if($insert){
            $this->mailAdmin();
        }
    }

    public function attributeLabels()
    {
        return [
            'email' => 'E-mail',
            'name' => Yii::t('easyii', 'Name'),
            'title' => Yii::t('easyii', 'Title'),
            'text' => Yii::t('easyii', 'Text'),
            
            'answer_subject' => Yii::t('easyii/contactus', 'Subject'),
            'answer_text' => Yii::t('easyii', 'Text'),
            'phone' =>  "Телефон",
            'reCaptcha' => Yii::t('easyii', 'Anti-spam check')
        ];
    }

    public function behaviors()
    {
        return [
            'cn' => [
                'class' => CalculateNotice::className(),
                'callback' => function(){
                    return self::find()->status(self::STATUS_NEW)->count();
                }
            ]
        ];
    }

    public function mailAdmin()
    {
        $settings = Yii::$app->getModule('admin')->activeModules['contactus']->settings;

        if(!$settings['mailAdminOnNewFeedback']){
            return false;
        }
        
         $user = User::findOne(12);
         
        return Mail::send(
            $user->email,
            $settings['subjectOnNewFeedback'],
            $settings['templateOnNewFeedback'],
            ['contactus' => $this, 'link' => Url::to(['/admin/feedback/a/view', 'id' => $this->primaryKey], true)]
        );
    }

    public function sendAnswer()
    {
        $settings = Yii::$app->getModule('admin')->activeModules['contactus']->settings;

         $user = User::findOne(12);
         
          
        
        return Mail::send(
            $this->email,
            $this->answer_subject,
            $settings['answerTemplate'],
            ['contactus' => $this],
            ['replyTo' => $user->email]
        );
    }
}