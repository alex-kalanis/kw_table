<?php

namespace kalanis\kw_table\form_nette_lte\Controls;


use kalanis\kw_table\form_nette_lte\Helper\DateTimeRangeButtonItem;
use Nette\Forms\Container;
use Nette\Utils\Html;
use Nette\Utils\DateTime;


class DateTimeRangeButton extends DateTimeRange
{
    /** @var  Html */
    protected $span;
    protected $script;
    /** @var string */
    protected $uniqueInputId;
    protected $value;

    public function __construct(string $name, $label = null, $maxLength = null, $searchFormat = null, \DateTime $startTime = null, \DateTime $endTime = null)
    {
        $this->uniqueId = strval(uniqid('adminLteDateTimeRange'));
        $this->uniqueInputId = $this->uniqueId . 'Input';
        parent::__construct($name, $label, $maxLength, $searchFormat, $startTime, $endTime);
    }

    /**
     * @return DateTimeRangeButtonItem[]
     */
    public static function getRangeTimes()
    {
        return [
            _('All')          => new DateTimeRangeButtonItem(_('All'), 'moment().subtract(1, \'days\').hour(0).minute(0).seconds(0).millisecond(0)', 'moment().add(1, \'days\').hour(23).minute(55).seconds(0).millisecond(0)', null, null),
            _('Next 7 days')  => new DateTimeRangeButtonItem(_('Next 7 days'), 'moment().hour(0).minute(0).seconds(0).millisecond(0)', 'moment().add(6, \'days\').hour(23).minute(55).seconds(0).millisecond(0)', 'today', '+6days'),
            _('Today')        => new DateTimeRangeButtonItem(_('Today'), 'moment().hour(0).minute(0).seconds(0).millisecond(0)', 'moment().hour(23).minute(55).seconds(0).millisecond(0)', 'today', 'today'),
            _('Yesterday')    => new DateTimeRangeButtonItem(_('Yesterday'), 'moment().subtract(1, \'days\').hour(0).minute(0).seconds(0).millisecond(0)', 'moment().subtract(1, \'days\').hour(23).minute(55).seconds(0).millisecond(0)', 'yesterday', 'yesterday'),
            _('Last 7 days')  => new DateTimeRangeButtonItem(_('Last 7 days'), 'moment().hour(0).minute(0).seconds(0).millisecond(0).subtract(6, \'days\')', 'moment().hour(23).minute(55).seconds(0).millisecond(0)', '-7days', 'today'),
            _('Last 30 days') => new DateTimeRangeButtonItem(_('Last 30 days'), 'moment().hour(0).minute(0).seconds(0).millisecond(0).subtract(29, \'days\')', 'moment().hour(23).minute(55).seconds(0).millisecond(0)', '-30days', 'today'),
            _('This month')   => new DateTimeRangeButtonItem(_('This month'), 'moment().startOf(\'month\').hour(0).minute(0).seconds(0).millisecond(0)', 'moment().endOf(\'month\').hour(23).minute(55).seconds(0).millisecond(0)', 'first day of this month', 'last day of this  month'),
            _('Last month')   => new DateTimeRangeButtonItem(_('Last month'), 'moment().subtract(1, \'month\').startOf(\'month\').hour(0).minute(0).seconds(0).millisecond(0)', 'moment().subtract(1, \'month\').endOf(\'month\').hour(23).minute(55).seconds(0).millisecond(0)', 'first day of last month', 'last day of last month'),
        ];
    }

    public function getValue()
    {
        $startValue = $endValue = null;
        $named = static::getRangeTimes();
        $namedKeys = array_keys($named);
        if (in_array($this->value, $namedKeys)) {
            $startRange = $named[$this->value]->startTime;
            if ($startRange) {
                $start = new DateTime($startRange);
                $start->setTime(0, 0, 0);
                $startValue = $start->format($this->searchFormat);
            }
            $endRange = $named[$this->value]->endTime;
            if ($endRange) {
                $end = new DateTime($endRange);
                $end->setTime(23, 59, 59);
                $endValue = $end->format($this->searchFormat);
            }
        }
        if (!$startValue && !empty($this->startValue)) {
            $startValue = $this->startValue->format($this->searchFormat);
        };
        if (!$endValue && !empty($this->endValue)) {
            $endValue = $this->endValue->format($this->searchFormat);
        }

        return [
            0 => $startValue,
            1 => $endValue
        ];
    }

