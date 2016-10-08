<?php

if ($pageVars["route"]["action"]=="sign") {
    echo ($pageVars["result"] == true) ? "Success" : "Failure" ;
} ?>