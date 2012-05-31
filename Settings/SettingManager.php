<?php

namespace Room13\PrefBundle\Settings;

use Symfony\Component\HttpFoundation\Session\Session;

class SettingManager
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var \Symfony\Component\HttpFoundation\Session
     */
    private $session;

    function __construct(\Doctrine\ORM\EntityManager $em, Session $session)
    {
        $this->em = $em;
        $this->session = $session;
    }

    public function get($key, $default=null, $locale = false)
    {

        if ($locale === false)
        {
            // TODO: get from request due to sf 2.1changes
            // @sf21
            //$locale = $this->session->getLocale();
            $locale = 'en';
        }

        $qb = $this->em->createQueryBuilder();

        $qb
            ->select('s')
            ->from('Room13PrefBundle:Setting', 's')
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->eq('s.name', $qb->expr()->literal($key)),
                    $qb->expr()->orX(
                        $qb->expr()->eq('s.locale', $qb->expr()->literal($locale)),
                        $qb->expr()->isNull('s.locale')
                    )
                )
            )
            ->orderBy('s.locale', 'DESC')
            ->setMaxResults(1)
        ;


        $setting = $qb->getQuery()->getOneOrNullResult();

        if(is_object($setting))
        {
            return $setting->getValue();
        }


        return $default;
    }

}
