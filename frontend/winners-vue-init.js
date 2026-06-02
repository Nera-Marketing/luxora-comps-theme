import { createApp } from 'vue';
import WinnersContainer from './components/Winners/WinnersContainer.vue';
import { VueQueryPlugin, queryClient } from './query-client.js';

/**
 * Winners Page Vue.js Initialization
 *
 * Mounts the WinnersContainer Vue component to the #winners-root element.
 * Follows the same pattern as instant-wins-vue-init.js.
 *
 * Hybrid Approach:
 * - Server-rendered initial HTML is displayed for SEO
 * - Vue mounts and takes over DOM for interactivity
 * - Additional data loaded from REST API on demand
 *
 * Usage:
 * - Automatically initializes on DOM ready
 * - Requires #winners-root element with data attributes:
 *   - data-page-id: Winners page ID
 *   - data-per-page: Items per page (default: 12)
 *   - data-show-quotes: Whether to show quotes (1 or 0)
 */

(function () {
  'use strict';

  /**
   * Initialize Winners Vue app
   */
  function initWinnersVue() {
    const container = document.getElementById('winners-root');

    if (!container) {
      console.error('[Winners Vue] winners-root element not found');
      return;
    }

    // Read data attributes
    const pageId = container.dataset.pageId;
    const perPage = parseInt(container.dataset.perPage, 10) || 12;
    const showQuotes = container.dataset.showQuotes === '1';

    if (!pageId) {
      console.error('[Winners Vue] Page ID not found in data-page-id attribute');
      return;
    }

    try {
      // Create Vue app with props
      const app = createApp(WinnersContainer, {
        pageId: parseInt(pageId, 10),
        perPage,
        showQuotes,
      });

      // Install TanStack Query for intelligent request management
      app.use(VueQueryPlugin, {
        queryClient,
      });

      // Mount to container (will replace server-rendered content)
      app.mount(container);
    } catch (error) {
      console.error('[Winners Vue] Error mounting Vue app:', error);
    }
  }

  // Auto-initialize on DOM ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initWinnersVue);
  } else {
    initWinnersVue();
  }
})();
