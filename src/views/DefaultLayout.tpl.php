<html>
    <head>
        <title>eBay Code Practice</title>
        <style media="all" type="text/css">
            @import url("<?= $_SERVER["REQUEST_URI"]; ?>css/style.css");
            @import url("<?= $_SERVER["REQUEST_URI"]; ?>css/daveCss.css");
        </style>
    </head>
    <body>
        <div class="wrap">
            <div class="page">
                <?php echo $templateData; ?>
            </div>
        </div>
    </body>
</html>