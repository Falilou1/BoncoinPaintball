<?php

namespace App\Form;

use App\Entity\Adverts;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdvertsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('category')
            ->add('price')
            ->add('description')
            ->add('brand')
            ->add('useCondition')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('status')
            ->add('region')
            ->add('owner')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adverts::class,
        ]);
    }
}
