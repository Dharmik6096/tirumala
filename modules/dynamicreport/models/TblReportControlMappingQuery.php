<?php

namespace app\modules\dynamicreport\models;

/**
 * This is the ActiveQuery class for [[TblReportControlMapping]].
 *
 * @see TblReportControlMapping
 */
class TblReportControlMappingQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblReportControlMapping[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblReportControlMapping|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
