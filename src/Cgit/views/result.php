<?php $colours = ['#CC3300', '#55AC2B']; ?>

<h3>Finished</h3>

<p>
    <strong>Processed:</strong> <?=count($result)?><br />
    <strong>Succeeded:</strong> <?=$successful?><br />
    <strong>Failed:</strong> <?=$failed?> missing files<br />
</p>

<p>Remember to rebuild all image thumbnails.</p>

<table class="widefat striped">
    <thead>
        <tr>
            <td>Result</td>
            <td>File path</td>
            <td>Full path</td>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($result as $image) : ?>
            <tr>
                <td style="color: <?=$colours[(int)$image['result']]?>"><?=$image['result'] ? 'Success' : 'Failed'?></td>
                <td><?=$image['file']?></td>
                <td><small><?=$image['path']?></small></td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>
