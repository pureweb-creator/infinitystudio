import { resolve } from 'path';
import { v4wp } from '@kucrut/vite-for-wp';
export default {
    plugins: [
        v4wp( {
            input: 'main.js',
            outDir: 'dist',
        } ),
    ],
    resolve: {
        alias: {
            $fonts: resolve('./fonts'),
            $images: resolve('./images')
        }
    },
};