<style type="text/css">
<!--
body {
	font-family: Osaka,Verdana,Arial,Helvetica,sans-serif;
	text-align: center;
	line-height: 150%;
	font-size: 80%;
	color: #333333;
	margin:100px auto 0px auto;
}
.error_out {
	margin:0 auto;
	width:500px;
	text-align:left;
	border-color:#990000;
	border-width:2px;
	border-style:solid;
}
.error_left {
	width:160px;
	line-height:400%;
	background:#990000;
	padding:10px;
	font-size:150%;
	font-weight:bold;
	color:#FFFFFF;
	text-align:center;
	float:left;
}
.error_right {
	width:290px;
	margin:15px 10px 10px 10px;
	text-align:left;
	float:right;
}    
-->
</style>

<div class="error_out">
<div class="error_left">Error!</div>
<div class="error_right">
  <strong>エラーが発生しました!</strong><br />
  URLはhttpsを使用してください<br />
  <div class="home"></div>
</div>
<br style="clear:both">
</div>

<div class="container">    
      <form name="input" action="download" method="post">
      <br>
      URLを入力してください <br>
      <input type="text" name="url" value="" size="100">
      <br>
      <input type="submit" value="submit">
      </form>
</div> <!-- /container -->
