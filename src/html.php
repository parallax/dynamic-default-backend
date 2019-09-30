<?php
?>

<!doctype html>
<html>

<head>

  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-42578255-7"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'UA-42578255-7');
  </script>

  <meta charset="utf-8">
  <title><?php echo $statusCode; ?> - Error</title>

  <link href="https://fonts.googleapis.com/css?family=Dosis|Muli&display=swap" rel="stylesheet">

  <style type="text/css">
    body {
      background-color: #3E3750;
    }
    h1 {
      color: white;
      font-size: calc(16px + 12vw);
      font-family: 'Dosis', sans-serif;
      margin-bottom: 0px;
      margin-top: 10vh;
    }
    h2 {
      color: white;
      font-size: calc(14px + 1vw);
      font-family: 'Muli', sans-serif;
      margin-top: 0px;
      margin-bottom: 10vh;
    }
    p {
      color: white;
      font-size: calc(14px + 0.5vw);
      line-height: calc(22px + 0.5vw);
      font-family: 'Muli', sans-serif;
      padding-left: 60px;
      padding-right: 60px;
      margin-bottom: 5vh;
    }
    p.small {
      color: white;
      font-size: calc(10px + 0.2vw);
      line-height: calc(14px + 0.3vw);
      font-family: 'Muli', sans-serif;
      padding-left: 60px;
      padding-right: 60px;
      margin-bottom: 5vh;
    }
  </style>

</head>

<body>
  <center>
    <h1><?php echo $statusCode; ?></h1>
    <h2><?php echo $title; ?></h2>
    <p><?php echo str_replace("\n", '</p><p>', $message); ?></p>
    <p class="small">This is an ingress-level error</p>
  </center>  
</body>

</html>
