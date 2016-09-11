<?php

namespace Tests;

use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;

/**
 * Created by IntelliJ IDEA.
 * User: Ivan Kalita
 * Date: 26.08.16
 * Time: 16:00.
 */
class BaseFormTestCase extends BaseServiceTestCase
{
    /**
     * @var FormFactory
     */
    protected static $formFactory;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        static::$formFactory = static::$container->get('form.factory');
    }

    /**
     * @param FormInterface $form
     */
    protected function assertValidAndSubmitted(FormInterface $form)
    {
        $this->assertTrue($form->isValid());
        $this->assertTrue($form->isSubmitted());
    }

    /**
     * @param FormInterface $form
     */
    protected function assertNotValid(FormInterface $form)
    {
        $this->assertFalse($form->isValid());
    }

    /**
     * @param string $formClass
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    protected function createForm($formClass)
    {
        return static::$formFactory->create($formClass);
    }
}
