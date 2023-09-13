<?php

namespace app\modules\globalmaster\models;

use Yii;

/**
 * This is the model class for table "tbl_rate_class".
 *
 * @property string $rate_class_code
 * @property string $rate_class
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 */
class TblRateClass extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_rate_class';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['rate_class_code'], 'required'],
            [['is_active'], 'integer'],
            [['created_at'], 'safe'],
            [['rate_class_code'], 'string', 'max' => 10],
            [['rate_class'], 'string', 'max' => 3],
            [['created_by'], 'string', 'max' => 14],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'rate_class_code' => Yii::t('app', 'Rate Class Code'),
            'rate_class' => Yii::t('app', 'Rate Class'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }

    public function getRateClass() {
        $query = $this->find()
                        ->where(['is_active' => 1])->all();

        return \yii\helpers\ArrayHelper::map($query, 'rate_class', 'rate_class');
    }

    public function getRateCode($class) {
        $query = $this->find()
                        ->where(['is_active' => 1, 'rate_class' => $class])->one();
        return !empty($query) ? $query->rate_class_code : '';
    }

}
