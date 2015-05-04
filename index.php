<?php
// Require class
include_once("includes/script/php/GoogleCDNFile.class.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="Maarten Zilverberg" />
    <title>GoogleCDNFile PHP Class</title>
    <!-- Bootstrap core CSS -->
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body>

    <div class="container">

        <div class="row">
            <div class="col-xs-12">
                <h1>GoogleCDNFile</h1>
                <p>A PHP Class for loading <code>JavaScript</code> and <code>CSS</code> documents from the Google Content Delivery Network. See <a href="https://developers.google.com/speed/libraries/" title="Google Developers reference page">Google Hosted Libraries</a>.</p>

                <h2>Why?</h2>
                <p>This class can be useful when requesting multiple libraries from Google's Content Delivery Network. You just need to specify which library (and what version) you want to use.</p>

                <ul>
                    <li>Easy-to-use</li>
                    <li>Supports all Google Hosted Libraries</li>
                    <li>Supports <code>JavaScript</code> as well as <code>CSS</code> (for <code>jQuery UI</code> and <code>jQuery Mobile</code>)</li>
                </ul>

                <h3>All Google Hosted Libraries?</h3>
                <p>Yep, all. That means:
                    <code>AngularJS</code>, <code>Angular Material</code>, <code>Dojo</code>, <code>Ext Core</code>, <code>jQuery</code>, <code>jQuery Mobile</code>, <code>jQuery UI</code>, <code>MooTools</code>, <code>Prototype</code>, <code>script.aculo.us</code>,<code>SPF</code>, <code>SWFObject</code>, <code>three.js</code> and <code>Web Font Loader</code>.</p>

                <h2>How does it work?</h2>
                <p>When a new instance is made, the library name will be changed into the name the class will use under the hood for the rest of the proces. That's because for some libraries, the folder structure is a little different from the name.</p>

                <blockquote>
                    <p>An example:<br /> The folder structure for Script.aculo.us uses the string &quot;scriptaculous&quot; (without dots) and the file &quot;scriptaculous.js&quot; (again, without dots) is loaded</p>
                </blockquote>

                <p>Then, when the <code>load()</code> function is called, the class will check if the requested file exists on Google's CDN. If the file doesn't exist, an error is shown.</p>

                <h2>Basic usage</h2>
                <p>The <code>GoogleCDNFile</code> class accepts 4 parameters:</p>
                <dl>
                    <dt>$library</dt>
                    <dd>
                        <code>string</code> - The name of the library<br />
                        <em>default: "jQuery"</em><br />
                        <small>This parameter takes some aliases into account, such as "Angular" while the function uses "AngularJS" under the hood.</small>
                    </dd>
                    <dt>$version</dt>
                    <dd>
                        <code>string</code> - The version number of the library<br />
                        <em>default: "1.11.2"</em>
                    </dd>
                    <dt>$extension</dt>
                    <dd>
                        <code>string</code> - The type of file that is loaded ("js" or "css")<br />
                        <em>default: "js"</em>
                    </dd>
                    <dt>$theme</dt>
                    <dd>
                        <code>string</code> - The theme name of the library's CSS file (only used for jQuery UI)<br />
                        <em>default: "smoothness"</em>
                    </dd>
                </dl>

                <p>These parameters are stored in <code>$variable-&gt;library</code>* , <code>$variable-&gt;version</code>, <code>$variable-&gt;extension</code> and <code>$variable-&gt;theme</code>, respectively. The full path of a library file is stored in <code>$variable-&gt;source</code>.</p>

                <p><em>* Note that this will return the library name that is used under the hood</em>.</p>

                <h2>Examples</h2>

                <h3>Default usage</h3>
                <p>By default, <code>jQuery</code> version 1.11.2 is loaded, since this is probably the most loaded** script from the CDN.</p>

                <p><em>** Obviously, I didn't base this on facts but on personal experience</em>.</p>

<pre><code class="language-php">&lt;body&gt;
    ...
    &lt;?php
    $jquery = new GoogleCDNFile();
    $jquery-&gt;load();
    ?&gt;
&lt;/body&gt;
</code></pre>

                <h3>jQuery UI or jQuery Mobile</h3>
                <p><code>jQuery UI</code> and <code>jQuery Mobile</code> depend on <code>jQuery</code>, and come with <code>CSS</code> as well. Thus, multiple calls are made. One in the <code>&lt;head&gt;</code>-tag and two before the closing <code>&lt;/body&gt;</code>-tag, like so:</p>

<pre><code class="language-php">&lt;head&gt;
    ...
    &lt;?php
    // Load CSS
    // Note: the last parameter is not used when loading jQuery Mobile
    $jqueryui = new GoogleCDNFile("jQueryUI", "1.11.2", "CSS", "Smoothness");
    $jqueryui-&gt;load();
    ?&gt;
&lt;/head&gt;
&lt;body&gt;
    ...
    &lt;?php
    // jQuery needs to be loaded as well
    $jquery = new GoogleCDNFile();
    $jquery-&gt;load();
    // Load JS by overriding changing the filetype
    $jqueryui-&gt;setFileType("JS");
    $jqueryui-&gt;load();
    ?&gt;
&lt;/body&gt;
</code></pre>

                <h3>Other libraries</h3>
                <p>For all other libraries, you need to specify the name and version number. For example:</p>

<pre><code class="language-php">&lt;body&gt;
    ...
    &lt;?php
    $moo = new GoogleCDNFile("MooTools", "1.5.1");
    $moo-&gt;load();
    ?&gt;
&lt;/body&gt;
</code></pre>

                <h3>&quot;Debugging&quot;</h3>
                <p>If there is a scenario where you would like to check the HTML output that would be added to the DOM, you can! Just pass <code>true</code> as an argument in the <code>load()</code> function:</p>

<pre><code class="language-php">&lt;body&gt;
    ...
    &lt;?php
    $jquery = new GoogleCDNFile();
    $jquery-&gt;load(true); // Outputs $this-&gt;source as a string, not as HTML
    ?&gt;
&lt;/body&gt;
</code></pre>

            </div>
        </div>

    </div><!-- .container -->

</body>
</html>
