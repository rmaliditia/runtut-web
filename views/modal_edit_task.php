<div class="modal fade" id="editTaskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content neo-border neo-shadow overflow-hidden rounded-0" style="background-color: var(--neo-bg);">

            <div class="modal-header border-bottom border-dark border-3 px-4 pt-4 pb-3 bg-warning rounded-0">
                <h4 class="fw-bold text-dark mb-0"><i class="fas fa-edit me-2"></i>EDIT TASK</h4>
                <button type="button" class="btn-close neo-border bg-white rounded-0 opacity-100" data-bs-dismiss="modal" style="padding: 0.5rem;"></button>
            </div>

            <form action="actions/task_handler.php?action=edit" method="POST">
                <!-- HIDDEN INPUT UNTUK ID TASK -->
                <input type="hidden" name="task_id" id="editTaskId">

                <div class="modal-body p-4 bg-white">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark">TASK TITLE</label>
                        <input type="text" name="title" maxlength="50" id="editTaskTitle" class="form-control fw-bold border-2" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark">DESCRIPTION</label>
                        <textarea name="description" maxlength="120" id="editTaskDesc" class="form-control fw-bold border-2" rows="3"></textarea>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold text-dark">CATEGORY</label>
                            <select name="category" id="editTaskCategory" class="form-select bg-white text-dark fw-bold border-2" style="cursor: pointer;">
                                <option value="None">None</option>
                                <option value="Personal">Personal</option>
                                <option value="Work">Work</option>
                                <option value="Study">Study</option>
                                <option value="Health">Health</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <div class="d-flex justify-content-between align-items-end mb-1">
                                <label class="form-label fw-bold text-dark mb-0">DUE DATE</label>
                                <div class="form-check m-0">
                                    <input class="form-check-input neo-border" type="checkbox" id="editAnytimeCheck" name="anytime" value="1" style="cursor: pointer;">
                                    <label class="form-check-label small fw-bold" for="editAnytimeCheck" style="cursor: pointer;">ANYTIME</label>
                                </div>
                            </div>
                            <input type="datetime-local" id="editDueDateInput" name="due_date" class="form-control bg-white text-dark fw-bold border-2" required>
                        </div>
                    </div>

                    <div class="p-3 bg-light neo-border border-2">
                        <div class="form-check">
                            <input class="form-check-input neo-border border-2" type="checkbox" id="editRepeatCheck" name="is_recurring" value="1" style="cursor: pointer;">
                            <label class="form-check-label fw-bold text-dark" for="editRepeatCheck" style="cursor: pointer;">
                                <i class="fas fa-redo-alt me-1"></i> REPEAT THIS TASK
                            </label>
                        </div>

                        <select name="recurrence_type" id="editRepeatType" class="form-select bg-white text-dark fw-bold border-2 mt-2 d-none">
                            <option value="daily">Daily (Every Day)</option>
                            <option value="weekly" id="editOptWeekly">Weekly</option>
                            <option value="monthly" id="editOptMonthly">Monthly</option>
                            <option value="yearly" id="editOptYearly">Yearly</option>
                        </select>
                        <div id="editRepeatHelp" class="form-text small fw-bold text-danger d-none mt-1">
                            *New task will be created automatically when this task is marked "DONE".
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-top border-dark border-3 px-4 py-3 bg-light">
                    <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">CANCEL</button>
                    <button type="submit" class="btn btn-warning fw-bold border-2 border-dark">UPDATE TASK</button>
                </div>
            </form>
        </div>
    </div>
</div>