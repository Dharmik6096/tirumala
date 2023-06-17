<?php

namespace app\modules\usermanagement\components;

use Yii;
use app\modules\usermanagement\models\User;

class GhostHtml extends \webvimark\modules\UserManagement\components\GhostHtml {

    public static function a($text, $url = null, $options = []) {
        if (in_array($url, [null, '', '#'])) {
            return parent::a($text, $url, $options);
        }
        if (is_array($url)) {
            $checkUrl = $url[0];
        } else {
            $baseurl = Yii::$app->request->baseUrl . '/';
            $checkUrl = str_replace($baseurl, '', $url);
            $checkUrl = explode('?', $checkUrl)[0];
            $checkUrl = Yii::$app->general->base64url_decode($checkUrl);
        }
        return User::canRoute($checkUrl) ? parent::a($text, $url, $options) : '';
    }

    public static function a_alert($text, $url = null, $options = []) {
        if (in_array($url, [null, '', '#'])) {
            return parent::a($text, $url, $options);
        }
        if (is_array($url)) {
            $checkUrl = $url[0];
        } else {
            $baseurl = Yii::$app->request->baseUrl . '/';
            $checkUrl = str_replace($baseurl, '', $url);
            $checkUrl = explode('?', $checkUrl)[0];
            $checkUrl = Yii::$app->general->base64url_decode($checkUrl);
        }
        return User::canRoute($checkUrl) ? parent::a($text, 'javascript:void(0)', $options) : '';
    }

}
