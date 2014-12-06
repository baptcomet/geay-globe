<?php
namespace Application\Filter;

use DateTime;

class DateTimeFormatter extends \Zend\Filter\DateTimeFormatter
{

    /**
     * Normalize the provided value to a formatted string
     *
     * @param  string|int|DateTime $value
     * @return DateTime
     */
    protected function normalizeDateTime($value)
    {
        var_dump($value);
        if ($value === '' || $value === null) {
            return $value;
        }

        if (is_int($value)) {
            $value = new DateTime('@' . $value);
        } elseif (!$value instanceof DateTime) {
            $value = DateTime::createFromFormat($this->format, $value);
        }

        return $value;
    }
}
