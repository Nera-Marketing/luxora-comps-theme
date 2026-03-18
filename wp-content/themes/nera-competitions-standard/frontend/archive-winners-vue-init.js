import { createApp } from 'vue';
import ArchiveWinnersContainer from './components/Winners/ArchiveWinnersContainer.vue';
import { VueQueryPlugin, queryClient } from './query-client.js';

/**
 * Archive Winners Page Vue.js Initialization
 *
 * Mounts the ArchiveWinnersContainer Vue component to the #archive-winners-app element.
 */

(function () {
  'use strict';

  function initArchiveWinnersVue() {
    const container = document.getElementById('archive-winners-app');

    if (!container) {
      // Not on the archive winners page or container missing
      return;
    }

    try {
      const app = createApp(ArchiveWinnersContainer);

      // Install TanStack Query
      app.use(VueQueryPlugin, {
        queryClient,
      });

      app.mount(container);
    } catch (error) {
      console.error('[Archive Winners Vue] Error mounting Vue app:', error);
    }
  }

  // Auto-initialize on DOM ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initArchiveWinnersVue);
  } else {
    initArchiveWinnersVue();
  }
})();
