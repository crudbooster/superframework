<html>
<head>
    <title><?php echo $response_code;?> | <?php echo config("app_name");?></title>
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/milligram/1.3.0/milligram.min.css">
    <style>.title {text-align: center;margin: 18% auto;}</style>
</head>
<body>
<div class="title">
    <h1><?php echo $response_code;?></h1>
    <?php if($message):?>
        <p><?php echo $message;?></p>
    <?php else:?>
        <p>Something went wrong :(</p>
    <?php endif;?>
</div>
</body>
</html>