<html>

    <head>

        <title>Papyrus Editor</title>

        <style>

            html {
                font-family: Helvetica, Arial ;
                text-align: center ;
            }

            h1 {
                font-family: Helvetica, Arial ;
                text-align: center ;
                font-size: 38pt;
                letter-spacing: 10px;
            }

            body {
                width: 100% ;
                display: block ;
            }

            p {
                font-family: Helvetica, Arial ;
                text-align: center ;
                font-size: 11pt;
                margin: 0px;
                padding: 1px;
            }

            .content {
                margin: 0 auto;
                width: 800px;
                display: block;
            }

            .papyrus-editor-plugin {
                width: 90%;
                display: block;
                border-color: black;
                margin: 5px 0px ;
                text-align: left ;
                padding: 5px 3px;
                border: 1px solid black;
                -webkit-border-radius: 25px;
                -moz-border-radius: 25px;
                border-radius: 25px;
            }

            .button {
                background: blue ;
                display: block;
                border: 2px;
                border-color: black;
                min-height: 40px;
                text-align: center;
            }

            .button:hover {
                cursor: pointer;
            }

            .button p {
                display: relative;
                top: 14px ;
            }

            .left_block {
                display: block;
                width: 75% ;
                float: left ;
            }

            .right_block {
                display: block;
                width: 25% ;
                float: left ;
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

        <script type="text/javascript">
            function searchAndReplace() {
                var inputs = document.getElementsByTagName('input');
                for (var i = 0; i < inputs.length; i += 1) {
                    if (inputs[i].type != "submit" && inputs[i].type != "Submit") {
                        if (inputs[i].id != "searchval" && inputs[i].id != "replaceval") {
                            var searchval = document.getElementById('searchval');
                            var replaceval = document.getElementById('replaceval');
                            var str = inputs[i].value ;
                            inputs[i].value = str.replace(searchval, replaceval); } } }​​​
            }
        </script>

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