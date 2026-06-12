<?php

namespace app\modules\globalmaster\models;

use Yii;
use app\models\ChildModel;

/**
 * This is the model class for table "tbl_designation".
 *
 * @property integer $designation_code
 * @property string $created_at
 * @property string $deleted_at
 * @property string $designation_name
 * @property integer $designation_type
 * @property integer $is_active
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 *
 * @property TblUsers $deletedBy
 * @property TblUsers $updatedBy
 * @property TblUsers $createdBy
 * @property TblDesignationLocal[] $tblDesignationLocals
 * @property TblDesignationLocalHistory[] $tblDesignationLocalHistories
 */
class TblDesignation extends ChildModel {

    /**
     * @inheritdoc
     */
    public $local_name;

    public static function tableName() {
        return 'tbl_designation';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['designation_name'], 'required', 'on' => ['androidsync']],
            [['created_at', 'deleted_at', 'updated_at', 'local_name', 'is_active'], 'safe'],
            [['designation_name', 'designation_type'], 'required'],
            [['designation_name'], 'unique'],
            [['designation_name'], function ($attribute, $params) {
                    Yii::$app->general->validateAlphaNumber($this, $attribute, $params);
                }, 'skipOnEmpty' => false],
            [['designation_name'], 'string', 'max' => 100],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'designation_code' => Yii::t('app', 'Designation Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'designation_name' => Yii::t('app', 'Designation Name'),
            'designation_type' => Yii::t('app', 'Designation Type'),
            'is_active' => Yii::t('app', 'Is Active'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblDesignationLocalHistories() {
        return $this->hasMany(TblDesignationLocalHistory::className(), ['designation_code' => 'designation_code']);
    }

    /**
     * @inheritdoc
     * @return TblDesignationQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblDesignationQuery(get_called_class());
    }

}
