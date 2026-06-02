/**
 * Alpine.js component for the Archive Winners page.
 *
 * Bootstraps from a PHP-rendered JSON data island (no initial fetch),
 * then handles search and pagination via the REST API.
 *
 * Must be loaded before Alpine.js initializes.
 */
document.addEventListener('alpine:init', () => {
  Alpine.data('archiveWinners', () => ({
    items: [],
    pagination: {},
    isLoading: false,
    isFetching: false,
    isError: false,
    searchInput: '',
    searchQuery: '',
    currentPage: 1,
    perPage: 12,
    _debounceTimer: null,
    _restUrl: '',
    _wpNonce: '',
    _pdfNonce: '',

    get totalPages() {
      return this.pagination.total_pages || 1;
    },

    init() {
      this._restUrl  = this.$el.dataset.restUrl  || '';
      this._wpNonce  = this.$el.dataset.wpNonce  || '';
      this._pdfNonce = this.$el.dataset.pdfNonce || '';

      // Bootstrap from PHP-rendered JSON island — no initial fetch needed
      const island = document.getElementById('archive-initial-data');
      if (island) {
        try {
          const data      = JSON.parse(island.textContent);
          this.items      = data.items      || [];
          this.pagination = data.pagination || {};
        } catch (e) {
          // Malformed JSON — fall back to fetching
          this.fetchData();
          return;
        }
      } else {
        // No data island found — fall back to fetching
        this.fetchData();
        return;
      }

      // Hide the static SSR grid now that Alpine has control
      const ssrGrid = document.getElementById('archive-ssr-grid');
      if (ssrGrid) ssrGrid.style.display = 'none';
    },

    handleSearch() {
      clearTimeout(this._debounceTimer);
      this._debounceTimer = setTimeout(() => {
        this.searchQuery = this.searchInput;
        this.currentPage = 1;
        this.fetchData();
      }, 400);
    },

    clearSearch() {
      this.searchInput = '';
      this.searchQuery = '';
      this.currentPage = 1;
      this.fetchData();
    },

    async fetchData() {
      this.isFetching = true;
      if (this.items.length === 0) this.isLoading = true;
      this.isError = false;

      // Ensure SSR grid is hidden once we start fetching
      const ssrGrid = document.getElementById('archive-ssr-grid');
      if (ssrGrid) ssrGrid.style.display = 'none';

      const params = new URLSearchParams({
        search:    this.searchQuery,
        page:      this.currentPage,
        per_page:  this.perPage,
        pdf_nonce: this._pdfNonce,
      });

      const headers = this._wpNonce ? { 'X-WP-Nonce': this._wpNonce } : {};

      try {
        const res  = await fetch(`${this._restUrl}?${params}`, { headers });
        if (!res.ok) throw new Error('Network error');
        const json = await res.json();
        this.items      = json.data?.items      || [];
        this.pagination = json.data?.pagination || {};
      } catch (e) {
        this.isError = true;
      } finally {
        this.isLoading  = false;
        this.isFetching = false;
      }
    },

    async nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
        await this.fetchData();
        window.scrollTo({ top: 0, behavior: 'smooth' });
      }
    },

    async prevPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
        await this.fetchData();
        window.scrollTo({ top: 0, behavior: 'smooth' });
      }
    },
  }));
});
