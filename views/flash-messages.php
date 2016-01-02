<?php
    $messages = $this->container->flash->getMessage( '' );
    if( count( $messages ) ):
        foreach( $this->container->flash->getMessage( '' ) as $message ):
?>
    <div class="alert <?= $message['type'] == 'error' ? 'alert-danger' : 'alert-success' ?>" role="alert">
        <?= $message['msg'] ?>
    </div>
    <?php endforeach; ?>
<?php endif; ?>

