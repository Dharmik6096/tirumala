<?php

namespace app\modules\webservice\bmc;

/**
 * bmc module definition class
 */
class Bmc extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\webservice\bmc\controllers';

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
        $this->modules = [
            'v1' => [
                'class' => 'app\modules\webservice\bmc\v1\V1',
            ],
        ];
    }

}
