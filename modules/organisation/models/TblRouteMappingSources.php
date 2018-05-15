<?php

namespace app\modules\organisation\models;

use Yii;
use yii\db\Query;
use yii\db\Expression;
use app\modules\organisation\models\TblSocietyCodes;

/**
 * This is the model class for table "tbl_route_mapping_sources".
 *
 * @property integer $route_mapping_source_code
 * @property string $route_code
 * @property string $from_type
 * @property string $from_dest
 * @property string $to_type
 * @property string $to_dest
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $is_active
 *
 * @property TblRouteMapping $routeCode
 */
class TblRouteMappingSources extends \app\models\ChildModel {

    public $dcs_code, $dcs_name;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_route_mapping_sources';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['route_code', 'from_type', 'from_dest', 'to_type', 'to_dest', 'created_by', 'updated_by'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['is_active'], 'integer'],
            [['route_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblRouteMapping::className(), 'targetAttribute' => ['route_code' => 'route_code']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'route_mapping_source_code' => Yii::t('app', 'Route Mapping Source Code'),
            'route_code' => Yii::t('app', 'Route Code'),
            'from_type' => Yii::t('app', 'From Type'),
            'from_dest' => Yii::t('app', 'From Dest'),
            'to_type' => Yii::t('app', 'To Type'),
            'to_dest' => Yii::t('app', 'To Dest'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'is_active' => Yii::t('app', 'Is Active'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRouteCode() {
        return $this->hasOne(TblRouteMapping::className(), ['route_code' => 'route_code']);
    }

    /**
     * @inheritdoc
     * @return TblRouteMappingSourcesQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblRouteMappingSourcesQuery(get_called_class());
    }

    public function getDestinations($code, $route_type, $union_code, $route_dest_type = 'from') {

        $results = new TblRouteMapping();
        $results = $results->getDestinations($route_type, $union_code, $route_dest_type = 'from');
        $values = $this->find()->select('from_dest')->where(['route_code' => $code->route_code, 'is_active' => 1])->asArray()->all();
        $selected = [];
        //var_dump($results);exit;
        if (!empty($results)) {
            foreach ($results as $key => $row) {
                if (in_array($row['code'], array_column($values, 'from_dest'), true) !== FALSE) {
                    $selected[] = $row['code'] . '-' . $row['tname'];
                }
            }
        }
        //var_dump($selected);exit;
        return ['destinations' => $results, 'selected' => $selected];
    }

    public function getDcsCode() {
        return $this->hasMany(TblDcs::className(), ['dcs_code' => 'from_dest']);
    }

    public function getRoutesWithDcs() {
        $query = TblSocietyCodes::find()->select(['route_code', 'dcs_code']);
        if (!empty(Yii::$app->session->get('Unions'))) {
            $query->where(['union_code' => explode(',', Yii::$app->session->get('Unions'))]);
        }
        if (!empty(Yii::$app->session->get('Dcs'))) {
            $query->andWhere(['dcs_code' => explode(',', Yii::$app->session->get('Dcs'))]);
        }
        $routes = $query->all();
        $dcs_routes = \yii\helpers\ArrayHelper::getColumn($routes, 'route_code');

        return empty($dcs_routes) ? [] : $dcs_routes;
    }

    public function getSocietyCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'from_dest']);
    }

    public function getRouteDcsData() {
        return $this->find()
                ->where(['from_dest' => $this->from_dest, 'from_type' => 'society'])
                ->one();
    }

}
