<?php

namespace app\modules\verification\models;

use Yii;
use app\modules\general\models\TblDocumentMaster;

/**
 * This is the model class for table "tbl_kyc_record".
 *
 * @property integer $kyc_code
 * @property string $module_name
 * @property string $module_id
 * @property integer $is_kyc
 * @property string $kyc_by
 * @property string $kyc_on
 * @property integer $kyc_doc1
 * @property integer $kyc_doc2
 * @property string $kyc_remark
 * @property string $updated_at
 * @property string $updated_by
 */
class TblKycRecord extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_kyc_record';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['kyc_remark', 'kyc_doc1', 'kyc_doc2'], 'required'],
            [['module_name', 'module_id', 'kyc_by', 'kyc_remark', 'updated_by'], 'string'],
            [['is_kyc', 'kyc_doc1', 'kyc_doc2'], 'integer'],
            [['kyc_on', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'kyc_code' => Yii::t('app', 'Kyc Code'),
            'module_name' => Yii::t('app', 'Module Name'),
            'module_id' => Yii::t('app', 'Module ID'),
            'is_kyc' => Yii::t('app', 'Is Kyc'),
            'kyc_by' => Yii::t('app', 'Kyc By'),
            'kyc_on' => Yii::t('app', 'Kyc On'),
            'kyc_doc1' => Yii::t('app', 'Address Proof'),
            'kyc_doc2' => Yii::t('app', 'Bank Proof'),
            'kyc_remark' => Yii::t('app', 'Remark'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblKycRecordQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblKycRecordQuery(get_called_class());
    }

    public function getAddressProof() {
        $model = new TblDocumentMaster();
        $model->doc_type = [1, 3];
        return $model->getRecord();
    }

    public function getBankProof() {
        $model = new TblDocumentMaster();
        $model->doc_type = [2, 3];
        return $model->getRecord();
    }

    public function getAddressDoc() {
        return $this->hasOne(TblDocumentMaster::className(), ['doc_id' => 'kyc_doc1']);
    }

    public function getBankDoc() {
        return $this->hasOne(TblDocumentMaster::className(), ['doc_id' => 'kyc_doc2']);
    }

}
