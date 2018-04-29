<?php

namespace app\modules\globalmaster\models;
/**
 * This is the ActiveQuery class for [[TblLandUnit]].
 *
 * @see TblLandUnit
 */
class TblLandUnitQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblLandUnit[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblLandUnit|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
