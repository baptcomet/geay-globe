<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Application\Models\AclManager;
use Blog\Entity\User;
use Zend\Http\PhpEnvironment\Response;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\MvcEvent;
use Zend\Permissions\Acl\Resource\GenericResource;
use Zend\Permissions\Acl\Role\GenericRole;
use Zend\Validator\AbstractValidator;

class Module implements ConfigProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }


    public function onBootstrap(MvcEvent $e)
    {
        $this->initAcl($e);

        //$e->getApplication()->getEventManager()->attach('route', array($this, 'checkAcl'));

        $translator = $e->getApplication()->getServiceManager()->get('translator');
        AbstractValidator::setDefaultTranslator($translator);
    }

    public function checkAcl(MvcEvent $e)
    {
        $route = $e->getRouteMatch();

        /** @var User $user */
        $user = $e->getViewModel()->acl->getIdentity();
        $controller = strtolower($route->getParam('controller'));
        $action = strtolower($route->getParam('action'));
        $id = $route->getParam('id');

        if ($controller != 'error' && $controller != 'index' && is_null($user)) {
            if ($controller != 'blog') {
                $url = $e->getRouter()->assemble(array('action' => 'auth'), array('name' => 'blog'));
                /** @var Response $response */
                $response = $e->getResponse();
                $response->getHeaders()->addHeaderLine('Location', $url);
                $response->setStatusCode(302);
                $response->sendHeaders();
                return $response;
            } else {
                $url = $e->getRouter()->assemble(array('action' => 'index'), array('name' => 'index'));
                /** @var Response $response */
                $response = $e->getResponse();
                $response->getHeaders()->addHeaderLine('Location', $url);
                $response->setStatusCode(302);
                $response->sendHeaders();
                return $response;
            }
        }

        $url = $e->getRouter()->assemble(array('action' => $action), array('name' => $controller));
        /** @var Response $response */
        $response = $e->getResponse();
        $response->getHeaders()->addHeaderLine('Location', $url);
        $response->setStatusCode(302);
        $response->sendHeaders();
        return $response;
    }

    private function initAcl(MvcEvent $e)
    {
        $acl = new AclManager();
        $roles = include __DIR__ . '/config/module.acl.roles.php';
        $allResources = array();

        //parcours du tableau de congiguration pour remettre à plat le tableau
        foreach ($roles as $role => $resources) {
            //ajout role
            $acl->addRole(new GenericRole($role));
            if (!empty($resources)) {
                foreach ($resources as $resource => $privileges) {
                    if (is_array($privileges) && !$acl->hasRole($resource)) {
                        $acl->addRole(new GenericRole($resource));
                        $allResources[$resource] = $privileges;
                    } else {
                        $allResources[$role] = $resources;
                    }
                }
            }
        }

        //on parcourt pour définir les ressources et pour donner les droits
        foreach ($allResources as $role => $resource) {
            foreach ($resource as $res => $privileges) {
                if (is_array($privileges)) {
                    if ($acl->hasResource($res) === false) {
                        $acl->addResource(new GenericResource($res));
                    }
                    foreach ($privileges as $privilege) {
                        if ($acl->hasResource($privilege) === false) {
                            $acl->addResource(new GenericResource($privilege));
                        }
                    }
                    $acl->allow($role, $res, $privileges);
                } else {
                    if ($acl->hasResource($privileges) === false) {
                        $acl->addResource(new GenericResource($privileges));
                    }
                    $acl->allow($role, $privileges);
                }
            }
        }

        //ajout du user dans les ACL
        $sm = $e->getApplication()->getServiceManager();
        $auth = $sm->get('doctrine.authenticationservice.orm_default');
        /** @var User $user */
        $user = $auth->getIdentity();
        $acl->setIdentity($user);

        //envoi des acl à la vue si besoin
        $e->getViewModel()->acl = $acl;
    }
}
