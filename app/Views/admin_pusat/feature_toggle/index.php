<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Feature Toggle Management' ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
</head>
<body>
    <?= $this->include('partials/sidebar_admin_pusat') ?>
    
    <div class="main-content">
        <div class="dashboard-header">
            <h1><i class="fas fa-toggle-on"></i> Feature Toggle Management</h1>
            <p>Kontrol fitur dan komponen dashboard User dan TPS</p>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid mb-4">
            <div class="stat-card primary">
                <div class="stat-icon">
                    <i class="fas fa-list"></i>
                </div>
                <div class="stat-content">
                    <h3><?= $statistics['total_features'] ?></h3>
                    <p>Total Features</p>
                </div>
            </div>
            
            <div class="stat-card success">
                <div class="stat-icon">
                    <i class="fas fa-toggle-on"></i>
                </div>
                <div class="stat-content">
                    <h3><?= $statistics['enabled_features'] ?></h3>
                    <p>Enabled Features</p>
                </div>
            </div>
            
            <div class="stat-card danger">
                <div class="stat-icon">
                    <i class="fas fa-toggle-off"></i>
                </div>
                <div class="stat-content">
                    <h3><?= $statistics['disabled_features'] ?></h3>
                    <p>Disabled Features</p>
                </div>
            </div>
            
            <div class="stat-card info">
                <div class="stat-icon">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div class="stat-content">
                    <h3><?= count($categories) ?></h3>
                    <p>Categories</p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons mb-4">
            <button class="btn btn-success" onclick="bulkToggleModal('enable')">
                <i class="fas fa-toggle-on"></i> Enable All
            </button>
            <button class="btn btn-warning" onclick="bulkToggleModal('disable')">
                <i class="fas fa-toggle-off"></i> Disable All
            </button>
            <button class="btn btn-info" onclick="exportConfig()">
                <i class="fas fa-download"></i> Export Config
            </button>
            <button class="btn btn-secondary" onclick="importConfigModal()">
                <i class="fas fa-upload"></i> Import Config
            </button>
            <a href="<?= base_url('/admin-pusat/feature-toggle/logs') ?>" class="btn btn-outline-primary">
                <i class="fas fa-history"></i> View Logs
            </a>
        </div>

        <!-- Feature Categories -->
        <?php foreach ($featuresByCategory as $category => $features): ?>
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5><i class="fas fa-folder"></i> <?= $categories[$category] ?></h5>
                <div class="category-actions">
                    <button class="btn btn-sm btn-success" onclick="bulkToggleCategory('<?= $category ?>', true)">
                        <i class="fas fa-toggle-on"></i> Enable All
                    </button>
                    <button class="btn btn-sm btn-warning" onclick="bulkToggleCategory('<?= $category ?>', false)">
                        <i class="fas fa-toggle-off"></i> Disable All
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php foreach ($features as $feature): ?>
                    <div class="col-lg-6 col-xl-4 mb-3">
                        <div class="feature-card">
                            <div class="feature-header">
                                <div class="feature-info">
                                    <h6><?= $feature['feature_name'] ?></h6>
                                    <small class="text-muted"><?= $feature['feature_key'] ?></small>
                                </div>
                                <div class="feature-toggle">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" 
                                               id="toggle_<?= $feature['feature_key'] ?>"
                                               <?= $feature['is_enabled'] ? 'checked' : '' ?>
                                               onchange="toggleFeature('<?= $feature['feature_key'] ?>')">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="feature-description">
                                <p><?= $feature['description'] ?></p>
                            </div>
                            
                            <div class="feature-meta">
                                <span class="badge bg-<?= (is_array($feature['target_roles']) ? in_array('both', $feature['target_roles']) : $feature['target_roles'] === 'both') ? 'primary' : ((is_array($feature['target_roles']) ? in_array('user', $feature['target_roles']) : $feature['target_roles'] === 'user') ? 'info' : 'warning') ?>">
                                    <?= is_array($feature['target_roles']) ? implode(', ', $feature['target_roles']) : ucfirst($feature['target_roles']) ?>
                                </span>
                                <?php if ($feature['config']): ?>
                                <button class="btn btn-sm btn-outline-secondary" onclick="showConfig('<?= $feature['feature_key'] ?>')">
                                    <i class="fas fa-cog"></i> Config
                                </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

        <!-- Recent Changes -->
        <?php if (!empty($recentChanges)): ?>
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-history"></i> Recent Changes</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Feature</th>
                                <th>Action</th>
                                <th>Admin</th>
                                <th>Date</th>
                                <th>Reason</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentChanges as $change): ?>
                            <tr>
                                <td>
                                    <strong><?= $change['feature_name'] ?? $change['feature_key'] ?></strong>
                                    <br><small class="text-muted"><?= $change['feature_key'] ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $change['action'] === 'enabled' ? 'success' : ($change['action'] === 'disabled' ? 'danger' : 'info') ?>">
                                        <?= ucfirst($change['action']) ?>
                                    </span>
                                </td>
                                <td><?= $change['admin_name'] ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($change['created_at'])) ?></td>
                                <td><?= $change['reason'] ?: '-' ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Feature Config Modal -->
    <div class="modal fade" id="configModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Feature Configuration</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="configForm">
                        <input type="hidden" id="config_feature_key" name="feature_key">
                        <div class="mb-3">
                            <label for="config_data" class="form-label">Configuration (JSON)</label>
                            <textarea class="form-control" id="config_data" name="config" rows="10"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="config_reason" class="form-label">Reason for Change</label>
                            <input type="text" class="form-control" id="config_reason" name="reason">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveConfig()">Save Configuration</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Toggle Modal -->
    <div class="modal fade" id="bulkToggleModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Bulk Toggle Features</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="bulkToggleForm">
                        <div class="mb-3">
                            <label for="bulk_category" class="form-label">Category</label>
                            <select class="form-select" id="bulk_category" name="category">
                                <option value="">All Categories</option>
                                <?php foreach ($categories as $key => $name): ?>
                                <option value="<?= $key ?>"><?= $name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="bulk_action" class="form-label">Action</label>
                            <select class="form-select" id="bulk_action" name="enabled">
                                <option value="1">Enable</option>
                                <option value="0">Disable</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="bulk_reason" class="form-label">Reason</label>
                            <input type="text" class="form-control" id="bulk_reason" name="reason">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="executeBulkToggle()">Execute</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Import Config Modal -->
    <div class="modal fade" id="importModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import Configuration</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="importForm" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="config_file" class="form-label">Configuration File (JSON)</label>
                            <input type="file" class="form-control" id="config_file" name="config_file" accept=".json">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="executeImport()">Import</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Toggle individual feature
        function toggleFeature(featureKey) {
            Swal.fire({
                title: 'Toggle Feature',
                input: 'text',
                inputLabel: 'Reason for change (optional)',
                showCancelButton: true,
                confirmButtonText: 'Toggle',
                showLoaderOnConfirm: true,
                preConfirm: (reason) => {
                    return fetch('<?= base_url('/admin-pusat/feature-toggle/toggle') ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `feature_key=${featureKey}&reason=${reason || ''}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (!data.success) {
                            throw new Error(data.message);
                        }
                        return data;
                    })
                    .catch(error => {
                        Swal.showValidationMessage(`Request failed: ${error}`);
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire('Success!', result.value.message, 'success');
                    // Update toggle state
                    document.getElementById(`toggle_${featureKey}`).checked = result.value.is_enabled;
                }
            });
        }

        // Show feature configuration
        function showConfig(featureKey) {
            fetch(`<?= base_url('/admin-pusat/feature-toggle/get-feature') ?>/${featureKey}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('config_feature_key').value = featureKey;
                        document.getElementById('config_data').value = JSON.stringify(data.feature.config, null, 2);
                        new bootstrap.Modal(document.getElementById('configModal')).show();
                    }
                });
        }

        // Save feature configuration
        function saveConfig() {
            const form = document.getElementById('configForm');
            const formData = new FormData(form);

            fetch('<?= base_url('/admin-pusat/feature-toggle/update-config') ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Success!', data.message, 'success');
                    bootstrap.Modal.getInstance(document.getElementById('configModal')).hide();
                } else {
                    Swal.fire('Error!', data.message, 'error');
                }
            });
        }

        // Bulk toggle by category
        function bulkToggleCategory(category, enabled) {
            Swal.fire({
                title: `${enabled ? 'Enable' : 'Disable'} all features in ${category}?`,
                input: 'text',
                inputLabel: 'Reason for change',
                showCancelButton: true,
                confirmButtonText: enabled ? 'Enable All' : 'Disable All',
                showLoaderOnConfirm: true,
                preConfirm: (reason) => {
                    return fetch('<?= base_url('/admin-pusat/feature-toggle/bulk-toggle') ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `category=${category}&enabled=${enabled ? '1' : '0'}&reason=${reason || ''}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (!data.success) {
                            throw new Error(data.message);
                        }
                        return data;
                    });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire('Success!', result.value.message, 'success').then(() => {
                        location.reload();
                    });
                }
            });
        }

        // Export configuration
        function exportConfig() {
            window.location.href = '<?= base_url('/admin-pusat/feature-toggle/export') ?>';
        }

        // Import configuration modal
        function importConfigModal() {
            new bootstrap.Modal(document.getElementById('importModal')).show();
        }

        // Execute import
        function executeImport() {
            const form = document.getElementById('importForm');
            const formData = new FormData(form);

            fetch('<?= base_url('/admin-pusat/feature-toggle/import') ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Success!', `Import completed. ${data.results.imported} imported, ${data.results.updated} updated.`, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error!', data.message, 'error');
                }
                bootstrap.Modal.getInstance(document.getElementById('importModal')).hide();
            });
        }
    </script>
