
<div class="panel panel-default">
    <div class="panel-heading">
        <?= isset( $item->id ) ? 'Edit': 'Add' ?> meaning
    </div>
    <div class="panel-body">
        <form class="form-horizontal" action="" method="post">
            <div class="form-group">
                <label class="col-sm-2 control-label" for="word">Word</label>
                <div class="col-sm-10">
                    <input class="form-control" placeholder="Word" type="text" name="word" value="<?= isset( $item->id ) ? $item->word : '' ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="meaning">Meaning</label>
                <div class="col-sm-10">
                    <textarea class="form-control" placeholder="Meaning" name="meaning" cols="30" rows="10"><?= isset( $item->id ) ? $item->meaning : '' ?></textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-default">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
