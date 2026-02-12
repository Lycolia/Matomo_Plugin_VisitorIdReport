<?php
namespace Piwik\Plugins\VisitorIdReport\Reports;

use Piwik\Piwik;
use Piwik\Plugin\Report;
use Piwik\Plugin\ViewDataTable;
use Piwik\Plugins\VisitorIdReport\Columns\VisitorId;

class GetVisitorIdList extends Report
{
    protected function init()
    {
        parent::init();

        # TODO: VisitorIdクラスの出来が悪いのか全体的にダメなのか機能していない
        $this->dimension     = new VisitorId();
        $this->name          = Piwik::translate('VisitorIdReport_VisitorIdList');
        $this->module        = 'VisitorIdReport';
        $this->action        = 'getVisitorIdList';
        $this->categoryId    = 'General_Visitors';          // 「ビジター」カテゴリ
        $this->subcategoryId = 'VisitorIdReport_VisitorIds'; // サブメニュー名
        $this->order         = 100;
        $this->dimension     = null;
    }

    public function getDimension()
    {
        if (empty($this->dimension)) {
            $this->dimension = new VisitorId();
        }
        return $this->dimension;
    }

    public function configureView(ViewDataTable $view)
    {
        $view->config->columns_to_display          = ['label', 'nb_visits'];
        $view->config->show_exclude_low_population  = false;
        $view->config->show_table_all_columns       = false;
        $view->config->addTranslation('label', Piwik::translate('VisitorIdReport_VisitorIds'));
        $view->requestConfig->filter_sort_column    = 'nb_visits';
        $view->requestConfig->filter_sort_order     = 'desc';
    }
}
