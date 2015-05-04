<?php
/*
-------------------------------------
GOOGLE CONTENT DELIVERY NETWORK FILES
-------------------------------------
 *
 * Description:     A PHP Class for loading `JavaScript` and `CSS` documents from the Google CDN
 * Version:			1.0
 * Author:			Maarten Zilverberg (www.mzilverberg.com)
 * Examples:		https://github.com/mzilverberg/GoogleCDNFile
 * More info:       https://developers.google.com/speed/libraries/
 *
 * Licensed under the GPL license:
 * http://www.gnu.org/licenses/gpl.html
 *
 * Last but not least:
 * if you like this script, I would appreciate it if you took the time to share it
*/
class GoogleCDNFile {
    // Overridable variables
    public $library, $version, $extension, $theme;
    // Constant (each file source begins with the same string)
    const SOURCE_PREFIX = "https://ajax.googleapis.com/ajax/libs/";

    /*
    Override variables
    ------------------
    After each change, we need to update the source location
    */
    // Library
    public function setLibrary($library) {
        $this->library = $this->modifyLibrary($library);
        $this->source = $this->setSource();
    }
    // Version
    public function setVersion($version) {
        $this->version = strtolower($version);
        $this->source = $this->setSource();
    }
    // Extension ("js" or "css")
    public function setFileType($extension) {
        $this->extension = strtolower($extension);
        $this->source = $this->setSource();
    }
    // CSS Theme
    public function setTheme($theme) {
        $this->theme = strtolower($theme);
        $this->source = $this->setSource();
    }

    /*
    Modify library name for further internal use
    --------------------------------------------
    @param `$string`   (string):   String from which characters will be removed
    */
    private function modifyLibrary($string) {
        // Make lowercase
        $string = strtolower($string);
        switch($string) {
            // If "Angular" or "Three.js" are requested without the JS in the name, add it
            case "angular":
            case "three":
                $string = $string . "js";
                break;
            // Remove spaces and "Loader" from "Web Font Loader"
            case "web font loader":
                $string = str_replace(array(" ", "loader"), "", $string);
                break;
            // Determine unwanted characters
            // Angular Material and Ext Core are special cases
            case "angular material":
            case "angular-material":
                $unwanted_chars = array(" ", "-");
                $replacement = "_";
                break;
            case "ext core":
            case "ext-core":
                $unwanted_chars = " ";
                $replacement = "-";
                break;
            default:
                // By default, remove dots, dashes and spaces
                $unwanted_chars = array(".", "-", " ");
                $replacement = "";
        }
        // If we need to strip unwanted characters, do so
        if(isset($unwanted_chars)) {
            $string = str_replace($unwanted_chars, $replacement, $string);
        }
        // Return modified string
        return $string;
    }

    /*
    Get filename based on library
    */
    private function setFilenameByLibrary() {
        $file = "";
        switch($this->library) {
            // Angular.js
            // Three.js
            case "angularjs":
            case "threejs":
                // Split library name and "js"
                // Get minified file
                $file = $this->setMinifiedFilename(explode("js", $this->library)[0]);
                break;
            // Angular Material
            case "angular_material":
                // Replace underscore with dash
                $file = $this->setMinifiedFilename(str_replace("_", "-", $this->library));
                break;
            // Dojo
            case "dojo":
                // Add another directory before the filename
                $file = $this->library . "/" . $this->library . "." . $this->extension;
                break;
            // jQuery
            case "jquery":
                // Get minified file
                $file = $this->setMinifiedFilename($this->library);
                break;
            // jQuery Mobile
            case "jquerymobile":
                // Explode filename before getting minified file
                // Add a dot (.) between "jQuery" and "mobile"
                $file = $this->setMinifiedFilename($this->setFilename("mobile", $this->library, ".")/*, $this->extension*/);
                break;
            // jQuery UI
            case "jqueryui":
                // Exploded filename
                $filename = $this->setFilename("ui", $this->library);
                // When loading JavaScript, get minified file
                // When loading CSS, we need to specify extra directories
                $file = $this->extension === "js" ? $this->setMinifiedFilename($filename) : "themes/" . $this->theme . "/" . $filename . "." . $this->extension;
                break;
            // MooTools
            case "mootools":
                // Get minified file with a different suffix
                $file = $this->setMinifiedFilename($this->library/*, $this->extension*/, "-yui-compressed.");
                break;
            // Other libraries
            default:
                // Just add extension to library name
                $file = $this->library . "." . $this->extension;
        }
        return $file;
    }

    /*
    Construct filename
    ------------------
    This function removes a certain string from the library name, and then adds it again with a connector
    ------------------
    @param `$delimiter` (string):   Text that will be removed from a string
    @param `$string`    (string):   String that will be split
    @param `$connector` (string):   String that is added between the two different strings
    */
    private function setFilename($delimiter, $string, $connector = "-") {
        // For example: jquerymobile -> jquery.mobile
        return explode($delimiter, $string)[0] . $connector . $delimiter;
    }

    /*
    Construct minified filename
    ---------------------------
    @param `$suffix`   (string):   String that is added to the file source
    */
    private function setMinifiedFilename($filename, $suffix = ".min.") {
        return $filename . $suffix . $this->extension;
    }

    /*
    Set CDN file source
    -------------------
    Build up the library file source by all given variables
    */
    private function setSource() {
        return self::SOURCE_PREFIX . $this->library . "/" . $this->version . "/" . $this->setFilenameByLibrary();
    }

    /*
    Check if file exists and is readable
    */
    private function fileExists() {
        // Get header response
        $response = get_headers($this->source);
        // Specify acceptable response codes
        $accepted_codes = ["200", "304"];
        $response_code = explode(" ", $response[0])[1];
        // Check if response code is accepted
        return in_array($response_code, $accepted_codes);
    }

    /*
    Load file
    ---------
    @param `$debug`   (boolean):   When set to true, the HTML element is printed as a string instead of added to the DOM
    */
    public function load($debug = false) {
        // Set output characters, use &lt; and &gt; when debugging
        $x = $debug ? ["&lt;", "&gt;"] : ["<", ">"];
        // Check if file exists
        if($this->fileExists()) {
            // Create appropriate tag
            $html = "";
            switch($this->extension) {
                // <link /> for CSS
                case "css":
                    $html = $x[0] . "link href='" . $this->source . "' rel='stylesheet' /" . $x[1];
                    break;
                // <script /> for JS
                default:
                    $html = $x[0] . "script src='" . $this->source . "'" . $x[1] . $x[0] . "/script" . $x[1];
            }
            // Add element to DOM
            echo $html;
        } else {
            // Error if file doesn't exist
            die("The file " . $this->source . " doesn't exist.");
        }
    }

    /*
    Constructor function
    --------------------
    @param `$library`   (string):   Library to load from the Google CDN
    @param `$version`   (string):   Library version
    @param `$extension` (string):   Either "js" or "css", because some libraries (like jQuery UI) rely on CSS as well
    @param `$theme`     (string):   Library CSS theme
    */
    public function __construct($library = "jQuery", $version = "1.11.2", $extension = "js", $theme = "smoothness") {
        // Set variables
        $this->library = $this->modifyLibrary($library);
        $this->version = strtolower($version);
        $this->extension = strtolower($extension);
        $this->theme = strtolower($theme);
        // Set source
        $this->source = $this->setSource();
    }
}
?>
