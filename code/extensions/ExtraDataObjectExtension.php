<?php

/*
 * MIT License
 *  
 * Copyright (c) 2016 Hudhaifa Shatnawi
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

/**
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, Dec 22, 2016 - 2:21:14 PM
 */
class ExtraDataObjectExtension
        extends DataExtension {

    private static $db = array(
    );

    /**
     *
     * @param type $haystack The string to search in.
     * @param type $needle If needle is not a string, it is converted to an integer and applied as the ordinal value of a character.
     * @return type True if the string starts with the given string
     */
    public function startsWith($haystack, $needle) {
        // search backwards starting from haystack length characters from the end
        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
    }

    /**
     *
     * @param type $haystack The string to search in.
     * @param type $needle If needle is not a string, it is converted to an integer and applied as the ordinal value of a character.
     * @return type True if the string ends with the given string
     */
    public function endsWith($haystack, $needle) {
        // search forward starting from end minus needle length characters
        return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
    }

    /// Database version
    public function getDBVersion() {
        return DB::get_conn()->getVersion();
    }

    /// Locale
    public function isRTL() {
        return i18n::get_script_direction(i18n::get_locale()) == 'rtl';
    }

    /**
     * Returns current year
     * @return current year
     */
    public function ThisYear() {
        return date('Y');
    }

/// Cookies
    public function getCookie($key, $defaultVal = '') {
        if (!($value = Cookie::get($key))) {
            Cookie::set($key, $defaultVal);
            $value = $defaultVal;
        }

        return $value;
    }

    public function setCookie($key, $value) {
        Cookie::set($key, $value);
    }

    /**
     * Returns a list of all public subsites
     * 
     * @return a list of all public subsites
     */
    public function getSubsites() {
        $subsites = array();
        $list = Subsite::get()->filter('IsPublic', true);
        foreach ($list as $subsite) {
            if ($subsite->ID != $this->SubsiteID) {
                $subsite->URL = 'Hello ' . Director::BaseURL();
                $subsites[] = $subsite;
            }
        }

        $subsites = ArrayList::create($subsites);
        return $subsites;
    }

    /// Member
    public function getLoggedUser() {
        return Member::currentUser();
    }

    public function isLoggedIn() {
        //This should return false but is returning null when user is not logged in
        if (!Member::currentUser()) {
            return false;
        }
        return true;
    }

    /// Utils ///
    function reorderField($fields, $name, $fromTab, $toTab, $disabled = false) {
        $field = $fields->fieldByName($fromTab . '.' . $name);

        if ($field) {
            $fields->removeFieldFromTab($fromTab, $name);
            $fields->addFieldToTab($toTab, $field);

            if ($disabled) {
                $field = $field->performDisabledTransformation();
            }
        }

        return $field;
    }

    function removeField($fields, $name, $fromTab) {
        $field = $fields->fieldByName($fromTab . '.' . $name);

        if ($field) {
            $fields->removeFieldFromTab($fromTab, $name);
        }

        return $field;
    }

    function trim($field) {
        if ($this->$field) {
            $this->$field = trim($this->$field);
        }
    }

    public function toString() {
        return $this->getTitle();
    }

}