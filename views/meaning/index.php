<table style='' class='table table-striped table-bordered table-condensed table-responsive'>
    <thead>
        <tr>
            <th style='text-align: left; width: 50px'>#Id</th>
            <th style='text-align: left;'>Word</th>
            <th style='text-align: left; width: 200px'>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach( $words as $word ): ?>
        <tr>
            <td><?= $word->id ?></td>
            <td>
                <span data-toggle="tooltip" data-html="true" title="<?= htmlspecialchars( nl2br( $word->meaning ), ENT_QUOTES, 'utf-8' ) ?>">
                    <em><?= $word->word ?></em>
                </span>
            </td>
            <td>
                <a href="/view/<?= $word->id ?>">View</a>
                <a href="/edit/<?= $word->id ?>">Edit</a>
                <a href="/delete/<?= $word->id ?>">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
