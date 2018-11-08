<?php
// Simple PHP-based authentication page for nginx RTMP streaming.  The "live" path will require the stream key set here
// or any attempt to publish to this path will be denied.

// Choose a secret stream key for your restreamer here.  The restreamer should use this as the Stream Key in OBS.
$STREAM_KEY = "YOUR_SECRET_STREAM_KEY";

if ($_POST["name"] == $STREAM_KEY) {
    http_response_code(200);
} else {
    http_response_code(403);
}
?>
