# Remove-Internal-Links
Ultra-lightweight and secure plugin to automatically delete ALL internal links from WordPress posts or pages. It quickly gets the job done by using PHP only - with 1 click of a button.

This plugin is ideal for people who want to start over their interlinking process (after messing up).

You can skip removing your cloaked affiliate links.

On my Cloudways Linode (1 core/1GB RAM) 171 posts are done within 7 seconds on the Fast speed setting.

The installed plugin is found under Settings / Remove Links.

<h2>Plugin Usage</h2>
        <ol>
            <li>Select Posts or Pages to remove internal links from.</li>
            <li>Choose the <b>Processing Speed</b>: "Fast" for high-performance servers, "Average" for most, or "Slow" for shared hosting.</li>
            <li>Enter the prefix used for affiliate links that should be ignored during link removal (e.g., /go/, /recommend/).</li>
			<li>Click the "Remove Links" button to start link removal.</li>
            <li>It could finish quickly or slowly, depending on how many posts/pages, chosen speed and hosting platform. You will be notified.</li>
        </ol>
        <p><strong>Note:</strong> Some plugins like <b>Rankmath SEO</b> are known to drastically slow down the process. Disable them temporarily.</p>

== Frequently Asked Questions ==

= What means the processing speed in ms? =

This is the delay in miliseconds before the next post/page is processed.

= Removing links is slow =

Disable the plugin(s) known to process posts after update, e.g., Rankmath.

== Changelog ==

= 1.0 =
* Initial release
