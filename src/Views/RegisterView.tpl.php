<h2>Register Page</h2>

<div class="pageRow">
    <form action="/index.php" id="registrationForm" method="POST">
        <div class="pageRow">
            <p class="centeredText">Username:</p>
            <?= $this->viewHelpers->renderTextInputField("userName", $pageVars); ?>
            <p class="centeredText"><?= $this->viewHelpers->renderFieldErrors("userName", $pageVars); ?></p>
        </div> <!-- end div pageRow -->
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
            <p class="centeredText">Repeat Password:</p>
            <?= $this->viewHelpers->renderTextInputField("userPass2", $pageVars, "password"); ?>
            <p class="centeredText"><?= $this->viewHelpers->renderFieldErrors("userPass2", $pageVars); ?></p>
        </div> <!-- end div pageRow -->
        <div class="pageRow">
            <input type="hidden" id="registerPageButtonControl" name="control" value="register" />
            <input type="hidden" id="registerPageButtonAction" name="action" value="register" />
            <input type="hidden" id="formId" name="formId" value="registrationForm" />
            <input type="submit" id="submit" name="submit" class="standardInput button" />
        </div> <!-- end div pageRow -->
    </form>
</div> <!-- end div pageRow -->