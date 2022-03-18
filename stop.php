<?php  
  $f = fopen ("stop.txt", "w+");
  fwrite ($f, "0");
  fclose ($f);?>