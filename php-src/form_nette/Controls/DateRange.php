<?php

namespace kalanis\kw_table\form_nette\Controls;


use Nette\Forms\Container;
use Nette\Forms\Controls\TextInput;
use Nette\Utils\Html;
use Nette\Utils\DateTime;


/**
 * Class DateRange
 * @package kalanis\kw_table\form_nette\Controls
 * Set ranges of dates
 */
class DateRange extends TextInput
{
    /** @var string */
    protected $inputFormat = 'd.m.Y H:i:s';
    /** @var string */
    protected $searchFormat = 'Y-m-d H:i:s';

    /** @var Html|null */
    protected $start;
    /** @var Html|null */
    protected $end;
    /** @var  DateTime | null */
    protected $startValue;
    /** @var  DateTime | null */
    protected $endValue;

    protected $started = false;

    public function __construct(string $name, $label = null, $maxLength = null)
    {
        parent::__construct($label, $maxLength);
        $this->setControlHtml($name);
        $this->started = true;
    }

    protected function setControlHtml(string $name)
    {
        $span = Html::el('span');
        $divSince = Html::el('div', ['class' => 'input-group dateTimePickerRange']);
        $divTo = clone $divSince;

        $start = $this->start = Html::el('input', [
            'type'        => 'text',
            'name'        => $name . '[]',
            'placeholder' => _('From'),
            'id'          => $name . 'StartId',
            'class'       => 'form-control cleanable listingDateTimePicker',
            'aria-label'  => _('Time from')
        ]);
        $end = $this->end = Html::el('input', [
            'type'        => 'text',
            'name'        => $name . '[]',
            'placeholder' => _('To'),
            'id'          => $name . 'StartId',
            'class'       => 'form-control cleanable listingDateTimePicker',
            'aria-label'  => _('Time to')
        ]);

        $divSince->add($start);
        $divTo->add($end);
        $span->add($divSince);
        $span->add($divTo);
        $this->control = $span;
    }

    public function setInputFormat(string $format)
    {
        $this->inputFormat = $format;
        return $this;
    }

    public function setSearchFormat(string $format)
    {
        $this->searchFormat = $format;
        return $this;
    }

    public function getValue()
    {
        if (empty($this->startValue)) {
            $startValue = new \DateTime();
            $startValue->setDate(1, 1, 1);
        } else {
            $startValue = $this->startValue;
        };
        if (empty($this->endValue)) {
            $endValue = new \DateTime();
            $endValue->modify('+1000year');
        } else {
            $endValue = $this->endValue;
        }

        return [
            0 => $startValue->format($this->searchFormat),
            1 => $endValue->format($this->searchFormat)
        ];
    }

    public function setValue($value)
    {
        $startValue = $this->startValue;
        $endValue = $this->endValue;

        if (is_array($value)) {
            if (isset($value[0])) {
                $startValue = $value[0];
            }
            if (isset($value[1])) {
                $endValue = $value[1];
            }
        } else {
            $startValue = $value;
        }

        if (!empty($startValue) && !($startValue instanceof \DateTime)) {
            $startValue = new \DateTime($startValue);
        }
        if (!empty($endValue) && !($endValue instanceof \DateTime)) {
            $endValue = new \DateTime($endValue);
        }

        $this->startValue = $startValue;
        $this->endValue = $endValue;
        if ($this->started) {
            $this->start->addAttributes(['value' => (!empty($startValue) ? $startValue->format($this->inputFormat) : null)]);
            $this->end->addAttributes(['value' => (!empty($endValue) ? $endValue->format($this->inputFormat) : null)]);
        }
    }

    public static function register(?string $inputFormat = null, ?string $searchFormat = null)
    {
        Container::extensionMethod('addTbDateRange', function ($container, $name, $label = null, $maxLength = null) use ($inputFormat, $searchFormat) {
            $picker = $container[$name] = new DateRange($name, $label, $maxLength);

            if (null !== $inputFormat)
                $picker->setInputFormat($inputFormat);
            if (null !== $searchFormat)
                $picker->setSearchFormat($searchFormat);

            return $picker;
        });
    }
}
