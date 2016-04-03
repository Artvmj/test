<?php


namespace app\modules\eu\traits;

use app\modules\eu\EuModule;

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
        return \Yii::$app->getModule('eu');
    }
}