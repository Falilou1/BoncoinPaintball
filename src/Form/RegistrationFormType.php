<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('lastname', TextType::class, ['label' => 'Nom'])
        ->add('firstname', TextType::class, ['label' => 'Prénom'])
        ->add('birthdate', BirthdayType::class, [
                'label' => 'Date de naissance',
                ])
        ->add('postalCode', NumberType::class, ['label' => 'Code Postal'])
        ->add('phoneNumber', NumberType::class, ['label' => 'Numéro'])
        ->add('pseudo', TextType::class, ['label' => 'Pseudo'])
        ->add('email', TextType::class, ['label' => 'Email'])
        ->add('agreeTerms', CheckboxType::class, [
            'mapped' => false,
            'label' => "J'accepte les Conditions d'utilisation",
            'constraints' => [
                new IsTrue([
                    'message' => "Vous devez accepter les conditions d'utilisation.",
                ]),
            ],
        ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
