<?php

namespace app\modules\vendorapi\components;

use Yii;

class SetError extends \yii\base\Component {

    public function error($message = '', $type = '') {
        Yii::$app->getSession()->setFlash('success', [
            'type' => $type,
            'message' => $message,
        ]);
    }

}

?>
