<?php




class CFext_Load extends CFext_Common
{


  private $form_locations = [];


  /**
   * 
   * - Загружаем плагин
   * - Вызывается 1 раз
   * 
   */

  public function setup()
  {
    $this->add_hooks();
    $this->init_save_settings();
  }







  private function add_hooks()
  {
    add_action('wp_enqueue_scripts', [$this, '__remove_recaptcha_script']);
    add_action('admin_menu', [$this, '__add_admin_page']);

    add_filter('wpcf7_display_message', [$this, '__wpcf7_custom_messages'], 1, 2);
  }








  public function __remove_recaptcha_script()
  {
    wp_deregister_script('google-recaptcha');
  }







  public function __add_admin_page()
  {
    add_submenu_page('wpcf7', __('Расширенный настройки Contact Form 7', 'cfextend'), __('Настройки', 'cfextend'), 'manage_options', 'wpcf7-settings', [$this, '__html_admin_page']);
  }








  public function __html_admin_page()
  {
    require_once CFEXT_PATH . 'template-parts/page-settings.php';
  }









  public function register_form_location($location, $settings = [])
  {
    if (!isset($settings['label'])) $settings['label'] = $location;

    if (isset($this->form_locations[$location])) return;

    $this->form_locations[$location] = $settings;
  }









  public function get_form_locations()
  {
    $forms = get_option('wpcf7_locations');
    if (!$forms) $forms = [];

    foreach ($this->form_locations as $location => &$sets) {
      if (isset($sets['__form_id'])) continue;
      $form_id = $this->get_from_array($forms, $location);
      if (!$form_id) continue;
      $sets['__form_id'] = (int)$form_id;
    }

    return $this->form_locations;
  }








  public function get_wpcf7_forms()
  {
    global $wpdb;

    $table = $wpdb->prefix . 'posts';

    $forms = $wpdb->get_results("SELECT * FROM $table WHERE `post_type` LIKE 'wpcf7_contact_form' AND `post_status` LIKE 'publish'");

    if (!$forms || is_wp_error($forms)) return false;

    return $forms;
  }










  private function init_save_settings()
  {
    $save_settings = $this->get_from_post('cfext_save_settings');
    if ($save_settings !== 'true') return;

    $this->save_form_locations();
    $this->save_form_common_messages();
  }









  private function save_form_locations()
  {
    $form_locations = $this->get_from_request('form-locations');
    if (!is_array($form_locations)) return;

    $forms = [];

    foreach ($form_locations as $location => $form_id) {
      $forms[$location] = $form_id;
    }

    update_option('wpcf7_locations', $forms);
  }









  private function save_form_common_messages()
  {
    $this->debug_post();
    $common_messages = $this->get_from_post('wpcf7-common-messages');
    if (is_array($common_messages)) {
      update_option('wpcf7_common_messages', $common_messages);
    }

    $use_common_messages = $this->get_from_post('wpcf7_use_common_messages');

    if (!$use_common_messages) {
      delete_option('wpcf7_use_common_messages');
    } else {
      update_option('wpcf7_use_common_messages', 1);
    }
  }









  public function get_form($location, $args = [])
  {
    $form_id = $this->get_form_id_by_location($location);
    if (!$form_id) return false;

    $append = $this->get_from_array($args, 'append', '');
    $form_class = $this->get_from_array($args, 'form_class');
    $btn_submit_class = $this->get_from_array($args, 'btn_submit_class', 'btn-submit');
    $btn_submit = $this->get_from_array($args, 'btn_submit');

    if ($form_class) {
      $html = do_shortcode('[contact-form-7 id="' . $form_id . '" html_class="' . $form_class . '"]');
    } else {
      $html = do_shortcode('[contact-form-7 id="' . $form_id . '"]');
    }

    $html = preg_replace('/aria-required/', 'data-required aria-required', $html);

    $html = preg_replace("/<p>/si", "", $html);
    $html = preg_replace("/<\/p>/si", "", $html);
    $html = preg_replace("/<[\s]*br[^\/]*\/>/si", "", $html);

    if (!$btn_submit) {
      preg_match('/input[\s]*type=\"submit\".*?value=\"([^\"]+)/m', $html, $matches);
      $btn_label = $this->get_from_array($matches, 1);
      if ($btn_label) {
        $html = preg_replace('/<[\s]*input.*?type=\"submit[^>]+>/m', '<button type="submit" class="' . $btn_submit_class . '">' . $btn_label . '</button>', $html);
      }
    } else {
      $html = preg_replace('/<[\s]*input.*?type=\"submit[^>]+>/m', $btn_submit, $html);
    }


    if (function_exists('pll_current_language')) {
      $current = pll_current_language();
      $html = preg_replace("/<\/form>/", '<input type="hidden" name="current_lang" value="' . $current . '">' . $append . '</form>', $html);
    } else {
      $html = preg_replace("/<\/form>/", $append . '</form>', $html);
    }

    $html = apply_filters('cfextend_form_html', $html);

    return $html;
  }









  public function get_form_id_by_location($location)
  {
    $locations = $this->get_form_locations();
    if (!$locations) return false;
    $sets = $this->get_from_array($locations, $location);
    $form_id = $this->get_from_array($sets, '__form_id');
    if (!$form_id) return false;
    return (int)$form_id;
  }










  public function get_common_messages()
  {
    $messages = wpcf7_messages();

    $common_messages = get_option('wpcf7_common_messages');

    if (!$common_messages) $common_messages = [];

    foreach ($messages as $key => &$msg) {
      $common = $this->get_from_array($common_messages, $key);
      if ($common) {
        $msg['value'] = $common;
      } else {
        $msg['value'] = $this->get_from_array($msg, 'default');
      }
    }

    return $messages;
  }









  public function is_use_common_messages()
  {
    return get_option('wpcf7_use_common_messages');
  }








  public function __wpcf7_custom_messages($mess, $status)
  {
    if (!$this->is_use_common_messages()) return $mess;
    $messages = $this->get_common_messages();
    $common_mess = $this->get_from_array($messages, $status);
    return $this->get_from_array($common_mess, 'value');
  }
}
