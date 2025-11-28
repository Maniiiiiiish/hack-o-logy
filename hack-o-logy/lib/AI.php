<?php
class AI {
 static function generate($prompt,$context='') {
  $cfg = require __DIR__ . '/../config/config.php';
  $provider = getenv('AI_PROVIDER');
  if (!$provider && isset($cfg['ai_provider'])) { $provider = $cfg['ai_provider']; }
  $okey = getenv('OPENAI_API_KEY');
  if (!$okey && isset($cfg['openai_api_key'])) { $okey = $cfg['openai_api_key']; }
  $omodel = getenv('OPENAI_MODEL') ?: ($cfg['openai_model'] ?? 'gpt-4o-mini');
  $gkey = getenv('GEMINI_API_KEY');
  if (!$gkey && isset($cfg['gemini_api_key'])) { $gkey = $cfg['gemini_api_key']; }
  $gmodel = getenv('GEMINI_MODEL') ?: ($cfg['gemini_model'] ?? 'gemini-1.5-flash');

  if ($provider === 'gemini' && $gkey) {
   $url = 'https://generativelanguage.googleapis.com/v1beta/models/'.$gmodel.':generateContent?key='.$gkey;
   $text = $prompt;
   if ($context !== '') { $text = $text."\n\nContext:\n".$context; }
   $payload = [
    'contents' => [ [ 'parts' => [ [ 'text' => $text ] ] ] ],
    'generationConfig' => [ 'temperature' => 0.3 ]
   ];
   $ch = curl_init($url);
   curl_setopt($ch, CURLOPT_POST, true);
   curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
   curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
   curl_setopt($ch, CURLOPT_TIMEOUT, 10);
   $resp = curl_exec($ch);
   if ($resp === false) { curl_close($ch); return null; }
   curl_close($ch);
   $data = json_decode($resp, true);
   if (!$data) return null;
   $out = '';
   if (isset($data['candidates'][0]['content']['parts'])) {
    foreach ($data['candidates'][0]['content']['parts'] as $p) { if (isset($p['text'])) { $out .= $p['text']; } }
   }
   if ($out === '' && isset($data['candidates'][0]['output_text'])) { $out = $data['candidates'][0]['output_text']; }
   return $out ?: null;
  }
  if ($okey) {
   $messages = [
    [ 'role' => 'system', 'content' => ($context!==''?("Use this context to ground answers.\n".$context):'') ],
    [ 'role' => 'user', 'content' => $prompt ]
   ];
   $payload = [ 'model' => $omodel, 'messages' => $messages, 'temperature' => 0.3 ];
   $ch = curl_init('https://api.openai.com/v1/chat/completions');
   curl_setopt($ch, CURLOPT_POST, true);
   curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json','Authorization: Bearer '.$okey]);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
   curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
   curl_setopt($ch, CURLOPT_TIMEOUT, 10);
   $resp = curl_exec($ch);
   if ($resp === false) { curl_close($ch); } else { curl_close($ch); $data = json_decode($resp,true); if ($data && isset($data['choices'][0]['message']['content'])) { return $data['choices'][0]['message']['content']; } }
  }
  if ($provider === 'openai' && !$okey) return null;
  if ($provider === 'openai' && $okey) { /* handled above */ }
  // default: try openai then gemini
  if (!$okey && !$gkey) return null;
  $url = 'https://generativelanguage.googleapis.com/v1beta/models/'.$gmodel.':generateContent?key='.$gkey;
  $text = $prompt;
  if ($context !== '') { $text = $text."\n\nContext:\n".$context; }
  $payload = [ 'contents' => [ [ 'parts' => [ [ 'text' => $text ] ] ] ] ];
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($ch, CURLOPT_TIMEOUT, 10);
  $resp = curl_exec($ch);
  if ($resp === false) { curl_close($ch); return null; }
  curl_close($ch);
  $data = json_decode($resp, true);
  if (!$data) return null;
  $out = '';
  if (isset($data['candidates'][0]['content']['parts'])) {
   foreach ($data['candidates'][0]['content']['parts'] as $p) { if (isset($p['text'])) { $out .= $p['text']; } }
  }
  if ($out === '' && isset($data['candidates'][0]['output_text'])) { $out = $data['candidates'][0]['output_text']; }
  return $out ?: null;
 }
}
