<?php

namespace Cgit;

class RebuildImageMeta
{
    /**
     * Singleton instance.
     *
     * @var null
     */
    private static $instance;

    /**
     * Perform start-up operations.
     *
     * @return void
     */
    private function __construct()
    {
        $this->addToolsMenu();
    }

    /**
     * Return instance.
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Add a menu item to access the plugin menu page.
     *
     * return void
     */
    private function addToolsMenu()
    {
        add_action(
            'admin_menu',
            function () {
                add_management_page(
                    'Rebuild Image Meta',
                    'Rebuild Image Meta',
                    'manage_options',
                    'cgit-wp-rebuild-image-meta',
                    [$this, 'displayPage']
                );
            }
        );
    }

    /**
     * Displays the administration page.
     *
     * @return void
     */
    public function displayPage()
    {
        include 'views/header.php';

        if (isset($_POST['cgit-wp-rebuild-image-meta'])) {
            // Regenerate
            $result = $this->process();

            $successful = count(
                array_filter(
                    array_map(
                        function ($a) {
                            return $a['result'];
                        },
                        $result
                    )
                )
            );

            $failed = count($result) - $successful;

            include 'views/result.php';
        } else {
            // Display regeneration warning
            include 'views/introduction.php';
        }
        include 'views/footer.php';
    }

    /**
     * Rebuild image meta data for every image.
     *
     * @return array
     */
    private function process()
    {
        $results = [];

        $images = $this->getImagePosts();

        foreach ($images as $image) {
            // Get the file name and path relative to the uploads directory

            $file_path = $this->attachmentUriToPath($image->guid);

            // Store the result
            $image_result = [
                'file' => $this->attachmentPath($file_path),
                'path' => $file_path,
            ];

            if (file_exists($file_path)) {
                // Rebuild meta data
                $this->rebuildImageMeta($image->ID, $file_path);
                $image_result['result'] = true;
            } else {
                $image_result['result'] = false;
            }

            $results[] = $image_result;
        }

        return $results;
    }

    /**
     * Rebuilds image meta data for a given post ID and file path.
     *
     * @param int $post_id
     * @param string $file_path
     *
     * @return void
     */
    private function rebuildImageMeta($post_id, $file_path)
    {
        // Generate
        $meta = $this->buildImageMeta($file_path);

        // Update
        update_post_meta($post_id, '_wp_attachment_metadata', $meta);
        update_post_meta($post_id, '_wp_attached_file', $this->attachmentPath($file_path));
    }

    /**
     * Constructs image meta data for a given file.
     *
     * @param string $file_path
     *
     * @return array
     */
    private function buildImageMeta($file_path)
    {
        list($width, $height) = getimagesize($file_path);

        return [
            'width' => $width,
            'height' => $height,
            'file' => $this->attachmentPath($file_path),
            'sizes' => [],
            'image_meta' => [],
        ];
    }

    /**
     * Retrieves all attachment type posts with an image mime type.
     *
     * @return \stdClass
     */
    private function getImagePosts()
    {
        // Fetch all attachment posts with an image mime-type
        global $wpdb;

        $sql = "SELECT ID, guid FROM ".$wpdb->posts." WHERE post_type = 'attachment' AND post_mime_type LIKE 'image/%'";

        return $wpdb->get_results($sql);
    }

    /**
     * Take a WordPress attachment path and return a relative path from the
     * upload directory.
     *
     * @param string $path
     *
     * @return string
     */
    private function attachmentPath($path)
    {
        $upload_dir = wp_upload_dir();

        return trim(str_replace($upload_dir['basedir'], '', $path), '/');
    }

    /**
     * Take a WordPress attachment URI and returns the path.
     *
     * @param string $uri
     *
     * @return string
     */
    private function attachmentUriToPath($uri)
    {
        $upload_dir = wp_upload_dir();

        return str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $uri);
    }
}
