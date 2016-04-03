<?php


namespace app\modules\user\traits;

use app\modules\user\UserModule;

/**
 * Trait ModuleTrait
 * @property-read Module $module
 * @package dektrium\user\traits
 */
trait ModuleTrait
{
    /**
     * @return Module
     */
    public function getModule()
    {
        return \Yii::$app->getModule('user');
    }
}