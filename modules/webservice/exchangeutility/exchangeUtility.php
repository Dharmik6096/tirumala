<?php

namespace app\modules\webservice\exchangeutility;

/**
 * exchangeUtility module definition class
 */
use app\modules\androiddpu\components\CaseConverterFilter;
class exchangeUtility extends \yii\base\Module {

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\webservice\exchangeutility\controllers';

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
        $this->modules = [
            'v1' => [
                'class' => 'app\modules\webservice\exchangeutility\v1\V1',
            ],
            'v2' => [
                'class' => 'app\modules\webservice\exchangeutility\v2\V2',
            ],
        ];
        // custom initialization code goes here
    }

    public function behaviors() {
        return [
            'caseConverter' => [
                'class' => CaseConverterFilter::class,
            ],
        ];
    }

}
