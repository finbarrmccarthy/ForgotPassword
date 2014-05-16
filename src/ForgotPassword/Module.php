<?php
namespace ForgotPassword;

use Zend\Loader\StandardAutoloader;
use Zend\Loader\AutoloaderFactory;

class Module {

    public function getAutoloaderConfig() {
        return array(
            AutoloaderFactory::STANDARD_AUTOLOADER => array(
                StandardAutoloader::LOAD_NS => array(
                    __NAMESPACE__ => __DIR__,
                ),
            ),
        );
    }

    public function getConfig() {
        return include __DIR__ . '/../../config/module.config.php';
    }

    public function getServiceConfig() {
        return array(
            'invokables' => array(
                'forgotpassword_password_service'            => 'ForgotPassword\Service\Password',
            ),

            'factories' => array(

                'forgotpassword_module_options' => function ($sm) {
                    $config = $sm->get('Config');
                    return new Options\ModuleOptions(isset($config['forgotpassword']) ? $config['forgotpassword'] : array());
                },

                'forgotpassword_forgot_form' => function($sm) {
                    $options = $sm->get('forgotpassword_module_options');
                    $form = new Form\Forgot(null, $options);
                    $validator = new \ZfcUser\Validator\RecordExists(array(
                            'mapper' => $sm->get('zfcuser_user_mapper'),
                            'key'    => 'email'
                    ));
                    $validator->setMessage('The email address you entered was not found.');
                    $form->setInputFilter(new Form\ForgotFilter($validator,$options));
                    return $form;
                },

                'forgotpassword_reset_form' => function($sm) {
                    $options = $sm->get('forgotpassword_module_options');
                    $form = new Form\Reset(null, $options);
                    $form->setInputFilter(new Form\ResetFilter($options));
                    return $form;
                },

                'forgotpassword_password_mapper' => function ($sm) {
                    $options = $sm->get('forgotpassword_module_options');
                    $mapper = new Mapper\Password;
                    $mapper->setDbAdapter($sm->get('zfcuser_zend_db_adapter'));
                    $entityClass = $options->getPasswordEntityClass();
                    $mapper->setEntityPrototype(new $entityClass);
                    $mapper->setHydrator(new Mapper\PasswordHydrator());
                    return $mapper;
                },
            ),
        );
    }
}

