<?php

namespace app\modules\dcsoperation\models;

/**
 * This is the ActiveQuery class for [[TblRateGenerateMethod]].
 *
 * @see TblRateGenerateMethod
 */
class TblRateGenerateMethodQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblRateGenerateMethod[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblRateGenerateMethod|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