    public function setValue($value)
    {
        $this->value = $value;
        $exploded = explode('-', $value, 2);
        if (1 < count($exploded)) {
            $this->startValue = new \DateTime(trim($exploded[0]));
            $this->endValue = new \DateTime(trim($exploded[1]));
        }

        if ($this->started) {
            $this->input->addAttributes(['value' => $value]);
            $named = static::getRangeTimes();
            $namedKeys = array_keys($named);
            if (in_array($value, $namedKeys)) {
                $this->span->removeChildren();
                $this->span->addText($value);

                $addedScriptText = '$(\'#' . $this->uniqueId . '\').data(\'daterangepicker\').setStartDate(' . $named[$value]->startJs . ');
								$(\'#' . $this->uniqueId . '\').data(\'daterangepicker\').setEndDate(' . $named[$value]->endJs . ');';
                $script = $this->setScriptText($addedScriptText);
            } else {
                $this->span->removeChildren();
                $this->span->addText($value);

                $addedScriptText = '';
                if ($this->startValue) {
                    $addedScriptText .= '    $(\'#' . $this->uniqueId . '\').data(\'daterangepicker\').setStartDate(moment("' . $this->startValue->format('Y-m-d H:i') . '"));';
                }
                if ($this->endValue) {
                    $addedScriptText .= '    $(\'#' . $this->uniqueId . '\').data(\'daterangepicker\').setEndDate(moment("' . $this->endValue->format('Y-m-d H:i') . '"));';
                }
                $script = $this->setScriptText($addedScriptText);
            }
            $this->control->offsetUnset(2);
            $this->control->insert(2, $script);
        }
    }

    protected function setControlHtml(string $name)
    {
        $divGroup = Html::el('div', ['class' => "input-group dateTimeRangeButtonDiv"]);
        $button = Html::el('button', ['class' => "btn btn-default pull-right", 'type' => "button", 'id' => $this->uniqueId]);
        $span = $this->span = Html::el('span');
//        $iCalendar = Html::el('i', ['class' => "fa fa-calendar"]);
        $spanText = _('All');
        $iCaret = Html::el('i', ['class' => "fa fa-caret-down"]);

        $rangeTimes = static::getRangeTimes();
        $addedScriptText = '$(\'#' . $this->uniqueId . '\').data(\'daterangepicker\').setStartDate(' . $rangeTimes[_('All')]->startJs . ');
							$(\'#' . $this->uniqueId . '\').data(\'daterangepicker\').setEndDate(' . $rangeTimes[_('All')]->endJs . ');';
        $script = $this->setScriptText($addedScriptText);

        $input = $this->input = Html::el('input', [
            'type' => 'hidden',
            'name' => $name,
            'id'   => $this->uniqueInputId
        ]);

        // $span->addHtml($iCalendar);
        $span->addText($spanText);
        $button->addHtml($span);
        $button->addHtml($iCaret);
        $divGroup->addHtml($button);
        $divGroup->addHtml($input);
        $divGroup->addHtml($script);
        $this->control = $divGroup;
    }

    private function setScriptText($addedScriptText = null)
    {
        $rangeTimes = static::getRangeTimes();
        $rangeText = null;
        foreach ($rangeTimes AS $key => $jsMomentTimes) {
            $rangeText .= '\'' . $key . '\': [' . $jsMomentTimes->startJs . ', ' . $jsMomentTimes->endJs . "],\n";
        }
        $scriptText = "	document.addEventListener('DOMContentLoaded', function() {";
        $scriptText .= "	$('#" . $this->uniqueId . "').daterangepicker(
							{
								ranges: {" . $rangeText . "},
								locale: { 
								    customRangeLabel: '" . _('Own range') . "', 
								    applyLabel: '" . _('Use') . "', 
								    cancelLabel: '" . _('Cancel') . "', 
                                },
                                timePicker: true, 
                                timePickerIncrement: 5, 
                                format: 'MM/DD/YYYY h:mm A',
                                //opens: 'right',
							},
							function (start, end, label) {
								if(label == '" . _('Own range') . "'){
									$('#" . $this->uniqueId . " span')
										.html(start.format('DD.MM.YYYY') + ' - ' + end.format('DD.MM.YYYY'));
									$('#" . $this->uniqueInputId . "')
										.val(start.format('DD.MM.YYYY 00:00:00') + ' - ' + end.format('DD.MM.YYYY 23:59:59'))
										.trigger('change');
								} else {
									if(label == '" . _('All') . "'){
										$('#" . $this->uniqueId . "').val('');
									}
									$('#" . $this->uniqueId . " span')
										.html(label);
										
									$('#" . $this->uniqueInputId . "')
										.val(label)
										.trigger('change');
								}
							}
						);
						$('.dateTimeRangeButtonDiv input').change(function() {
							$(this).parents('form').submit();
						});";
        if ($addedScriptText) {
            $scriptText .= $addedScriptText . "\n";
        }
        $scriptText .= "}, false);";
        $this->script = Html::el('script')->addHtml($scriptText);
        return $this->script;
    }

    public static function register(?string $searchFormat = null, ?\DateTime $startTime = null, ?\DateTime $endTime = null)
    {
        Container::extensionMethod('addDateTimeRangeButton', function ($container, $name, $label = null, $maxLength = null, $searchFormat = null, \DateTime $startTime = null, \DateTime $endTime = null) {
            $picker = $container[$name] = new DateTimeRangeButton($name, $label, $maxLength, $searchFormat, $startTime, $endTime);
            return $picker;
        });
    }
}
