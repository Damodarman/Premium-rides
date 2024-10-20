<!-- app/Views/layouts/base.php -->

<?= view('partials/header') ?>
<?= view('partials/navbar') ?>
<?= view('partials/sidebar') ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <?= $this->renderSection('content') ?>
    </section>
</div>

<?= view('partials/footer') ?>
