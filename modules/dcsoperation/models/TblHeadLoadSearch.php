<?php

namespace app\modules\dcsoperation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsoperation\models\TblHeadLoad;

/**
 * TblHeadLoadSearch represents the model behind the search form about `app\modules\dcsoperation\models\TblHeadLoad`.
 */
class TblHeadLoadSearch extends TblHeadLoad
{
    public $federation_code;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['head_load_code', 'created_at', 'criteria_description','federation_code','criteria_type_code', 'deleted_at', 'updated_at', 'created_by', 'dcs_code', 'deleted_by', 'union_code', 'updated_by'], 'safe'],
            [['is_active', 'is_delete'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
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
    public function search($params)
    {
        $query = TblHeadLoad::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        $query->joinWith(['criteriaTypeCode','unionCode','dcsCode','unionCode.federationCode']);
        
        $query->andwhere(['tbl_federations.federation_code' => $this->federation_code]);
        
        if(Yii::$app->session->get('Unions')!==''){
            $query->andFilterWhere([ 'tbl_head_load.union_code'=>explode(',',Yii::$app->session->get('Unions'))]);
        }else
            $query->andFilterWhere([ 'tbl_head_load.union_code'=>$this->union_code]);
        
        if(Yii::$app->session->get('Dcs')!==''){
            $query->andFilterWhere([ 'tbl_head_load.dcs_code'=>explode(',',Yii::$app->session->get('Unions'))]);
        }else
            $query->andFilterWhere([ 'tbl_head_load.dcs_code'=>$this->dcs_code]);
        
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_head_load.is_active' => $this->is_active,
            'tbl_head_load.is_delete' => 0,
        ]);

        $query->andFilterWhere(['like', 'tbl_head_load.head_load_code', $this->head_load_code])
            ->andFilterWhere(['like', 'criteria_description', $this->criteria_description])
            ->andFilterWhere(['like', 'tbl_head_load_criteria.criteria_name', $this->criteria_type_code]);

        return $dataProvider;
    }
}
