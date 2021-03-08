<?php
  /**
   *
   * @copyright 2008 - https://www.clicshopping.org
   * @Brand : ClicShopping(Tm) at Inpi all right Reserved
   * @Licence GPL 2 & MIT
   * @licence MIT - Portion of osCommerce 2.4
   * @Info : https://www.clicshopping.org/forum/trademark/
   *
   */

  use ClicShopping\OM\Registry;
  use ClicShopping\OM\CLICSHOPPING;

  class ht_mailchimp_360
  {
    public $code;
    public $group;
    public string $title;
    public string $description;
    public ?int $sort_order = 0;
    public bool $enabled = false;

    public function __construct()
    {
      $this->code = get_class($this);
      $this->group = basename(__DIR__);
      $this->title = CLICSHOPPING::getDef('module_header_tags_mailchimp_360_title');
      $this->description = CLICSHOPPING::getDef('module_header_tags_mailchimp_360_description');

      if (\defined('MODULE_HEADER_TAGS_MAILCHIMP_360_STATUS')) {
        $this->sort_order = MODULE_HEADER_TAGS_MAILCHIMP_360_SORT_ORDER;
        $this->enabled = (MODULE_HEADER_TAGS_MAILCHIMP_360_STATUS == 'True');
      }
    }

    public function execute()
    {

      $CLICSHOPPING_Template = Registry::get('Template');

      include_once($CLICSHOPPING_Template->getModuleDirectory() . '/header_tags/ht_mailchimp_360/MCAPI.class.php');
      include_once($CLICSHOPPING_Template->getModuleDirectory() . '/header_tags/ht_mailchimp_360/mc360.php');

      $mc360 = new mc360();
      $mc360->set_cookies();

      if (isset($_GET['Checkout']) && isset($_GET['Success'])) {
        $mc360->process();
      }
    }

    public function isEnabled()
    {
      return $this->enabled;
    }

    public function check()
    {
      return \defined('MODULE_HEADER_TAGS_MAILCHIMP_360_STATUS');
    }

    public function install()
    {
      $CLICSHOPPING_Db = Registry::get('Db');

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Enable MailChimp 360 Module',
          'configuration_key' => 'MODULE_HEADER_TAGS_MAILCHIMP_360_STATUS',
          'configuration_value' => 'True',
          'configuration_description' => 'Do you want to activate this module in your shop?',
          'configuration_group_id' => '6',
          'sort_order' => '1',
          'set_function' => 'clic_cfg_set_boolean_value(array(\'True\', \'False\'))',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'API Key',
          'configuration_key' => 'MODULE_HEADER_TAGS_MAILCHIMP_360_API_KEY',
          'configuration_value' => '',
          'configuration_description' => 'An API Key assigned to your MailChimp account',
          'configuration_group_id' => '6',
          'sort_order' => '0',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Debug E-Mail',
          'configuration_key' => 'MODULE_HEADER_TAGS_MAILCHIMP_360_DEBUG_EMAIL',
          'configuration_value' => '',
          'configuration_description' => 'If an e-mail address is entered, debug data will be sent to it',
          'configuration_group_id' => '6',
          'sort_order' => '0',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Sort Order',
          'configuration_key' => 'MODULE_HEADER_TAGS_MAILCHIMP_360_SORT_ORDER',
          'configuration_value' => '135',
          'configuration_description' => 'Sort order of display. Lowest is displayed first.',
          'configuration_group_id' => '6',
          'sort_order' => '115',
          'date_added' => 'now()'
        ]
      );

// Internal parameters

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'MailChimp Store ID',
          'configuration_key' => 'MODULE_HEADER_TAGS_MAILCHIMP_360_STORE_ID',
          'configuration_value' => '',
          'configuration_description' => 'Do not edit. Store ID value.',
          'configuration_group_id' => '6',
          'sort_order' => '0',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'MailChimp Key Valid',
          'configuration_key' => 'MODULE_HEADER_TAGS_MAILCHIMP_360_KEY_VALID',
          'configuration_value' => '',
          'configuration_description' => 'Do not edit. Key Value value.',
          'configuration_group_id' => '6',
          'sort_order' => '0',
          'date_added' => 'now()'
        ]
      );
    }

    public function remove()
    {
      Registry::get('Db')->query('delete from :table_configuration where configuration_key in ("' . implode('", "', $this->keys()) . '")');

// Internal parameters
      Registry::get('Db')->query('delete from :table_configuration where configuration_key in ("MODULE_HEADER_TAGS_MAILCHIMP_360_STORE_ID", "MODULE_HEADER_TAGS_MAILCHIMP_360_KEY_VALID")');
    }

    public function keys()
    {
      return array('MODULE_HEADER_TAGS_MAILCHIMP_360_STATUS',
        'MODULE_HEADER_TAGS_MAILCHIMP_360_API_KEY',
        'MODULE_HEADER_TAGS_MAILCHIMP_360_DEBUG_EMAIL',
        'MODULE_HEADER_TAGS_MAILCHIMP_360_SORT_ORDER');
    }
  }
