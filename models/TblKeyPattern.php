<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_key_pattern".
 *
 * @property integer $key_pattern_code
 * @property string $pattern_desc
 * @property string $pattern_for
 * @property integer $ex_code_auto
 * @property integer $ex_code_length
 * @property string $ex_code_reset_on
 * @property string $prefix_field
 * @property integer $ref_code_type
 * @property integer $ref_code_length
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 */
class TblKeyPattern extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_key_pattern';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['pattern_desc', 'pattern_for', 'ex_code_reset_on', 'prefix_field', 'union_code', 'created_by'], 'string'],
            [['ex_code_auto', 'ex_code_length', 'ref_code_type', 'ref_code_length'], 'integer'],
            [['created_at', 'has_preffix'], 'safe'],
            [['pattern_for', 'union_code'], 'unique', 'targetAttribute' => ['pattern_for', 'union_code'], 'message' => 'The combination of Pattern For and Union Code has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'key_pattern_code' => Yii::t('app', 'Key Pattern Code'),
            'pattern_desc' => Yii::t('app', 'Pattern Desc'),
            'pattern_for' => Yii::t('app', 'Pattern For'),
            'ex_code_auto' => Yii::t('app', 'Ex Code Auto'),
            'ex_code_length' => Yii::t('app', 'Ex Code Length'),
            'ex_code_reset_on' => Yii::t('app', 'Ex Code Reset On'),
            'prefix_field' => Yii::t('app', 'Prefix Field'),
            'ref_code_type' => Yii::t('app', 'Ref Code Type'),
            'ref_code_length' => Yii::t('app', 'Ref Code Length'),
            'union_code' => Yii::t('app', 'Union Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }

}
