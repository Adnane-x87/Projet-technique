/**
 * Alpine.js component for managing jobs in the
 */
export default (initialJobsData, initialTotal) => ({
    jobs: [],
    total: 0,
    search: '',
    skill: '',
    
    // Pagination state
    page: 1,
    totalPages: 1,
    loading: false,

    get paginatedJobs() {
        return this.jobs; // Jobs are already paginated from server
    },

    get pageCount() {
        return this.totalPages;
    },

    setPage(n) {
        if (n >= 1 && n <= this.totalPages) {
            this.page = n;
            this.fetchJobs();
        }
    },

    nextPage() {
        if (this.page < this.totalPages) {
            this.page++;
            this.fetchJobs();
        }
    },

    prevPage() {
        if (this.page > 1) {
            this.page--;
            this.fetchJobs();
        }
    },

    get pageNumbers() {
        const range = [];
        for (let i = 1; i <= this.totalPages; i++) {
            range.push(i);
        }
        return range;
    },

    init() {
        // Handle server-side pagination structure or fallback
        if (initialJobsData && initialJobsData.data) {
            this.jobs = initialJobsData.data;
            this.page = initialJobsData.current_page;
            this.totalPages = initialJobsData.last_page;
            this.total = initialJobsData.total;
        } else if (Array.isArray(initialJobsData)) {
            // Fallback for flat array (though controller should now return object)
            this.jobs = initialJobsData;
            this.total = initialTotal || initialJobsData.length;
            this.totalPages = 1;
        } else {
            console.error('Invalid jobs data received:', initialJobsData);
            this.jobs = [];
        }

        window.addEventListener('job-saved', () => {
            this.fetchJobs();
        });
    },

    async fetchJobs() {
        this.loading = true;
        try {
            const params = new URLSearchParams({
                search: this.search,
                skill: this.skill,
                page: this.page // Send current page to server
            });

            const response = await fetch(`/api/emplois?${params.toString()}`);
            const data = await response.json();

            // Handle response from EmploiController::search
            // It returns { data: [...], current_page: N, last_page: M, total: T }
            if (data.data) {
                this.jobs = data.data;
                this.page = data.current_page;
                this.totalPages = data.last_page;
                this.total = data.total;
            } else {
                // Fallback if structure is different
                this.jobs = data.emplois || [];
                this.total = data.count || 0;
            }

        } catch (error) {
            console.error('Erreur lors de la récupération des offres:', error);
        } finally {
            this.loading = false;
        }
    },

    async deleteJob(id) {
        if (!confirm('Êtes-vous sûr de vouloir supprimer cette offre ?')) return;

        try {
            const response = await fetch(`/emplois/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                this.fetchJobs();
            } else {
                alert('Une erreur est survenue lors de la suppression');
            }
        } catch (error) {
            console.error('Erreur:', error);
        }
    },
    
    // resetFilters method
    resetFilters() {
        this.search = '';
        this.skill = '';
        this.page = 1;
        this.fetchJobs();
    },

    openCreateModal() {
        Alpine.store('jobModal').openCreate();
    },

    openEditModal(job) {
        Alpine.store('jobModal').openEdit(job);
    }
});
