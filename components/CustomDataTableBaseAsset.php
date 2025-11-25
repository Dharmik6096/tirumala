<?php

namespace app\components;

use nullref\datatable\assets\DataTableBaseAsset;

class CustomDataTableBaseAsset extends DataTableBaseAsset {

    public function init() {
        parent::init();
        $jsFileName = 'js/jquery.dataTables' . (YII_ENV_DEV ? '' : '.min') . '.js';
        $customJsPath = \Yii::getAlias('@web') . '/themes/pcdf/assets/' . $jsFileName;
        if (($key = array_search($jsFileName, $this->js)) !== false) {
            $this->js[$key] = $customJsPath;
        }
    }

}
