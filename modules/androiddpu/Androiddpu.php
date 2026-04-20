<?php

namespace app\modules\androiddpu;

/**
 * androiddpu module definition class
 */

use app\modules\androiddpu\components\CaseConverterFilter;
use app\modules\androiddpu\components\HttpRequest;
use app\modules\androiddpu\components\HttpResponse;
use Yii;

class Androiddpu extends \yii\base\Module {

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\androiddpu\controllers';

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
        $this->modules = [
            'v1' => [
                'class' => 'app\modules\androiddpu\v1\V1',
            ],
            'v2' => [
                'class' => 'app\modules\androiddpu\v2\V2',
            ],
            'v3' => [
                'class' => 'app\modules\androiddpu\v3\V3',
            ],
            'v4' => [
                'class' => 'app\modules\androiddpu\v4\V4',
            ],
            'v5' => [
                'class' => 'app\modules\androiddpu\v5\V5',
            ],
        ];
        // custom initialization code goes here
    }

    public function behaviors()
    {
        return [
            'caseConverter' => [
                'class' => CaseConverterFilter::class,
            ],
        ];
    }
    /**
     * Module-scoped singletons for request/response helpers
     */
    protected $httpRequest;
    protected $httpResponse;

    public function getHttpRequest()
    {
        if (!empty(Yii::$app) && Yii::$app->has('androidHttpRequest')) {
            return Yii::$app->get('androidHttpRequest');
        }
        if ($this->httpRequest === null) {
            $this->httpRequest = new HttpRequest();
        }
        return $this->httpRequest;
    }

    public function getHttpResponse()
    {
        if (!empty(Yii::$app) && Yii::$app->has('androidHttpResponse')) {
            return Yii::$app->get('androidHttpResponse');
        }
        if ($this->httpResponse === null) {
            $this->httpResponse = new HttpResponse();
        }
        return $this->httpResponse;
    }

}
