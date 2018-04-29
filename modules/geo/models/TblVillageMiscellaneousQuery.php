<?php

namespace app\modules\geo\models;

/**
 * This is the ActiveQuery class for [[TblVillageMiscellaneous]].
 *
 * @see TblVillageMiscellaneous
 */
class TblVillageMiscellaneousQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblVillageMiscellaneous[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblVillageMiscellaneous|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
