<?php

return array(
    'Blog\Module' => __DIR__ . '/Module.php',

// CONTROLLER
    'Blog\Controller\AbstractActionController' => __DIR__ . '/src/Blog/Controller/AbstractActionController.php',
    'Blog\Controller\ArticleController' => __DIR__ . '/src/Blog/Controller/ArticleController.php',
    'Blog\Controller\PublicController' => __DIR__ . '/src/Blog/Controller/PublicController.php',
    'Blog\Controller\UserController' => __DIR__ . '/src/Blog/Controller/UserController.php',
    'Blog\Controller\ManageController' => __DIR__ . '/src/Blog/Controller/ManageController.php',

// ENTITY
    'Blog\Entity\AbstractEntity' => __DIR__ . '/src/Blog/Entity/AbstractEntity.php',
    'Blog\Entity\Article' => __DIR__ . '/src/Blog/Entity/Article.php',
    'Blog\Entity\Category' => __DIR__ . '/src/Blog/Entity/Category.php',
    'Blog\Entity\Comment' => __DIR__ . '/src/Blog/Entity/Comment.php',
    'Blog\Entity\Tag' => __DIR__ . '/src/Blog/Entity/Tag.php',
    'Blog\Entity\User' => __DIR__ . '/src/Blog/Entity/User.php',

// FORM
    'Blog\Form\View\Helper\FormElementErrors' => __DIR__ . '/src/Blog/Form/View/Helper/FormElementErrors.php',
    'Blog\Form\View\Helper\RequiredMarkInFormLabel' => __DIR__ . '/src/Blog/Form/View/Helper/RequiredMarkInFormLabel.php',
    'Blog\Form\ArticleForm' => __DIR__ . '/src/Blog/Form/ArticleForm.php',
    'Blog\Form\AuthForm' => __DIR__ . '/src/Blog/Form/AuthForm.php',
    'Blog\Form\CommentForm' => __DIR__ . '/src/Blog/Form/CommentForm.php',
    'Blog\Form\UserFilter' => __DIR__ . '/src/Blog/Form/UserFilter.php',
    'Blog\Form\UserForm' => __DIR__ . '/src/Blog/Form/UserForm.php',
    'Blog\Form\UserPasswordForm' => __DIR__ . '/src/Blog/Form/UserPasswordForm.php',
    'Blog\Form\UserPasswordFilter' => __DIR__ . '/src/Blog/Form/UserPasswordFilter.php',

// REPOSITORY
    //'Blog\Repository\FileRepository' => __DIR__ . '/src/Blog/Repository/FileRepository.php',
);
