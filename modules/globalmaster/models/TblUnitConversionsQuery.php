<?php

namespace app\modules\globalmaster\models;

/**
 * This is the ActiveQuery class for [[TblUnitConversions]].
 *
 * @see TblUnitConversions
 */
class TblUnitConversionsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblUnitConversions[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblUnitConversions|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
