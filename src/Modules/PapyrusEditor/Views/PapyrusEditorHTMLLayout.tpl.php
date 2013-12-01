<html>

    <head>

        <title>Papyrus Editor</title>

        <style>

            html {
                font-family: Impact,Verdana,Arial;
            }

            h1 {
                font-family: arial;
                text-align: center;
            }

            h2 {
                font-family: arial;
                text-align: center;
            }

            p {
                font-family: arial;
                text-align: center;
            }

            div.innerPage {
                font-family: arial;
                text-align: center;
                max-width: 70%;
                margin: 0 auto;
            }

            div.fullPage {
                width: 100%;
                display: block;
            }

        </style>

    </head>

    <body>

        <div class="fullPage">

            <div class="innerPage">

                <h1> GC Cleopatra </h1>
                <?php echo $this->renderMessages($pageVars); ?>
                <?php echo $templateData; ?>

            </div>

         </div>

    </body>

</html>