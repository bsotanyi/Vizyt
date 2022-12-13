<?php ob_start(); ?>
    <h1><?= e( $title ) ?></h1>
    
    <p>
        <?php if (true == false) { ?>
            <p>true is false</p>
        <?php } elseif ($_GET['not_sure'] ?? false) { ?>
            <p>i don't know</p>
        <?php } else { ?>
            true is not false
        <?php } ?>
    </p>
    <p>
        <?php if (!empty($_GET)) { foreach ($_GET as $key => $value) { ?>
            <b style="display: inline-block; margin: 5px; background: lime">$_GET['<?= e( $key ) ?>'] => <?= e( $value ) ?></b>
        <?php } } else { ?>
            <b>no get parameters found sorry</b>
        <?php } ?>
    </p>
    <table border="1">
        <?php foreach ($_SERVER as $key => $item) { ?>
            <tr>
                <td><?= e( $key ) ?></td>
                <td><?= e( $item ) ?></td>
            </tr>
        <?php } ?>
    </table>
<?php $__sections['main'] = ob_get_clean(); ?>
<?php view('test', ['__sections' => $__sections ?? [], '__pushes' => $__pushes ?? [], '__all_vars' => $__all_vars]); ?>