<?php

namespace app\modules\geo\models;

/**
 * This is the ActiveQuery class for [[TblVillageMiscellaneousLocal]].
 *
 * @see TblVillageMiscellaneousLocal
 */
class TblVillageMiscellaneousLocalQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblVillageMiscellaneousLocal[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblVillageMiscellaneousLocal|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
