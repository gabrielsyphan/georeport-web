<?php $v->layout("_theme.php") ?>

<?php echo "Erro: ". $error; ?>

<?php $v->start("scripts"); ?>
<script>
    $(document).ready(function () {
        $("#loader-div").hide();
    });
</script>
<?php $v->end(); ?>
