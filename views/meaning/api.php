<div class="panel panel-default">
    <div class="panel-heading">Quizlet Api</div>
    <div class="panel-body">
    
        <div>
        <?php if( $hasCredentials ): ?>
            <span class='glyphicon glyphicon-ok text-success mar-r-5 vmid'></span>
            Quizlet credentials are set
        <?php else: ?>
            <span class='glyphicon glyphicon-remove text-danger mar-r-5 vmid'></span>
            Quizlet credentials arent set !
        <?php endif; ?>
        </div>

        <div style="margin-top:5px;">
        <?php if( $hasSetId ): ?>
            <span class='glyphicon glyphicon-ok text-success mar-r-5 vmid'></span>
            Quizlet set ID is set
        <?php else: ?>
            <span class='glyphicon glyphicon-remove text-danger mar-r-5 vmid'></span>
            Quizlet set ID isnt set !
        <?php endif; ?>
        </div>

        <div style="margin-top:5px;">
        <?php if( $accessToken ): ?>
            <span class='glyphicon glyphicon-ok text-success mar-r-5 vmid'></span>
            App is authorized to make API calls
        <?php else: ?>
            <span class='glyphicon glyphicon-remove text-danger mar-r-5 vmid'></span>
            App isnt authorized to make API calls !
        <?php endif; ?>
        </div>

        <?php if( $hasCredentials && $hasSetId ): ?>
        <form class='mar-t-15' action="" method="post">
            <div class="form-group">
                <button type="submit" class="btn btn-success"><?= $accessToken ? 'Re-authenticate' : 'Authenticate' ?></button>
            </div>
        </form>
        <?php endif; ?>

    </div>
</div>
