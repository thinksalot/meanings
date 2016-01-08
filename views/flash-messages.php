<?php
    $messages = $this->container->flash->getMessage( '' );
    $typeClassMap = array(
        'error' => 'alert-danger',
        'success' => 'alert-success',
        'info' => 'alert-info'
    );

    if( count( $messages ) ):
        foreach( $messages as $message ):
?>
    <div class="alert <?= isset( $typeClassMap[ $message['type'] ] ) ? $typeClassMap[ $message['type'] ] : $typeClassMap['info'] ?>" role="alert">
        <?= $message['msg'] ?>
    </div>
        <?php endforeach; ?>
    <?php endif; ?>

