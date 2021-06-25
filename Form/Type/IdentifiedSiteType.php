<?php

declare(strict_types=1);

namespace FH\Bundle\MultiSiteBundle\Form\Type;

use FH\Bundle\MultiSiteBundle\Site\IdentifiedSiteInterface;
use Symfony\Bridge\Doctrine\Form\DataTransformer\CollectionToArrayTransformer;
use Symfony\Bridge\Doctrine\Form\EventListener\MergeDoctrineCollectionListener;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Joris van de Sande <joris.van.de.sande@freshheads.com>
 */
final class IdentifiedSiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if ($options['multiple']) {
            $builder
                ->addEventSubscriber(new MergeDoctrineCollectionListener())
                ->addViewTransformer(new CollectionToArrayTransformer(), true);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'exclude_sites' => [],
        ]);

        $resolver->setNormalizer('choices', function (Options $options, $value) {
            if ($options['exclude_sites']) {
                $value = array_filter($value, function (IdentifiedSiteInterface $site) use ($options): bool {
                    return !\in_array($site->getIdentifier(), $options['exclude_sites'], true);
                });
            }

            return $value;
        });
    }

    public function getParent(): string
    {
        return SiteType::class;
    }
}
