<?php
// Simple PHP-based authentication page for nginx RTMP streaming.  The "live" path will require one of the stream keys set here
// or any attempt to publish to this path will be denied.

// Choose several secret stream keys for your restreamer here.  The restreamer should use one of these as the Stream Key in OBS.
$STREAM_KEYS = array(
    "YOUR_SECRET_STREAM_KEY1",
    "YOUR_SECRET_STREAM_KEY2",
    "YOUR_SECRET_STREAM_KEY3"
);

// The stream key comes through as a POST variable called "name" from the on_publish event in nginx.
if (in_array($_POST["name"], $STREAM_KEYS)) {
    http_response_code(200);
} else {
    http_response_code(403);
}
?>
