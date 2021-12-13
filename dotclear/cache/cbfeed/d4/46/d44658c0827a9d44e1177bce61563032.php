O:10:"feedParser":7:{s:9:"feed_type";s:8:"atom 1.0";s:5:"title";s:20:"Dotclear News - News";s:4:"link";s:26:"https://dotclear.org/blog/";s:11:"description";s:25:"Blog management made easy";s:7:"pubdate";s:25:"2021-11-30T12:14:11+01:00";s:9:"generator";s:8:"Dotclear";s:5:"items";a:20:{i:0;O:8:"stdClass":8:{s:4:"link";s:57:"https://dotclear.org/blog/post/2021/11/19/Dotclear-2.20.1";s:5:"title";s:15:"Dotclear 2.20.1";s:7:"creator";s:6:"Franck";s:11:"description";s:0:"";s:7:"content";s:113:"    <p>A small update that fixes three not very serious but potentially annoying bugs in the use of Dotclear.</p>";s:7:"subject";a:1:{i:0;s:4:"News";}s:7:"pubdate";s:25:"2021-11-19T13:47:00+01:00";s:2:"TS";i:1637326020;}i:1;O:8:"stdClass":8:{s:4:"link";s:55:"https://dotclear.org/blog/post/2021/11/13/Dotclear-2.20";s:5:"title";s:13:"Dotclear 2.20";s:7:"creator";s:6:"Franck";s:11:"description";s:0:"";s:7:"content";s:6798:"    <p><img src="https://dotclear.org/public/images/.still-gardening_m.jpg" alt="" /></p>


<p>Still gardening and happy tooyou <a href="http://www.kozlika.org/kozeries/">Kozlika</a>!</p>


<hr />


<p>As <a href="https://dotclear.org/blog/post/2021/08/13/Dotclear-2.19">announced</a> at the time of the 2.19 release, we are publishing new versions more often - or at least trying to.</p>


<p>In this new version 2.20, the highlights are as follows:</p>

<ul>
<li>A new <strong>alternative repository</strong> system has been set up for third-party <strong>plugins</strong> and <strong>themes</strong>, which can be useful if the DotAddict server is running out of steam, as it did recently (thanks to <a href="https://www.noecendrier.fr/" hreflang="fr">Noé</a> for getting it up and running again), or if the author does not wish to deposit his work elsewhere than on his own public repository. We detail the procedure to follow below.</li>
<li>A new I<strong>Pv6</strong>-specific spam filter (which is starting to be deployed quite a bit) is included in parallel with the IPv4-specific filter.</li>
<li>Users can now enter <strong>several additional</strong> email addresses and websites in their profile. Indeed, some themes allow the author of a post to be differentiated from other commenters on the basis of those email and web site addresses, which may change over time. This system therefore makes it possible to indicate new addresses without having to modify the metadata of old comments.</li>
<li>Dotclear's <strong>wiki</strong> syntax has been extended to allow the easy insertion of HTML block <code>details</code>. A vertical bar at the beginning of the line, followed by the text of the summary is necessary to start this block, followed by the free content of the block, followed by a line with a vertical bar as the first character only ending the whole, i.e.&nbsp;:</li>
</ul>
<pre>
|summary of the detail block (hidden by default)
    …
content of my block
    …
|
</pre>


<p>Please note: this version is the <strong>last</strong> to support <strong>PHP 7.3</strong>; the next <strong>2.21</strong> will require at least PHP <strong>7.4</strong> (or PHP 8). A message will be displayed on your dashboard if your PHP version is affected.</p>


<hr />


<h3>Alternative repositories:</h3>


<p>To implement an alternative repository for a module, plugin or theme, you need two things:</p>

<ol>
<li>A <strong>repository</strong> entry in the properties provided in the module's <code>_define.php</code> file, such as: <code>'repository' =&gt; 'https://raw.githubusercontent.com/franck-paul/sysInfo/main/dcstore.xml'</code></li>
<li>A <code>dcstore.xml</code> file structured as follows, and stored in accordance with the URL provided above:</li>
</ol>
<pre>
&lt;modules xmlns:da=&quot;http://dotaddict.org/da/&quot;&gt;
  &lt;module id=&quot;[MODULE_ID]&quot;&gt;
    &lt;name&gt;[MODULE NAME]&lt;/name&gt;
    &lt;version&gt;[MODULE.VERSION]&lt;/version&gt;
    &lt;author&gt;[MODULE AUTHOR]&lt;/author&gt;
    &lt;desc&gt;[MODULE DESCRIPTION]&lt;/desc&gt;
    &lt;file&gt;[MODULE_ARCHIVE.ZIP]&lt;/file&gt;
    &lt;da:dcmin&gt;[MODULE_DOTCLEAR_VERSION_MIN]&lt;/da:dcmin&gt;
    &lt;da:details&gt;[MODULE_DETAIL_URL]&lt;/da:details&gt;
    &lt;da:support&gt;[MODULE_SUPPORT_URL]&lt;/da:support&gt;
  &lt;/module&gt;
&lt;/modules&gt;
</pre>


<p>Example for the <a href="https://plugins.dotaddict.org/dc2/details/sysInfo">sysInfo</a> plugin:</p>

<pre>
&lt;modules xmlns:da=&quot;http://dotaddict.org/da/&quot;&gt;
  &lt;module id=&quot;sysInfo&quot;&gt;
    &lt;name&gt;System Information&lt;/name&gt;
    &lt;version&gt;1.16.3&lt;/version&gt;
    &lt;author&gt;System Information&lt;/author&gt;
    &lt;desc&gt;System Information&lt;/desc&gt;
    &lt;file&gt;https://github.com/franck-paul/sysInfo/releases/download/1.16.3/plugin-sysInfo-1.16.3.zip&lt;/file&gt;
    &lt;da:dcmin&gt;2.19&lt;/da:dcmin&gt;
    &lt;da:details&gt;https://open-time.net/docs/plugins/sysInfo&lt;/da:details&gt;
    &lt;da:support&gt;https://github.com/franck-paul/sysInfo&lt;/da:support&gt;
  &lt;/module&gt;
&lt;/modules&gt;
</pre>


<p>Note that the <code>dcstore.xml</code> file does not need to be included in the module installation archive.</p>


<p>As soon as a module, indicating in its <code>_define.php</code> file an alternative repository, will be installed with Dotclear version 2.20, then the latter will also consult this repository to check for the presence of a new version.</p>


<hr />


<h3>One more thing!</h3>


<p>It is possible to save the <strong>default settings</strong> for inserting a media file (image, sound, ...) which is then used when editing posts and pages. See Blog settings, section "Media and images". It is also possible to save the current insertion parameters when inserting media into a post.</p>


<p>This is convenient but can be counterproductive in some cases.</p>


<p>Dotclear version 2.20 now takes into account the presence of a <code>.mediadef</code> file (or <code>.mediadef.json</code>) structured as follows, so that the settings specified in it become automatically pre-selected instead of those saved by default for the blog:</p>

<pre>
{
&quot;size&quot;: &quot;o&quot;,
&quot;legend&quot;: &quot;none&quot;,
&quot;alignment&quot;: &quot;center&quot;,
&quot;link&quot;: false
}
</pre>


<p>Voilà les valeurs possibles pour les différents réglages&nbsp;:</p>

<ul>
<li><code>size</code>&nbsp;: <samp>"sq"</samp> for <strong>thumbnail</strong>, <samp>"s"</samp> for <strong>small</strong>, <samp>"m"</samp> for <strong>medium</strong>, <samp>"o"</samp> for <strong>original</strong></li>
<li><code>legend</code>&nbsp;: <samp>"none"</samp> for <strong>none</strong>, <samp>"title"</samp> for <strong>title</strong> only, <samp>"legend"</samp> for <strong>title and legend</strong></li>
<li><code>alignment</code>&nbsp;: <samp>"none"</samp> for <strong>none</strong>, <samp>"left"</samp> to <strong>left</strong> align, <samp>"right"</samp> to <strong>right</strong> align, <samp>"center"</samp> to <strong>center</strong></li>
<li><code>link</code>&nbsp;: <samp>true</samp> <strong>with</strong> the link, <samp>false</samp> <strong>without</strong> the original image link</li>
</ul>

<p>You are not obliged to specify all the settings and if one or more of them are missing, the one or more saved for the blog will be used.</p>


<p>Moreover, this preset file is <strong>only</strong> valid for the folder in which it is saved and therefore only for the media it contains.</p>


<hr />


<h3>Conclusion</h3>


<p>For the rest, the curious can consult the details of the modifications in the <a href="https://git.dotclear.org/dev/dotclear/src/branch/2.20/CHANGELOG">CHANGELOG</a> file of this version.</p>


<p><em>Et voilà !</em></p>";s:7:"subject";a:1:{i:0;s:4:"News";}s:7:"pubdate";s:25:"2021-11-13T06:35:00+01:00";s:2:"TS";i:1636781700;}i:2;O:8:"stdClass":8:{s:4:"link";s:55:"https://dotclear.org/blog/post/2021/08/13/Dotclear-2.19";s:5:"title";s:13:"Dotclear 2.19";s:7:"creator";s:6:"Franck";s:11:"description";s:0:"";s:7:"content";s:1289:"    <p>A new version to celebrate the 18 years of Dotclear.</p>


<p>On the program, a more robust code (PHP and Javascript), some improvements for themes developers, a minimal version of <strong>PHP 7.3</strong> required, the compatibility with PHP 8 being ensured, the few used libraries have been updated (jQuery, CKEditor, codemirror, ...).</p>


<p>Note that the <strong>MySQL</strong> driver support has been removed and is now replaced by the <strong>MySQLi</strong> driver. You don't have to change anything if you were using the old one, the replacement is automatic.</p>


<p>Furthermore, the "remember me" function present on the blog comment forms, previously managed via the creation of a cookie, is now replaced by a local storage in the browser via the <strong>localStorage</strong> API.</p>


<p>Note also that <strong>Google's FLoC</strong> tracking system is automatically disabled (which can be overridden via the blog settings).</p>


<p>The curious can study the <code><a href="https://git.dotclear.org/dev/dotclear/commit/df16306eb1ff386012f1bdc69d2ae933fe354613">CHANGELOG</a></code> file for details.</p>


<p>We will also try to publish new versions more often with probably less stuff each time as the application has already reached its maturity/majority :-)</p>";s:7:"subject";a:1:{i:0;s:4:"News";}s:7:"pubdate";s:25:"2021-08-13T16:36:00+02:00";s:2:"TS";i:1628865360;}i:3;O:8:"stdClass":8:{s:4:"link";s:57:"https://dotclear.org/blog/post/2021/02/13/Dotclear-2.18.1";s:5:"title";s:15:"Dotclear 2.18.1";s:7:"creator";s:6:"Franck";s:11:"description";s:0:"";s:7:"content";s:109:"    <p>A maintenance version that corrects a few bugs, especially when putting programmed entries online.</p>";s:7:"subject";a:1:{i:0;s:4:"News";}s:7:"pubdate";s:25:"2021-02-13T11:28:00+01:00";s:2:"TS";i:1613212080;}i:4;O:8:"stdClass":8:{s:4:"link";s:55:"https://dotclear.org/blog/post/2020/11/13/Dotclear-2.18";s:5:"title";s:13:"Dotclear 2.18";s:7:"creator";s:6:"Franck";s:11:"description";s:0:"";s:7:"content";s:513:"    <p>A new version that brings some changes and updates.</p>


<p>The most notable are&nbsp;:</p>

<ol>
<li>The IP addresses - especially from comments - are now displayed in the administration only if you are administrator or super-administrator.</li>
<li>The HTML syntax and the CKEditor editor are now proposed by default for new users and new blogs.</li>
<li>The CKEditor editor now integrates footnotes management.</li>
</ol>

<p>Note that the next major release, 2.19, will require PHP 7.0 or greater!</p>";s:7:"subject";a:1:{i:0;s:4:"News";}s:7:"pubdate";s:25:"2020-11-13T13:04:00+01:00";s:2:"TS";i:1605269040;}i:5;O:8:"stdClass":8:{s:4:"link";s:57:"https://dotclear.org/blog/post/2020/08/17/Dotclear-2.17.2";s:5:"title";s:15:"Dotclear 2.17.2";s:7:"creator";s:6:"Franck";s:11:"description";s:0:"";s:7:"content";s:75:"    <p>A maintenance version that fixes two minor problems with Safari.</p>";s:7:"subject";a:1:{i:0;s:4:"News";}s:7:"pubdate";s:25:"2020-08-17T10:21:00+02:00";s:2:"TS";i:1597652460;}i:6;O:8:"stdClass":8:{s:4:"link";s:57:"https://dotclear.org/blog/post/2020/08/15/Dotclear-2.17.1";s:5:"title";s:15:"Dotclear 2.17.1";s:7:"creator";s:6:"Franck";s:11:"description";s:0:"";s:7:"content";s:120:"    <p>A maintenance version to fix a problem caused by Chrome with the optional password fields of posts and pages.</p>";s:7:"subject";a:1:{i:0;s:4:"News";}s:7:"pubdate";s:25:"2020-08-15T09:53:00+02:00";s:2:"TS";i:1597477980;}i:7;O:8:"stdClass":8:{s:4:"link";s:55:"https://dotclear.org/blog/post/2020/08/13/Dotclear-2.17";s:5:"title";s:13:"Dotclear 2.17";s:7:"creator";s:6:"Franck";s:11:"description";s:0:"";s:7:"content";s:2081:"    <p>Here's the “Jurassic blog edition”, aka Dotclear 2.17 to celebrate 17 years of Dotclear today \o/</p>


<hr />


<p>The CHANGELOG:</p>

<pre>
* 🐘 PHP 5.6+ is required, PHP 7.4 compliance
* 🛡 Security: Password is now needed to export blog settings and contents (full/simple)
* Themes can now be cloned
* New helper button (show/hide) for password fields
* Enhancement of filter/sort usage for lists (posts, comments, …)
* 3rd automatic theme for backend theme (which follow OS setting)
* Authentication (backend) and password form (public for password protected entry) have been redesigned
* Add a Cancel button wherever relevant in backend
* PHP files can now be edited in Theme editor
* Plugins may now use SVG icon rather than JPG/PNG
* Black/White list names become Block/Allow list (antispam)
* Wiki: subscript syntax changed from _subscript_ to ,,subscript,,
* Wiki: add ;;span-content;; syntax
* Wiki: add §§attributes[|list attributes]§§ for blocks (at end of the 1st line of block)
* Wiki: add §attributes§ for inline elements (just before closing marker, warning: cannot be nested)
* Tpl: Add {{tpl:BlogNbEntriesFirstPage}} and {{tpl:BlogNbEntriesPerPage}}
* Tpl: Add optional even attribute to &lt;tpl:EntryIfOdd&gt;, &lt;tpl:CommentIfOdd&gt; and &lt;tpl:PingIfOdd&gt;
* Tpl: Add author=&quot;…&quot; as attribute of &lt;tpl:EntryIf&gt;
* Sys: Add several behaviors, coreBeforeImageMetaCreate, themeBeforeClone and themeAfterClone
* a11y: Reduce motion if required in provided themes and backend
* Lib: Update jQuery to 3.5.1 (backend and public)
* Lib: Update Codemirror to 5.55.0
* Lib: CKEditor new color palette (configurable)
* Fix: Notification system refactored (now based on db rather than PHP Session)
* Fix: Missing confirmation before closing modified forms / unecessary confirmation asked before closing not modified forms
* i18n: Switch from Transifex to Crowdin for localisation purpose (https://dotclear.crowdin.com/)
* 🐛 → Various bugs, a11y concerns and typos fixed
* 🌼 → Some locales and cosmetic adjustments
</pre>";s:7:"subject";a:1:{i:0;s:4:"News";}s:7:"pubdate";s:25:"2020-08-13T10:18:00+02:00";s:2:"TS";i:1597306680;}i:8;O:8:"stdClass":8:{s:4:"link";s:57:"https://dotclear.org/blog/post/2020/06/02/Dotclear-2.16.9";s:5:"title";s:15:"Dotclear 2.16.9";s:7:"creator";s:6:"Franck";s:11:"description";s:0:"";s:7:"content";s:59:"    <p>A new little version that fixes some minor bugs.</p>";s:7:"subject";a:1:{i:0;s:4:"News";}s:7:"pubdate";s:25:"2020-06-02T16:04:00+02:00";s:2:"TS";i:1591106640;}i:9;O:8:"stdClass":8:{s:4:"link";s:57:"https://dotclear.org/blog/post/2020/05/27/Dotclear-2.16.8";s:5:"title";s:15:"Dotclear 2.16.8";s:7:"creator";s:6:"Franck";s:11:"description";s:0:"";s:7:"content";s:102:"    <p>This version fixes the use of the Clearbricks library, not updated in the previous version.</p>";s:7:"subject";a:1:{i:0;s:4:"News";}s:7:"pubdate";s:25:"2020-05-27T15:37:00+02:00";s:2:"TS";i:1590586620;}i:10;O:8:"stdClass":8:{s:4:"link";s:57:"https://dotclear.org/blog/post/2020/05/27/Dotclear-2.16.7";s:5:"title";s:15:"Dotclear 2.16.7";s:7:"creator";s:6:"Franck";s:11:"description";s:0:"";s:7:"content";s:103:"    <p>As the previous one, a new little version that fixes some minor but sometimes annoying bugs.</p>";s:7:"subject";a:1:{i:0;s:4:"News";}s:7:"pubdate";s:25:"2020-05-27T10:25:00+02:00";s:2:"TS";i:1590567900;}i:11;O:8:"stdClass":8:{s:4:"link";s:57:"https://dotclear.org/blog/post/2020/05/22/Dotclear-2.16.6";s:5:"title";s:15:"Dotclear 2.16.6";s:7:"creator";s:6:"Franck";s:11:"description";s:0:"";s:7:"content";s:75:"    <p>A new version that fixes some minor but sometimes annoying bugs.</p>";s:7:"subject";a:1:{i:0;s:4:"News";}s:7:"pubdate";s:25:"2020-05-22T13:23:00+02:00";s:2:"TS";i:1590146580;}i:12;O:8:"stdClass":8:{s:4:"link";s:57:"https://dotclear.org/blog/post/2020/05/14/Dotclear-2.16.5";s:5:"title";s:15:"Dotclear 2.16.5";s:7:"creator";s:6:"Franck";s:11:"description";s:0:"";s:7:"content";s:75:"    <p>A new version that fixes some minor but sometimes annoying bugs.</p>";s:7:"subject";a:1:{i:0;s:4:"News";}s:7:"pubdate";s:25:"2020-05-14T11:37:00+02:00";s:2:"TS";i:1589449020;}i:13;O:8:"stdClass":8:{s:4:"link";s:57:"https://dotclear.org/blog/post/2020/04/24/Dotclear-2.16.4";s:5:"title";s:15:"Dotclear 2.16.4";s:7:"creator";s:6:"Franck";s:11:"description";s:0:"";s:7:"content";s:100:"    <p>A new version which integrates the correction of a bug forgotten in the previous version.</p>";s:7:"subject";a:1:{i:0;s:4:"News";}s:7:"pubdate";s:25:"2020-04-24T12:32:00+02:00";s:2:"TS";i:1587724320;}i:14;O:8:"stdClass":8:{s:4:"link";s:57:"https://dotclear.org/blog/post/2020/04/21/Dotclear-2.16.3";s:5:"title";s:15:"Dotclear 2.16.3";s:7:"creator";s:6:"Franck";s:11:"description";s:0:"";s:7:"content";s:52:"    <p>A new version that fixes two minors bugs.</p>";s:7:"subject";a:1:{i:0;s:4:"News";}s:7:"pubdate";s:25:"2020-04-21T10:59:00+02:00";s:2:"TS";i:1587459540;}i:15;O:8:"stdClass":8:{s:4:"link";s:57:"https://dotclear.org/blog/post/2020/04/17/Dotclear-2.16.2";s:5:"title";s:15:"Dotclear 2.16.2";s:7:"creator";s:6:"Franck";s:11:"description";s:0:"";s:7:"content";s:121:"    <p>A new minor version that fixes the lack of warning when content has been modified and not saved with CKEditor.</p>";s:7:"subject";a:1:{i:0;s:4:"News";}s:7:"pubdate";s:25:"2020-04-17T13:27:00+02:00";s:2:"TS";i:1587122820;}i:16;O:8:"stdClass":8:{s:4:"link";s:57:"https://dotclear.org/blog/post/2020/04/15/Dotclear-2.16.1";s:5:"title";s:15:"Dotclear 2.16.1";s:7:"creator";s:6:"Franck";s:11:"description";s:0:"";s:7:"content";s:104:"    <p>A few small bugs not serious but annoying on a daily basis have been fixed with this version.</p>";s:7:"subject";a:1:{i:0;s:4:"News";}s:7:"pubdate";s:25:"2020-04-15T09:36:00+02:00";s:2:"TS";i:1586936160;}i:17;O:8:"stdClass":8:{s:4:"link";s:55:"https://dotclear.org/blog/post/2020/03/13/Dotclear-2.16";s:5:"title";s:13:"Dotclear 2.16";s:7:"creator";s:6:"Franck";s:11:"description";s:0:"";s:7:"content";s:841:"    <p>Let have some fun by publishing a very new version of Dotclear this friday the 13th \o/</p>


<p>The menu:</p>

<ul>
<li>🐘 PHP 5.6+ is required, PHP 7.4 compliance</li>
<li>🛡 Security: all requests from/to Dotclear and DotAddict servers use now HTTPS</li>
<li>jQuery upgraded to 3.4.1, older version will be removed, jQuery not anymore requested for "Remember me" feature</li>
<li>New "static" mode for home page. In this mode the list of last posts is available with the following URL: https://example.com/index.php?posts</li>
<li>Media description may now be updated</li>
<li>Add &lt;i [lang="…"]&gt;…&lt;/i&gt; support to Dotclear wiki, syntax: ££text[|lang]££ (ex: ££français|fr££)</li>
</ul>

<p>And also some visual or not visual bugs have been fixed, the support of MySQL 8+…</p>


<p>Let's play&nbsp;!</p>";s:7:"subject";a:1:{i:0;s:4:"News";}s:7:"pubdate";s:25:"2020-03-13T10:03:00+01:00";s:2:"TS";i:1584090180;}i:18;O:8:"stdClass":8:{s:4:"link";s:57:"https://dotclear.org/blog/post/2019/11/28/Dotclear-2.15.3";s:5:"title";s:15:"Dotclear 2.15.3";s:7:"creator";s:6:"Franck";s:11:"description";s:0:"";s:7:"content";s:268:"    <p>Une new minor release which fixes some issues:</p>

<ul>
<li>Avoid weird side-effect of JS minifier</li>
<li>Insertion of default type media (non image/audio/video) in XHTML entries</li>
<li>Cope with old themes for 'remember me' string defined in JS</li>
</ul>";s:7:"subject";a:1:{i:0;s:4:"News";}s:7:"pubdate";s:25:"2019-11-28T10:18:00+01:00";s:2:"TS";i:1574932680;}i:19;O:8:"stdClass":8:{s:4:"link";s:57:"https://dotclear.org/blog/post/2019/10/01/Dotclear-2.15.2";s:5:"title";s:15:"Dotclear 2.15.2";s:7:"creator";s:6:"Franck";s:11:"description";s:0:"";s:7:"content";s:223:"    <p>A new release which fixes:</p>

<ul>
<li>saving of files in theme editor when using syntaxic coloration;</li>
<li>video insertion with the two editors</li>
<li>badge position for dashboard modules counters</li>
</ul>";s:7:"subject";a:1:{i:0;s:4:"News";}s:7:"pubdate";s:25:"2019-10-01T10:23:00+02:00";s:2:"TS";i:1569918180;}}}