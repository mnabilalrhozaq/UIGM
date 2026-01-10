<?= $this->extend('layouts/admin_pusat_new') ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3>Test Simple View</h3>
    </div>
    <div class="card-body">
        <h1>Hello from Admin Pusat!</h1>
        <p>If you can see this, the layout system is working correctly.</p>
        
        <div class="alert alert-success">
            <strong>Success!</strong> View rendering is working.
        </div>
        
        <h3>Available Data:</h3>
        <pre><?php print_r(get_defined_vars()); ?></pre>
    </div>
</div>

<?= $this->endSection() ?>
