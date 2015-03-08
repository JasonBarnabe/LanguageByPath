<?php if (!defined('APPLICATION')) exit();

function LanguageByPathPluginConfig() {
  $Config = [];

  # Map your URL segment to Vanilla's locale code.
  $Config['LocaleOverrides'] = [
    #'en' => 'en-CA',
    #'fr-CA' => 'fr_CA',
    #'nb' => 'no',
    #'pt-BR' => 'pt_BR',
    #'zh-CN' => 'zh',
    #'zh-TW' => 'zh_TW'
  ];

  return $Config;
}
