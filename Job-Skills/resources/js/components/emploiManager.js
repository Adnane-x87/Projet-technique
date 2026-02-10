/**
 * Alpine.js component for managing jobs in the admin dashboard
 * Handles search, filter, and CRUD operations
 */
export default (initialJobs, initialTotal) => ({
    jobs: initialJobs,
    total: initialTotal,
    search: '',
    skill: '',

    init() {
        window.addEventListener('job-saved', () => {
            this.fetchJobs();
        });
    },

    fetchJobs() {
        const params = new URLSearchParams();
        if (this.search) params.append('search', this.search);
        if (this.skill) params.append('skill', this.skill);

        fetch('/api/emplois?' + params.toString())
            .then(res => res.json())
            .then(data => {
                this.jobs = data.emplois;
                this.total = data.count || data.emplois.length;
            });
    },

    resetFilters() {
        this.search = '';
        this.skill = '';
        this.fetchJobs();
    },

    openCreateModal() {
        Alpine.store('jobModal').openCreate();
    },

    openEditModal(job) {
        Alpine.store('jobModal').openEdit(job);
    },

    async deleteJob(id) {
        if (!confirm('Supprimer cette offre ?')) return;
        try {
            const response = await fetch(`/emplois/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                this.fetchJobs();
            } else {
                alert('Erreur lors de la suppression');
            }
        } catch (e) {
            console.error(e);
            alert('Erreur de connexion');
        }
    }
});
