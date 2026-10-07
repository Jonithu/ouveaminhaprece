<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');header('X-Content-Type-Options: nosniff');
session_set_cookie_params(['httponly'=>true,'secure'=>!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off','samesite'=>'Lax']);session_start();
function reply(int $code,array $body): void {http_response_code($code);echo json_encode($body,JSON_UNESCAPED_UNICODE);exit;}
if (empty($_SESSION['csrf'])) $_SESSION['csrf']=bin2hex(random_bytes(32));
if ($_SERVER['REQUEST_METHOD']==='GET') reply(200,['csrf'=>$_SESSION['csrf']]);
if ($_SERVER['REQUEST_METHOD']!=='POST') reply(405,['message'=>'Método não permitido.']);
if (!is_string($_POST['csrf']??null) || !hash_equals($_SESSION['csrf'],$_POST['csrf'])) reply(403,['message'=>'Atualiza a página e tenta novamente.']);
if (!empty($_POST['website'])) reply(400,['message'=>'Não foi possível validar o pedido.']);
$name=trim(is_string($_POST['name']??null)?$_POST['name']:'');$email=strtolower(trim(is_string($_POST['email']??null)?$_POST['email']:''));
if (strlen($name)<2||strlen($name)>240||preg_match('/[\x00-\x1F\x7F]/',$name)||strlen($email)>254||!filter_var($email,FILTER_VALIDATE_EMAIL)||($_POST['delivery']??'')!=='1') reply(422,['message'=>'Confirma o nome, o email e a autorização para receber o ebook.']);
try {
 $config=require __DIR__.'/config.php';$dir=$config['storage_dir'];
 if (!is_dir($dir)&&!mkdir($dir,0700,true)) throw new RuntimeException('storage');
 $real=realpath($dir);$public=realpath($_SERVER['DOCUMENT_ROOT']??__DIR__);
 if(!$real||!$public||$real===$public||str_starts_with($real,$public.DIRECTORY_SEPARATOR)) throw new RuntimeException('storage must be outside web root');
 $lock=fopen($dir.'/lock','c');if(!$lock||!flock($lock,LOCK_EX))throw new RuntimeException('lock');
 $now=time();$ratefile=$dir.'/rate.json';$rates=is_file($ratefile)?json_decode(file_get_contents($ratefile),true):[];if(!is_array($rates))$rates=[];
 $rates=array_filter($rates,fn($v)=>is_array($v)&&($v['until']??0)>$now);
 $ip=hash('sha256',$_SERVER['REMOTE_ADDR']??'unknown');$rate=$rates[$ip]??['until'=>$now+3600,'count'=>0];
 if($rate['count']>=10){flock($lock,LOCK_UN);fclose($lock);reply(429,['message'=>'Demasiados pedidos. Tenta novamente mais tarde.']);}
 $rate['count']++;$rates[$ip]=$rate;
 if(file_put_contents($ratefile,json_encode($rates),LOCK_EX)===false)throw new RuntimeException('rate');chmod($ratefile,0600);
 $file=$dir.'/registos.json';$rows=is_file($file)?json_decode(file_get_contents($file),true):[];if(!is_array($rows))throw new RuntimeException('invalid existing storage');
 $cutoff=gmdate('c',$now-86400*(int)$config['retention_days']);$rows=array_values(array_filter($rows,fn($r)=>($r['created_at']??'')>=$cutoff));
 $record=['id'=>bin2hex(random_bytes(16)),'name'=>$name,'email'=>$email,'delivery_consent'=>true,'updates_consent'=>($_POST['updates']??'')==='1','privacy_version'=>$config['privacy_version'],'created_at'=>gmdate('c')];
 $rows[]=$record;$temp=$file.'.tmp';if(file_put_contents($temp,json_encode($rows,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT))===false)throw new RuntimeException('write');chmod($temp,0600);if(!rename($temp,$file))throw new RuntimeException('rename');
 flock($lock,LOCK_UN);fclose($lock);session_regenerate_id(true);$_SESSION['registered']=$record['id'];$_SESSION['csrf']=bin2hex(random_bytes(32));
 // Notification failure must not undo a saved registration.
 try {
  $recipient=$config['notification_email']??'';
  $sender=$config['notification_from']??'';
  if(!filter_var($recipient,FILTER_VALIDATE_EMAIL)||!filter_var($sender,FILTER_VALIDATE_EMAIL)||preg_match('/[\r\n]/',$recipient.$sender)) throw new RuntimeException('invalid mail configuration');
  $subject='Novo pre-registo - Ouve a Minha Prece';
  $body="Novo pre-registo no site Ouve a Minha Prece.\n\n"
   ."Nome: ".$record['name']."\n"
   ."Email: ".$record['email']."\n"
   ."Aviso de disponibilidade do ebook: autorizado\n"
   ."Preces e novidades: ".($record['updates_consent']?'autorizado':'nao autorizado')."\n"
   ."Data UTC: ".$record['created_at']."\n"
   ."ID: ".$record['id']."\n";
  $headers=['From: Ouve a Minha Prece <'.$sender.'>','MIME-Version: 1.0','Content-Type: text/plain; charset=UTF-8'];
  if(!function_exists('mail')||!@mail($recipient,$subject,$body,implode("\r\n",$headers))) throw new RuntimeException('mail transport failed');
 } catch(Throwable $notificationError) {
  error_log('OMP: registration saved; notification mail was not accepted by the local transport.');
 }
 reply(200,['message'=>'Pré-registo guardado. Obrigado por fazeres parte desta caminhada. O ebook está em preparação; o aviso de disponibilidade será enviado quando estiver pronto.']);
} catch(Throwable $e) {reply(503,['message'=>'Não foi possível guardar o registo. Tenta novamente mais tarde.']);}
