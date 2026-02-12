<?php
namespace Piwik\Plugins\VisitorIdReport;

use Piwik\DataTable;
use Piwik\Db;
use Piwik\Common;
use Piwik\Period;
use Piwik\Site;
use Piwik\Piwik;

class API extends \Piwik\Plugin\API
{
    public function getVisitorIdList($idSite, $period, $date, $segment = false)
    {
        Piwik::checkUserHasViewAccess($idSite);

        $site     = new Site($idSite);
        $timezone = $site->getTimezone();
        $periodObj = Period\Factory::build($period, $date, $timezone);
        $startDate = $periodObj->getDateStart()->toString();
        $endDate   = $periodObj->getDateEnd()->toString();

        $sql = "SELECT
                    LOWER(HEX(idvisitor)) AS label,
                    COUNT(*)              AS nb_visits
                FROM " . Common::prefixTable('log_visit') . "
                WHERE idsite = ?
                  AND DATE(visit_last_action_time) >= ?
                  AND DATE(visit_last_action_time) <= ?
                GROUP BY idvisitor
                ORDER BY nb_visits DESC
                LIMIT 500";

        $rows  = Db::fetchAll($sql, [$idSite, $startDate, $endDate]);
        $table = new DataTable();

        foreach ($rows as $row) {
            $tableRow = new \Piwik\DataTable\Row();
            $tableRow->setColumns([
                'label'     => $row['label'],
                'nb_visits' => (int) $row['nb_visits'],
            ]);

            // ★ これだけで標準のセグメントアイコンが出る
            $tableRow->setMetadata(
                'segment',
                'visitorId==' . $row['label']
            );

            $table->addRow($tableRow);
        }

        return $table;
    }
}
