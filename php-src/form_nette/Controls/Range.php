<?php

namespace kalanis\kw_table\form_nette\Controls;


use Nette\Forms\Container;
use Nette\Forms\Controls\TextInput;
use Nette\Utils\Html;
use Nette\Utils\DateTime;


/**
 * Class Range
 * @package kalanis\kw_table\form_nette\Controls
 * Set ranges
 */
class Range extends TextInput
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

    private $started = false;

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

        $start = $this->start = Html::el('input', [
            'type'        => 'text',
            'name'        => $name . '[]',
            'placeholder' => _('From'),
            'id'          => $name . 'StartId',
            'class'       => 'form-control cleanable',
            'aria-label'  => _('Time from')
        ]);
        $end = $this->end = Html::el('input', [
            'type'        => 'text',
            'name'        => $name . '[]',
            'placeholder' => _('To'),
            'id'          => $name . 'StartId',
            'class'       => 'form-control cleanable',
            'aria-label'  => _('Time to')
        ]);

        $divTo = clone $divSince;
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
        return [
            0 => $this->startValue,
            1 => $this->endValue
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

        $this->startValue = $startValue;
        $this->endValue = $endValue;
        if ($this->started) {
            $this->start->addAttributes(['value' => $startValue]);
            $this->end->addAttributes(['value' => $endValue]);
        }
    }

    public static function register(?string $inputFormat = null, ?string $searchFormat = null)
    {
        Container::extensionMethod('addRange', function ($container, $name, $label = null, $maxLength = null) use ($inputFormat, $searchFormat) {
            $picker = $container[$name] = new Range($name, $label, $maxLength);

            if (null !== $inputFormat)
                $picker->setInputFormat($inputFormat);
            if (null !== $searchFormat)
                $picker->setSearchFormat($searchFormat);

            return $picker;
        });
    }
}
