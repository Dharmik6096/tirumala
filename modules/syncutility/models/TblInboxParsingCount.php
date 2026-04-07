<?php

namespace app\modules\syncutility\models;

use Yii;

/**
 * This is the model class for table "tbl_inbox_parsing_count".
 *
 * @property integer $inbox_parsing_count_code
 * @property integer $total_count
 * @property integer $data_status
 * @property string $post_id
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $response_datetime
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblInboxParsingCount extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_inbox_parsing_count';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['total_count', 'originating_type', 'success_count', 'error_count'], 'safe'],
                [['created_at', 'updated_at', 'response_datetime'], 'safe'],
                [['post_id'], 'safe'],
                [['created_by', 'updated_by'], 'safe'],
                [['originating_org_code', 'originating_org_type'], 'safe'],
                [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'inbox_parsing_count_code' => Yii::t('app', 'Inbox Parsing Count Code'),
            'total_count' => Yii::t('app', 'Total Count'),
            'post_id' => Yii::t('app', 'Post ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'response_datetime' => Yii::t('app', 'Response Datetime'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
        ];
    }

}
