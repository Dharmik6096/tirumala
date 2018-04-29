<?php

namespace app\modules\organisation\models;

/**
 * This is the ActiveQuery class for [[TblDcsMilkTypeHistory]].
 *
 * @see TblDcsMilkTypeHistory
 */
class TblDcsMilkTypeHistoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblDcsMilkTypeHistory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblDcsMilkTypeHistory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
