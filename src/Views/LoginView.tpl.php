<h2>Login Page</h2>

<div class="pageRow">
    <form action="/" id="loginForm" method="POST">
        <div class="pageRow">
            <p class="centeredText">Email:</p>
            <?= $this->viewHelpers->renderTextInputField("email", $pageVars); ?>
            <p class="centeredText"><?= $this->viewHelpers->renderFieldErrors("email", $pageVars); ?></p>
        </div> <!-- end div pageRow -->
        <div class="pageRow">
            <p class="centeredText">Password:</p>
            <?= $this->viewHelpers->renderTextInputField("userPass", $pageVars, "password"); ?>
            <p class="centeredText"><?= $this->viewHelpers->renderFieldErrors("userPass", $pageVars); ?></p>
        </div> <!-- end div pageRow -->
        <div class="pageRow">
            <input type="hidden" id="loginPageButtonControl" name="control" value="login" />
            <input type="hidden" id="loginPageButtonAction" name="action" value="login" />
            <input type="hidden" id="formId" name="formId" value="loginForm" />
            <input type="submit" id="submit" name="submit" class="standardInput button" />
        </div> <!-- end div pageRow -->
    </form>
</div> <!-- end div pageRow -->