<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace app\modules\eu;

use Yii;
use yii\base\Module as BaseModule;
use yii\authclient\Collection;

use yii\easyii\models\Module as ModuleModel;


/**
 * This is the main module class for the Yii2-user.
 *
 * @property array $modelMap
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
//class EuModule extends BaseModule
//{
    
  class EuModule extends \yii\easyii\components\Module
  {   
    const VERSION = '1.0.0-dev';

    /** Email is changed right after user enter's new email address. */
    const STRATEGY_INSECURE = 0;

    /** Email is changed after user clicks confirmation link sent to his new email address. */
    const STRATEGY_DEFAULT = 1;

    /** Email is changed after user clicks both confirmation links sent to his old and new email addresses. */
    const STRATEGY_SECURE = 2;

    /** @var bool Whether to show flash messages. */
    public $enableFlashMessages = true;

    /** @var bool Whether to enable registration. */
    public $enableRegistration = true;

    /** @var bool Whether to remove password field from registration form. */
    public $enableGeneratingPassword = false;

    /** @var bool Whether user has to confirm his account. */
    public $enableConfirmation = true;

    /** @var bool Whether to allow logging in without confirmation. */
    public $enableUnconfirmedLogin = false;

    /** @var bool Whether to enable password recovery. */
    public $enablePasswordRecovery = true;

    /** @var int Email changing strategy. */
    public $emailChangeStrategy = self::STRATEGY_DEFAULT;

    /** @var int The time you want the user will be remembered without asking for credentials. */
    public $rememberFor = 1209600; // two weeks

    /** @var int The time before a confirmation token becomes invalid. */
    public $confirmWithin = 86400; // 24 hours

    /** @var int The time before a recovery token becomes invalid. */
    public $recoverWithin = 21600; // 6 hours

    /** @var int Cost parameter used by the Blowfish hash algorithm. */
    public $cost = 10;

    /** @var array An array of administrator's  ids. */
    public $admins = [12];

    /** @var array Mailer configuration */
    public $mailer = [];

    /** @var array Model map */
    public $modelMap = [];

    /**
     * @var string The prefix for user module URL.
     *
     * @See [[GroupUrlRule::prefix]]
     */
    public $urlPrefix = 'eu';

    /** @var array The rules to be used in URL management. */
    public $urlRules = [
       // '<id:\d+>'                               => 'profile/show',
      //  '<action:(login|logout)>'                => 'security/<action>',
      //  '<action:(register|resend)>'             => 'register/<action>',
      //  'confirm/<id:\d+>/<code:[A-Za-z0-9_-]+>' => 'register/confirm',
      //  'forgot'                                 => 'recovery/request',
      //  'recover/<id:\d+>/<code:[A-Za-z0-9_-]+>' => 'recovery/reset',
      //  'settings/<action:\w+>'                  => 'settings/<action>'
    ]; 
    
    
       /** @var array Model's map */
    private $_modelMap = [
        'User'             => 'app\modules\eu\models\User',
        'Account'          => 'app\modules\eu\models\Account',
        'Profile'          => 'app\modules\eu\models\Profile',
        'Token'            => 'app\modules\eu\models\Token',
        'RegistrationForm' => 'app\modules\eu\models\RegistrationForm',
        'ResendForm'       => 'app\modules\eu\models\ResendForm',
        'LoginForm'        => 'app\modules\eu\models\LoginForm',
        'SettingsForm'     => 'app\modules\eu\models\SettingsForm',
        'RecoveryForm'     => 'app\modules\eu\models\RecoveryForm',
        'UserSearch'       => 'app\modules\eu\models\UserSearch',
    ];
    
     
    
    
     public function init()
    {
        parent::init();
         
        
           $app =  Yii::$app;
           
           $module = $this;
        
        
         // $this->_modelMap = array_merge($this->_modelMap, $module->modelMap);
            foreach ($this->_modelMap as $name => $definition) {
                $class = "app\\modules\\eu\\models\\" . $name;
                Yii::$container->set($class, $definition);
                $modelName = is_array($definition) ? $definition['class'] : $definition;
                $module->modelMap[$name] = $modelName;
                if (in_array($name, ['User', 'Profile', 'Token', 'Account'])) {
                    Yii::$container->set($name . 'Query', function () use ($modelName) {
                        return $modelName::find();
                    });
                }
            }
            Yii::$container->setSingleton(Finder::className(), [
                'userQuery'    => Yii::$container->get('UserQuery'),
                'profileQuery' => Yii::$container->get('ProfileQuery'),
                'tokenQuery'   => Yii::$container->get('TokenQuery'),
                'accountQuery' => Yii::$container->get('AccountQuery'),
            ]);

         
              //  Yii::$container->set('yii\web\User', [
              //      'enableAutoLogin' => true,
               //     'loginUrl'        => ['/user/security/login'],
               //     'identityClass'   => $module->modelMap['User'],
              //  ]);

              //  $configUrlRule = [
                  //  'prefix' => $module->urlPrefix,
                  //  'rules'  => $module->urlRules,
               // ];

             //   if ($module->urlPrefix != 'user') {
              //      $configUrlRule['routePrefix'] = 'user';
             //   }

                $configUrlRule['class'] = 'yii\web\GroupUrlRule';
                $rule = Yii::createObject($configUrlRule);
                
                $app->urlManager->addRules([$rule], false);

                
         

            

            Yii::$container->set('app\modules\eu\Mailer', $module->mailer);
         
        
    }
    
      
    
}
