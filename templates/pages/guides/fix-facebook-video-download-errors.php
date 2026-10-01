<p>Every error <?= e(SITE_NAME) ?> shows maps to a specific, identifiable cause — there's no generic "something went wrong" black box. Here's what each one means and what to do about it.</p>

<h2>"This video is private or requires login"</h2>
<p><strong>What it means:</strong> The video is only visible to specific people — friends of the poster, members of a private group, or people the poster has approved. No downloader tool, including this one, can access private content without your Facebook login, and legitimate tools never ask for it.</p>
<p><strong>Fix:</strong> Ask the person who posted it to change the privacy setting to Public, or to send you the video file directly.</p>

<h2>"That video could not be found"</h2>
<p><strong>What it means:</strong> Either the video was deleted, the post was taken down, or the link itself is malformed (missing characters from an incomplete copy-paste).</p>
<p><strong>Fix:</strong> Re-open the original post and copy the link again from scratch using the Share button, rather than editing or retyping a URL by hand.</p>

<h2>"No downloadable video was found at this link"</h2>
<p><strong>What it means:</strong> The link is a valid Facebook URL, but it points to something that isn't a video — a photo post, a text status, or a profile page.</p>
<p><strong>Fix:</strong> Double check you copied the link from the video itself (using the Share arrow on the video), not from the page or profile it's posted on.</p>

<h2>"Too many requests right now"</h2>
<p><strong>What it means:</strong> Rate limiting kicked in — either ours (to keep the service fair and fast for everyone) or Facebook's own systems responding slowly to a burst of requests.</p>
<p><strong>Fix:</strong> Wait about a minute and try again. This isn't related to your specific video.</p>

<h2>"The request to Facebook timed out"</h2>
<p><strong>What it means:</strong> Facebook's servers didn't respond in time, which usually happens during temporary slowdowns on their end rather than anything wrong with your link.</p>
<p><strong>Fix:</strong> Try again after a minute. If it keeps happening for the same link over a longer period, the video may have been removed since you first saw it.</p>

<h2>The link resolved, but the download itself stalls or fails</h2>
<p><strong>What it means:</strong> The metadata step succeeded, but the network connection dropped mid-download — usually a Wi-Fi/mobile-data switch or a very unstable connection.</p>
<p><strong>Fix:</strong> Try again on a more stable connection. Downloads stream directly rather than resuming partial files, so a retry starts fresh rather than picking up where it left off.</p>

<h2>Still not working?</h2>
<p>Open the video in a private/incognito browser tab first — if you can't view it there without logging in, <?= e(SITE_NAME) ?> can't resolve it either, since both rely on the same public accessibility. If it plays fine privately but still won't resolve, <a href="/#contact">let us know</a> with the link (or a description of the post) and we'll look into it.</p>
