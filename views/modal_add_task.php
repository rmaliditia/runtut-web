<div class="modal fade" id="addTaskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <h5 class="fw-bold"><i class="fas fa-plus-circle text-primary me-2"></i>New Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="actions/task_handler.php?action=add" method="POST">
                <div class="modal-body p-4">

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">TASK TITLE</label>
                        <input type="text" name="title" class="form-control shadow-sm bg-light border-0 py-2 px-3" placeholder="Misal: Rapat Project Design" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">DESCRIPTION</label>
                        <textarea name="description" class="form-control shadow-sm bg-light border-0 py-2 px-3" rows="3" placeholder="Tambahkan detail, catatan, atau link penting..."></textarea>
                    </div>

                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold text-secondary">CATEGORY</label>
                            <select name="category" class="form-select shadow-sm bg-light border-0 py-2">
                                <option value="Personal">Personal</option>
                                <option value="Work">Work</option>
                                <option value="Study">Study</option>
                                <option value="Health">Health</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-secondary">DUE DATE</label>
                            <input type="datetime-local" name="due_date" class="form-control shadow-sm bg-light border-0 py-2">
                        </div>
                    </div>

                </div>

                <div class="modal-footer border-0 px-4 pb-4 pt-0">
                    <button type="button" class="btn btn-light text-muted rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">Save Task</button>
                </div>
            </form>

        </div>
    </div>
</div>