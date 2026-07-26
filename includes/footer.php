<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content neo-border neo-shadow text-center p-4" style="background-color: var(--neo-yellow);">
            <div class="mb-3 text-dark">
                <div class="bg-white neo-border rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px; box-shadow: 3px 3px 0px var(--black);">
                    <i class="fas fa-trash-alt fs-3"></i>
                </div>
            </div>
            <h5 class="fw-bold mb-2 text-dark">ARE YOU SURE?</h5>
            <p class="small mb-4 text-dark fw-bold">This action cannot be undone. Delete this?</p>
            <div class="d-flex gap-2 justify-content-center">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">CANCEL</button>
                <a href="#" id="confirmDeleteBtn" class="btn btn-danger">YES, DELETE</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content neo-border neo-shadow text-center p-4" style="background-color: var(--neo-yellow);">
            <div class="mb-3 text-dark">
                <div class="bg-white neo-border rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px; box-shadow: 3px 3px 0px var(--black);">
                    <i class="fas fa-sign-out-alt fs-3 ps-1"></i>
                </div>
            </div>
            <h5 class="fw-bold mb-2 text-dark">LOG OUT?</h5>
            <p class="small mb-4 text-dark fw-bold">Ready to end your current session?</p>
            <div class="d-flex gap-2 justify-content-center">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">CANCEL</button>
                <a href="actions/auth.php?action=logout" class="btn btn-danger fw-bold">YES, LOGOUT</a>
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