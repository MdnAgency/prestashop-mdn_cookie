<?php
/**
* 2007-2023 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2023 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class Mdn_cookie extends Module
{
    const MDN_COOKIE_LIVE_MODE = "MDN_COOKIE_LIVE_MODE";
    const MDN_COOKIE_FORCE_CONSENT = "MDN_COOKIE_FORCE_CONSENT";
    const MDN_COOKIE_CONSENT_LAYOUT = "MDN_COOKIE_CONSENT_LAYOUT";
    const MDN_COOKIE_CONSENT_POSITION = "MDN_COOKIE_CONSENT_POSITION";
    const MDN_COOKIE_SETTINGS_LAYOUT = "MDN_COOKIE_SETTINGS_LAYOUT";
    const MDN_COOKIE_SHOW_THIRD_BUTTON = "MDN_COOKIE_SHOW_THIRD_BUTTON";
    const MDN_COOKIE_DISPLAY_SECTION_FUNCTION = "MDN_COOKIE_DISPLAY_SECTION_FUNCTION";
    const MDN_COOKIE_DISPLAY_SECTION_CUSTOMISE = "MDN_COOKIE_DISPLAY_SECTION_CUSTOMISE";
    const MDN_COOKIE_DISPLAY_SECTION_SECURITY = "MDN_COOKIE_DISPLAY_SECTION_SECURITY";
    const MDN_COOKIE_DISPLAY_SECTION_ADS = "MDN_COOKIE_DISPLAY_SECTION_ADS";
    const MDN_COOKIE_DISPLAY_SECTION_ANALYTICS = "MDN_COOKIE_DISPLAY_SECTION_ANALYTICS";
    const MDN_COOKIE_AUTO_CLEAR = "MDN_COOKIE_AUTO_CLEAR";
    const MDN_COOKIE_PARAM_BTN_COLOR = "MDN_COOKIE_PARAM_BTN_COLOR";
    const MDN_COOKIE_PARAM_BTN_BG = "MDN_COOKIE_PARAM_BTN_BG";

    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'mdn_cookie';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Loris Pinna';
        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        $this->displayName = ('MDN Cookie Content');
        $this->description = ('This module is a wrapper for cookie-consent by 68publishers for easy cookie consent management with Google Tag Manager');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);

        parent::__construct();
    } 

    public function install()
    {
        Configuration::updateValue(self::MDN_COOKIE_LIVE_MODE, true);
        Configuration::updateValue(self::MDN_COOKIE_FORCE_CONSENT, false);
        Configuration::updateValue(self::MDN_COOKIE_CONSENT_LAYOUT, "cloud");
        Configuration::updateValue(self::MDN_COOKIE_SETTINGS_LAYOUT, "box");
        Configuration::updateValue(self::MDN_COOKIE_CONSENT_POSITION, "bottom right");
        Configuration::updateValue(self::MDN_COOKIE_SHOW_THIRD_BUTTON, true);
        Configuration::updateValue(self::MDN_COOKIE_DISPLAY_SECTION_ADS, true);
        Configuration::updateValue(self::MDN_COOKIE_DISPLAY_SECTION_ANALYTICS, true);
        Configuration::updateValue(self::MDN_COOKIE_DISPLAY_SECTION_FUNCTION, true);
        Configuration::updateValue(self::MDN_COOKIE_DISPLAY_SECTION_CUSTOMISE, true);
        Configuration::updateValue(self::MDN_COOKIE_DISPLAY_SECTION_SECURITY, true);
        Configuration::updateValue(self::MDN_COOKIE_AUTO_CLEAR, false);
        Configuration::updateValue(self::MDN_COOKIE_PARAM_BTN_COLOR, "#2d4156");
        Configuration::updateValue(self::MDN_COOKIE_PARAM_BTN_BG, "#000");

        return parent::install() &&
            $this->registerHook('header') && 
            $this->registerHook('displayFooter');
    }

    public function uninstall()
    {
        return parent::uninstall();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        /**
         * If values have been submitted in the form, process.
         */
        if (((bool)Tools::isSubmit('submitMdn_cookieModule')) == true) {
            $this->postProcess();
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');

        return $output.$this->renderForm();
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitMdn_cookieModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('Settings'),
                'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Enable Module'),
                        'name' => self::MDN_COOKIE_LIVE_MODE,
                        'is_bool' => true,
                        'desc' => $this->l('Use this module in live mode'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Force consent'),
                        'name' => self::MDN_COOKIE_FORCE_CONSENT,
                        'is_bool' => true,
                        'desc' => $this->l('Force consent before interact with page'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Position'),
                        'name' => self::MDN_COOKIE_CONSENT_POSITION,
                        'desc' => $this->l('Choose position of consent modal'),
                        'options' => array(
                            'query' => [
                                array(
                                    'id_option' => 'bottom left',
                                    'label' => $this->l('Bottom Left')
                                ),
                                array(
                                    'id_option' => 'bottom center',
                                    'label' => $this->l('Bottom Center')
                                ),
                                array(
                                    'id_option' => 'bottom right',
                                    'label' => $this->l('Bottom Right')
                                ),
                                array(
                                    'id_option' => 'middle left',
                                    'label' => $this->l('Middle Left')
                                ),
                                array(
                                    'id_option' => 'middle center',
                                    'label' => $this->l('Middle Center')
                                ),
                                array(
                                    'id_option' => 'middle right',
                                    'label' => $this->l('Middle Right')
                                ),
                                array(
                                    'id_option' => 'top left',
                                    'label' => $this->l('Top Left')
                                ),
                                array(
                                    'id_option' => 'top center',
                                    'label' => $this->l('Top Center')
                                ),
                                array(
                                    'id_option' => 'top right',
                                    'label' => $this->l('Top Right')
                                ),
                            ],
                                'id' => 'id_option',
                                'name' => 'label',
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Layout'),
                        'name' => self::MDN_COOKIE_CONSENT_LAYOUT,
                        'desc' => $this->l('Choose layout'),
                        'options' => array(
                            'query' => [
                                array(
                                    'id_option' => 'cloud',
                                    'label' => $this->l('Cloud')
                                ),
                                array(
                                    'id_option' => 'box',
                                    'label' => $this->l('Box')
                                ),
                                array(
                                    'id_option' => 'bar',
                                    'label' => $this->l('Bar')
                                ),
                            ],
                                'id' => 'id_option',
                                'name' => 'label',
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Settings modal Layout'),
                        'name' => self::MDN_COOKIE_SETTINGS_LAYOUT,
                        'desc' => $this->l('Choose layout of settings'),
                        'options' => array(
                            'query' => [
                                array(
                                    'id_option' => 'box',
                                    'label' => $this->l('Box')
                                ),
                                array(
                                    'id_option' => 'bar',
                                    'label' => $this->l('Bar')
                                ),
                            ],
                                'id' => 'id_option',
                                'name' => 'label',
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Show third button'),
                        'name' => self::MDN_COOKIE_SHOW_THIRD_BUTTON,
                        'is_bool' => true,
                        'desc' => $this->l('Show third button with personnalize'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Enable Functionality Cookie section'),
                        'name' => self::MDN_COOKIE_DISPLAY_SECTION_FUNCTION,
                        'is_bool' => true,
                        'desc' => $this->l('Enable Functionality Cookie section in front office'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Enable Customise Cookie section'),
                        'name' => self::MDN_COOKIE_DISPLAY_SECTION_CUSTOMISE,
                        'is_bool' => true,
                        'desc' => $this->l('Enable Customise Cookie section in front office'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Enable Security Cookie section'),
                        'name' => self::MDN_COOKIE_DISPLAY_SECTION_SECURITY,
                        'is_bool' => true,
                        'desc' => $this->l('Enable Security Cookie section in front office'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Enable Ads Cookie section'),
                        'name' => self::MDN_COOKIE_DISPLAY_SECTION_ADS,
                        'is_bool' => true,
                        'desc' => $this->l('Enable Ads Cookie section in front office'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Enable Analytics Cookie section'),
                        'name' => self::MDN_COOKIE_DISPLAY_SECTION_ANALYTICS,
                        'is_bool' => true,
                        'desc' => $this->l('Enable Analytics Cookie section in front office'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Auto Clear Cookie'),
                        'name' => self::MDN_COOKIE_AUTO_CLEAR,
                        'is_bool' => true,
                        'desc' => $this->l('Enable Auto Clear Cookies if refused'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'color',
                        'label' => $this->l('Text Color'),
                        'name' => self::MDN_COOKIE_PARAM_BTN_COLOR,
                        'desc' => $this->l('Change text color'),
                    ),
                    array(
                        'type' => 'color',
                        'label' => $this->l('Background Color'),
                        'name' => self::MDN_COOKIE_PARAM_BTN_BG,
                        'desc' => $this->l('Change background color'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        return array(
            self::MDN_COOKIE_LIVE_MODE => Configuration::get(self::MDN_COOKIE_LIVE_MODE,null, null, null,  true),
            self::MDN_COOKIE_FORCE_CONSENT => Configuration::get(self::MDN_COOKIE_FORCE_CONSENT, null, null, null, false),
            self::MDN_COOKIE_CONSENT_POSITION => Configuration::get(self::MDN_COOKIE_CONSENT_POSITION, null, null, null, "bottom right"),
            self::MDN_COOKIE_CONSENT_LAYOUT => Configuration::get(self::MDN_COOKIE_CONSENT_LAYOUT, null, null, null, "cloud"),
            self::MDN_COOKIE_SETTINGS_LAYOUT => Configuration::get(self::MDN_COOKIE_SETTINGS_LAYOUT,null, null, null,  "box"),
            self::MDN_COOKIE_SHOW_THIRD_BUTTON => Configuration::get(self::MDN_COOKIE_SHOW_THIRD_BUTTON, null, null, null, true),
            self::MDN_COOKIE_AUTO_CLEAR => Configuration::get(self::MDN_COOKIE_AUTO_CLEAR,null, null, null,  false),
            self::MDN_COOKIE_DISPLAY_SECTION_ADS => Configuration::get(self::MDN_COOKIE_DISPLAY_SECTION_ADS, null, null, null, true),
            self::MDN_COOKIE_DISPLAY_SECTION_ANALYTICS => Configuration::get(self::MDN_COOKIE_DISPLAY_SECTION_ANALYTICS, null, null, null, true),
            self::MDN_COOKIE_DISPLAY_SECTION_FUNCTION => Configuration::get(self::MDN_COOKIE_DISPLAY_SECTION_FUNCTION, null, null, null, true),
            self::MDN_COOKIE_DISPLAY_SECTION_CUSTOMISE => Configuration::get(self::MDN_COOKIE_DISPLAY_SECTION_CUSTOMISE, null, null, null, true),
            self::MDN_COOKIE_DISPLAY_SECTION_SECURITY => Configuration::get(self::MDN_COOKIE_DISPLAY_SECTION_SECURITY, null, null, null, true),
            self::MDN_COOKIE_PARAM_BTN_BG => Configuration::get(self::MDN_COOKIE_PARAM_BTN_BG, null, null, null, "#fff"),
            self::MDN_COOKIE_PARAM_BTN_COLOR => Configuration::get(self::MDN_COOKIE_PARAM_BTN_COLOR, null, null, null, "#2d4156"),
        );
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');
    }

    
    public function hookDisplayFooter()
    {
        $this->context->smarty->assign(
            [
                'colors' => [
                    'text' => Configuration::get(self::MDN_COOKIE_PARAM_BTN_COLOR, null, null, null, "#2d4156"),
                    'bg' => Configuration::get(self::MDN_COOKIE_PARAM_BTN_BG, null, null, null, "#fff")
                ],
                'config' => json_encode([
                    'global' => ['enabled' => Configuration::get(self::MDN_COOKIE_LIVE_MODE, null, null, null, true)],
                    'plugin_options' => [
                        "cookie_name" => "consent-settings",
                        "force_consent" => Configuration::get(self::MDN_COOKIE_FORCE_CONSENT, null, null, null, true),
                        "page_scripts" => true
                    ],
                    'auto_clear_options' => [
                        'enabled' => Configuration::get(self::MDN_COOKIE_AUTO_CLEAR, null, null, null, false),
                        "strategy" => "clear_all_except_defined"
                    ],
                    "consent_modal_options" => [
                        'layout' =>  Configuration::get(self::MDN_COOKIE_CONSENT_LAYOUT, null, null, null, "cloud"),
                        'position' => Configuration::get(self::MDN_COOKIE_CONSENT_POSITION, null, null, null, "bottom right"),
                        'secondary_button_role' => Configuration::get(self::MDN_COOKIE_SHOW_THIRD_BUTTON, null, null, null, true) ? "accept_necessary": "settings",
                        'show_third_button' => Configuration::get(self::MDN_COOKIE_SHOW_THIRD_BUTTON, null, null, null, true),
                    ],
                    "settings_modal_options" => [
                        'layout' => Configuration::get(self::MDN_COOKIE_SETTINGS_LAYOUT, null, null, null, "box"),
                        "transition" => "slide",
                        "modal_trigger_selector" => ".open-mdn-cookie"
                    ],
                    "functionality_storage" => [
                        "enabled_by_default" => 1,
                        "display_in_widget" => Configuration::get(self::MDN_COOKIE_DISPLAY_SECTION_FUNCTION, null, null, null, true),
                        "readonly" => 1,
                    ],
                    "personalization_storage" => [
                        "enabled_by_default" => 0,
                        "display_in_widget" => Configuration::get(self::MDN_COOKIE_DISPLAY_SECTION_CUSTOMISE, null, null, null, true),
                        "readonly" => 0,
                    ],
                    "security_storage" => [
                        "enabled_by_default" => 1,
                        "display_in_widget" => Configuration::get(self::MDN_COOKIE_DISPLAY_SECTION_SECURITY, null, null, null, true),
                        "readonly" => 1,
                    ],
                    "ad_storage" => [
                        "enabled_by_default" => 0,
                        "display_in_widget" => Configuration::get(self::MDN_COOKIE_DISPLAY_SECTION_ADS, null, null, null, true),
                        "readonly" => 0,
                    ],
                    "analytics_storage" => [
                        "enabled_by_default" => 0,
                        "display_in_widget" => Configuration::get(self::MDN_COOKIE_DISPLAY_SECTION_ANALYTICS, null, null, null, true),
                        "readonly" => 0,
                    ],
                    "storage_pool" => [
                        [
                            "name" => "functionality_storage",
                            "enabled_by_default" => true,
                            "display_in_widget" => Configuration::get(self::MDN_COOKIE_DISPLAY_SECTION_FUNCTION, true),
                            "readonly" => true,
                        ],
                        [
                            "name" => "personalization_storage",
                            "enabled_by_default" => false,
                            "display_in_widget" => Configuration::get(self::MDN_COOKIE_DISPLAY_SECTION_CUSTOMISE, true),
                            "readonly" => false,
                        ],
                        [
                            "name" => "security_storage",
                            "enabled_by_default" => true,
                            "display_in_widget" => Configuration::get(self::MDN_COOKIE_DISPLAY_SECTION_SECURITY, true),
                            "readonly" => true,
                        ],
                        [
                            "name" => "ad_storage",
                            "enabled_by_default" => false,
                            "display_in_widget" => Configuration::get(self::MDN_COOKIE_DISPLAY_SECTION_ADS, true),
                            "readonly" => false,
                        ],
                        [
                            "name" => "analytics_storage",
                            "enabled_by_default" => false,
                            "display_in_widget" => Configuration::get(self::MDN_COOKIE_DISPLAY_SECTION_ANALYTICS, true),
                            "readonly" => false,
                        ]
                    ]
                ]),

            ]
        );
        return $this->display(__FILE__, "views/templates/front/hook/displayFooter.tpl");
    }
}
