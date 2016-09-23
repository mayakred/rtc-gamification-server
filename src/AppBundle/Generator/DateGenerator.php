<?php

namespace AppBundle\Generator;

use AppBundle\Entity\Image;
use Sonata\MediaBundle\Generator\GeneratorInterface;
use Sonata\MediaBundle\Model\MediaInterface;

class DateGenerator implements GeneratorInterface
{
    public function generatePath(MediaInterface $media)
    {
        if ($media instanceof Image) {
            /**
             * @var Image $media
             */
            $date = $media->isFixture()
                ? new \DateTime('2000-01-01', new \DateTimeZone('UTC'))
                : $media->getCreatedAt();

            $year = $date->format('Y');
            $month = $date->format('m');
            $day = $date->format('d');

            return sprintf('%s/%s/%s/%s', $media->getContext(), $year, $month, $day);
        }

        return null;
    }
}
