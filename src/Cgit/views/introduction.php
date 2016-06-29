<p>
    This plugin will rebuild all meta data for images in your media library. Doing so will delete thumbnail
    information. You will need to regenerate all thumbnails on completion.
</p>

<h3>Do you wish to proceed</h3>

<form method="post" action="<?=admin_url('tools.php?page=cgit-wp-rebuild-image-meta')?>">
    <input type="submit" name="cgit-wp-rebuild-image-meta" class="button" value="Regenerate" />
</form>
