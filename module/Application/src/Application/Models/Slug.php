<?php

namespace Application\Models;

class Slug
{
    /**
     * Retourne le text en slug
     * Found on http://stackoverflow.com/questions/2955251/php-function-to-make-slug-url-string
     * Inspired by http://jobeet.thuau.fr/le-routage
     *
     * @param string $text
     * @param string $slug
     *
     * @return string
     */
    public static function slugify($text, $slug = '-')
    {
        // replace non letter or digits by $slug
        $text = preg_replace('~[^\\pL\d]+~u', $slug, $text);

        // trim
        $text = trim($text, $slug);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // lowercase
        $text = strtolower($text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        return $text;
    }

    /**
     * @param string $slug
     * @return bool
     */
    public function isValid($slug)
    {
        $validator = new \Zend\Validator\Regex(array('pattern' => '#[a-z0-9-]#'));

        return $validator->isValid($slug);
    }
}
