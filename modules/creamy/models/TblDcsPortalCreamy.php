<?php

namespace app\modules\creamy\models;

use Yii;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "tbl_dcs_portal".
 *
 * @property string $dcs_code
 * @property string $route_code
 * @property string $create_at
 * @property string $create_by
 */
class TblDcsPortalCreamy extends \app\models\ChildModel {

    public static function getDb() {
        return Yii::$app->get('db_creamy'); // second database
    }

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_dcs_portal';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['dcs_code', 'route_code', 'create_by'], 'string'],
            [['create_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'route_code' => Yii::t('app', 'Route Code'),
            'create_at' => Yii::t('app', 'Create At'),
            'create_by' => Yii::t('app', 'Create By'),
        ];
    }

    public function getVlcc() {
        $data = $this->find()->all();
        return ArrayHelper::map($data, 'dcs_code', 'dcs_code');
    }

}
