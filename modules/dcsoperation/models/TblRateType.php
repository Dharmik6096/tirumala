<?php

namespace app\modules\dcsoperation\models;

use Yii;

/**
 * This is the model class for table "tbl_rate_type".
 *
 * @property integer $code
 * @property string $rate_type
 * @property integer $is_active
 * @property string $created_by
 * @property string $created_at
 * @property string $updated_at
 * @property string $updated_by
 */
class TblRateType extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_rate_type';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['rate_type'], 'required'],
            [['is_active'], 'integer'],
            [['created_at', 'updated_at', 'updated_by'], 'safe'],
            [['rate_type'], 'string', 'max' => 30],
            [['created_by'], 'string', 'max' => 14],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'code' => Yii::t('app', 'ID'),
            'rate_type' => Yii::t('app', 'Rate Type'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblRateTypeQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblRateTypeQuery(get_called_class());
    }

    public function getRateTypeCode($rate_type) {
        $code = $this->find()->select(['code'])->where(['is_active' => 1, 'rate_type' => $rate_type])->one();
        return !empty($code) ? $code->code : '';
    }

    public function getRecords() {
        $data = $this->find()->select(['rate_type', 'code'])->where(['is_active' => 1])->all();

        return $data;
    }

}
