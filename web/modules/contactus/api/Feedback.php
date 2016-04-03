<?php
namespace app\modules\contactus\api;

use Yii;
use app\modules\contactus\models\Feedback as FeedbackModel;

use app\modules\eu\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
 
use yii\captcha\Captcha;


/**
 * Feedback module API
 * @package app\modules\contactus\api
 *
 * @method static string form(array $options = []) Returns fully worked standalone html form.
 * @method static array save(array $attributes) If you using your own form, this function will be useful for manual saving feedback's.
 */

class Feedback extends \yii\easyii\components\API
{
    const SENT_VAR = 'feedback_sent';

    private $_defaultFormOptions = [
        'errorUrl' => '',
        'successUrl' => ''
    ];

    public function api_form($options = [])
    {
        $model = new FeedbackModel;
        
      
       if(isset(\Yii::$app->user->identity->id)) 
       {    
        $user_id =     \Yii::$app->user->identity->id;
        
        $user  =  User::findOne($user_id);
         
       if(isset($user->profile->phone)) 
          $model->phone =  $user->profile->phone;
       
        if(isset($user->profile->name)) 
          $model->name =  $user->profile->name;
        
        if(isset($user->email))
          $model->email =  $user->email;
        
       } 
        
        
        
        
        $settings = Yii::$app->getModule('admin')->activeModules['feedback']->settings;
        $options = array_merge($this->_defaultFormOptions, $options);

        ob_start();
        $form = ActiveForm::begin([
            'fieldConfig' => ['template' => "{label}\n{input}\n "],
            'enableClientValidation' => true,
            'action' => Url::to(['/admin/feedback/send'])
        ]);

        echo Html::hiddenInput('errorUrl', $options['errorUrl'] ? $options['errorUrl'] : Url::current([self::SENT_VAR => 0]));
        echo Html::hiddenInput('successUrl', $options['successUrl'] ? $options['successUrl'] : Url::current([self::SENT_VAR => 1]));

        echo $form->field($model, 'name');  
        echo $form->field($model, 'email')->input('email'); 
        echo $form->field($model, 'phone'); 

        echo $form->field($model, 'text')->textarea()->label("");

        if(\Yii::$app->user->isGuest)
        echo $form->field($model, 'captcha')->widget(Captcha::className())->label("");

        echo Html::submitButton(Yii::t('easyii', 'Send'), ['class' => 'btn btn-primary']);
        ActiveForm::end();

        return ob_get_clean();
    }

    public function api_save($data)
    {
        $model = new FeedbackModel($data);
        if($model->save()){
            return ['result' => 'success'];
        } else {
            return ['result' => 'error', 'error' => $model->getErrors()];
        }
    }
}