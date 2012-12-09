<html>
    <head>
        <title>eBay Code Practice</title>
        <!--<link rel="stylesheet" type="text/css" href="/css/style.css" >-->
        <link rel="stylesheet" type="text/css" href="/css/daveCss.css" >
    </head>
    <body>
        <div class="wrap">
            <div class="page">
                <div class="pageRow">
                    <div class="navMenu">
                        <form action="/index.php" id="homePageButton" method="POST">
                            <input type="hidden" id="homePageButtonControl" name="control" value="index" class="buttonInput" />
                            <input type="hidden" id="homePageButtonAction" name="action" value="index" class="buttonInput" />
                            <input type="submit" id="homePageButtonSubmit" name="submit" value="Home" class="buttonInput button" />
                        </form>
                        <form action="/index.php" id="registerPageButton" method="POST">
                            <input type="hidden" id="registerPageButtonControl" name="control" value="register" class="buttonInput" />
                            <input type="hidden" id="registerPageButtonAction" name="action" value="register" class="buttonInput" />
                            <input type="submit" id="registerPageButtonSubmit" name="submit" value="Register" class="buttonInput button" />
                        </form>

                <?php if ( $pageVars["user"]->getLoginStatus() ==true) { ?>
                        <form action="/index.php" id="logoutPageButton" method="POST">
                            <input type="hidden" id="logoutPageButtonControl" name="control" value="logout" class="buttonInput" />
                            <input type="hidden" id="logoutPageButtonAction" name="action" value="logout" class="buttonInput" />
                            <input type="submit" id="logoutPageButtonSubmit" name="submit" value="logout" class="buttonInput button" />
                        </form>
                <?php } else { ?>
                        <form action="/index.php" id="loginPageButton" method="POST">
                            <input type="hidden" id="loginPageButtonControl" name="control" value="login" class="buttonInput" />
                            <input type="hidden" id="loginPageButtonAction" name="action" value="login" class="buttonInput" />
                            <input type="submit" id="loginPageButtonSubmit" name="submit" value="Login" class="buttonInput button" />
                        </form>
                <?php } ?>

                    </div> <!-- end navMenu -->

                <?php

                    if ( $pageVars["user"]->getLoginStatus() ==true) { ?>

                    <div class="navMenu">
                        <form action="/index.php" id="userPageButton" method="POST">
                            <input type="hidden" id="userPageButtonControl" name="control" value="user" class="buttonInput" />
                            <input type="hidden" id="userPageButtonAction" name="action" value="user" class="buttonInput" />
                            <input type="submit" id="userPageButtonSubmit" name="submit" value="user" class="buttonInput button" />
                        </form>
                        <form action="/index.php" id="groupPageButton" method="POST">
                            <input type="hidden" id="groupPageButtonControl" name="control" value="group" class="buttonInput" />
                            <input type="hidden" id="groupPageButtonAction" name="action" value="group" class="buttonInput" />
                            <input type="submit" id="groupPageButtonSubmit" name="submit" value="group" class="buttonInput button" />
                        </form>
                        <form action="/index.php" id="resultsPageButton" method="POST">
                            <input type="hidden" id="resultsPageButtonControl" name="control" value="results" class="buttonInput" />
                            <input type="hidden" id="resultsPageButtonAction" name="action" value="results" class="buttonInput" />
                            <input type="submit" id="resultsPageButtonSubmit" name="submit" value="results" class="buttonInput button" />
                        </form>
                    </div>

                <?php } ?>
                </div> <!-- end pageRow div-->

                <div class="pageRow">
                    <?= $this->viewHelpers->renderMessages($pageVars); ?>
                </div> <!-- end pageRow div-->

                <div class="pageRow">
                    <?php echo $templateData; ?>
                </div> <!-- end pageRow div-->
            </div>
        </div>
    </body>
</html>