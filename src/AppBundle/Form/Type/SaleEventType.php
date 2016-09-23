<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;

class SaleEventType extends AbstractEventType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('items', CollectionType::class, [
                'entry_type' => SaleItemType::class,
                'entry_options' => [
                    'constraints' => new Valid(),
                ],
                'allow_add' => true,
            ])
        ;
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\SaleEvent',
        ]);
    }
}
