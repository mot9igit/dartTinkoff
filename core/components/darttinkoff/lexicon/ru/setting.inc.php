<?php

$_lang['area_darttinkoff_main'] = 'Тинькофф';

$_lang['setting_darttinkoff_test'] = 'Тестовый режим?';
$_lang['setting_darttinkoff_test_desc'] = 'Включайте его для проверки платежей';

$_lang['setting_darttinkoff_test_url'] = 'Тестовый URL';
$_lang['setting_darttinkoff_test_url_desc'] = 'URL для тестовых запросов';

$_lang['setting_darttinkoff_actual_url'] = 'Боевой URL';
$_lang['setting_darttinkoff_actual_url_desc'] = 'URL для боевых запросов';

$_lang['setting_darttinkoff_terminal_key'] = 'Идентификатор терминала';
$_lang['setting_darttinkoff_terminal_key_desc'] = 'Идентификатор терминала выдается Продавцу Банком.';

$_lang['setting_darttinkoff_secret_key'] = 'Секретный ключ';
$_lang['setting_darttinkoff_secret_key_desc'] = 'Секретный ключ выдается Продавцу Банком.';

$_lang['setting_darttinkoff_pay_type'] = 'Тип оплаты';
$_lang['setting_darttinkoff_pay_type_desc'] = 'O - одностадийная, T - двухстадийная';

$_lang['setting_darttinkoff_receipt_format'] = 'Формат фискальных документов';
$_lang['setting_darttinkoff_receipt_format_desc'] = 'Идентификатор формата фискальных документов';

$_lang['setting_darttinkoff_receipt_payment_mode'] = 'Признак способа расчета';
$_lang['setting_darttinkoff_receipt_payment_mode_desc'] = "Признак способа расчета. Возможные значения: <br> full_prepayment	— Полная предоплата;<br>partial_prepayment	— Частичная предоплата;<br>advance	— Аванс;<br>full_payment — Полный расчет;<br>partial_payment	— Частичный расчет и кредит;<br>credit — Кредит;<br>credit_payment	— Выплата по кредиту;<br>";

$_lang['setting_darttinkoff_receipt_payment_subject'] = 'Признак предмета расчета';
$_lang['setting_darttinkoff_receipt_payment_subject_desc'] =  "Признак предмета расчета. Возможные значения: <br>commodity — Товар;<br>excise — Подакцизный товар;<br>job	— Работа;<br>service	— Услуга;<br>gambling_bet — Ставка в азартной игре;<br>gambling_prize	— Выигрыш в азартной игре;<br>lottery	— Лотерейный билет;<br>lottery_prize — Выигрыш в лотерею;<br>intellectual_activity — Результаты интеллектуальной деятельности;<br>payment	— Платеж;<br>agent_commission — Агентское вознаграждение;<br>composite — Несколько вариантов;<br>another	— Другое;<br>";

$_lang['setting_darttinkoff_test'] = 'Тестовый режим?';
$_lang['setting_darttinkoff_test_desc'] = 'Включайте его для проверки платежей';

$_lang['setting_darttinkoff_payment_reference_term'] = 'Время жизни ссылки на оплату.';
$_lang['setting_darttinkoff_payment_reference_term_desc'] = 'Время жизни ссылки в виде "5d" - (5 дней).
По умолчанию срок жизни ссылки 1 сутки.
- «m» - месяц; 
- «d» - день; 
- «h» - час; 
- «i» - минута; 
';

$_lang['setting_darttinkoff_tax'] = 'Ставка налога.';
$_lang['setting_darttinkoff_tax_desc'] = 'Перечисление со значениями:
- «none» – без НДС;
- «vat0» – НДС по ставке 0%;
- «vat10» – НДС чека по ставке 10%;
- «vat18» – НДС чека по ставке 18%;
- «vat20» – НДС чека по ставке 20%;
- «vat110» – НДС чека по расчетной ставке 10/110;
- «vat118» – НДС чека по расчетной ставке 18/118.';

$_lang['setting_darttinkoff_taxation'] = 'Система налогообложения.';
$_lang['setting_darttinkoff_taxation_desc'] ='Перечисление со значениями: 
- «osn» – общая СН; 
- «usn_income» – упрощенная СН (доходы); 
- «usn_income_outcome» – упрощенная СН (доходы минус расходы); 
- «envd» – единый налог на вмененный доход; 
- «esn» – единый сельскохозяйственный налог; 
- «patent» – патентная СН. ';

$_lang['setting_darttinkoff_success_page_id'] = 'ID страницы успешного платежа';
$_lang['setting_darttinkoff_success_page_id_desc'] = 'На нее будет переадресовн пользователь';

$_lang['setting_darttinkoff_fail_page_id'] = 'ID страницы при неудачной оплате';
$_lang['setting_darttinkoff_fail_page_id_desc'] = 'ID ресурса, на который будет перенаправлен пользователь (покупатель) при неудачной оплате.';

$_lang['setting_darttinkoff_currency'] = 'Валюта платежа.';
$_lang['setting_darttinkoff_currency_desc'] = 'Валюта платежа - 643.';

$_lang['setting_darttinkoff_process_receipt'] = 'Обработать данные чека?';
$_lang['setting_darttinkoff_process_receipt_desc'] = 'Фискальные документы';

$_lang['setting_darttinkoff_log'] = 'Логировать запросы?';
$_lang['setting_darttinkoff_log_desc'] = 'Лог находится по адресу core/cache/logs/ наименование файла в настройке darttinkoff_log_file';

$_lang['setting_darttinkoff_log_file'] = 'Файл для логирования запросов';
$_lang['setting_darttinkoff_log_file_desc'] = 'core/cache/logs/{darttinkoff_log_file}';

$_lang['setting_darttinkoff_generate_status'] = 'ID статуса для генерации платежа';
$_lang['setting_darttinkoff_generate_status_desc'] = '';