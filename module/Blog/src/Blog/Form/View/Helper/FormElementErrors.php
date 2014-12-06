<?php
namespace Blog\Form\View\Helper;

use Zend\Form\View\Helper\FormElementErrors as ZendFormElementErrors;

class FormElementErrors extends ZendFormElementErrors
{
    protected $messageCloseString     = '</li></ul>';
    protected $messageOpenFormat      = '<ul class="text-danger"><li>';
    protected $messageSeparatorString = '</li><li>';
}