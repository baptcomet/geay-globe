<?php
namespace Blog\Form\Filter;

use Zend\Filter\File\RenameUpload;
use Zend\InputFilter\FileInput;
use Zend\InputFilter\InputFilter;
use Zend\Validator\File\Size;

/**
 * Class FilesInputFilter
 * @author Alejandro Celaya Alastrué
 * @link http://www.alejandrocelaya.com
 */
class FilesInputFilter extends InputFilter
{
    public function __construct($basepath, $maxsize)
    {
        $input = new FileInput('file');
        $input->getValidatorChain()->attach(new Size(['max' => $maxsize]));
        $input->getFilterChain()->attach(new RenameUpload([
            'overwrite'         => false,
            'use_upload_name'   => true,
            'target'            => $basepath
        ]));

        $this->add($input);
    }
}
