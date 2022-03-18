<?php
  header('Content-Type: text/html; charset=utf-8');
  set_time_limit (100000);
  //if (!file_exists ($_GET ["dir1"]))
    //mkdir ($_GET ["dir1"]);
  $f = fopen ("stop.txt", "w");
  fwrite ($f, "1");
  fclose ($f);
  $f = fopen ("words_next.txt", "w");
  fclose ($f);
  $f = fopen ("words_prev.txt", "w");
  fclose ($f);
  go ($_GET ["dir"]); 
  $a = array ();
  $words = explode (" ", file_get_contents ("words_next.txt"));
  for ($i = 0; $i < count ($words) - 1; $i++)
    $a [$words [$i]] = 0;
  for ($i = 0; $i < count ($words) - 1; $i++)
    $a [$words [$i]]++;
  $isNext = true;
  $time = time ();
  $f = fopen ($time.".txt", "w");
  $file_cur = $f;
  fclose ($f);
  $file1 = fopen ($time.".txt", "a");
  fwrite ($file1, "Статистика словосочетаний со словом \"".$_GET ["word"]."\".

");
  if (count ($a) > 0)
  {  
    arsort ($a);
	fwrite ($file1, "Частота слов, последующих за данным словом:

");
    foreach ($a as $word => $frequency)
      fwrite ($file1, $word." => ".$frequency."
");
  }
  else
    $isNext = false;
  $a1 = array ();
  $words = explode (" ", file_get_contents ("words_prev.txt"));
  for ($i = 0; $i < count ($words) - 1; $i++)
    $a1 [$words [$i]] = 0;
  for ($i = 0; $i < count ($words) - 1; $i++)
    $a1 [$words [$i]]++;
  if (count ($a1) > 0)
  {
    if (!$isNext)
      fwrite ($file1, "В данном каталоге искомое слово если содержится, то только в конце файлов.
");
    arsort ($a1);
	fwrite ($file1, "Частота слов, предшедствующих данному слову:

");
    foreach ($a1 as $word => $frequency)
      fwrite ($file1, $word." => ".$frequency."
");
  }
  else
    if ($isNext)
      fwrite ($file1, "В данном каталоге искомое слово если содержится, то только в начале файлов.");
	else
	  fwrite ($file1, "В данном каталоге искомое слово не содержится.");
  fclose ($file1);
  $file1 = fopen ($time.".htm", "a");
  fwrite ($file1, "<!doctype>
  <html>
  <head>
  <title>Статистика словосочетаний</title>
  <meta http-equiv='Content-Type' content='text/html'; charset='UTF-8'/>
  <link rel='stylesheet' type='text/css' href='style.css'>
  </head>
  <body>
  <div class='centre'>
  <h2>Статистика словосочетаний со словом \"".$_GET ["word"]."\".</h2>");
  if (count ($a) > 0)
  {
	fwrite ($file1, "<h3>Частота слов, последующих за данным словом:</h3>
	<table>");
    foreach ($a as $word => $frequency)
      fwrite ($file1, "<tr><td >".$word."</td><td >".$frequency."</td></tr>");
	fwrite ($file1, "</table>");
  }
  else
    $isNext = false;
  if (count ($a1) > 0)
  {
    if (!$isNext)
      fwrite ($file1, "<p>В данном каталоге искомое слово если содержится, то только в конце файлов.</p>");
	fwrite ($file1, "<h3>Частота слов, предшедствующих данному слову:</h3>
	<table>");
    foreach ($a1 as $word => $frequency)
      fwrite ($file1, "<tr><td>".$word."</td><td>".$frequency."</td></tr>");
	fwrite ($file1, "</table>");
  }
  else
    if ($isNext)
      fwrite ($file1, "<p>В данном каталоге искомое слово если содержится, то только в начале файлов.</p>");
	else
	  fwrite ($file1, "<p>В данном каталоге искомое слово не содержится.</p>");
  fwrite ($file1, "<div class='centre'></body></html>");
  fclose ($file1);
  unlink ("words_next.txt");
  unlink ("words_prev.txt");
  echo "Поиск успешно завершён.<br/>
  <a href='downloads.php?file=".$time.".htm'>Ссылка на результат:</a><br/>";
  function go ($dir)
  {
    if (file_get_contents ("stop.txt") == "0")
	  return;
    if (is_dir ($dir))
	{
      $d = @opendir ($dir);
	  while ($file = @readdir ($d))
	    if (($file != ".") && ($file != "..") && ($file != ""))
          go ($dir."/".$file);
	}
	else
	{
	  if (((!strstr ($dir, ".htm")) && (!strstr ($dir, ".HTM")) && (!strstr ($dir, ".txt")) && (!strstr ($dir, ".TXT")) && (!strstr ($dir, ".fb2")) && (!strstr ($dir, ".FB2"))) || (@filesize ($dir) > 10000000))
	    return;
	  $word = mb_strtolower (str_replace ("Ё", "е", str_replace ("ё", "е", $_GET["word"])), "UTF-8");
	  $temp = "";
	  $fgcd = @file_get_contents ($dir);
      if (strstr ($fgcd, "/9j/"))
		$fgcd = strstr ($fgcd, "/9j/", true);
	  if ((strstr ($dir, ".htm")) || (strstr ($dir, ".HTM")) || (strstr ($dir, ".txt")) || (strstr ($dir, ".TXT") || (strstr ($dir, ".fb2")) || (strstr ($dir, ".FB2"))))
	  {
	    $temp = mb_strtolower (str_replace ("Ё", "е", str_replace ("ё", "е", @iconv ("Windows-1251", "UTF-8", strip_tags ($fgcd)))), "UTF-8");
	  }
	  if ((preg_match ("/[\s,\.\?!\(\);:<>\n\"'\+\*\*\&\/-]".$word."[\s,\.\?!\(\);:<>\n\"'\+\*\*\&\/-=]/", mb_strtolower (str_replace ("Ё", "е", str_replace ("ё", "е", (strip_tags ($fgcd)))), "UTF-8"))) || (($temp != "") && (preg_match ("/[\s,\.\?!\(\);:<>\n\"'\+\*\*\&\/-=]".$word."[\s,\.\?!\(\);:<>\n\"'\+\*\*\&\/-=]/", $temp))) || (preg_match ("/^".$word."[\s,\.\?!\(\);:<>\n\"'\+\*\*\&\/-]/", mb_strtolower (str_replace ("Ё", "е", str_replace ("ё", "е", strip_tags ($fgcd))), "UTF-8"))) || (($temp != "") && (preg_match ("/^".$word."[\s,\.\?!\(\);:<>\n\"'\+\*\*\&\/-=]/", $temp))) || (preg_match ("/[^\wА-Яа-я]".$word."$/", mb_strtolower (str_replace ("Ё", "е", str_replace ("ё", "е", strip_tags ($fgcd))), "UTF-8"))) || (($temp != "") && (preg_match ("/[\s,\.\?!\(\);:<>\n\"'\+\*\*\&\/-=]".$word."$/", $temp))))
	  {
		$fragment = "";
		if ((preg_match ("/[\s,\.\?!\(\);:<>\n\"'\+\*\*\&\/-=]".$word."[\s,\.\?!\(\);:<>\n\"'\+\*\*\&\/-=]/", mb_strtolower (str_replace ("Ё", "е", str_replace ("ё", "е", (strip_tags ($fgcd)))))) || (preg_match ("/".$word."[\s,\.\?!\(\);:<>\n\"'\+\*\*\&\/-=]/", mb_strtolower (str_replace ("Ё", "е", str_replace ("ё", "е", strip_tags (file_get_contents ($dir)))), "UTF-8"))) || (preg_match ("/[\s,\.\?!\(\);:<>\n\"'\+\*\*\&\/-=]".$word."$/", mb_strtolower (str_replace ("Ё", "е", str_replace ("ё", "е", strip_tags ($fgcd))), "UTF-8")))))
		  $fragment = mb_strtolower (str_replace ("Ё", "е", str_replace ("ё", "е", (strip_tags ($fgcd)))), "UTF-8");
		else
		  $fragment = $temp;
		if (strstr ($fragment, "/9j/"))
		  $fragment = strstr ($fragment, "/9j/", true);
		$words = preg_split ("/[\s,\.\?!\(\);:<>\n\"'\+\*\*\&\/-=]+/", $fragment);
		if (file_exists ("exceptions.txt"))
		  $exceptions = preg_split ("/\s+/", iconv ("Windows-1251", "UTF-8", file_get_contents ($_GET ["dir1"]."/exceptions.txt")));
	    else
		  $exceptions = array ();
		for ($i = 0; $i < count ($words); $i++)
		{
		  if ($words [$i] == $word)
		  {
		    if (($i < count ($words) - 1) && (!array_search ($words [$i + 1], $exceptions)))
			{
		      $file1 = fopen ("words_next.txt", "a");
		      fwrite ($file1, $words [$i + 1]." ");
		      fclose ($file1);
			}
			if (($i > 0) && (!array_search ($words [$i - 1], $exceptions)))
			{
		      $file1 = fopen ("words_prev.txt", "a");
		      fwrite ($file1, $words [$i - 1]." ");
		      fclose ($file1);
			}
		  }
		}
	  }
	}
  }
?>