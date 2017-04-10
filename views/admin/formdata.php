<?php foreach ($data as $item) : ?>
    <p data-type="<?php echo $item['type']; ?>">
        <strong><?php echo $item['label']; ?></strong><br>
        <?php echo nl2br($item['value']); ?>
    </p>
<?php endforeach; ?>
