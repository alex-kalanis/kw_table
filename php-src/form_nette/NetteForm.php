<?php

namespace kalanis\kw_table\form_nette;


use Nette\ComponentModel\IContainer;


/**
 * Class NetteFilterForm
 * @package kalanis\kw_table_nette
 * Form used for filtering in Nette
 *
 * @method addTbDatePicker($name, $label = null) \RadekDostal\NetteComponents\DateTimePicker\TbDatePicker
 * @method addTbDateTimePicker($name, $label = null) \RadekDostal\NetteComponents\DateTimePicker\TbDateTimePicker
 * @method addBootstrapDateRange($name, $label = null) \RadekDostal\NetteComponents\DateTimePicker\TbDateTimePicker
 * @method addTbDateRange($name, $label = null) \RadekDostal\NetteComponents\DateTimePicker\TbDateTimePicker
 * @method addBootstrapRange($name, $label = null) \RadekDostal\NetteComponents\DateTimePicker\TbDateTimePicker
 * @method addRange($name, $label = null) \RadekDostal\NetteComponents\DateTimePicker\TbDateTimePicker
 */
class NetteForm extends \Nette\Application\UI\Form
{

    public function __construct(IContainer $parent = null, string $name = null)
    {
        parent::__construct($parent, $name);
        $this->setMethod('GET');
        $this->addSubmit('apply');
    }

    /**
     * Remove Nette param "do=?" in hidden element - tables do not need them and it is just error-introduction
     */
    protected function beforeRender()
    {
    }
}