</body>
</html>

<style>
body {
    margin: 0;
    padding: 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f8f9fa;
}

.main-content {
    margin-left: 280px;
    padding: 30px;
    min-height: 100vh;
}

.dashboard-header {
    margin-bottom: 30px;
}

.dashboard-header h1 {
    color: #2c3e50;
    font-size: 28px;
    font-weight: 600;
    margin-bottom: 5px;
}

.dashboard-header p {
    color: #7f8c8d;
    font-size: 16px;
    margin: 0;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.stat-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    border: none;
    display: flex;
    align-items: center;
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 20px;
    font-size: 24px;
    color: white;
}

.stat-card.primary .stat-icon { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.stat-card.success .stat-icon { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
.stat-card.danger .stat-icon { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
.stat-card.info .stat-icon { background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); }

.stat-content h3 {
    font-size: 32px;
    font-weight: 700;
    margin: 0 0 5px 0;
    color: #2c3e50;
}

.stat-content p {
    font-size: 14px;
    color: #7f8c8d;
    margin: 0;
    font-weight: 500;
}

.action-buttons {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.feature-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    height: 100%;
    transition: transform 0.2s ease;
}

.feature-card:hover {
    transform: translateY(-2px);
}

.feature-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 15px;
}

.feature-info h6 {
    margin: 0;
    color: #2c3e50;
    font-weight: 600;
}

.feature-description {
    margin-bottom: 15px;
}

.feature-description p {
    font-size: 14px;
    color: #7f8c8d;
    margin: 0;
}

.feature-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.form-check-input:checked {
    background-color: #28a745;
    border-color: #28a745;
}

.card {
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    border: none;
}

.card-header {
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    color: white;
    border-radius: 15px 15px 0 0 !important;
    padding: 20px;
}

.category-actions {
    display: flex;
    gap: 10px;
}

@media (max-width: 768px) {
    .main-content {
        margin-left: 0;
        padding: 20px;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .action-buttons {
        justify-content: center;
    }
    
    .category-actions {
        flex-direction: column;
        gap: 5px;
    }
}
</style>