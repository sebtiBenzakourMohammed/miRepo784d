<?php

declare(strict_types=1);

/**
 * @class formDatetime
 * @brief HTML Forms datetime field creation helpers
 *
 * @package Clearbricks
 * @subpackage html.form
 *
 * @since 1.2 First time this was introduced.
 *
 * @copyright Olivier Meunier & Association Dotclear
 * @copyright GPL-2.0-only
 */
class formDatetime extends formInput
{
    /**
     * Constructs a new instance.
     *
     * @param      string  $id     The identifier
     */
    public function __construct(?string $id = null, ?string $value = null)
    {
        parent::__construct($id, 'datetime-local');
        $this
            ->size(16)
            ->maxlength(16)
            ->pattern('[0-9]{4}-[0-9]{2}-[0-9]{2}T[0-9]{2}:[0-9]{2}')
            ->placeholder('1962-05-13T14:45');
        if ($value !== null) {
            $this->value($value);
        }
    }
}
