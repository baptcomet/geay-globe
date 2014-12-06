<?php
namespace Blog\Form\View\Helper;

use Zend\Form\View\Helper\FormLabel as OriginalFormLabel;
use Zend\Form\ElementInterface;
use Zend\Form\Exception;

class RequiredMarkInFormLabel extends OriginalFormLabel
{
    public function __invoke(ElementInterface $element = null, $labelContent = null, $position = null)
    {
        if (!$element) {
            return $this;
        }

        $openTag = $this->openTag($element);
        $label   = '';
        if ($labelContent === null || $position !== null) {
            $label = $element->getLabel();

            if (null !== ($translator = $this->getTranslator())) {
                $label = $translator->translate(
                    $label, $this->getTranslatorTextDomain()
                );
            }
        }

        if ($label && $labelContent) {
            switch ($position) {
                case self::APPEND:
                    $labelContent .= $label;
                    break;
                case self::PREPEND:
                default:
                    $labelContent = $label . $labelContent;
                    break;
            }
        }

        if ($label && null === $labelContent) {
            $labelContent = $label;
        }

        // Set $required to a default of true | existing elements required-value
        $required = ($element->hasAttribute('required') ? true : false);

        if (true === $required) {
            $labelContent = sprintf(
                '<span class="im-required" alt="Champ Obligatoire" title="Champ Obligatoire">(*)</span> %s',
                $labelContent
            );
        }
        return $openTag . $labelContent . $this->closeTag();
    }
}