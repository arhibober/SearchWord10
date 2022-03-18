<?php
  header('Content-Type: text/html; charset=utf-8');
  set_time_limit (100000);
  echo "<!doctype>
  <html>
  <head>
  <title>Статистика словосочетаний</title>
  <link rel='stylesheet' type='text/css' href='style.css'>
  </head>
  <body>
  <div class='centre'>
  <h1>Статистика словосочетаний</h1>
  Введите каталог с исходными данными:<input type='text' name='dir' id='dir' value='D:'/><br/>
  Введите слово:<input type='text' name='word' id='word'/><br/>
  <input type='submit' value='Пуск' onClick='thr ()'/><br/>
  <input type='submit' value='Стоп' onClick='thr1 ()'/>
  <div id='dest'></div>
  <div id='dest1'></div>
  </div>
  </body>
  </html>";
?>
<script language="javascript">
  function thr ()
  {
    if (document.getElementById ("dir").value.lenght == 0)
	{
	  alert ("Вы не ввели каталог с исходными данными!") 
	  return;
	}
    if (!document.getElementById ("dir").value.match (/^[A-Za-z]:/))
	{
	  alert ("Ошибка: некорректное название каталога с исходными данными!") 
	  return;
	}
    if (document.getElementById ("word").value.lenght == 0)
	{
	  alert ("Вы не ввели искомое слово!") 
	  return;
	}
    if (document.getElementById ("word").value.match (/[\s,\.\?!\(\);:<>\n\"'\+\*\*\&\/-=]/))
	{
	  alert ("Ошибка! Искомое слово в поле может быть только одно!") 
	  return;
	}
    var req = null;
    document.getElementById ("dest").innerHTML = "Идёт обработка файлов...";
    if (window.XMLHttpRequest)
	  req = new XMLHttpRequest();
    else
	  if (window.ActiveXObject)
	  {
	    try
	    {
		  req = new ActiveXObject ("Msxml2.XMLHTTP");
	    }
	    catch (e)
	    {
		  try
		  {
		    req = new ActiveXObject ("Microsoft.XMLHTTP");
	      }
		  catch (e)
		  {
	      }
	    }
	  }
    req.onreadystatechange = function ()
    {
      if (req.readyState == 4)
      {
	    if (req.status == 200)
	    {
	      document.getElementById ("dest").innerHTML = req.responseText;
	    }
	    else
	    {
	      document.getElementById ("dest").innerHTML = "Код ошибки: " + req.status + " " + req.statusText;
	    }
      }
    };
	var dir = document.getElementById ("dir").value;
	var word = document.getElementById ("word").value;
    var url = "process.php?dir=" + dir + "&word=" + word;
    req.open ("GET", url, true);
    req.send (null);
  }
  function thr1 ()
  {
    var req = null;
    document.getElementById ("dest1").innerHTML = "Идёт обработка файлов...";
    if (window.XMLHttpRequest)
	  req = new XMLHttpRequest();
    else
	  if (window.ActiveXObject)
	  {
	    try
	    {
		  req = new ActiveXObject ("Msxml2.XMLHTTP");
	    }
	    catch (e)
	    {
		  try
		  {
		    req = new ActiveXObject ("Microsoft.XMLHTTP");
	      }
		  catch (e)
		  {
	      }
	    }
	  }
    req.onreadystatechange = function ()
    {
      if (req.readyState == 4)
      {
	    if (req.status == 200)
	    {
	      document.getElementById ("dest1").innerHTML = req.responseText;
	    }
	    else
	    {
	      document.getElementById ("dest1").innerHTML = "Код ошибки: " + req.status + " " + req.statusText;
	    }
      }
    };
	var dir1 = document.getElementById ("dir1").value;
    var url = "stop.php?dir=" + dir1;
	console.log (" d: " + dir1);
    req.open ("GET", url, true);
    req.send (null);
  }
</script>
	  
