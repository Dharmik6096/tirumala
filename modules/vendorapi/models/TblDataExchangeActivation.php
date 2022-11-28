<?php

namespace app\modules\vendorapi\models;

use Yii;

/**
 * This is the model class for table "tbl_data_exchange_activation".
 *
 * @property integer $id
 * @property string $token
 * @property string $valid_from
 * @property string $valid_to
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblDataExchangeActivation extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_data_exchange_activation';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['token', 'valid_from', 'valid_to', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'token' => Yii::t('app', 'Token'),
            'valid_from' => Yii::t('app', 'Valid From'),
            'valid_to' => Yii::t('app', 'Valid To'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

}
