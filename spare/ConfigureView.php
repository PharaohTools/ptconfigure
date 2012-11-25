        <div>
         <h1><?php $this->configs->give("conf_page_title") ?></h1>
        <form action="IndexController.php" method="POST">
        <?php
         if (isset($pageVars["uservar"]) && is_array($pageVars["uservar"]) && count($pageVars["uservar"])>0 ) {
        ?>
            <div>
            <?php
            foreach ($pageVars["uservar"] as $uservar) {
                if ($uservar["type"]=="text") {
                    ?>
                    <div>
                      <div><p> <?= $uservar["title"] ?></p></div>
                      <div><input class="configTextField" type="text" name="conf_'.$uservar["idString"].'" id="conf_'.$uservar["idString"].'"
                    if ($uservar["curValue"]!="novalue") {
                         value="'.$uservar["curValue"].'"
                    }
                      /></div>
                     <div><p class="leftAlignText">'.$uservar["description"].'</p></div>
                    </div>
                }
             <?php
            }
            ?>
             </div>
                <?php
        }
        ?>
        
            <div>
                <input type="submit" name="submit" class="gcbutton" value="Submit" />
                <input type="hidden" name="run" id="run" value="1" />
                <input type="hidden" name="control" id="control" value="configure" />
                <input type="hidden" name="action" id="action" value="configure" />
            </div>
       </form>
         </div>