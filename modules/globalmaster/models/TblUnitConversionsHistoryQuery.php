<?php

namespace app\modules\globalmaster\models;

/**
 * This is the ActiveQuery class for [[TblUnitConversionsHistory]].
 *
 * @see TblUnitConversionsHistory
 */
class TblUnitConversionsHistoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblUnitConversionsHistory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblUnitConversionsHistory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
