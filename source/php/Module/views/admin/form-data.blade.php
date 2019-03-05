<?php

    foreach ($form_fields as $item) : ?>
        <?php
        if (empty($item['value']) || (isset($data['excludedFront']) && in_array($item['acf_fc_layout'],
                    $data['excludedFront']))) {
            continue;
        }
        ?>

        <p data-type="<?php echo $item['acf_fc_layout']; ?>">
            <strong><?php echo $item['label']; ?></strong><br>
            <?php
            if ($item['acf_fc_layout'] === 'file_upload' && is_array($item['value'])) {
                $i = 0;
                $uploadFolder = wp_upload_dir();
                $uploadFolder = $uploadFolder['baseurl'] . '/modularity-form-builder/';

                foreach ($item['value'] as $file) {
                    $i++;
                    if ($i > 1) {
                        echo '<br>';
                    }

                    //Get path of file
                    $filePath = file_exists($file) ? $uploadFolder . basename($file) : $file;
                    $filePath = $parentClass->getDownloadLink($filePath, $module_id); 

                    //Get filename
                    $fileName = file_exists($file) ? basename($file) : $file;

                    //Print link
                    if($filePath) {
                        echo '<a href="' . $filePath . '" class="link-item link" target="_blank">' . $fileName . '</a>';
                    } elseif($fileName) {
                        echo '<span class="link-item link">' . $fileName . ' ('. $translation['removed_file'].')</span>';
                    } else {
                        echo '<span class="link-item link">'. $translation['unknown_file'].'</span>';
                    }
                }
            } elseif (is_array($item['value'])) {
                foreach ($item['value'] as $value) {
                    if (!empty($value)) {
                        echo nl2br($value) . '<br>';
                    }
                }
            } else {
                echo nl2br($item['value']);
            }

            ?>

        </p>
    <?php endforeach;

?>



