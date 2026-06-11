<?php ob_start(); ?>

<?php 
$currentPath = '/doctors/nurse-applications';
?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-8">
    <div>
        <h1 style="font-size: 1.75rem; font-weight: 800; margin: 0 0 0.5rem 0;">Nurse Applications</h1>
        <p class="text-muted mb-0">Review and accept nurse applications</p>
    </div>
</div>

<!-- Stats Overview -->
<div class="stats-grid mb-8">
    <div class="stat-card">
        <div class="stat-icon">
            <i class="bi bi-person-plus"></i>
        </div>
        <div class="stat-content">
            <h3><?= number_format($stats['total'] ?? 0) ?></h3>
            <p>Total Applications</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: #fef3c7; color: #f59e0b;">
            <i class="bi bi-hourglass"></i>
        </div>
        <div class="stat-content">
            <h3><?= number_format($stats['pending'] ?? 0) ?></h3>
            <p>Pending</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: #d1fae5; color: #10b981;">
            <i class="bi bi-check-circle"></i>
        </div>
        <div class="stat-content">
            <h3><?= number_format($stats['accepted'] ?? 0) ?></h3>
            <p>Accepted</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: #fee2e2; color: #ef4444;">
            <i class="bi bi-x-circle"></i>
        </div>
        <div class="stat-content">
            <h3><?= number_format($stats['rejected'] ?? 0) ?></h3>
            <p>Rejected</p>
        </div>
    </div>
</div>

<!-- Applications Table -->
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h3>Nurse Applications</h3>
            <div class="d-flex gap-3">
                <div style="position: relative;">
                    <i class="bi bi-search" style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: var(--text-tertiary);"></i>
                    <input type="text" class="form-control" placeholder="Search applications..." style="padding-left: 2.25rem; width: 250px;">
                </div>
                <select class="form-control" style="width: 150px;">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Accepted</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nurse</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Applied On</th>
                        <th>CV</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($applications)): ?>
                    <tr>
                        <td colspan="7">
                            <div class="text-center" style="padding: 4rem 1rem;">
                                <i class="bi bi-person-plus" style="color: var(--text-tertiary); font-size: 3rem;"></i>
                                <p class="text-muted mt-4">No nurse applications found</p>
                            </div>
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($applications as $application): ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div style="width: 40px; height: 40px; background: var(--accent); border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 0.875rem;">
                                    <?= strtoupper(substr($application['first_name'] ?? 'N', 0, 1)) ?>
                                </div>
                                <div>
                                    <div style="font-weight: 600; color: var(--text-primary);"><?= htmlspecialchars($application['first_name'] . ' ' . $application['last_name']) ?></div>
                                    <div style="font-size: 0.875rem; color: var(--text-tertiary);">@<?= htmlspecialchars($application['username']) ?></div>
                                </div>
                            </div>
                        </td>
                        <td><?= htmlspecialchars($application['email']) ?></td>
                        <td><?= htmlspecialchars($application['phone'] ?? 'N/A') ?></td>
                        <td><?= date('M d, Y', strtotime($application['created_at'])) ?></td>
                        <td>
                            <?php if (!empty($application['cv_path'])): ?>
                                <a href="<?= htmlspecialchars($application['cv_path']) ?>" target="_blank" class="btn btn-sm btn-secondary">
                                    <i class="bi bi-file-earmark-pdf"></i>
                                    <span>View CV</span>
                                </a>
                            <?php else: ?>
                                <span class="text-muted" style="font-size: 0.875rem;">No CV</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge <?= getApplicationBadgeClass($application['registration_status']) ?>">
                                <?= ucfirst($application['registration_status'] ?? 'pending') ?>
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <?php if ($application['registration_status'] === 'pending'): ?>
                                    <form method="POST" action="/doctors/nurse-applications/<?= $application['id'] ?>/accept" style="display: inline;">
                                        <input type="hidden" name="_token" value="<?= $csrf_token ?>">
                                        <button type="submit" class="btn btn-sm btn-success" title="Accept" onclick="return confirm('Accept this nurse application?')">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="/doctors/nurse-applications/<?= $application['id'] ?>/reject" style="display: inline;">
                                        <input type="hidden" name="_token" value="<?= $csrf_token ?>">
                                        <button type="submit" class="btn btn-sm btn-danger" title="Reject" onclick="return confirm('Reject this nurse application?')">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                                <a href="/doctors/nurse-applications/<?= $application['id'] ?>" class="btn btn-sm btn-secondary" title="View Details">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
function getApplicationBadgeClass($status) {
    $classes = [
        'pending' => 'badge-warning',
        'approved' => 'badge-success',
        'rejected' => 'badge-danger'
    ];
    return $classes[$status] ?? 'badge-info';
}
?>

<?php $content = ob_get_clean(); ?>

<?php require __DIR__ . '/../layouts/app.php'; ?>
