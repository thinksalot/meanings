<div class="panel panel-info">
    <div class="panel-heading">
        Detail
        <a class='pull-right' href="/edit/<?= $item->id ?>">
            <span class="glyphicon glyphicon-pencil"></span>
        </a>
    </div>
    <div class="panel-body">
        <blockquote>
            <p><?= $item->word ?></p>
            <footer><cite><?= nl2br( $item->meaning ) ?></cite></footer>
        </blockquote>
    </div>
</div>
