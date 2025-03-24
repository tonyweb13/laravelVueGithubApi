<template>
    <div class="container mt-5">
        <h1 class="mb-4">GitHub Open Issues</h1>

        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">Issue Number</th>
                        <th scope="col">Title</th>
                        <th scope="col">Created</th>
                        <th scope="col" style="width: 17%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="task in tasks" :key="task.number">
                        <td>#{{ task.number }}</td>
                        <td>{{ task.title }}</td>
                        <td>{{ task.created_at }}</td>
                        <td>
                            <router-link
                                :to="{ name: 'View', params: { number: task.number } }"
                                class="btn btn-sm btn-outline-primary me-2 mx-2"
                            >
                                <i class="bi bi-pencil-square me-1"></i> View
                            </router-link>

                            <button
                                class="btn btn-sm btn-outline-danger mx-2"
                                @click="openDeleteConfirmation(task.number)"
                            >
                                <i class="bi bi-trash me-1"></i> Closed
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Close Confirmation Modal -->
    <div
        class="modal fade"
        id="deleteConfirmationModal"
        tabindex="-1"
        aria-labelledby="deleteConfirmationModalLabel"
        ref="deleteModal"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Close Issue</h5>
                    <button type="button" class="btn-close" @click="closeModal" aria-label="Close"></button>
                </div>
                <div class="modal-body">Are you sure you want to close this issue?</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal">Cancel</button>
                    <button type="button" class="btn btn-danger" @click="confirmDelete">Close Issue</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { onMounted, ref, computed } from 'vue';
import axios from 'axios';

export default {
    setup () {
        const tasks = ref([]);
        const taskIdToDelete = ref(null);
        const deleteModal = ref(null);
        const successMessage = ref('');
        const statusFilter = ref('All'); // New filter state

        const fetchTasks = async () => {
            try {
                const uri = `${import.meta.env.VITE_API_URL}/api/github/issues`;
                const response = await axios.get(uri);
                tasks.value = response.data;
            } catch (error) {
                console.error('Error fetching tasks:', error);
            }
        };

        const openDeleteConfirmation = (number) => {
            taskIdToDelete.value = number;
            const modalInstance = new bootstrap.Modal(deleteModal.value);
            modalInstance.show();
        };

        const closeModal = () => {
            const modalInstance = bootstrap.Modal.getInstance(deleteModal.value);
            if (modalInstance) {
                modalInstance.hide();
            }
        };

        const confirmDelete = async () => {
            if (taskIdToDelete.value !== null) {
                await deleteTask(taskIdToDelete.value);
                closeModal();
            }
        };

        const deleteTask = async (number) => {
            try {
                const uri = `${import.meta.env.VITE_API_URL}/api/github/issues/${number}`;
                await axios.patch(uri);
                tasks.value = tasks.value.filter((task) => task.number !== number);
                successMessage.value = `Issue ${number} has been closed successfully!`;
                setTimeout(() => {
                    successMessage.value = '';
                }, 1000);
            } catch (error) {
                console.error('Error deleting task:', error);
            }
        };

        onMounted(fetchTasks);

        return {
            tasks,
            openDeleteConfirmation,
            confirmDelete,
            deleteTask,
            deleteModal,
            closeModal,
            successMessage,
        };
    }
};
</script>
