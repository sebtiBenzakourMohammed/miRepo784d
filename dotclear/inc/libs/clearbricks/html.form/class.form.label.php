<?php

declare(strict_types=1);

/**
 * @class formLabel
 * @brief HTML Forms label creation helpers
 *
 * @package Clearbricks
 * @subpackage html.form
 *
 * @since 1.2 First time this was introduced.
 *
 * @copyright Olivier Meunier & Association Dotclear
 * @copyright GPL-2.0-only
 */
class formLabel extends formComponent
{
    private const DEFAULT_ELEMENT = 'label';

    // Position of linked component and position of text/label
    public const INSIDE_TEXT_BEFORE   = 0;
    public const INSIDE_TEXT_AFTER    = 1;
    public const OUTSIDE_LABEL_BEFORE = 2;
    public const OUTSIDE_LABEL_AFTER  = 3;

    // Aliases
    public const INSIDE_LABEL_BEFORE = 0;
    public const INSIDE_LABEL_AFTER  = 1;
    public const OUTSIDE_TEXT_BEFORE = 2;
    public const OUTSIDE_TEXT_AFTER  = 3;

    /**
     * Position of linked component:
     *   INSIDE_TEXT_BEFORE   = inside label, label text before component
     *   INSIDE_TEXT_AFTER    = inside label, label text after component
     *   OUTSIDE_LABEL_BEFORE = after label
     *   OUTSIDE_LABEL_AFTER  = before label
     *
     * @var        int
     */
    private $_position = self::INSIDE_TEXT_BEFORE;

    /**
     * Constructs a new instance.
     *
     * @param      string       $text      The text
     * @param      int          $position  The position
     * @param      null|string  $id        The identifier
     */
    public function __construct(string $text = '', int $position = self::INSIDE_TEXT_BEFORE, ?string $id = null)
    {
        parent::__construct(__CLASS__, self::DEFAULT_ELEMENT);
        $this->_position = $position;
        $this
            ->text($text);
        if ($id !== null) {
            $this->for($id);
        }
    }

    /**
     * Renders the HTML component.
     *
     * @param      null|string  $buffer  The buffer
     *
     * @return     string
     */
    public function render(?string $buffer = ''): string
    {
        /**
         * sprintf formats
         *
         * %1$s = label opening block
         * %2$s = text of label
         * %3$s = linked component
         * %4$s = label closing block
         *
         * @var        array
         */
        $formats = [
            '<%1$s>%2$s %3$s</%4$s>', // Component inside label with label text before it
            '<%1$s>%3$s %2$s</%4$s>', // Component inside label with label text after it
            '<%1$s>%2$s</%4$s> %3$s', // Component after label (for attribute will be used)
            '%3$s <%1$s>%2$s</%4$s>'  // Component before label (for attribute will be used)
        ];

        if ($this->_position < 0 || $this->_position > count($formats)) {
            $this->_position = self::INSIDE_TEXT_BEFORE;
        }

        $start = ($this->getElement() ?? self::DEFAULT_ELEMENT);
        /* @phpstan-ignore-next-line */
        if (($this->_position !== self::INSIDE_TEXT_BEFORE || $this->_position !== self::INSIDE_TEXT_AFTER) && isset($this->for)) {
            $start .= ' for="' . $this->for . '"';
        }
        $start .= $this->renderCommonAttributes();

        $end = ($this->getElement() ?? self::DEFAULT_ELEMENT);

        return sprintf($formats[$this->_position], $start, $this->text, $buffer ?: '', $end);
    }

    /**
     * Sets the position.
     *
     * @param      int   $position  The position
     */
    public function setPosition(int $position = self::INSIDE_TEXT_BEFORE)
    {
        $this->_position = $position;

        return $this;
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
