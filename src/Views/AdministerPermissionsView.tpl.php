<div class="pageRow">
    <h2>Administer Permissions</h2>
</div>

<div class="pageRow">
    <form action="/index.php" id="administerPermissionsForm" method="POST">

        <div class="dataGrid">
        <?php

        if ($pageVars["userOrGroup"] == "group") {
            foreach ($pageVars["groupData"] as $groupData) {
                ?>
                <div class="groupName"><?= $groupData["name"] ?></div>
                <div class="hash"><?= $groupData["hash"] ?></div>
                <?php
            }
        } else {
            ?>
            <div class="gridRecordDetailsTitleRow">
                <div class="gridRecordDetailsField">
                    <div class="gridRecordDetailsInnerField">
                        Username
                    </div>
                    <div class="gridRecordDetailsInnerField">
                        Hash
                    </div>
                </div>

                <div class="gridRecordOptionsField">
                    <?php
                    foreach ($pageVars["roles"] as $role) {
                        ?>

                        <div class="gridRecordDetailsInnerField">
                            <div class="role">
                                <?= $role["name"] ?>
                            </div>
                        </div>

                        <?php } ?>
                </div>

            </div>

            <?php
            foreach ($pageVars["userData"] as $userData) {
            ?>

            <div class="gridRecordDetailsRow">
                <div class="gridRecordDetailsField">
                    <div class="gridRecordDetailsInnerField">
                        <div class="userName"><?= $userData["userName"] ?></div>
                    </div>
                    <div class="gridRecordDetailsInnerField">
                        <div class="hash"><?= $userData["hash"] ?></div>
                    </div>
                </div>

                <div class="gridRecordOptionsField">
                    <?php
                    foreach ($pageVars["roles"] as $role) {
                        ?>

                        <div class="gridRecordDetailsInnerField">
                            <div class="role">
                                <input type="checkbox" id="role_<?= $role["id"] ?>" />
                            </div>
                        </div>

                        <?php } ?>
                </div>

            </div>

            <?php
            }
        }
        ?>
        </div>

        <div class="pageRow">
            <input type="hidden" id="administerPermissionsButtonControl" name="control" value="administerPermissions" />
            <input type="hidden" id="administerPermissionsButtonAction" name="action" value="save" />
            <input type="hidden" id="formId" name="formId" value="administerPermissionsForm" />
            <input type="submit" id="submit" name="submit" value="Change Permissions" class="standardInput button" />
        </div> <!-- end div pageRow -->
    </form>
</div> <!-- end div pageRow -->