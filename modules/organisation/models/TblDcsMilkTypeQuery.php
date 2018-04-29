<?php

namespace app\modules\organisation\models;

/**
 * This is the ActiveQuery class for [[TblDcsMilkType]].
 *
 * @see TblDcsMilkType
 */
class TblDcsMilkTypeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblDcsMilkType[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblDcsMilkType|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
