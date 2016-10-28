<?php
/* template head */
/* end template head */ ob_start(); /* template body */ ?>    <div class="container">

      <form name="input" action="csv_down.html" method="get">
      
      URLを入力してください <br>
      <input type="text" name="url" value="" size="100">
      <br>
      <input type="submit" value="submit">

      </form>

    </div> <!-- /container -->
<?php  /* end template body */
return $this->buffer . ob_get_clean();
?>