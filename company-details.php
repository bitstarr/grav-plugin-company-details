<?php
namespace Grav\Plugin;

use Composer\Autoload\ClassLoader;
use Grav\Common\Plugin;
use Grav\Events\PermissionsRegisterEvent;
use Grav\Framework\Acl\PermissionsReader;

/**
 * Class CompanyDetailsPlugin
 * @package Grav\Plugin
 */
class CompanyDetailsPlugin extends Plugin
{
    public $features = [
        'blueprints' => 1000,
    ];
    protected $version;

    protected $route = 'company-details';

    /**
     * @return array
     *
     * The getSubscribedEvents() gives the core a list of events
     *     that the plugin wants to listen to. The key of each
     *     array section is the event that the plugin listens to
     *     and the value (in the form of an array) contains the
     *     callable (or function) as well as the priority. The
     *     higher the number the higher the priority.
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'onPluginsInitialized' => [
                // Uncomment following line when plugin requires Grav < 1.7
                // ['autoload', 100000],
                ['onPluginsInitialized', 0]
            ]
        ];
    }

    /**
     * Composer autoload
     *
     * @return ClassLoader
     */
    public function autoload(): ClassLoader
    {
        return require __DIR__ . '/vendor/autoload.php';
    }

    /**
     * Initialize the plugin
     */
    public function onPluginsInitialized(): void
    {
        if (!$this->isAdmin()) {
            return;
        }

        // Store this version and prefer newer method
        if (method_exists($this, 'getBlueprint'))
        {
            $this->version = $this->getBlueprint()->version;
        }
        else
        {
            $this->version = $this->grav['plugins']->get('admin')->blueprints()->version;
        }

        $this->enable([
            'onAdminMenu' => ['onAdminMenu', 0],
            PermissionsRegisterEvent::class => [
                ['onRegisterPermissions', 100]
            ],
        ]);
    }

    /**
     * Registering permissions
     *
     * @param PermissionsRegisterEvent $event
     * @return void
     */
    public function onRegisterPermissions(PermissionsRegisterEvent $event): void
    {
        $permissions = $event->permissions;

        $actions = [];

        $data = [
            'admin.configuration.details' => [
                'type' => 'access',
                'label' => 'PLUGIN_COMPANY_DETAILS.PERMISSION_LABEL'
            ]
        ];

        $actions[] = PermissionsReader::fromArray($data, $permissions->getTypes());

        $permissions->addActions(array_replace(...$actions));
    }

    /**
     * modify admin menu permissions
     */
    public function onAdminMenu()
    {
        // add custom permission to the settings menu item, so it will show up…
        $this->grav['twig']->plugins_hooked_nav['PLUGIN_ADMIN.CONFIGURATION']['authorize'][] = 'admin.configuration.details';

    }

}
