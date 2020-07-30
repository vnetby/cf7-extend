<?php
$form_locations = $this->get_form_locations();
$cf_forms = $this->get_wpcf7_forms();


$messages = $this->get_common_messages();

$use_common_messages = $this->is_use_common_messages();
?>

<div class="wrap" id="wpcf7-settings">

  <h1><?= __('Расширенные настройки', 'cfextend'); ?></h1>


  <form action="" method="post">
    <input type="hidden" name="cfext_save_settings" value="true">


    <div class="settings-block">
      <h2 class="title"><?= __('Управление областями', 'cfextend'); ?></h2>
      <table class="widefat fixed" id="menu-locations-table">
        <thead>
          <tr>
            <th scope="col" class="manage-column column-locations"><?= __('Область темы', 'cfextend'); ?></th>
            <th scope="col" class="manage-column column-menus"><?= __('Назначенная форма', 'cfextend'); ?></th>
          </tr>
        </thead>
        <tbody class="menu-locations">
          <?php
          foreach ($form_locations as $location => &$sets) {
            $label = $this->get_from_array($sets, 'label');
            $form_id = $this->get_from_array($sets, '__form_id');
          ?>
            <tr class="menu-locations-row">
              <td class="menu-location-title">
                <label for="locations-<?= $location; ?>"><?= $label; ?></label>
              </td>
              <td class="menu-location-menus">
                <select name="form-locations[<?= $location; ?>]" id="locations-<?= $location; ?>">
                  <option value="0">— Выберите форму —</option>
                  <?php
                  if ($cf_forms) {
                    foreach ($cf_forms as $i => &$form) {
                      $form_title = $form->post_title;
                      $cur_form_id = $form->ID;
                      $selected = (int)$cur_form_id === $form_id;
                  ?>
                      <option value="<?= $cur_form_id; ?>" <?= $selected ? 'selected' : ''; ?>><?= $form_title; ?></option>
                  <?php
                    }
                  }
                  ?>
                </select>
              </td>
            </tr>
          <?php
          }
          ?>
        </tbody>
      </table>
    </div>




    <div class="settings-block">
      <h2 class="title"><?= __('Уведомления при отправке', 'cfextend'); ?></h2>
      <p>
        <label for="wpcf7_use_common_messages">
          <input name="wpcf7_use_common_messages" type="checkbox" id="wpcf7_use_common_messages" <?= $use_common_messages ? 'checked' : ''; ?>>
          <?= __('Использовать общие сообщения для всех форм', 'cfextend'); ?>
        </label>
      </p>

      <div class="widefat">

        <?php
        foreach ($messages as $key => &$msg) {
          $desc = $this->get_from_array($msg, 'description');
          $val = $this->get_from_array($msg, 'value');
        ?>
          <p class="description">
            <label for="wpcf7-common-message-<?= $key; ?>">
              <?= $desc; ?>
              <br>
              <input type="text" id="wpcf7-common-message-<?= $key; ?>" name="wpcf7-common-messages[<?= $key; ?>]" class="large-text" size="70" value="<?= $val; ?>">
            </label>
          </p>
        <?php
        }
        ?>
      </div>
    </div>



    <p class="button-controls wp-clearfix">
      <button type="submit" class="button button-primary left">
        <?= __('Сохранить изменения', 'cfextend'); ?>
      </button>
    </p>
  </form>
</div>