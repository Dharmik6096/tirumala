<?php

namespace app\modules\organisation\models;

use Yii;
use app\modules\organisation\models\TblDcs;

/**
 * This is the model class for table "tbl_dcs_election".
 *
 * @property integer $election_id
 * @property string $dcs_code
 * @property string $election_date
 * @property string $tenure_from
 * @property string $tenure_to
 * @property string $remarks
 * @property integer $is_active
 * @property integer $is_delete
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblDcsElection extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_dcs_election';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['is_active'], 'default', 'value' => 1],
            [['is_delete'], 'default', 'value' => 0],
            [['dcs_code', 'election_date', 'tenure_from'], 'required'],
            [['tenure_from'], 'rangeValidate'],
            [['dcs_code', 'remarks', 'created_by', 'updated_by'], 'string'],
            [['election_date', 'tenure_from', 'tenure_to', 'created_at', 'updated_at'], 'safe'],
            [['is_active', 'is_delete'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'election_id' => Yii::t('app', 'Election Code'),
            'dcs_code' => Yii::t('app', 'Society'),
            'election_date' => Yii::t('app', 'Election Date'),
            'tenure_from' => Yii::t('app', 'Tenure From'),
            'tenure_to' => Yii::t('app', 'Tenure To'),
            'remarks' => Yii::t('app', 'Remarks'),
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
     * @return TblDcsElectionQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblDcsElectionQuery(get_called_class());
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getRecord() {
        return $this->find()->where(['dcs_code' => $this->dcs_code, 'is_active' => 1, 'is_delete' => 0])->orderBy('election_id DESC')->one();
    }

    public function rangeValidate($attribute, $params) {
        if (!empty($this->tenure_from) && !empty($this->tenure_to)) {
            if ($this->tenure_to < $this->tenure_from) {
                $this->addError($attribute, Yii::t('app/validation', 'Tenure To cannot be less then Tenure From.'));
                return false;
            } else if ($this->tenure_to == $this->tenure_from) {
                $this->addError($attribute, Yii::t('app/validation', 'Tenure To and Tenure From cannot be same.'));
                return false;
            }
        }
    }

}
