Запуск тестів для Ubuntu

1. Встановити:
      а) PHPUnit(https://phpunit.de/)
      б) Guzzle(http://guzzle.readthedocs.org/en/latest/overview.html#installation). 
   
   Обидва можно встановити використовуючи Сomposer(https://getcomposer.org/)
      $ composer require guzzlehttp/guzzle:^6.1 phpunit/phpunit:^5.5

2. Відкрити консоль Ctrl+Alt+T та запустити тести за допомогою наступної команди:
      $ php vendor/bin/phpunit /тут/потрібно/вказати/шлях/до/файлу/BooksTest.php
