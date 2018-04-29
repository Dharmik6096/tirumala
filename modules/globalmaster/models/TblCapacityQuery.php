<?php

namespace app\modules\globalmaster\models;

/**
 * This is the ActiveQuery class for [[TblCapacity]].
 *
 * @see TblCapacity
 */
class TblCapacityQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblCapacity[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblCapacity|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
