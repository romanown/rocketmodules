<?php
namespace rocket\humhub\modules\rocketadminstats\helpers;

use Yii;

class PeriodString
{
    /**
     * Returns string representation of dates range
     *
     * @param string $startDate
     * @param string $endDate
     * @return string
     */
    public static function fromDates($startDate, $endDate)
    {
        $datesRangeStr = Yii::t('RocketadminstatsModule.base', '(for last 24h)');
        if ($startDate && $endDate) {
            $datesRangeStr = Yii::t(
                'RocketadminstatsModule.base',
                '(for period {startDate} - {endDate})',
                ['startDate' => $startDate, 'endDate' => $endDate]
            );
        } else if ($startDate) {
            $datesRangeStr = Yii::t(
                'RocketadminstatsModule.base',
                '(since {startDate})',
                ['startDate' => $startDate,]
            );
        } else if ($endDate) {
            $datesRangeStr = Yii::t(
                'RocketadminstatsModule.base',
                '(up to {endDate})',
                ['endDate' => $endDate,]
            );
        }

        return $datesRangeStr;
    }

    /**
     * @param $model
     * @return string
     */
    public static function fromModel($model)
    {
        return static::fromDates($model->startDate, $model->endDate);
    }
}
