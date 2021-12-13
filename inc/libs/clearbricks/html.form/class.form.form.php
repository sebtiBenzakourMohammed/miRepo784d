<?php

declare(strict_types=1);

/**
 * @class formForm
 * @brief HTML Forms form creation helpers
 *
 * @package Clearbricks
 * @subpackage html.form
 *
 * @since 1.2 First time this was introduced.
 *
 * @copyright Olivier Meunier & Association Dotclear
 * @copyright GPL-2.0-only
 */
class formForm extends formComponent
{
    private const DEFAULT_ELEMENT = 'form';

    /**
     * Constructs a new instance.
     *
     * @param      null|string  $id       The identifier
     * @param      null|string  $element  The element
     */
    public function __construct(?string $id = null, ?string $element = null)
    {
        parent::__construct(__CLASS__, $element ?? self::DEFAULT_ELEMENT);
        if ($id !== null) {
            $this
                ->id($id)
                ->name($id);
        }
    }

    /**
     * Renders the HTML component.
     *
     * @return     string
     */
    public function render(?string $fieldFormat = null): string
    {
        if (!$this->checkMandatoryAttributes()) {
            return '';
        }

        $buffer = '<' . ($this->getElement() ?? self::DEFAULT_ELEMENT) .
            (isset($this->action) ? ' action="' . $this->action . '"' : '') .
            (isset($this->method) ? ' method="' . $this->method . '"' : '') .
            $this->renderCommonAttributes() . '>' . "\n";

        if (isset($this->fields)) {
            if (is_array($this->fields)) {
                foreach ($this->fields as $field) {
                    $buffer .= sprintf(($fieldFormat ?: '%s'), $field->render());
                }
            }
        }

        $buffer .= '</' . ($this->getElement() ?? self::DEFAULT_ELEMENT) . '>' . "\n";

        return $buffer;
    }

    /**
     * Gets the default element.
     *
     * @return     string  The default element.
     */
    public function getDefaultElement(): string
    {
        return self::DEFAULT_ELEMENT;
    }
}
