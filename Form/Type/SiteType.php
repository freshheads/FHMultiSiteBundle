<?php

declare(strict_types=1);

namespace FH\Bundle\MultiSiteBundle\Form\Type;

use FH\Bundle\MultiSiteBundle\Site\SiteInterface;
use FH\Bundle\MultiSiteBundle\Site\SiteRepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Joris van de Sande <joris.van.de.sande@freshheads.com>
 */
final class SiteType extends AbstractType
{
    private $siteRepository;

    public function __construct(SiteRepositoryInterface $siteRepository)
    {
        $this->siteRepository = $siteRepository;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'choices' => $this->siteRepository->findAll(),
            'choice_label' => static function (SiteInterface $site = null) {
                return (string) $site;
            },
            'choice_value' => 'id',
        ]);
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
