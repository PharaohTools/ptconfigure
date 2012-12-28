<?php

Namespace Core ;

class ViewHelpers {

    public function renderTextInputField($field, $pageVars, $type="text") {
        $htmlVar = '<input type="';
        $type = ($type != "text") ? $type : "text" ;
        $htmlVar .= $type.'" id="'.$field.'" name="'.$field.'"';
        if (isset($pageVars["formRequest"][$field])) { $htmlVar .= ' value="'.$pageVars["formRequest"][$field].'"'; }
        $htmlVar .= 'class="standardInput" />';
        return $htmlVar;}

    public function renderFieldErrors($field, $pageVars) {
        $htmlVar = '';
        if (isset($pageVars["formResult"])) {
            foreach ($pageVars["formResult"]["errors"] as $error) {
                if ( $error["field"] == $field ) { $htmlVar .= $error["messages"]; } } }
        return $htmlVar; }

    public function renderMessages($pageVars) {
        $htmlVar = '';
        if (isset($pageVars["messages"])) {
            foreach ($pageVars["messages"] as $message ) {
                $htmlVar .= '<p>'.$message.'</p>'; } }
        return $htmlVar; }

}