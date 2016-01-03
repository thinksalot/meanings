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
                <a class='btn btn-default btn-xs' href="/edit/<?= $word->id ?>">
                    <span class="glyphicon glyphicon-pencil"></span>
                </a>
                <a class='btn btn-default btn-xs' href="/delete/<?= $word->id ?>">
                    <span class="glyphicon glyphicon-trash"></span>
                </a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
