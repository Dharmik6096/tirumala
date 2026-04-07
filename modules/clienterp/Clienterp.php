<?php

namespace app\modules\clienterp;

/**
 * clienterp module definition class
 */
class Clienterp extends \yii\base\Module {

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\clienterp\controllers';

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();

        $this->modules = [
            'eipl' => [
                'class' => 'app\modules\clienterp\eipl\Eipl',
            ],
            'vka' => [
                'class' => 'app\modules\clienterp\vka\Vka',
            ],
            'cargill' => [
                'class' => 'app\modules\clienterp\cargill\Cargill',
            ],
            'devmilk' => [
                'class' => 'app\modules\clienterp\devmilk\Devmilk',
            ],
            'nddb' => [
                'class' => 'app\modules\clienterp\nddb\Nddb',
            ],
            'tally' => [
                'class' => 'app\modules\clienterp\tally\Tally',
            ],
        ];
    }

}
