<div class="sort-bar">
    <div class="sort pull-left">
        <span class="glyphicon glyphicon-sort vmid mar-r-3 text-muted"></span>
        <div class="btn-group">
            <a href="<?= $_url ?>" class="btn <?= $sorting == "created" ? 'btn-primary' : 'btn-default' ?> btn-xs">Oldest</a>
            <a href="<?= $_url ?>?sort=word" class="btn <?= $sorting == "word" ? 'btn-primary' : 'btn-default' ?>  btn-xs">Alphabetical</a>
        </div>
    </div>
    <div class="search pull-right">
        <form class="form" action="/search/">
            <div class="input-group">
                <input type="text" name="" value="<?= isset( $keyword ) ? $keyword : "" ?>" class="form-control input-sm col-sm-2" placeholder="Search">
                <span class="input-group-btn">
                    <button class="btn btn-default btn-sm" type="submit">
                        <span class="glyphicon glyphicon-search"></span>
                    </button>
                </span>
            </div>
        </form>
    </div>
</div>

<?= $this->renderFlash(); ?>

<div class="items">
    <?php foreach( $words as $word ): ?>
    <div class="item_details">
        <div class="term">
            <strong> <?= $word->word ?> </strong>
        </div>
        <div class="definition">
            <?= nl2br( $word->meaning ) ?>
        </div>
        <div class="actions">
            <div class="buttons">
                <a class='btn btn-default btn-xs' href="edit/<?= $word->id ?>">
                    <span class="glyphicon glyphicon-pencil"></span>
                </a>
                <a data-toggle="modal" data-target="#delete-modal" data-url="delete/<?= $word->id ?>" id='delete' class='btn btn-default btn-xs' href="#">
                    <span class="glyphicon glyphicon-trash"></span>
                </a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div id="delete-modal" class="modal fade" tabIndex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span>&times</span></button>
                <h4 class="modal-title">Delete</h4>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this term ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a id="ok" class="btn btn-primary" href="#">Ok</a>
            </div>
        </div>
    </div>
</div>
