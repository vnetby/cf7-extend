<h2>Contact Form 7 Extend</h2>
<p>
  Данный плагин решает следующие задачи:
</p>
<ul>
  <li>Добавляет возможность привязки форм к заданным областям в теме</li>
  <li>Добавляет возможность использовать одни сообщения для всех форм</li>
  <li>
    Расширяет интеграцию с google recaptcha. Загрузка скриптов recaptcha
    происходит тоько перед отправкой формы, таким образом решается проблема со
    скоростью загрузки
  </li>
</ul>

<h3>Документация:</h3>
<p>
  Плагин создает глобальный экземпляр класса $cfextend;
</p>
<p>
  Для создания области в теме:

```
  add_action('after_setup_theme', 'register_forms_locations');

  function register_forms_locations () {
    global $cfextend;
    $cfextend->register_form_location('back_request', ['label' => 'Заказать обратный звонок']);
  }
```

</p>
<p>
  Для вывода формы привязанной к заданной области:

```
echo $cfextend->get_form('back_request');
```

</p>
