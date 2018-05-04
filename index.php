<?php

return [

    'name' => 'theme-one',

    /**
     * Menu positions
     */
    'menus' => [

        'main' => 'Main',
        'offcanvas' => 'Offcanvas'

    ],

    /**
     * Widget positions
     */
    'positions' => [

        'navbar' => 'Navbar',
        'hero' => 'Hero',
        'top-top' => 'Top-top',
        'top' => 'Top',
        'top2' => 'Top 2',
        'sidebar' => 'Sidebar',
        'bottom' => 'Bottom',
        'footer' => 'Footer',
        'offcanvas' => 'Offcanvas'

    ],

    /**
     * Node defaults
     */
    'node' => [

        'title_hide' => false,
        'title_large' => false,
        'alignment' => '',
        'html_class' => '',
        'hide_system_output' => false,
        'sidebar_first' => false,
        'hero_image' => '',
        'hero_viewport' => '',
        'hero_contrast' => '',
        'hero_parallax' => '',
        'navbar_transparent' => '',
        'top_style' => 'uk-block-muted',
        'main_style' => 'uk-block-default',
        'bottom_style' => 'uk-block-muted'

    ],

    /**
     * Widget defaults
     */
    'widget' => [

        'title_hide' => false,
        'title_size' => 'uk-panel-title',
        'alignment' => '',
        'html_class' => '',
        'panel' => ''

    ],

    /**
     * Settings url
     */
    'settings' => '@site/settings#site-theme',

    /**
     * Configuration defaults
     */
    'config' => [

        'logo_contrast' => '',
        'logo_offcanvas' => ''

    ],

    /**
     * Events
     */
    'events' => [

        'view.system/site/admin/settings' => function ($event, $view) use ($app) {
            $view->script('site-theme', 'theme:app/bundle/site-theme.js', 'site-settings');
            $view->data('$theme', $this);
        },

        'view.system/site/admin/edit' => function ($event, $view) {
            $view->script('node-theme', 'theme:app/bundle/node-theme.js', 'site-edit');
        },

        'view.system/widget/edit' => function ($event, $view) {
            $view->script('widget-theme', 'theme:app/bundle/widget-theme.js', 'widget-edit');
        },

        /**
         * Set traditional meta-description
         */
        'view.meta' => function ($event, $meta) use ($app) {
            $config = $app->config('system/site');
            $meta([
                'og:type' => 'website',
                'description' => $config->get('meta.description'),
            ]);
        },

        /**
         * Custom markup calculations based on theme settings
         */
        'view.layout' => function ($event, $view) use ($app) {

            if ($app->isAdmin()) {
                return;
            }

            $params = $view->params;

            $classes = [
                'navbar' => 'tm-navbar',
                'hero' => '',
                'parallax' => ''
            ];

            $sticky = [
                'media' => 767,
                'showup' => true,
                'animation' => 'uk-animation-slide-top'
            ];

            if ($params['hero_viewport']) {
                $classes['hero'] = 'tm-hero-height';
            }

            // Sticky overlay navbar if hero position exists
            if ($params['navbar_transparent'] && $view->position()->exists('hero') && $params['hero_image']) {

                $sticky['top'] = '.uk-sticky-placeholder + *';
                $classes['navbar'] .= ' tm-navbar-overlay tm-navbar-transparent';

                if ($params['hero_viewport']) {
                    $classes['hero'] = 'uk-height-viewport';
                } else {
                    $classes['hero'] = 'tm-hero-padding';
                }

                if ($params['hero_contrast']) {

                    $sticky['clsinactive'] = 'tm-navbar-transparent tm-navbar-contrast';
                    $classes['navbar'] .= ' tm-navbar-contrast';

                } else {
                    $sticky['clsinactive'] = 'tm-navbar-transparent';
                }

            }

            if ($params['hero_parallax'] && $view->position()->exists('hero') && $params['hero_image']) {
                $classes['parallax'] = 'data-uk-parallax="{bg: \'-400\'}"';
            }

            if ($params['hero_contrast'] && $params['hero_image']) {
                $classes['hero'] .= ' uk-contrast';
            }

            if (stripos($params['html_class'], 'jm-full-width') !== false) {
                $classes['hero'] .= ' uk-hidden-small';
            }

            $classes['sticky'] = '';//'data-uk-sticky=\''.json_encode($sticky).'\'';

            $params['classes'] = $classes;
        },

        'view.system/site/widget-menu' => function ($event, $view) {

            if ($event['widget']->position == 'navbar') {
                $event->setTemplate('menu-navbar.php');
            }

        }

    ]

];
