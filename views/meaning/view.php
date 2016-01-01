<div class="panel panel-info">
    <div class="panel-heading">
        <?= $item->word ?>
        <a class='pull-right' href="/edit/<?= $item->id ?>">
            <span class="glyphicon glyphicon-pencil"></span>
        </a>
    </div>
    <div class="panel-body">
        <?= nl2br($item->meaning) ?>
    </div>
</div>
