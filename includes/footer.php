<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow-lg rounded-4 text-center p-4">
                <div class="mb-3 text-danger">
                    <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="fas fa-trash-alt fs-3"></i>
                    </div>
                </div>
                <h5 class="fw-bold mb-2">Are you sure?</h5>
                <p class="text-muted small mb-4">This action cannot be undone. Do you really want to delete this?</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <a href="#" id="confirmDeleteBtn" class="btn btn-danger rounded-pill px-4">Yes, Delete</a>
                </div>
            </div>
        </div>
    </div>

<div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow-lg rounded-4 text-center p-4">
                <div class="mb-3 text-primary">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="fas fa-sign-out-alt fs-3 ps-1"></i>
                    </div>
                </div>
                <h5 class="fw-bold mb-2">Log Out?</h5>
                <p class="text-muted small mb-4">Are you sure you want to end your current session?</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <a href="logout.php" class="btn btn-primary rounded-pill px-6 fw-bold">Yes, Logout</a>
                </div>
            </div>
        </div>
    </div>

    <?php include_once __DIR__ . '/../views/modal_add_task.php'; ?>

    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script src="assets/js/script.js"></script>

</body>
</html>