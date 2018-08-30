<?php

namespace app\modules\webservice\supervisor\v1;

/**
 * v1 module definition class
 */
class V1 extends \yii\base\Module {

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\webservice\supervisor\v1\controllers';

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
    }

    public static function getLabels($l) {
        $label = V1::ServiceArray();
        return isset($label[$l]) ? $label[$l] : NULL;
    }

    public static function ServiceArray() {
        $label = [];
        return $label;
    }

}
