<?php

/**
 * Created by IntelliJ IDEA.
 * User: Ivan Kalita
 * Date: 27.05.16
 * Time: 12:32.
 */
namespace AppBundle\Serializer\Handlers;

use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\JsonSerializationVisitor;

class CachedEntityHandler implements SubscribingHandlerInterface
{
    /**
     * @return array
     */
    public static function getSubscribingMethods()
    {
        return [
            [
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => 'json',
                'type' => 'CachedEntity',
                'method' => 'returnEntityCache',
            ],
        ];
    }

    /**
     * @param JsonSerializationVisitor $visitor
     * @param mixed                    $entity
     * @param array                    $type
     * @param Context                  $context
     *
     * @return int
     */
    public function returnEntityCache(JsonSerializationVisitor $visitor, $entity, array $type, Context $context)
    {
        return $entity->getCache();
    }
}
