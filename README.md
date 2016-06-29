# Castlegate IT WP Rebuild Image Meta

This plugin examines the database for posts of `attachment` type with an image mime type. It attempts to regenerate all associated meta data for each image found.

The plugin can be used to restore corrupt image meta data, or to completely rebuild image meta data, if for example you've imported the post data but no associated meta data.

All thumbnail information will be lost during the rebuild so thumbnails must be regenerated using a different plugin.

Access the plugin via the `Tools > Regenerate Image Meta` menu.
