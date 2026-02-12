export const baseLogic = (service) => ({
    service,
    isLoading: false,
    error: null,

    async performFetch(fetchFn, successCallback) {
        this.isLoading = true;
        this.error = null;

        try {
            const response = await fetchFn();
            if (successCallback) {
                successCallback(response);
            }
            return response;
        } catch (err) {
            this.error = "Une erreur est survenue lors de la récupération des données.";
            console.error("Fetch Error:", err);
        } finally {
            this.isLoading = false;
        }
    },

    reinitUI() {
        this.$nextTick(() => {
            // Ré-initialisation Preline (HSStaticMethods)
            if (window.HSStaticMethods) {
                window.HSStaticMethods.autoInit();
            }

            // Ré-initialisation des icônes Lucide
            if (window.createIcons && window.icons) {
                window.createIcons({ icons: window.icons });
            }
        });
    }
});

