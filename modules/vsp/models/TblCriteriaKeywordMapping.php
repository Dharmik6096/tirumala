<?php

namespace app\modules\vsp\models;

use Yii;

/**
 * This is the model class for table "tbl_criteria_keyword_mapping".
 *
 * @property integer $id
 * @property string $keyword
 * @property string $replace_column
 */
class TblCriteriaKeywordMapping extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_criteria_keyword_mapping';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['keyword', 'replace_column'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'keyword' => Yii::t('app', 'Keyword'),
            'replace_column' => Yii::t('app', 'Replace Column'),
        ];
    }

    public function getKeywordData(){
        return $this->find()->all();
    }
}
