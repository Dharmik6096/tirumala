<?php

namespace app\modules\welfarescheme\models;

use Yii;
use app\modules\organisation\models\TblUnions;

/**
 * This is the model class for table "tbl_scheme_document_master".
 *
 * @property integer $doc_id
 * @property string $doc_group
 * @property string $doc_name
 * @property string $doc_ext
 * @property integer $is_active
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 */
class TblSchemeDocumentMaster extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_scheme_document_master';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['is_active', 'originating_type'], 'integer'],
                [['created_by', 'updated_by', 'union_code', 'doc_ext', 'doc_name', 'created_at', 'is_active', 'originating_type', 'updated_at', 'doc_group', 'originating_org_code', 'originating_org_type'], 'safe'],
                [['is_active'], 'default', 'value' => 1],
                [['doc_name', 'doc_group', 'union_code'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'doc_id' => 'Doc ID',
            'doc_group' => 'Doc Group',
            'doc_name' => 'Doc Name',
            'doc_ext' => 'Doc Ext',
            'is_active' => 'Is Active',
            'union_code' => 'Union Code',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'originating_type' => 'Originating Type',
            'originating_org_code' => 'Originating Org Code',
            'originating_org_type' => 'Originating Org Type',
        ];
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

}
