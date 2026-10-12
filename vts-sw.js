/* ===================================================================
   ROOT LOADER FOR THE SITE-WIDE SERVICE WORKER.

   The worker itself is assets/js/vts-sw.js. A worker may only control
   pages at or below its own folder, so from assets/js/ it needed the
   server to send "Service-Worker-Allowed: /" (.htaccess) to look after the
   whole app. InfinityFree does not send that header: registration failed
   there and the live site had no offline pages at all.

   A worker loaded from the app root needs no header, so this file is that
   worker and simply pulls in the real one. The browser checks this file
   and the imported one for changes (registered with updateViaCache:'none'),
   so bumping VTS_BUILD in assets/js/vts-sw.js is still what ships an update.
   =================================================================== */
importScripts('assets/js/vts-sw.js');
