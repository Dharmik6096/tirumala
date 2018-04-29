<?php

namespace app\modules\hardwareconfigutation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\hardwareconfigutation\models\TblUnionConfig;

/**
 * TblUnionConfigSearch represents the model behind the search form about `app\modules\hardwareconfigutation\models\TblUnionConfig`.
 */
class TblUnionConfigSearch extends TblUnionConfig
{
    public $federation_code;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['union_config_code','federation_code', 'created_by','union_code','perc_disp_recp_milk', 'min_member_age', 'manual_days_collection', 'audit_response_time', 'auto_audit_resolution', 'range_end', 'created_at', 'updated_by', 'updated_at', 'deleted_by', 'deleted_at', 'flg_sentbox_entry', 'sync_status', 'sync_timestamp'], 'safe'],
            //[['perc_disp_recp_milk', 'auto_audit_resolution', 'range_end'], 'number'],
            [['is_delete', 'is_active'], 'integer'],
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
        $query = TblUnionConfig::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        $query->joinWith(['unionCode.federationCode']);
        
        $query->andwhere(['tbl_federations.federation_code' => $this->federation_code]);
        
        if(Yii::$app->session->get('Unions')!==''){
            $query->andFilterWhere([ 'tbl_union_config.union_code'=>explode(',',Yii::$app->session->get('Unions'))]);
        }else
            $query->andFilterWhere([ 'tbl_union_config.union_code'=>$this->union_code]);
        
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'perc_disp_recp_milk' => $this->perc_disp_recp_milk,
            'min_member_age' => $this->min_member_age,
            'manual_days_collection' => $this->manual_days_collection,
            'audit_response_time' => $this->audit_response_time,
            'auto_audit_resolution' => $this->auto_audit_resolution,
            'range_end' => $this->range_end,
            'tbl_union_config.is_delete' => 0,
            'tbl_union_config.is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'union_config_code', $this->union_config_code]);

        return $dataProvider;
    }
}
