/**
 * View your website at your own local server.
 * 
 * http://localhost:5173 is serving Vite on development. Access this URL will show empty page.
 */

import { defineConfig } from "vite";
import { resolve } from 'path';

export default defineConfig({
    publicDir:  resolve(__dirname + 'assets/img'),
    plugins: [
        {
            handleHotUpdate({ file, server }) {
                if (file.endsWith('.php')) {
                    server.ws.send({ type: 'full-reload', path: '*' });
                }
            }
        }
    ],

    build: {
        // emit manifest so PHP can find the hashed files
        manifest: true,
        rollupOptions: {
            input: {
                main: resolve(__dirname + '/assets/js/theme.js')
            },
        }
    },

    server: {
        cors: {
            origin: "*"
        },
        // We need a strict port to match on PHP side.
        // You can change it. But, please update it in inc/enqueu.php to match the same port
        strictPort: true,
        port: 5173
    },
});