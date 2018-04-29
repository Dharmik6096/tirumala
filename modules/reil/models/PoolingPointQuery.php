<?php

namespace app\modules\reil\models;

/**
 * This is the ActiveQuery class for [[PoolingPoint]].
 *
 * @see PoolingPoint
 */
class PoolingPointQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return PoolingPoint[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return PoolingPoint|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
