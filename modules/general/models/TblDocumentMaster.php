<?php

namespace app\modules\general\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_document_master".
 *
 * @property integer $doc_id
 * @property integer $doc_type
 * @property string $doc_name
 * @property integer $is_active
 * @property integer $is_delete
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblDocumentMaster extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_document_master';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['doc_type', 'is_active', 'is_delete'], 'integer'],
            [['doc_name', 'created_by', 'updated_by'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'doc_id' => Yii::t('app', 'Doc ID'),
            'doc_type' => Yii::t('app', 'Doc Type'),
            'doc_name' => Yii::t('app', 'Doc Name'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblDocumentMasterQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblDocumentMasterQuery(get_called_class());
    }

    public function getRecord() {
        $data = $this->find()->select(['doc_id', 'doc_name'])->where(['doc_type' => $this->doc_type, 'is_active' => 1, 'is_delete' => 0])->all();
        return ArrayHelper::map($data, 'doc_id', 'doc_name');
    }

}
