<?php

namespace app\modules\androiddpu\components;

use Yii;
use yii\base\ActionFilter;
use yii\helpers\Inflector;
use yii\helpers\Json;
use yii\web\Response;

/**
 * ApiFilter is a filter that converts camelCase request keys to underscore_case.
 */
class CaseConverterFilter extends ActionFilter
{

    public function beforeAction($action)
    {
        $request = Yii::$app->request;
        $raw = $request->getRawBody();
        $data = !empty($raw) ? Json::decode($raw) : $request->post();

        if (empty($data)) {
            $data = $request->get();
        }

        if (!empty($data)) {
            $converted = $this->convert($data, 'underscore');
            $request->setBodyParams($converted);
            $request->setQueryParams($converted);
        }
        return parent::beforeAction($action);
    }

    public function afterAction($action, $result)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (empty($result)) {
            return $result;
        }
        $converted = $this->convert($result, 'camel');
        return $converted;
    }

    private function convert($data, $type)
    {
        if (!is_array($data)){
            return $data;
        }
        $newArray = [];
        foreach ($data as $key => $value) {
            $newKey = ($type === 'underscore') ? Inflector::underscore($key) : Inflector::variablize($key);
            $newArray[$newKey] = is_array($value) ? $this->convert($value, $type) : $value;
        }
        return $newArray;
    }
}