<?= $this->extend('layouts/admin_unit') ?>

<?= $this->section('content') ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <i class="fas fa-exclamation-triangle text-warning" style="font-size: 4rem;"></i>
                    </div>

                    <h2 class="mb-3">Terjadi Kesalahan</h2>

                    <p class="text-muted mb-4">
                        <?= isset($error) ? htmlspecialchars($error, ENT_QUOTES, 'UTF-8') : 'Maaf, terjadi kesalahan dalam memuat dashboard. Silakan coba lagi atau hubungi administrator sistem.' ?>
                    </p>

                    <div class="d-flex gap-3 justify-content-center">
                        <a href="<?= base_url('/admin-unit') ?>" class="btn btn-primary-custom">
                            <i class="fas fa-redo me-2"></i>
                            Coba Lagi
                        </a>

                        <a href="<?= base_url('/demo/admin-unit') ?>" class="btn btn-outline-primary-custom">
                            <i class="fas fa-eye me-2"></i>
                            Lihat Demo
                        </a>
                    </div>

                    <div class="mt-4">
                        <small class="text-muted">
                            Jika masalah terus berlanjut, silakan hubungi tim IT support.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>