<?php foreach ($data as $item) : ?>
    <p data-type="<?php echo $item['type']; ?>">
        <strong><?php echo $item['label']; ?></strong><br>
        <?php
        if ($item['type'] === 'file_upload' && is_array($item['value'])) {
            $i = 0;
            $uploadFolder = wp_upload_dir();
            $uploadFolder = $uploadFolder['baseurl'] . '/modularity-form-builder/';

            foreach ($item['value'] as $file) {
                $i++;
                if ($i > 1) echo '<br>';
                echo 'Öppna fil: <a target="_blank" href="' . $uploadFolder . '/' . basename($file) . '">' . basename($file) . '</a>';
            }
        } else {
            echo nl2br($item['value']);
        }
        ?>
    </p>
<?php endforeach; ?>
