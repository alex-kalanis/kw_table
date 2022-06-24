<?php

namespace kalanis\kw_table\form_nette_lte\Controls;


use Nette\Forms\Container;
use Nette\Forms\Controls\TextInput;
use Nette\Utils\Html;
use Nette\Utils\DateTime;


class DateTimeRange extends TextInput
{
    /** @var string */
    protected $uniqueId;
    /** @var string */
    protected $searchFormat = 'Y-m-d H:i:s';

    /** @var  Html */
    protected $input;
    /** @var  DateTime | null */
    protected $startValue;
    /** @var  DateTime | null */
    protected $endValue;

    /** @var bool */
    protected $started = false;

    public function __construct(string $name, $label = null, $maxLength = null, $searchFormat = null, \DateTime $startTime = null, \DateTime $endTime = null)
    {
        parent::__construct($label, $maxLength);
        $this->uniqueId = (!$this->uniqueId) ? uniqid('adminLteDateTimeRange') : $this->uniqueId;
        if ($searchFormat) {
            $this->setSearchFormat($searchFormat);
        }
        $this->setControlHtml($name);
        $this->startValue = $startTime;
        $this->endValue = $endTime;
        $this->started = true;
    }

    protected function setControlHtml(string $name)
    {
        $divGroup = Html::el('div', ['class' => "input-group dateTimeRangeDiv"]);
        $divGroupAddonClock = Html::el('div', ['class' => "input-group-addon", 'id' => $this->uniqueId . 'Focus']);
        $divGroupAddonTimes = Html::el('div', ['class' => "input-group-addon", 'id' => $this->uniqueId . 'Clear']);
        $iClock = Html::el('i', ['class' => "fa fa-clock-o"]);
        $iTimes = Html::el('i', ['class' => "fa fa-times"]);
        $input = $this->input = Html::el('input', [
            'type'       => 'text',
            'name'       => $name,
            'id'         => $this->uniqueId,
            'class'      => 'form-control pull-right active',
            'aria-label' => _('Time from - to')
        ]);

        // http://www.daterangepicker.com/
        $scriptText = 'document.addEventListener(\'DOMContentLoaded\', function() {
			moment.locale(\'cs\');
			$("#' . $this->uniqueId . '").daterangepicker({
				autoUpdateInput: false,
				timePicker: true,
    			timePicker24Hour: true,
				timePickerIncrement: 15,
				locale: {
					"format": "DD.MM.YYYY H:mm", // TODO because processing until it will be defined internationalization
					customRangeLabel: "' . t('Own range') . '", 
                    applyLabel: "' . t('Use') . '", 
                    cancelLabel: "' . t('Cancel') . '", 
				},
			},
			function (start, end) {
				$(\'#' . $this->uniqueId . '\')
					.val(start.format(\'DD.MM.YYYY H:mm\') + \' - \' + end.format(\'DD.MM.YYYY H:mm\'))
					.trigger(\'change\');
			}
			);
			$(\'.dateTimeRangeDiv input\').change(function() {
				$(this).parents(\'form\').submit();
			});
			$(\'#' . $this->uniqueId . 'Focus' . '\').click(function(){
                $(' . $this->uniqueId . ').focus();
            });
			$(\'#' . $this->uniqueId . 'Clear' . '\').click(function(){
                $(' . $this->uniqueId . ').val(\'\');
                $(this).parents(\'form\').submit();
            });
		}, false);';
        $script = Html::el('script')->add($scriptText);

        $divGroupAddonClock->add($iClock);
        $divGroupAddonTimes->add($iTimes);
        $divGroup->add($divGroupAddonClock);
        $divGroup->add($divGroupAddonTimes);
        $divGroup->add($input);
        $divGroup->add($script);
        $this->control = $divGroup;
    }

    public static function register(?string $searchFormat = null, ?\DateTime $startTime = null, ?\DateTime $endTime = null)
    {
        Container::extensionMethod('addAdminLteDateTimeRange', function ($container, $name, $label = null, $maxLength = null) use ($searchFormat, $startTime, $endTime) {
            $picker = $container[$name] = new DateTimeRange($name, $label, $maxLength, $searchFormat, $startTime, $endTime);
            return $picker;
        });
    }

    public function setSearchFormat(string $format)
    {
        $this->searchFormat = $format;
        return $this;
    }

    public function getValue()
    {
        $startValue = $endValue = null;
        if (!empty($this->startValue)) {
            $startValue = $this->startValue->format($this->searchFormat);
        };
        if (!empty($this->endValue)) {
            $endValue = $this->endValue->format($this->searchFormat);
        }

        return [
            0 => $startValue,
            1 => $endValue
        ];
    }

    public function setValue($value)
    {
        $exploded = explode('-', $value, 2);
        if (1 < count($exploded)) {
            $this->startValue = new \DateTime(trim($exploded[0]));
            $this->endValue = new \DateTime(trim($exploded[1]));
        }

        if ($this->started) {
            $this->input->addAttributes(['value' => $value]);
        }
    }
}
