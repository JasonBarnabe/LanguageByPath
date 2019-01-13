<?php if (!defined('APPLICATION')) exit();

require_once dirname(__FILE__).'/config.php';

class LanguageByPathPlugin extends Gdn_Plugin {

  public $Config = null;

  public function __construct() {
    parent::__construct();
    $this->Config = LanguageByPathPluginConfig();
  }

  public function Gdn_Dispatcher_AfterAnalyzeRequest_Handler($Sender) {
    $Locale = $this->ValidateLocale(GetValue('locale', $_GET, FALSE));
    if ($Locale) {
      Gdn::Locale()->set($Locale);
    }
  }

  protected function GetEnabledLocales() {
    $LocaleModel = new LocaleModel();
    $Options = $LocaleModel->EnabledLocalePacks();
    $Options['English'] = 'en-CA'; // Hackily include the default
    return $Options;
  }

  protected function ValidateLocale($Locale) {
    $Options = $this->GetEnabledLocales();
    $Locale = $this->UrlCodeToVanillaCode($Locale);
    return (in_array($Locale, $Options)) ? $Locale : FALSE;
  }

  // Returns the Vanilla locale code for the passed URL locale code
  protected function UrlCodeToVanillaCode($UrlCode) {
    if (isset($this->Config['LocaleOverrides'][$UrlCode])) {
      return $this->Config['LocaleOverrides'][$UrlCode];
    }
    return $UrlCode;
  }

  // Returns the URL locale code for the passed Vanilla locale code
  protected function VanillaCodeToUrlCode($VanillaCode) {
    $Map = array_flip($this->Config['LocaleOverrides']);
    if (isset($Map[$VanillaCode])) {
      return $Map[$VanillaCode];
    }
    return $VanillaCode;
  }

  // Add <link rel="alternate" hreflang>
  public function Base_Render_Before($Sender) {
    foreach ($this->GetEnabledLocales() as $Name => $Code) {
      $Code = $this->VanillaCodeToUrlCode($Code);
      // Standard is a dash, not an underscore
      $Hreflang = str_replace('_', '-', $Code);
      $PathPattern = '/^\\/'.GetValue('locale', $_GET, FALSE).'\\//';
      $Url = $_SERVER["REQUEST_URI"];
      if (preg_match($PathPattern, $Url)) {
        $Url = preg_replace($PathPattern, '/'.$Code.'/', $Url);
      } else {
        $Url = '/'.$Code.$Url;
      }
      $Sender->Head->AddTag('link', ['rel' => 'alternate', 'hreflang' => $Hreflang, 'href' => $Url]);
    }
  }

}
