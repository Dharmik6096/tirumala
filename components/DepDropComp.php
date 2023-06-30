<?php

/*
 *
 */

namespace app\components;

use yii;
use yii\base\Component;
use kartik\depdrop\DepDrop;
use kartik\base\Config;
use kartik\depdrop\DepDropAsset;
use kartik\depdrop\DepDropExtAsset;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\web\View;
use ReflectionClass;
use dosamigos\multiselect\MultiSelect;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\db\Query;

class DepDropComp extends DepDrop {

    const TYPE_MULTISELECT = 3;

    public $type = self::TYPE_MULTISELECT;
    public $multiSelectOptions = [];

    public function run() {
        $this->registerAssets();
    }

    public function registerAssets() {
        $view = $this->getView();
        DepDropAsset::register($view)->addLanguage($this->language, 'depdrop_locale_');
        DepDropExtAsset::register($view);
        $this->registerPlugin($this->pluginName);
        if ($this->type === self::TYPE_MULTISELECT) {

            $loading = ArrayHelper::getValue($this->pluginOptions, 'loadingText', 'Loading ...');
            $this->multiSelectOptions['data'] = $this->data;
            $this->multiSelectOptions['options'] = $this->options;
            if ($this->hasModel()) {
                $settings = ArrayHelper::merge($this->multiSelectOptions, [
                            'model' => $this->model,
                            'attribute' => $this->attribute
                ]);
            } else {
                $settings = ArrayHelper::merge($this->multiSelectOptions, [
                            'name' => $this->name,
                            'value' => $this->value
                ]);
            }
            echo Select2::widget($settings);
            //var_dump($settings); exit;
            $id = !empty($this->options['id'])?$this->options['id']:'msdrop';
            $vals=  json_encode($this->value);
            $view->registerJs("initDepdropMs('{$id}','{$loading}','{$vals}');");
        } else {
            echo $this->getInput('dropdownList', true);
        }
    }

}
