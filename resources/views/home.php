<?php
// Extract variables with safe defaults
$words = $words ?? [];
$count = $count ?? 0;
$error = $error ?? null;
$success = $success ?? null;
$query = $query ?? [];
$config = config() ?? [];
?>

<?php include dirname(__FILE__) . '/layout.php'; ?>

<div class="container py-4">
    <div class="row mt-4">
        <!-- Flash Messages -->
        <div class="col-12">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle"></i>
                    <?= esc_html($error) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i>
                    <?= esc_html($success) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
        </div>

        <!-- Input Section -->
        <div class="col-lg-6 col-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">🎯 Enter Your Clues</h5>
                </div>
                <div class="card-body">
                    <!-- Letter Inputs -->
                    <label class="form-label fw-bold mb-3">Known Letters</label>
                    <div class="row gap-2 mb-4">
                        <?php for ($i = 1; $i <= ($config['word_length'] ?? 5); $i++): ?>
                            <div class="col flex-grow-1">
                                <input
                                    type="text"
                                    class="form-control form-control-lg text-center fw-bold letter-input"
                                    id="l<?= $i ?>"
                                    placeholder="<?= $i ?>"
                                    maxlength="1"
                                    data-position="<?= $i ?>"
                                    value="<?= isset($query['known_letters'][$i - 1]) ? esc_attr($query['known_letters'][$i - 1]) : '' ?>"
                                >
                            </div>
                        <?php endfor; ?>
                    </div>

                    <!-- Excluded Letters -->
                    <label for="excluded" class="form-label fw-bold">❌ Excluded Letters</label>
                    <small class="text-muted d-block mb-2">(comma separated)</small>
                    <input
                        type="text"
                        class="form-control mb-4"
                        id="excluded"
                        placeholder="e.g., A, B, C, D"
                        value="<?= !empty($query['excluded_letters']) ? esc_attr(implode(', ', $query['excluded_letters'])) : '' ?>"
                    >

                    <!-- Action Buttons -->
                    <div class="d-grid gap-2 d-sm-flex">
                        <button type="button" class="btn btn-primary btn-lg flex-grow-1" id="guess-btn">
                            ✨ Guess Word
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-lg" id="clear-btn">
                            🔄 Clear
                        </button>
                    </div>

                    <!-- Tips -->
                    <div class="alert alert-info mt-4 mb-0">
                        <h6 class="fw-bold">💡 Tips</h6>
                        <ul class="mb-0 small">
                            <li>Enter at least <strong>1 known letter</strong></li>
                            <li>Provide <strong>excluded letters</strong> to narrow results faster</li>
                            <li>Each position can hold <strong>A-Z</strong></li>
                            <li>Leave blank for <strong>unknown positions</strong></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results Section -->
        <div class="col-lg-6 col-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">📝 Results (<?= count($words) ?>)</h5>
                </div>
                <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                    <?php if (empty($words)): ?>
                        <div class="text-center text-muted py-5">
                            <p>
                                <span style="font-size: 2rem;">🤔</span><br>
                                No words found yet. Try adjusting your clues!
                            </p>
                        </div>
                    <?php else: ?>
                        <ol class="list-group list-group-numbered">
                            <?php foreach ($words as $index => $word): ?>
                                <li class="list-group-item">
                                    <strong><?= esc_html(strtoupper($word)) ?></strong>
                                </li>
                            <?php endforeach; ?>
                        </ol>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
