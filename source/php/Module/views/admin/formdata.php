<?php foreach ($data['form_fields'] as $item) : ?>
    <p data-type="<?php echo $item['acf_fc_layout']; ?>">
        <strong><?php echo $item['label']; ?></strong><br>
        <?php
        if ($item['acf_fc_layout'] === 'file_upload' && is_array($item['value'])) {
            $i = 0;
            $uploadFolder = wp_upload_dir();
            $uploadFolder = $uploadFolder['baseurl'] . '/modularity-form-builder/';

            foreach ($item['value'] as $file) {
                $i++;
                if ($i > 1) echo '<br>';
                $filePath = file_exists($file) ? $uploadFolder . basename($file) : $file;
                $fileName = file_exists($file) ? basename($file) : $file;
                echo 'Öppna fil: <a target="_blank" href="' . $filePath . '">' . $fileName . '</a>';
            }
        } elseif (is_array($item['value'])) {
            foreach ($item['value'] as $value) {
                if (! empty($value)) echo nl2br($value) . '<br>';
            }
        } else {
            echo nl2br($item['value']);
        }
        ?>
    </p>
<?php endforeach; ?>
