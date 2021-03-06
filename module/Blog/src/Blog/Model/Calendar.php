<?php

namespace Blog\Model;

class Calendar
{
    const JANUARY = 1;
    const FEBRUARY = 2;
    const MARCH = 3;
    const APRIL = 4;
    const MAY = 5;
    const JUNE = 6;
    const JULY = 7;
    const AUGUST = 8;
    const SEPTEMBER = 9;
    const OCTOBER = 10;
    const NOVEMBER = 11;
    const DECEMBER = 12;

    public static function getStaticMonthNames()
    {
        return array(
            self::JANUARY => 'Janvier',
            self::FEBRUARY => 'Février',
            self::MARCH => 'Mars',
            self::APRIL => 'Avril',
            self::MAY => 'Mai',
            self::JUNE => 'Juin',
            self::JULY => 'Juillet',
            self::AUGUST => 'Août',
            self::SEPTEMBER => 'Septembre',
            self::OCTOBER => 'Octobre',
            self::NOVEMBER => 'Novembre',
            self::DECEMBER => 'Décembre',
        );
    }

    public static function getStaticMonthShortNames()
    {
        return array(
            self::JANUARY => 'Jan',
            self::FEBRUARY => 'Fév',
            self::MARCH => 'Mars',
            self::APRIL => 'Avr',
            self::MAY => 'Mai',
            self::JUNE => 'Juin',
            self::JULY => 'Juil',
            self::AUGUST => 'Août',
            self::SEPTEMBER => 'Sept',
            self::OCTOBER => 'Oct',
            self::NOVEMBER => 'Nov',
            self::DECEMBER => 'Déc',
        );
    }

    public static function getStaticMonthNumbers()
    {
        return array(
            self::JANUARY => '01',
            self::FEBRUARY => '02',
            self::MARCH => '03',
            self::APRIL => '04',
            self::MAY => '05',
            self::JUNE => '06',
            self::JULY => '07',
            self::AUGUST => '08',
            self::SEPTEMBER => '09',
            self::OCTOBER => '10',
            self::NOVEMBER => '11',
            self::DECEMBER => '12',
        );
    }
}