<?php
/* template head */
/* end template head */ ob_start(); /* template body */ ?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?php echo (is_string($tmp=$this->readVarInto(array (  1 =>   array (    0 => '->',  ),  2 =>   array (    0 => 'pageTitle',  ),  3 =>   array (    0 => '',    1 => '',  ),), $this->scope["this"], false)) ? htmlspecialchars($tmp, ENT_QUOTES, $this->charset) : $tmp);?></title>

    <!-- Bootstrap core CSS -->
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../bootstrap/css/signin.css" rel="stylesheet">
  </head>

  <body>
    <noscript>
      <p class="noJavascript first">JavaScriptの設定がオンになっていないため、一部ご利用いただけない機能があります。<br />
      お手数ですが設定をオンにしてご利用ください。</p>
    </noscript>

    <?php echo $this->scope["content"];?>


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../bootstrap/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
<?php  /* end template body */
return $this->buffer . ob_get_clean();
?>