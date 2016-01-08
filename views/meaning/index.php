<div class="sort-bar">
    <div class="sort pull-left">
        <div class="btn-group">
            <a href="" class="btn <?= $sorting == "created" ? 'btn-primary' : 'btn-default' ?> btn-xs">Oldest first</a>
            <a href="?sort=word" class="btn <?= $sorting == "word" ? 'btn-primary' : 'btn-default' ?>  btn-xs">Alphabetical</a>
        </div>
    </div>
    <div class="search pull-right">
        <form class='form form-inline' action="search" method="get">
            <div class="form-group form-group-sm">
                <input type="text" name="name" class="form-control" placeholder="Search">
            </div>
            <button type="submit" class="btn btn-default btn-sm">Search</button>
        </form>
    </div>
</div>
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
                <a class='btn btn-default btn-xs' href="delete/<?= $word->id ?>">
                    <span class="glyphicon glyphicon-trash"></span>
                </a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
