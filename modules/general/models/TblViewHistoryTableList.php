<?php

namespace app\modules\general\models;

use Yii;

/**
 * This is the model class for table "tbl_view_history_table_list".
 *
 * @property integer $id
 * @property string $union_code
 * @property string $table_name
 * @property string $created_at
 * @property string $created_by
 */
class TblViewHistoryTableList extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_view_history_table_list';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['created_at', 'union_code', 'table_name', 'created_by'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'union_code' => Yii::t('app', 'Union Code'),
            'table_name' => Yii::t('app', 'Table Name'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }

}
