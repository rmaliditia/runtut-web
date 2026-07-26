<div class="modal fade" id="addTaskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <!-- Container Modal dengan class Neubrutalism -->
        <div class="modal-content neo-border neo-shadow overflow-hidden rounded-0" style="background-color: var(--neo-bg);">

            <div class="modal-header border-bottom border-dark border-3 px-4 pt-4 pb-3 bg-primary rounded-0">
                <h4 class="fw-bold text-dark mb-0"><i class="fas fa-plus-square me-2"></i>NEW TASK</h4>
                <!-- Tombol Close bergaya kotak kasar -->
                <button type="button" class="btn-close neo-border bg-white rounded-0 opacity-100" data-bs-dismiss="modal" style="padding: 0.5rem;"></button>
            </div>

            <form action="actions/task_handler.php?action=add" method="POST">
                <div class="modal-body p-4 bg-white">

                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark">TASK TITLE</label>
                        <input type="text" name="title" maxlength="50" class="form-control" placeholder="e.g., Project Design Meeting" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark">DESCRIPTION</label>
                        <textarea name="description" class="form-control" maxlength="120" rows="3" placeholder="Add details, notes, or important links..."></textarea>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold text-dark">CATEGORY</label>
                            <select name="category" class="form-select bg-white text-dark fw-bold border-2" style="cursor: pointer;">
                                <option value="None" selected>None</option>
                                <option value="Personal">Personal</option>
                                <option value="Work">Work</option>
                                <option value="Study">Study</option>
                                <option value="Health">Health</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <!-- Label dan Checkbox Anytime berdampingan -->
                            <div class="d-flex justify-content-between align-items-end mb-1">
                                <label class="form-label fw-bold text-dark mb-0">DUE DATE</label>
                                <div class="form-check m-0">
                                    <input class="form-check-input neo-border" type="checkbox" id="anytimeCheck" name="anytime" value="1" style="cursor: pointer;">
                                    <label class="form-check-label small fw-bold" for="anytimeCheck" style="cursor: pointer;">ANYTIME</label>
                                </div>
                            </div>
                            <input type="datetime-local" id="dueDateInput" name="due_date" class="form-control bg-white text-dark fw-bold border-2" required>
                        </div>
                    </div>

                    <!-- TAMBAHAN: Opsi Ulangi Task (Tersembunyi secara default) -->
                    <div class="p-3 bg-light neo-border border-2">
                        <div class="form-check">
                            <input class="form-check-input neo-border border-2" type="checkbox" id="repeatCheck" name="is_recurring" value="1" style="cursor: pointer;" disabled>
                            <label class="form-check-label fw-bold text-dark" for="repeatCheck" style="cursor: pointer;">
                                <i class="fas fa-redo-alt me-1"></i> REPEAT TASK
                            </label>
                        </div>

                        <select name="recurrence_type" id="repeatType" class="form-select bg-white text-dark fw-bold border-2 mt-2 d-none">
                            <option value="daily">Daily (Every day)</option>
                            <option value="weekly" id="optWeekly">Weekly</option>
                            <option value="monthly" id="optMonthly">Monthly</option>
                            <option value="yearly" id="optYearly">Yearly</option>
                        </select>
                        <div id="repeatHelp" class="form-text small fw-bold text-danger d-none mt-1">
                            *A new task will be created once this one is done.
                        </div>
                    </div>

                </div>

                <div class="modal-footer border-top border-dark border-3 px-4 py-3 bg-light">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">CANCEL</button>
                    <button type="submit" class="btn btn-primary fw-bold">SAVE TASK</button>
                </div>
            </form>

        </div>
    </div>
</div>