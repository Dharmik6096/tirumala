<?php

namespace app\modules\webservice\emilkprolite;

/**
 * emilkProLite module definition class
 */
class emilkProLite extends \yii\base\Module {

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\webservice\emilkprolite\controllers';

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
        $this->modules = [
            'v1' => [
                'class' => 'app\modules\webservice\emilkprolite\v1\V1',
            ],
        ];
    }

}
