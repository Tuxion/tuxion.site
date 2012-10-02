<?php if(!defined('TX')) die('No direct access.');

function __($phrase, $only_return = false, $case = null)
{

  //Load ini file.
  $lang_file = PATH_SITE.'/languages/'.LANGUAGE_CODE.'.ini';
  if(!is_file($lang_file)){
    throw new \exception\FileMissing('The file \'%s\' can not be found.', $lang_file);
  }

  //Parse ini file.
  $ini_arr = parse_ini_file($lang_file);
  
  //Translate.
  if(array_key_exists($phrase, $ini_arr)){
    $phrase = $ini_arr[$phrase];
  }

  //Convert case?
  switch($case)
  {
    case 'ucfirst':
      $phrase = ucfirst($phrase);
      break;
    case 'l':
    case 'lower':
    case 'lowercase':
      $phrase = strtolower($phrase);
      break;
    case 'u':
    case 'upper':
    case 'uppercase':
      $phrase = strtoupper($phrase);
      break;
  }
  
  //Return (translated) phrase.
  if($only_return){
    return $phrase;
  }else{
    echo $phrase;
  }

}

function ___($phrase, $case = null)
{
  return __($phrase, 1, $case);
}