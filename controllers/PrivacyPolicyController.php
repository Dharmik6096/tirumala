<?php

namespace app\controllers;

use Yii;
use app\modules\organisation\models\TblUnions;
use yii\web\Controller;

/**
 * PrivacyPolicyController implements the CRUD actions for TblUsers model.
 */
class PrivacyPolicyController extends Controller
{

    public function actionIosPrivacyPolicy()
    {
        $eiplCode = TblUnions::find()->select('eipl_code')->where(['is_active' => 1])->scalar();
        $eiplCode = !empty($eiplCode) ? ($eiplCode) : '';
        $loginFile = 'ios_privacy_policy';
        $fileName = 'ios_privacy_policy_' . strtolower($eiplCode);
        $path = Yii::$app->basePath . '/views/privacy-policy/' . $fileName . '.php';
        if (file_exists($path)) {
            $loginFile = $fileName;
        }
        $this->layout = false;
        return $this->render($loginFile);
    }
}
