<?php

namespace app\modules\dcsoperation\models;

/**
 * This is the ActiveQuery class for [[TblFormulaMaster]].
 *
 * @see TblFormulaMaster
 */
class TblFormulaMasterQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblFormulaMaster[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblFormulaMaster|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
