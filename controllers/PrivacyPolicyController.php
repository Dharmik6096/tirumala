<?php

namespace app\controllers;

use Yii;
use app\modules\organisation\models\TblUnions;
use yii\web\Controller;

class PrivacyPolicyController extends Controller
{

    public function actionIosEc()
    {
        $eiplCode = TblUnions::find()->select('eipl_code')->where(['is_active' => 1])->scalar();
        $eiplCode = !empty($eiplCode) ? ($eiplCode) : '';
        $privacyPolicyFile  = 'ios_privacy_policy';
        $customFileName  = 'ios_privacy_policy_' . strtolower($eiplCode);
        $path = Yii::$app->basePath . '/views/privacy-policy/' . $customFileName . '.php';
        if (file_exists($path)) {
            $privacyPolicyFile = $customFileName;
        }
        $this->layout = false;
        return $this->render($privacyPolicyFile);
    }
}
