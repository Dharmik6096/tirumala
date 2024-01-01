<?php

namespace app\modules\tankermovement\models;

/**
 * This is the ActiveQuery class for [[TblTankerRate]].
 *
 * @see TblTankerRate
 */
class TblTankerRateQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblTankerRate[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblTankerRate|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
