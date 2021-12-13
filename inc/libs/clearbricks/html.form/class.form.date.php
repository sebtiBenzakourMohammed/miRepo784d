<?php

declare(strict_types=1);

/**
 * @class formDate
 * @brief HTML Forms date field creation helpers
 *
 * @package Clearbricks
 * @subpackage html.form
 *
 * @since 1.2 First time this was introduced.
 *
 * @copyright Olivier Meunier & Association Dotclear
 * @copyright GPL-2.0-only
 */
class formDate extends formInput
{
    /**
     * Constructs a new instance.
     *
     * @param      string  $id     The identifier
     */
    public function __construct(?string $id = null, ?string $value = null)
    {
        parent::__construct($id, 'date');
        $this
            ->size(10)
            ->maxlength(10)
            ->pattern('[0-9]{4}-[0-9]{2}-[0-9]{2}')
            ->placeholder('1962-05-13');
        if ($value !== null) {
            $this->value($value);
        }
    }
}
