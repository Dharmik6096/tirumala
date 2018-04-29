<?php

namespace app\modules\payment\models;

use Yii;

/**
 * This is the model class for table "tbl_reverse_payment_file_log".
 *
 * @property integer $file_id
 * @property string $file_name
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblReversePaymentFileLog extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_reverse_payment_file_log';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['file_name', 'union_code', 'created_by', 'updated_by'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'file_id' => Yii::t('app', 'File ID'),
            'file_name' => Yii::t('app', 'File Name'),
            'union_code' => Yii::t('app', 'Union Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblReversePaymentFileLogQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblReversePaymentFileLogQuery(get_called_class());
    }

    public function getRecord() {
        return $this->find()->where(['union_code' => $this->union_code])->all();
    }

}
