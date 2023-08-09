<?php

namespace app\modules\document\models;

use Yii;
use app\modules\welfarescheme\models\TblDocumentMasterInfo;
use yii\helpers\ArrayHelper;
use app\modules\organisation\models\TblUnions;
use app\modules\document\models\TblAttachment;

/**
 * This is the model class for table "tbl_document_mapping".
 *
 * @property integer $mapping_id
 * @property integer $doc_id
 * @property integer $is_mandate
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 */
class TblDocumentMapping extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_document_mapping';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['doc_id', 'is_mandate', 'originating_type', 'created_at', 'updated_at', 'union_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'master_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'mapping_id' => Yii::t('app', 'Mapping ID'),
            'doc_id' => Yii::t('app', 'Document Name'),
            'is_mandate' => Yii::t('app', 'Is Mandate ?'),
            'union_code' => Yii::t('app', 'Union Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
        ];
    }

    public function getDocId() {
        return $this->hasOne(TblDocumentMasterInfo::className(), ['doc_id' => 'doc_id']);
    }

    public function getunionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getExistingMapping() {
        $model = new TblDocumentMasterInfo();
        $config = $model->find()
                ->select('doc_id')
                ->where(['is_active' => 1])
                ->all();
        if (!empty($config)) {
            $codes = [];
            foreach ($config as $code) {
                $codes[] = $code->doc_id;
            }
            $query = $this->find()
                    ->where(['IN', 'doc_id', $codes])
                    ->andWhere(['master_type' => $this->master_type])
                    ->all();
            return ArrayHelper::map($query, 'doc_id', 'doc_id');
        } else {
            return [];
        }
    }

    public function getExistingMappingIsmandate() {
        $model = new TblDocumentMasterInfo();
        $config = $model->find()
                ->select('doc_id')
                ->where(['is_active' => 1])
                ->all();
        if (!empty($config)) {
            $codes = [];
            foreach ($config as $code) {
                $codes[] = $code->doc_id;
            }
            $query = $this->find()
                    ->where(['IN', 'doc_id', $codes])
                    ->andWhere(['master_type' => $this->master_type, 'is_mandate' => 1])
                    ->all();
            return ArrayHelper::map($query, 'doc_id', 'doc_id');
        } else {
            return [];
        }
    }

    public function getExistMappedControl() {
        return $this->find()
                        ->where(['doc_id' => $this->doc_id])
                        ->one();
    }

    public function uploadedDocument($mapping_id, $doc_id, $module_code) {
        return TblAttachment::find()->where(['mapping_id' => $mapping_id, 'doc_id' => $doc_id, 'module_code' => $module_code])->one();
    }

}
