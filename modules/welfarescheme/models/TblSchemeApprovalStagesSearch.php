<?php

namespace app\modules\welfarescheme\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\welfarescheme\models\TblSchemeApprovalStages;

/**
 * TblSchemeApprovalStagesSearch represents the model behind the search form about `app\modules\welfarescheme\models\TblSchemeApprovalStages`.
 */
class TblSchemeApprovalStagesSearch extends TblSchemeApprovalStages {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['stage_id', 'scheme_id', 'level', 'originating_type'], 'safe'],
                [['user_code', 'approval_mode', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params) {
        $query = TblSchemeApprovalStages::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => FALSE
        ]);


        $query->where(['scheme_id' => $this->scheme_id])->orderBy('level ASC');
        return $dataProvider;
    }

}
