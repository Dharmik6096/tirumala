<?php

namespace app\modules\globalmaster\models;

use Yii;

/**
 * This is the model class for table "tbl_committee_type".
 *
 * @property integer $committee_type_code
 * @property string $committee_type_name
 * @property string $committee_type_desc
 * @property string $created_at
 * @property string $created_by
 */
class TblCommitteeType extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_committee_type';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['committee_type_name', 'committee_type_desc', 'created_at', 'created_by'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'committee_type_code' => Yii::t('app', 'Committee Type Code'),
            'committee_type_name' => Yii::t('app', 'Committee Type Name'),
            'committee_type_desc' => Yii::t('app', 'Committee Type Desc'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }

}
