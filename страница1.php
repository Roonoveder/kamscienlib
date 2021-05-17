<?php
function ValidateEmail($email)
{
   $pattern = '/^([0-9a-z]([-.\w]*[0-9a-z])*@(([0-9a-z])+([-\w]*[0-9a-z])*\.)+[a-z]{2,6})$/i';
   return preg_match($pattern, $email);
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['formid']) && $_POST['formid'] == 'contact')
{
   $mailto = 'oleynikalexey98@gmail.com';
   $mailfrom = isset($_POST['email']) ? $_POST['email'] : $mailto;
   $mailcc = 'roonoveder@gmail.com';
   $subject = 'Сообщение из моей библиотеки';
   $message = 'Values submitted from web site form:';
   $success_url = './contact.php';
   $error_url = '';
   $eol = "\n";
   $error = '';
   $internalfields = array ("submit", "reset", "send", "filesize", "formid", "captcha", "recaptcha_challenge_field", "recaptcha_response_field", "g-recaptcha-response", "h-captcha-response");
   $boundary = md5(uniqid(time()));
   $header  = 'From: '.$mailfrom.$eol;
   $header .= 'Reply-To: '.$mailfrom.$eol;
   $header .= 'Cc: '.$mailcc.$eol;
   $header .= 'MIME-Version: 1.0'.$eol;
   $header .= 'Content-Type: multipart/mixed; boundary="'.$boundary.'"'.$eol;
   $header .= 'X-Mailer: PHP v'.phpversion().$eol;

   try
   {
      if (!ValidateEmail($mailfrom))
      {
         $error .= "The specified email address (" . $mailfrom . ") is invalid!\n<br>";
         throw new Exception($error);
      }
      $message .= $eol;
      foreach ($_POST as $key => $value)
      {
         if (!in_array(strtolower($key), $internalfields))
         {
            if (is_array($value))
            {
               $message .= ucwords(str_replace("_", " ", $key)) . " : " . implode(",", $value) . $eol;
            }
            else
            {
               $message .= ucwords(str_replace("_", " ", $key)) . " : " . $value . $eol;
            }
         }
      }
      $body  = 'This is a multi-part message in MIME format.'.$eol.$eol;
      $body .= '--'.$boundary.$eol;
      $body .= 'Content-Type: text/plain; charset=UTF-8'.$eol;
      $body .= 'Content-Transfer-Encoding: 8bit'.$eol;
      $body .= $eol.stripslashes($message).$eol;
      if (!empty($_FILES))
      {
         foreach ($_FILES as $key => $value)
         {
             if ($_FILES[$key]['error'] == 0)
             {
                $body .= '--'.$boundary.$eol;
                $body .= 'Content-Type: '.$_FILES[$key]['type'].'; name='.$_FILES[$key]['name'].$eol;
                $body .= 'Content-Transfer-Encoding: base64'.$eol;
                $body .= 'Content-Disposition: attachment; filename='.$_FILES[$key]['name'].$eol;
                $body .= $eol.chunk_split(base64_encode(file_get_contents($_FILES[$key]['tmp_name']))).$eol;
             }
         }
      }
      $body .= '--'.$boundary.'--'.$eol;
      if ($mailto != '')
      {
         mail($mailto, $subject, $body, $header);
      }
      header('Location: '.$success_url);
   }
   catch (Exception $e)
   {
      $errorcode = file_get_contents($error_url);
      $replace = "##error##";
      $errorcode = str_replace($replace, $e->getMessage(), $errorcode);
      echo $errorcode;
   }
   exit;
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>contacts</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="css/font-awesome.min.css" rel="stylesheet">
<link href="css/diplom.css" rel="stylesheet">
<link href="css/страница1.css" rel="stylesheet">
<script src="jquery-1.12.4.min.js"></script>
<script src="jquery-ui.min.js"></script>
<script>
$(document).ready(function()
{
   $("a[href*='#header']").click(function(event)
   {
      event.preventDefault();
      $('html, body').stop().animate({ scrollTop: $('#wb_header').offset().top }, 600, 'easeOutCirc');
   });
   $("a[href*='#home']").click(function(event)
   {
      event.preventDefault();
      $('html, body').stop().animate({ scrollTop: $('#wb_home').offset().top-88 }, 600, 'easeOutCirc');
   });
   $("a[href*='#contact']").click(function(event)
   {
      event.preventDefault();
      $('html, body').stop().animate({ scrollTop: $('#wb_contact').offset().top }, 600, 'easeInCubic');
   });
   $("a[href*='#footer']").click(function(event)
   {
      event.preventDefault();
      $('html, body').stop().animate({ scrollTop: $('#wb_footer').offset().top-68 }, 600, 'easeOutCirc');
   });
});
</script>
</head>
<body>
<div id="wb_header">
<div id="header">
<div class="row">
<div class="col-1">
<div id="wb_headerIcon" style="display:inline-block;width:47px;height:42px;text-align:center;z-index:0;">
<a href="./index.html"><div id="headerIcon"><i class="headerIcon"></i></div></a>
</div>
</div>
<div class="col-2">
<div id="wb_CssMenu1" style="display:inline-block;width:490px;height:48px;z-index:1;">
<ul id="CssMenu1" role="menubar" class="nav">
<li class="nav-item firstmain"><a role="menuitem" class="nav-link" href="./index.html" target="_self">&#1043;&#1083;&#1072;&#1074;&#1085;&#1072;&#1103;&nbsp;&#1089;&#1090;&#1088;&#1072;&#1085;&#1080;&#1094;&#1072;</a>
</li>
<li class="nav-item"><a role="menuitem" class="nav-link" href="./about.html" target="_self">&#1054;&nbsp;&#1085;&#1072;&#1089;</a>
</li>
<li class="nav-item"><a role="menuitem" class="nav-link" href="./articles-1.html" target="_self">&#1057;&#1090;&#1072;&#1090;&#1100;&#1080;</a>
</li>
<li class="nav-item"><a role="menuitem" class="nav-link" href="./team.html" target="_self">&#1050;&#1086;&#1084;&#1072;&#1085;&#1076;&#1072;</a>
</li>
<li class="nav-item"><a role="menuitem" class="nav-link" href="./contact.php" target="_self">&#1050;&#1086;&#1085;&#1090;&#1072;&#1082;&#1090;</a>
</li>
</ul>

</div>
</div>
</div>
</div>
</div>
<div id="wb_home">
<div id="home-divider-top">
<svg version="1.1" viewBox="0 0 240 24" preserveAspectRatio="none">
<path class="divider-fill" style="opacity:0.33" d="M0,13.229V23.87h240V13.27C212.247,6.884,170.508,0,119.81,0 C69.18,0,27.642,6.884,0,13.229z" />
<path class="divider-fill" style="opacity:0.33" d="M0,18.904V24h240v-5.036c-21.641-6.315-64.639-16.257-120.171-16.257 C64.351,2.707,21.572,12.589,0,18.904z" />
<path class="divider-fill" d="M119.829,5.115C47.852,5.115,0,24,0,24h239.961C239.961,24,191.816,5.115,119.829,5.115z" />
</svg>
</div>
<div id="home">
<div class="col-1">
<div id="wb_homeText">
<p style="font-weight:bold;">Контакт</p>
<p style="font-size:15px;line-height:16.5px;">&nbsp;</p>
<p style="font-size:16px;line-height:18px;">Камышинская научная библиотека находится по адресу: </p>
<p style="font-size:16px;line-height:18px;">г. Камышин</p>
</div>
</div>
</div>
</div>
<div id="StickyLayer" style="position:fixed;text-align:left;left:auto;right:25px;top:auto;bottom:25px;width:50px;height:50px;z-index:14;">
<div id="wb_up-arrow" style="position:absolute;left:9px;top:8px;width:24px;height:24px;text-align:center;z-index:3;">
<a href="./index.html#home"><div id="up-arrow"><i class="fa fa-angle-up"></i></div></a></div>
</div>
<div id="wb_contact">
<div id="contact-divider-bottom">
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 240 22" preserveAspectRatio="none">
<path class="divider-fill" style="opacity:0.33" d="m 239.971,0.03506085 c -4.61,-0.17416486 -9.552,0.31166344 -15,1.20998745 C 204.576,4.6559297 198.738,12.530015 185.552,17.095884 163.344,24.776555 144.006,3.7851053 119.933,3.7851053 94.252,3.7851053 89.447,26.941699 57.427,20.331684 34.632,15.582483 27.625,7.6350655 10.323,10.13754 7.613,10.540869 3.77,11.090863 0,11.796689 V 22 H 240 V 0.03506085 Z" />
<path class="divider-fill" d="m 239.99,5.2731131 c -4.825,-0.584952 -10.606,-0.1919374 -17.48,0.813449 -20.276,2.943954 -24.593,9.9542389 -38.892,13.9026649 -23.967,6.65383 -39.71,-14.633855 -61.787,-14.633855 -23.521,0 -37.345,20.794132 -63.486,15.119183 C 39.701,16.406396 32.922,9.5240694 15.861,11.69936 9.751,12.476249 4.438,14.039168 0,17.816679 V 22 H 240 V 6.6011371 Z" />
<path class="divider-fill" style="opacity:0.33" d="M 222.049,3.2571465 C 201.246,6.7240757 196.851,14.983196 182.2,19.643048 157.589,27.470666 141.472,2.4081215 118.858,2.4081215 94.734,2.4081215 80.571,26.930123 53.776,20.210617 34.707,15.400719 27.743,7.3112169 10.27,9.8648154 6.553,10.414678 3.121,11.28141 0,12.399774 V 22 H 240 V 2.3242443 C 234.979,1.6345862 229.1,2.0912517 222.049,3.2571465 Z" />
</svg>
</div>
<form name="contact" method="post" action="<?php echo basename(__FILE__); ?>" enctype="multipart/form-data" accept-charset="UTF-8" target="_blank" id="contact">
<input type="hidden" name="formid" value="contact">
<div class="row">
<div class="col-1">
<div id="wb_Heading10" style="display:inline-block;width:100%;z-index:4;">
<h2 id="Heading10">Есть вопросы? <br>Хотите предоставить статью?<br>Не стесняйтесь обращаться к нам.</h2>
</div>
<div id="wb_FontAwesomeIcon1" style="display:inline-block;width:64px;height:64px;text-align:center;z-index:5;">
<div id="FontAwesomeIcon1"><i class="fa fa-envelope-o"></i></div>
</div>
</div>
<div class="col-2">
<div class="col-2-padding">
<input type="text" id="Editbox1" style="display:block;width: 100%;height:52px;z-index:6;" name="Имя" value="" spellcheck="false">
</div>
</div>
</div>
</form>
</div>
<div id="wb_footer" style="z-index: 10000 !important;">
<div id="footer">
<div class="row">
<div class="col-1">
</div>
<div class="col-2">
<div id="wb_footerLabel2" style="display:inline-block;width:100%;z-index:7;">
<h3 id="footerLabel2">КОНТАКТЫ</h3>
</div>
<div id="wb_footerText">
<p>Phone: +7 999 626 24 07</p>
<p>Email: oleynikalexey98@gmail.com</p>
<p>&nbsp;</p>
</div>
<div id="wb_IconFont1" style="display:inline-block;width:18px;height:18px;text-align:center;z-index:9;">
<a href="https://vk.com/oleynikalexey"><div id="IconFont1"><i class="fa fa-vk"></i></div></a>
</div>
</div>
<div class="col-3">
<div id="wb_footerLabel3" style="display:inline-block;width:100%;z-index:10;">
<h3 id="footerLabel3">ПОЛЕЗНЫЕ ССЫЛКИ</h3>
</div>
<div id="wb_footerMenu" style="display:inline-block;width:100%;z-index:11;">
<ul id="footerMenu" role="menubar" class="nav">
<li class="nav-item firstmain"><a role="menuitem" class="nav-link" href="./index.html" target="_self">&#1043;&#1083;&#1072;&#1074;&#1085;&#1072;&#1103;&nbsp;&#1089;&#1090;&#1088;&#1072;&#1085;&#1080;&#1094;&#1072;</a>
</li>
<li class="nav-item"><a role="menuitem" class="nav-link" href="./about.html" target="_self">&#1054;&nbsp;&#1085;&#1072;&#1089;</a>
</li>
<li class="nav-item"><a role="menuitem" class="nav-link" href="./articles-1.html" target="_self">&#1057;&#1090;&#1072;&#1090;&#1100;&#1080;</a>
</li>
<li class="nav-item"><a role="menuitem" class="nav-link" href="./team.html" target="_self">&#1050;&#1086;&#1084;&#1072;&#1085;&#1076;&#1072;</a>
</li>
<li class="nav-item"><a role="menuitem" class="nav-link" href="./contact.php" target="_self">&#1050;&#1086;&#1085;&#1090;&#1072;&#1082;&#1090;</a>
</li>
</ul>

</div>
</div>
</div>
</div>
</div>
</body>
</html>