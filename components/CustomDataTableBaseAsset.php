<?php

namespace app\components;

use nullref\datatable\assets\DataTableBaseAsset;

class CustomDataTableBaseAsset extends DataTableBaseAsset {

    public function init() {
        parent::init();
        $jsFileName = 'js/jquery.dataTables' . (YII_ENV_DEV ? '' : '.min') . '.js';
        $webPath = \Yii::getAlias('@web') . '/themes/emilk/assets/' . $jsFileName;
        $filePath = \Yii::getAlias('@webroot') . '/themes/emilk/assets/' . $jsFileName;
        if (file_exists($filePath) && ($key = array_search($jsFileName, $this->js)) !== false) {
            $this->js[$key] = $webPath;
        }
    }

}
