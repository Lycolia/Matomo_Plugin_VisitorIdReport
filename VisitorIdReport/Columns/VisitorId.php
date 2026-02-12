<?php
namespace Piwik\Plugins\VisitorIdReport\Columns;

use Piwik\Columns\Dimension;

# TODO: 多分実装が何か足りない
class VisitorId extends Dimension
{
    protected $nameSingular = 'VisitorIdReport_VisitorIds';
    protected $type         = self::TYPE_TEXT;
}
