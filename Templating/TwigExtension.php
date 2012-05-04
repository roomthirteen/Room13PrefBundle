<?php

namespace Room13\PrefBundle\Templating;

use Doctrine\ORM\EntityManager;
use Room13\PrefBundle\Settings\SettingManager;


class TwigExtension extends \Twig_Extension
{
    /**
     * @var \Room13\PrefBundle\Settings\SettingManager
     */
    private $settings;

    public function __construct(SettingManager $settings)
    {
        $this->settings = $settings;
    }

    public function getFilters()
    {
        return array(
            'room13_pref' => new \Twig_Filter_Method($this, 'getSetting', array('is_safe' => array('html'))),
        );
    }

    public function getSetting($key, $default = null)
    {
       return $this->settings->get($key,$default);
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    function getName()
    {
        return 'room13_pref';
    }

}