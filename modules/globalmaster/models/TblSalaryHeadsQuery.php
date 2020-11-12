<?php

namespace app\modules\globalmaster\models;

/**
 * This is the ActiveQuery class for [[TblSalaryHeads]].
 *
 * @see TblSalaryHeads
 */
class TblSalaryHeadsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblSalaryHeads[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblSalaryHeads|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
