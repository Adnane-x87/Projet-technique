
export default () => ({
    search: new URLSearchParams(window.location.search).get('search') || '',
    skill: new URLSearchParams(window.location.search).get('skill') || '',

    init() {
        // Component initialized
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
                document.getElementById('jobs-grid').innerHTML = html;
            })
            .catch(err => console.error(err));
    },

    applyFilters() {
        this.fetchJobs();
    },

    resetFilters() {
        this.search = '';
        this.skill = '';
        this.fetchJobs();
    }
});
