
import { baseLogic } from './baseComponent.js';

const emploiService = {
    async getAll(params) {
        return axios.get('/api/emplois', {
            params,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
    },

    async delete(id) {
        return axios.delete(`/emplois/${id}`, {
            headers: {
                'X-CSRF-TOKEN': document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute('content'),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
    }
};

// Composant Alpine pour la partie publique (liste + filtre emplois)
export const emploiFilter = () => ({
    ...baseLogic({}),

    search: new URLSearchParams(window.location.search).get('search') || '',
    skill: new URLSearchParams(window.location.search).get('skill') || '',

    init() {
        // Composant initialisé
    },

    fetchJobs() {
        const url = new URL(window.location.href);
        url.searchParams.set('page', 1);

        if (this.search) {
            url.searchParams.set('search', this.search);
        } else {
            url.searchParams.delete('search');
        }

        if (this.skill) {
            url.searchParams.set('skill', this.skill);
        } else {
            url.searchParams.delete('skill');
        }

        window.history.pushState({}, '', url);

        fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(res => res.text())
            .then(html => {
                const jobsGrid = document.getElementById('jobs-grid');
                if (jobsGrid) {
                    jobsGrid.innerHTML = html;
                }
                this.reinitUI();
            })
            .catch(err => console.error(err));
    },

    resetFilters() {
        this.search = '';
        this.skill = '';
        this.fetchJobs();
    }
});

// Composant Alpine pour le tableau de bord admin
export default (initialJobs, initialTotal, initialPage = 1, initialLastPage = 1) => ({
    // Logique commune (loading, error, reinitUI, performFetch)
    ...baseLogic(emploiService),

    // État spécifique à ton dashboard
    jobs: initialJobs,
    total: initialTotal,
    search: '',
    skill: '',
    currentPage: initialPage,
    lastPage: initialLastPage,
    perPage: 5,

    init() {
        // Réagir automatiquement aux changements de filtres
        this.$watch('search', () => this.fetchJobs(1));
        this.$watch('skill', () => this.fetchJobs(1));

        // Quand une offre est créée / éditée via la modale
        window.addEventListener('job-saved', () => {
            this.fetchJobs();
        });

        this.reinitUI();
    },

    async fetchJobs(page = 1) {
        await this.performFetch(
            () =>
                this.service.getAll({
                    search: this.search || '',
                    skill: this.skill || '',
                    page,
                    perPage: this.perPage
                }),
            (res) => {
                this.jobs = res.data.emplois;
                this.total = res.data.count ?? res.data.emplois.length;
                this.currentPage = res.data.current_page ?? this.currentPage;
                this.lastPage = res.data.last_page ?? this.lastPage;
                this.reinitUI();
            }
        );
    },

    resetFilters() {
        this.search = '';
        this.skill = '';
        this.fetchJobs(1);
    },

    goToPage(page) {
        if (page < 1 || page > this.lastPage) return;
        this.fetchJobs(page);
    },

    nextPage() {
        if (this.currentPage < this.lastPage) {
            this.fetchJobs(this.currentPage + 1);
        }
    },

    prevPage() {
        if (this.currentPage > 1) {
            this.fetchJobs(this.currentPage - 1);
        }
    },

    openCreateModal() {
        Alpine.store('jobModal').openCreate();
    },

    openEditModal(job) {
        Alpine.store('jobModal').openEdit(job);
    },

    async deleteJob(id) {
        if (!confirm('Supprimer cette offre ?')) return;

        await this.performFetch(
            () => this.service.delete(id),
            () => {
                // Remove the job from the local list
                this.jobs = this.jobs.filter(job => job.id !== id);
                this.total--;

                // If the current page becomes empty and we're not on the first page, go to previous page
                if (this.jobs.length === 0 && this.currentPage > 1) {
                    this.prevPage();
                } else if (this.jobs.length === 0 && this.currentPage === 1) {
                     // If we are on page 1 and it's empty, we might want to stay here or show empty state (already handled by template)
                }
            }
        );
    }
});

