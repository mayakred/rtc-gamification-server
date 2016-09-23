<?php
/**
 * Created by IntelliJ IDEA.
 * User: Ivan Kalita
 * Date: 23.09.16
 * Time: 13:50
 */

namespace tests\AppBundle\Form\Type;


use AppBundle\Form\Type\ConfirmationType;
use AppBundle\Model\Confirmation;
use Tests\BaseFormTestCase;

class ConfirmationTypeTest extends BaseFormTestCase
{
    private function assertInvalidData($data)
    {
        $form = $this->createForm(ConfirmationType::class);
        $form->submit($data);
        $this->assertNotValid($form);
    }

    private function getValidData($ios = true)
    {
        return [
            'password' => 'password',
            'platform' => $ios ? 'ios' : 'android',
            'device_id' => 'device_id',
            'phone' => '+70000000000',
        ];
    }

    public function testConfirmationTypeWithoutPhone()
    {
        $data = $this->getValidData();
        unset($data['phone']);
        $this->assertInvalidData($data);
    }

    public function testConfirmationTypeWithInvalidPhone()
    {
        $data = $this->getValidData();
        $data['phone'] = 'invalid';
        $this->assertInvalidData($data);
    }

    public function testConfirmationTypeWithoutPassword()
    {
        $data = $this->getValidData();
        unset($data['password']);
        $this->assertInvalidData($data);
    }

    public function testConfirmationTypeWithoutPlatform()
    {
        $data = $this->getValidData();
        unset($data['platform']);
        $this->assertInvalidData($data);
    }

    public function testConfirmationTypeWithInvalidPlatform()
    {
        $data = $this->getValidData();
        $data['platform'] = 'some_platform';
        $this->assertInvalidData($data);
    }

    public function testConfirmationTypeValidData()
    {
        $data = $this->getValidData();
        $form = $this->createForm(ConfirmationType::class);
        $form->submit($data);
        $this->assertValidAndSubmitted($form);
        /**
         * @var Confirmation $confirmation
         */
        $confirmation = $form->getData();
        $this->assertNotNull($confirmation);
        $this->assertEquals($confirmation->getPhone(), $data['phone']);
        $this->assertEquals($confirmation->getDeviceId(), $data['device_id']);
        $this->assertEquals($confirmation->getPassword(), $data['password']);
        $this->assertEquals($confirmation->getPlatform(), $data['platform']);
    }
}