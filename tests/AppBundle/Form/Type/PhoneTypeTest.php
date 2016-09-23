<?php
/**
 * Created by IntelliJ IDEA.
 * User: Ivan Kalita
 * Date: 23.09.16
 * Time: 11:47
 */

namespace tests\AppBundle\Form\Type;


use AppBundle\Form\Type\PhoneType;
use AppBundle\Model\Phone;
use Tests\BaseFormTestCase;

class PhoneTypeTest extends BaseFormTestCase
{
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        static::$formFactory = static::$container->get('form.factory');
    }

    public function testPhoneTypeEmpty()
    {
        $form = $this->createForm(PhoneType::class);
        $form->submit([]);
        $this->assertNotValid($form, 'Form is valid!');
    }

    public function testPhoneTypeSuccess()
    {
        $form = $this->createForm(PhoneType::class);
        $form->submit(['phone' => '+70000000000']);
        $this->assertValidAndSubmitted($form);
        /**
         * @var Phone $phone
         */
        $phone = $form->getData();
        $this->assertEquals($phone->getPhone(), '+70000000000');
    }

    public function testPhoneTypeInvalid()
    {
        $form = $this->createForm(PhoneType::class);
        $form->submit(['phone' => 'invalid phone number']);
        $this->assertNotValid($form, 'Form is valid!');
    }
}