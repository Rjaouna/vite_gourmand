<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'required' => true,
                'constraints' => [new Assert\NotBlank(message: 'Prénom obligatoire')],
            ])
            ->add('lastName', TextType::class, [
                'required' => true,
                'constraints' => [new Assert\NotBlank(message: 'Nom obligatoire')],
            ])
            ->add('phone', TelType::class, [
                'required' => true,
                'constraints' => [new Assert\NotBlank(message: 'Téléphone obligatoire')],
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(message: 'Email obligatoire'),
                    new Assert\Email(message: 'Email invalide'),
                ],
            ])
            ->add('addressLine1', TextType::class, [
                'required' => true,
                'constraints' => [new Assert\NotBlank(message: 'Adresse obligatoire')],
            ])
            ->add('addressLine2', TextType::class, [
                'required' => false,
            ])
            ->add('postalCode', IntegerType::class, [
                'required' => true,
                'constraints' => [new Assert\NotBlank(message: 'Code postal obligatoire')],
            ])
            ->add('city', TextType::class, [
                'required' => true,
                'constraints' => [new Assert\NotBlank(message: 'Ville obligatoire')],
            ])
            ->add('country', TextType::class, [
                'required' => true,
                'constraints' => [new Assert\NotBlank(message: 'Pays obligatoire')],
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(message: 'Mot de passe obligatoire'),
                    new Assert\Length(min: 10, minMessage: '10 caractères minimum'),
                    new Assert\Regex(pattern: '/[A-Z]/', message: 'Au moins une majuscule'),
                    new Assert\Regex(pattern: '/[a-z]/', message: 'Au moins une minuscule'),
                    new Assert\Regex(pattern: '/\d/', message: 'Au moins un chiffre'),
                    new Assert\Regex(pattern: '/[^A-Za-z0-9]/', message: 'Au moins un caractère spécial'),
                ],
            ])
        ;

        // Si tu as encore agreeTerms dans ton projet, laisse-le.
        // Sinon, supprime cette partie + dans Twig.
        if ($builder->has('agreeTerms')) {
            // rien
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
