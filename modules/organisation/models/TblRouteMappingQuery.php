<?php

namespace app\modules\organisation\models;

/**
 * This is the ActiveQuery class for [[TblRouteMapping]].
 *
 * @see TblRouteMapping
 */
class TblRouteMappingQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblRouteMapping[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblRouteMapping|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
