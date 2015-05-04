# GoogleCDNFile

A PHP Class for loading `JavaScript` and `CSS` documents from the Google Content Delivery Network. See [Google Hosted Libraries](https://developers.google.com/speed/libraries/ "Google Developers reference page").

## Why?

This class is useful when requesting multiple libraries from Google's Content Delivery Network. You just need to specify which library (and what version) you want to use.

- Easy-to-use
- Supports all Google Hosted Libraries
- Supports `JavaScript` as well as `CSS` (for `jQuery UI` and `jQuery Mobile`)

### All Google Hosted Libraries?

Yep, all. That means:
`AngularJS`, `Angular Material`, `Dojo`, `Ext Core`, `jQuery`, `jQuery Mobile`, `jQuery UI`, `MooTools`, `Prototype`, `script.aculo.us`,`SPF`, `SWFObject`, `three.js` and `Web Font Loader`.

## How does it work?

When a new instance is made, the library name will be changed into the name the class will use under the hood for the rest of the proces. That's because for some libraries, the folder structure is a little different from the name.

> An example:<br /> The folder structure for Script.aculo.us uses the string "scriptaculous" (without dots) and the file "scriptaculous.js" (again, without dots) is loaded

Then, when the `load()` function is called, the class will check if the requested file exists on Google's CDN. If the file doesn't exist, an error is shown.

## Basic usage

The `GoogleCDNFile` class accepts 4 parameters:

<dl>
    <dt>$library</dt>
    <dd>
        `string` - The name of the library<br />
        _default: "jQuery"_<br />
        <small>This parameter takes some aliases into account, such as "Angular" while the function uses "AngularJS" under the hood.</small>
    </dd>
    <dt>$version</dt>
    <dd>
        `string` - The version number of the library<br />
        _default: "1.11.2"_
    </dd>
    <dt>$extension</dt>
    <dd>
        `string` - The type of file that is loaded ("js" or "css")<br />
        _default: "js"_
    </dd>
    <dt>$theme</dt>
    <dd>
        `string` - The theme name of the library's CSS file (only used for jQuery UI)<br />
        _default: "smoothness"_
    </dd>
</dl>

These parameters are stored in `$variable->library`* , `$variable->version`, `$variable->extension` and `$variable->theme`, respectively.
The full path of a library file is stored in `$variable->source`.

_* Note that this will return the library name that is used under the hood_.

## Examples

### Default usage

By default, `jQuery` version 1.11.2 is loaded, since this is probably the most loaded** script from the CDN.

_** Obviously, I didn't base this on facts but on personal experience_.

```php
<body>
    ...
    <?php
    $jquery = new GoogleCDNFile();
    $jquery->load();
    ?>
</body>
```

### jQuery UI or jQuery Mobile

`jQuery UI` and `jQuery Mobile` depend on `jQuery`, and come with `CSS` as well. Thus, multiple calls are made. One in the `<head>`-tag and two before the closing `</body>`-tag, like so:

```php
<head>
    ...
    <?php
    // Load CSS
    // Note: the last parameter is not used when loading jQuery Mobile
    $jqueryui = new GoogleCDNFile("jQueryUI", "1.11.2", "CSS", "Smoothness");
    $jqueryui->load();
    ?>
</head>
<body>
    ...
    <?php
    // jQuery needs to be loaded as well
    $jquery = new GoogleCDNFile();
    $jquery->load();
    // Load JS by overriding changing the filetype
    $jqueryui->setFileType("JS");
    $jqueryui->load();
    ?>
</body>
```

### Other libraries

For all other libraries, you need to specify the name and version number. For example:

```php
<body>
    ...
    <?php
    $moo = new GoogleCDNFile("MooTools", "1.5.1");
    $moo->load();
    ?>
</body>
```

### "Debugging"

If there is a scenario where you would like to check the HTML output that would be added to the DOM, you can! Just pass `true` as an argument in the `load()` function:

```php
<body>
    ...
    <?php
    $jquery = new GoogleCDNFile();
    $jquery->load(true); // Outputs $this->source as a string, not as HTML
    ?>
</body>
```
