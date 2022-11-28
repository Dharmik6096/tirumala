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
            'ho' => [
                'class' => 'app\modules\webservice\ho\Ho',
            ],
            'supervisor' => [
                'class' => 'app\modules\webservice\supervisor\Supervisor',
            ],
            'amcs' => [
                'class' => 'app\modules\webservice\amcs\Amcs',
            ],
            'eipl' => [
                'class' => 'app\modules\webservice\eipl\Eipl',
            ],
            'emilkprolite' => [
                'class' => 'app\modules\webservice\emilkprolite\emilkProLite',
            ],
            'dataexchange' => [
                'class' => 'app\modules\webservice\dataexchange\dataexchange',
            ],
        ];
    }

}
