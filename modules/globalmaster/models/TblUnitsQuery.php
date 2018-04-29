<?php

namespace app\modules\globalmaster\models;

/**
 * This is the ActiveQuery class for [[TblUnits]].
 *
 * @see TblUnits
 */
class TblUnitsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblUnits[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblUnits|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
