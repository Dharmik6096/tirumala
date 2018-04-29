<?php

namespace app\modules\globalmaster\models;

/**
 * This is the ActiveQuery class for [[TblMilkType]].
 *
 * @see TblMilkType
 */
class TblMilkTypeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblMilkType[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblMilkType|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
