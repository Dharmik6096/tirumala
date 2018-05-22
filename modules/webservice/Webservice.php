<?php

namespace app\modules\webservice;

/**
 * Webservice module definition class
 */
class Webservice extends \yii\base\Module {

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\webservice\controllers';

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
        $this->modules = [
            'bmc' => [
                'class' => 'app\modules\webservice\bmc\Bmc',
            ],
            'member' => [
                'class' => 'app\modules\webservice\member\Member',
            ],
            'vsp' => [
                'class' => 'app\modules\webservice\vsp\Vsp',
            ],
        ];
    }

}
